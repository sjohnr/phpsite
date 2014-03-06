<?php

/**
 * TwitterFeed class.
 *
 * @author Stephen Riesenberg
 */
class TwitterFeed {
	private $api;
	private $feed;
	
	/**
	 * Wrap an xml feed.
	 *
	 * @param object api
	 * @param string feed
	 */
	public function __construct(TwitterApi $api, $feed) {
		$this->api = $api;
		$this->feed = $feed;
	}
	
	/**
	 * Retrieve a user from this feed.
	 *
	 * @return object TwitterUser
	 */
	public function getUser() {
		return new TwitterUser($this->api, $this->feed);
	}
	
	/**
	 * Retrieve a list of updates from this feed.
	 *
	 * @return array [object TwitterUpdate]
	 */
	public function getUpdates() {
		$updates = array();
		foreach ($this->feed->status as $status) {
			$updates[] = new TwitterUpdate($this->api, $status);
		}
		
		return $updates;
	}
}

?>
