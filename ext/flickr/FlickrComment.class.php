<?php

/**
 * FlickrComment class.
 *
 * @author Stephen Riesenberg
 */
class FlickrComment {
	private $api;
	private $comment;
	
	/**
	 * Construct a Flickr entry wrapper.
	 *
	 * @param object api
	 * @param string comment
	 */
	public function __construct(FlickrApi $api, $comment) {
		$this->api = $api;
		$this->comment = $comment;
	}
	
	/**
	 * Retrieve the comment id.
	 *
	 * @return string
	 */
	public function getId() {
		return (string) $this->comment['id'];
	}
	
	/**
	 * Retrieve the comment id.
	 *
	 * @return string
	 */
	public function getUserId() {
		return (string) $this->comment['author'];
	}
	
	/**
	 * Retrieve the username.
	 *
	 * @return string
	 */
	public function getUsername() {
		return (string) $this->comment['authorname'];
	}
	
	/**
	 * Retrieve the comment text.
	 *
	 * @return string
	 */
	public function getContent() {
		return (string) $this->comment;
	}
	
	/**
	 * Retrieve the date created.
	 *
	 * @return string
	 */
	public function getPublishedDate($format = 'Y-m-d H:i:s') {
		return date($format, strtotime((string) $this->comment['datecreate']));
	}
	
	/**
	 * Retrieve the owner of this comment.
	 *
	 * @return object FlickrUser
	 */
	public function getUser() {
		return $this->api->getUserById($this->getUserId());
	}
}

?>
