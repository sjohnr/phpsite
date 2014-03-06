<?php

/**
 * TwitterUser class.
 *
 * @author Stephen Riesenberg
 */
class TwitterUser {
	private $api;
	private $user;
	
	/**
	 * Construct a Twitter user wrapper.
	 *
	 * @param object api
	 * @param string user
	 */
	public function __construct(TwitterApi $api, $user) {
		$this->api = $api;
		$this->user = $user;
	}
	
	/**
	 * Retrieve the user id.
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->user->id;
	}
	
	/**
	 * Retrieve the username.
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->user->screen_name;
	}
	
	/**
	 * Retrieve the name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->user->name;
	}
	
	/**
	 * Retrieve the user location string.
	 *
	 * @return string
	 */
	public function getLocation() {
		return $this->user->location;
	}
	
	/**
	 * Retrieve the description.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->user->description;
	}
	
	/**
	 * Retrieve the profile image url.
	 *
	 * @return string
	 */
	public function getProfileImageUrl() {
		return $this->user->profile_image_url;
	}
	
	/**
	 * Retrieve the website url.
	 *
	 * @return string
	 */
	public function getWebsiteUrl() {
		return $this->user->url;
	}
	
	/**
	 * Retrieve the number of followers.
	 *
	 * @return integer
	 */
	public function getFollowersCount() {
		return $this->user->followers_count;
	}
	
	/**
	 * Retrieve the number of friends.
	 *
	 * @return integer
	 */
	public function getFriendsCount() {
		return $this->user->friends_count;
	}
	
	/**
	 * Retrieve the number of favorites.
	 *
	 * @return integer
	 */
	public function getFavoritesCount() {
		$this->user->favorites_count;
	}
	
	/**
	 * Retrieve the number of statuses (updates).
	 *
	 * @return integer
	 */
	public function getUpdatesCount() {
		return $this->user->statuses_count;
	}
	
	/**
	 * Retrieve the most current update.
	 *
	 * @return integer
	 */
	public function getCurrentUpdate() {
		return new TwitterUpdate($this->api, $this->user->status);
	}
	
	/**
	 * Retrieve this users timeline.
	 *
	 * @return array
	 */
	public function getTimeline() {
		return $this->api->getTimelineByName($this->user->screen_name);
	}
}

?>
