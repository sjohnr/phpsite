<?php

/**
 * BloggerFeed class.
 *
 * @author Stephen Riesenberg
 */
class BloggerFeed {
	private $feed;
	private $cache;
	
	/**
	 * Wrap an xml feed.
	 *
	 * @param string feed
	 */
	public function __construct($feed) {
		$this->feed = $feed;
	}
	
	/**
	 * Retrieve blog id from this feed.
	 *
	 * @return integer
	 */
	public function getBlogID() {
		return substr($this->feed->id, strpos($this->feed->id, 'blog-')+5);
	}
	
	/**
	 * Retrieve list of posts from this feed.
	 *
	 * @return array [object BloggerPost]
	 */
	public function getPosts() {
		// return cache if exists
		if ($this->cache != null) {
			return $this->cache;
		}
		
		// build posts array
		$posts = array();
		foreach ($this->getEntries() as $entry) {
			$posts[] = new BloggerPost($entry);
		}
		
		// HACK: add postID to slugs cache
		if (Config::getInstance()->get('is_debug')) {
			$this->cache($posts);
		}
		
		// cache the post array
		$this->cache = $posts;
		
		return $posts;
	}
	
	/**
	 * Retrieve a single post from this feed.
	 *
	 * @return object BloggerPost
	 */
	public function getPost() {
		$entries = $this->getEntries();
		$post = new BloggerPost($entries[0]);
		
		// HACK: add postID to slugs cache
		$this->cache(array($post));
		
		return $post;
	}
	
	/**
	 * HACK: add postID to slugs cache
	 *
	 * @param array posts
	 */
	private function cache($posts) {
		$cache = new ArrayCache(array(
			'dir' => Config::getInstance()->get('cache_dir').'/blog',
		));
		
		$mappings = $cache->get('slugs', array());
		foreach ($posts as $post) {
			$mappings[$post->getNormalizedTitle()] = $post->getPostID();
		}
		
		$cache->set('slugs', $mappings);
	}
	
	/**
	 * Retrieve list of comments from this feed.
	 *
	 * @return array
	 */
	public function getComments() {
		// return cache if exists
		if ($this->cache != null) {
			return $this->cache;
		}
		
		$comments = array();
		foreach ($this->getEntries() as $entry) {
			$comments[] = new BloggerComment($entry);
		}
		
		// cache the post objects
		$this->cache = $comments;
		
		return $comments;
	}
	
	/**
	 * Utility function to initiailize a standardized entry array.
	 *
	 * @return array
	 */
	private function getEntries() {
		$feed = $this->feed;
		
		$entries = array();
		if (isset($feed->entry) && isset($feed->entry[0]))
			foreach ($feed->entry as $entry)
				$entries[] = $entry;
		else if (isset($feed->entry))
			$entries[] = $feed->entry;
		else if (isset($feed->content))
			$entries[] = $feed;
		
		return $entries;
	}
	
	// TODO
	public function getTotalResults() {
		return $this->feed['openSearch:totalResults'];
	}
}

?>