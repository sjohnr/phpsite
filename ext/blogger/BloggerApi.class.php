<?php

/**
 * BloggerApi class.
 *
 * @author Stephen Riesenberg
 */
class BloggerApi {
	private $version;
	private $auth;
	
	/**
	 * Access the Blogger API.
	 *
	 * @param string version (default, summary, full)
	 */
	public function __construct($version = 'default') {
		$this->version = $version;
	}
	
	/**
	 * Retrieve a list of posts.
	 *
	 * @param string name
	 * @param integer idx
	 * @param integer limit
	 * @return object BloggerFeed
	 */
	public function getPosts($name, $idx = 1, $limit = 10) {
		return $this->executeMethod('blogger.posts.getPosts', array(
			'version' => $this->version,
			'name'    => $name,
			'idx'     => $idx,
			'limit'   => $limit,
		))->getPosts();
	}
	
	/**
	 * Retrieve a list of posts by label.
	 *
	 * @param string name
	 * @param string label
	 * @param integer idx
	 * @param integer limit
	 * @return object BloggerFeed
	 */
	public function getPostsByLabel($name, $label, $idx = 1, $limit = 10) {
		return $this->executeMethod('blogger.posts.getPostsByLabel', array(
			'version' => $this->version,
			'name'    => $name,
			'label'   => $label,
			'idx'     => $idx,
			'limit'   => $limit,
		))->getPosts();
	}
	
	/**
	 * Retrieve a post by id.
	 *
	 * @param string name
	 * @param integer postID
	 * @return object BloggerFeed
	 */
	public function getPostByID($name, $postID) {
		return $this->executeMethod('blogger.posts.getPostByID', array(
			'version' => $this->version,
			'name'    => $name,
			'postID'  => $postID,
		))->getPost();
	}
	
	/**
	 * Retrieve a post by slug, using cached mappings of slugs to postIDs.
	 *
	 * @param string name
	 * @param string slug
	 * @return object BloggerFeed
	 */
	public function getPostBySlug($name, $slug) {
		$cache = new ArrayCache(array(
			'dir' => Config::getInstance()->get('cache_dir').'/blog',
		));
		$mappings = $cache->get('slugs', array());
		$postID = $mappings[$slug];
		
		return $this->getPostByID($name, $postID);
	}
	
	/**
	 * Retrieve a list of comments.
	 *
	 * @param string name
	 * @return object BloggerFeed
	 */
	public function getComments($name) {
		return $this->executeMethod('blogger.comments.getComments', array(
			'version' => $this->version,
			'name'    => $name,
		))->getComments();
	}
	
	/**
	 * Retrieve a list of comments.
	 *
	 * @param string name
	 * @param integer postID
	 * @return object BloggerFeed
	 */
	public function getCommentsByPostID($name, $postID) {
		return $this->executeMethod('blogger.comments.getCommentsByPostID', array(
			'version' => $this->version,
			'name'    => $name,
			'postID'  => $postID,
		))->getComments();
	}
	
	/**
	 * Add a comment to a post.
	 *
	 * @param string name
	 * @param integer postID
	 * @return object BloggerFeed
	 */
	public function addComment($name, $postID, $author, $title, $comment) {
		if (!Session::has('auth', 'blogger')) {
			Session::set('auth', $this->authenticate(), 'blogger');
		}
		$auth = Session::get('auth', '', 'blogger');
		$url = $this->buildUrl('blogger.comments.getCommentsByPostID', array(
			'version' => $this->version,
			'name'    => $name,
			'postID'  => $postID,
		));
		
		$xml = array();
		$xml[] = '<entry xmlns="http://www.w3.org/2005/Atom">';
		$xml[] = '  <title type="text">'.$title.'</title>';
		$xml[] = '  <content type="html">'.$comment.'</content>';
		$xml[] = '  <author>';
		$xml[] = '    <name>'.$author.'</name>';
		$xml[] = '  </author>';
		$xml[] = '</entry>';
		
		// POST a comment
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/atom+xml',
			'Authorization: GoogleLogin auth='.$auth,
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode("", $xml));
		
		$data = curl_exec($ch);
		curl_close($ch);
		
		// TODO: decide if $data needs to be returned (contains <entry> result)
	}
	
	/**
	 * Authenticate with google, and return the authentication string.
	 *
	 * @return string
	 */
	public function authenticate() {
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/accounts/ClientLogin');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array( // TODO: externalize credentials
			'Email' => 'stephen.riesenberg@gmail.com',
			'Passwd' => 'turkeynmayo',
			'service' => 'blogger',
			'accountType' => 'GOOGLE',
			'source' => 'iconium-www-3',
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$data = curl_exec($ch);
		curl_close($ch);
		
		$auth = explode("\n", $data);
		$auth2 = explode('=', $auth[2]);
		
		return $auth2[1];
	}
	
	/**
	 * Build a url, based on an api method.
	 *
	 * @param string method
	 * @param array params
	 * @return string
	 */
	public function buildUrl($method, $params = array()) {
		switch ($method) {
			case 'blogger.blogs.getBlogsByUserID':
				return "http://www.blogger.com/feeds/{$params['userID']}/blogs";
				
			case 'blogger.blogs.getBlogsByUsername':
				return ""; // TODO
				
			case 'blogger.blogs.getBlogByName':
				return "http://{$params['name']}.blogspot.com/feeds"; // TODO
				
			case 'blogger.blogs.getBlogByID':
				return "http://www.blogger.com/feeds/{$params['blogID']}"; // TODO
				
			case 'blogger.posts.getPosts':
				//return "http://www.blogger.com/feeds/{$params['blogID']}/posts/{$params['version']}?start-index={$params['idx']}&max-results={$params['limit']}";
				return "http://{$params['name']}.blogspot.com/feeds/posts/{$params['version']}?start-index={$params['idx']}&max-results={$params['limit']}";
				
			case 'blogger.posts.getPostsByLabel':
				//return "http://www.blogger.com/feeds/{$params['blogID']}/posts/{$params['version']}/-/{$params['label']}?start-index={$params['idx']}&max-results={$params['limit']}";
				return "http://{$params['name']}.blogspot.com/feeds/posts/{$params['version']}/-/{$params['label']}?start-index={$params['idx']}&max-results={$params['limit']}";
				
			case 'blogger.posts.getPostByID':
				//return "http://www.blogger.com/feeds/{$params['blogID']}/posts/{$params['version']}/{$params['postID']}";
				return "http://{$params['name']}.blogspot.com/feeds/posts/{$params['version']}/{$params['postID']}";
				
			case 'blogger.comments.getComments':
				//return "http://www.blogger.com/feeds/{$params['blogID']}/comments/{$params['version']}";
				return "http://{$params['name']}.blogspot.com/feeds/comments/{$params['version']}";
				
			case 'blogger.comments.getCommentsByPostID':
				//return "http://www.blogger.com/feeds/{$params['blogID']}/{$params['postID']}/comments/{$params['version']}";
				return "http://{$params['name']}.blogspot.com/feeds/{$params['postID']}/comments/{$params['version']}";
				
			case 'blogger.comments.getCommentByID':
				return ""; // TODO
		}
	}
	
	/**
	 * Execute this Blogger API request.
	 *
	 * @param string method
	 * @param array params
	 * @param integer timeout
	 * @return object BloggerFeed
	 */
	public function executeMethod($method, $params = array(), $timeout = 30) {
		$cache = new FileCache(array(
			'dir' => Config::getInstance()->get('cache_dir').'/blog',
			'timeout' => Config::getInstance()->get('cache_timeout', 60) * 60 * 24,
			'ext' => '.cache',
		));
		$url = $this->buildUrl($method, $params);
		$data = $cache->get(md5($url));
		if (!$data) {
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			
			// bump PHP's timeout
			set_time_limit($timeout + $timeout + 5);
			
			$data = curl_exec($ch);
			curl_close($ch);
			
			$cache->set(md5($url), $data);
		}
		
		return new BloggerFeed(simplexml_load_string($data));
	}
}

?>