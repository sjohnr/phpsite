<?php

/**
 * FlickrApi class.
 *
 * @author Stephen Riesenberg
 */
class FlickrApi {
	const REST_ENDPOINT_URL = 'http://flickr.com/services/rest/';
	
	private $key;
	private $secret;
	private $token;
	
	/**
	 * Access the Flickr API.
	 *
	 * @param string key
	 * @param string secret
	 * @param string token
	 */
	public function __construct($key, $secret, $token = null) {
		$this->key = $key;
		$this->secret = $secret;
		$this->token = $token;
	}
	
	/**
	 * Retrieve the API key.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}
	
	/**
	 * Retrieve the API secret.
	 *
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}
	
	/**
	 * Retrieve the authentication token for this session.
	 *
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}
	
	/**
	 * Construct the url for this Flickr API request.
	 *
	 * @param string method
	 * @param array params
	 * @return string
	 */
	private function buildUrl($method, $params = array()) {
		$tmp = array();
		$tmp['api_key'] = $this->key;
		if ($this->token) {
			$tmp['auth_token'] = $this->token;
		}
		$tmp['method'] = $method;
		
		$url = array();
		$sig = '';
		$params = array_merge($tmp, $params);
		ksort($params);
		foreach ($params as $key => $value) {
			$sig .= $key.$value;
			$url[] = $key.'='.$value;
		}
		$url[] = 'api_sig='.md5($this->secret.$sig);
		
		return FlickrApi::REST_ENDPOINT_URL.'?'.implode('&', $url);
	}
	
	/**
	 * Execute this Flickr API request.
	 *
	 * @param integer timeout
	 */
	public function executeMethod($method, $params = array(), $timeout = 30) {
		$cache = new FileCache(array(
			'dir' => Config::getInstance()->get('cache_dir').'/flickr',
			'timeout' => Config::getInstance()->get('cache_timeout', 60) * 60 * 24,
			'ext' => '.cache',
		));
		$url = $this->buildUrl($method, $params);
		$data = $cache->get(md5($url));
		if (!$data) {
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			
			// bump PHP's timeout
			set_time_limit($timeout + $timeout + 5);
			
			$data = curl_exec($ch);
			curl_close($ch);
			
			$cache->set(md5($url), $data);
		}
		
		$xml = simplexml_load_string($data);
		if (((string) $xml['stat']) != 'ok') {
			throw new Exception('Error: '.$xml->err['msg']);
		}
		
		return new FlickrResult($this, $xml);
	}
	
	/**
	 * Retrieve a user by username.
	 *
	 * @param string username
	 */
	public function getUserByUsername($username) {
		$feed = $this->executeMethod('flickr.people.findByUsername', array(
			'username' => $username,
		))->getRaw();
		
		return $this->getUserById((string) $feed->user['nsid']);
	}
	
	/**
	 * Retrieve a user by id.
	 *
	 * @param string user_id
	 */
	public function getUserById($user_id) {
		return $this->executeMethod('flickr.people.getInfo', array(
			'user_id' => $user_id,
		))->getUser();
	}
	
	/**
	 * Retrieve a photo by id.
	 *
	 * @param string photo_id
	 */
	public function getPhotoById($photo_id) {
		return $this->executeMethod('flickr.photos.getInfo', array(
			'photo_id' => $photo_id,
		))->getPhoto();
	}
	
	/**
	 * Retrieve a list of comments for a given photo.
	 *
	 * @param integer photo_id
	 * @return array [object FlickrComment]
	 */
	public function getCommentsByPhotoId($photo_id) {
		return $this->executeMethod('flickr.photos.comments.getList', array(
			'photo_id' => $photo_id,
		))->getComments();
	}
	
	/**
	 * Retrieve public photos for a given user.
	 *
	 * @param string user_id
	 * @param integer page
	 * @param integer per_page
	 * @return array [object FlickrPhoto]
	 */
	public function getPhotosByUserId($user_id, $page = 1, $per_page = 10) {
		return $this->executeMethod('flickr.people.getPublicPhotos', array(
			'user_id'  => $user_id,
			'per_page' => $per_page,
			'page'     => $page,
		))->getPhotos();
	}
	
	/**
	 * Retrieve public photos for a given user by tag.
	 *
	 * @param string user_id
	 * @param string tag
	 * @param integer page
	 * @param integer per_page
	 * @return array [object FlickrPhoto]
	 */
	public function getPhotosByTag($user_id, $tag, $page = 1, $per_page = 10) {
		return $this->executeMethod('flickr.photos.search', array(
			'user_id'  => $user_id,
			'tags'     => $tag,
			'per_page' => $per_page,
			'page'     => $page,
		))->getPhotos();
	}
}

?>
