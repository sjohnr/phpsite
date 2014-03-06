<?php

/**
 * FlickrUser class.
 *
 * @author Stephen Riesenberg
 */
class FlickrUser {
	private $user;
	
	/**
	 * Construct a Flickr user wrapper.
	 *
	 * @param object api
	 * @param string user
	 */
	public function __construct(FlickrApi $api, $user) {
		$this->api = $api;
		$this->user = $user;
	}
	
	/**
	 * Retrieve the user id for this Flickr user.
	 *
	 * @return string
	 */
	public function getId() {
		return (string) $this->user['nsid'];
	}
	
	/**
	 * Retrieve the username for this Flickr user.
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->user->username;
	}
	
	/**
	 * Retrieve public photos for this user.
	 *
	 * @return array [object FlickrPhoto]
	 */
	public function getPhotos($page = 1, $per_page = 10) {
		return $this->api->getPhotosByUserId($this->getId(), $page, $per_page);
	}
	
	/**
	 * Retrieve public photos for this user by tag.
	 *
	 * @param string tag
	 * @param integer page
	 * @param integer per_page
	 * @return array [object FlickrPhoto]
	 */
	public function getPhotosByTag($tag, $page = 1, $per_page = 10) {
		return $this->api->getPhotosByTag($this->getId(), $tag, $page, $per_page);
	}
}

?>
