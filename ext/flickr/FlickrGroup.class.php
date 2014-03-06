<?php

/**
 * FlickrGroup class.
 *
 * @author Stephen Riesenberg
 */
class FlickrEntry {
	private $api;
	private $group;
	
	/**
	 * Construct a Flickr entry wrapper.
	 *
	 * @param object api
	 * @param string group
	 */
	public function __construct(FlickrApi $api, $group) {
		$this->api = $api;
		$this->group = $group;
	}
	
	/**
	 * Retrieve the group id for this Flickr group.
	 *
	 * @return string
	 */
	public function getId() {
		return (string) $this->group['nsid'];
	}
	
	/**
	 * Retrieve the name for this Flickr group.
	 *
	 * @return string
	 */
	public function getName() {
		return (string) $this->group['name'];
	}
	
	/**
	 * Build the public (flickr) url for this photo.
	 *
	 * @return string
	 */
	public function getPublicUrl() {
		return "http://flickr.com/groups/{$this->getId()}/";
	}
}

?>
