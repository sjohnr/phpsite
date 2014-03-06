<?php

/**
 * FlickrResult class.
 *
 * @author Stephen Riesenberg
 */
class FlickrResult {
	private $api;
	private $feed;
	
	/**
	 * Construct a Flickr result wrapper.
	 *
	 * @param object api
	 * @param string feed
	 */
	public function __construct(FlickrApi $api, $feed) {
		$this->api = $api;
		$this->feed = $feed;
	}
	
	/**
	 * Retrieve raw xml object.
	 *
	 * @return object
	 */
	public function getRaw() {
		return $this->feed;
	}
	
	/**
	 * Retrieve a list of photos from the Flickr feed.
	 *
	 * @return array [object FlickrPhoto]
	 */
	public function getPhotos() {
		$photos = array();
		foreach ($this->feed->photos->photo as $photo) {
			$photos[] = $this->api->getPhotoById((string) $photo['id']);
		}
		
		return $photos;
	}
	
	/**
	 * Retrieve a photo from this Flickr feed.
	 *
	 * @return object FlickrPhoto
	 */
	public function getPhoto() {
		return new FlickrPhoto($this->api, $this->feed->photo);
	}
	
	/**
	 * Retrieve a list of groups from the Flickr feed.
	 *
	 * @return array [object FlickrGroup]
	 */
	public function getGroups() {
		$groups = array();
		foreach ($this->feed->group as $group) {
			$groups[] = new FlickrGroup($this->api, $group);
		}
		
		return $groups;
	}
	
	/**
	 * Retrieve a list of comments from this Flickr feed.
	 *
	 * @return array [object FlickrComment]
	 */
	public function getComments() {
		$comments = array();
		foreach ($this->feed->comments->comment as $comment) {
			$comments[] = new FlickrComment($this->api, $comment);
		}
		
		return $comments;
	}
	
	/**
	 * Retrieve a user from the Flickr feed.
	 *
	 * @return object FlickrUser
	 */
	public function getUser() {
		return new FlickrUser($this->api, $this->feed->person);
	}
}

?>
