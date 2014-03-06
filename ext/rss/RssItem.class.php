<?php

/**
 * RssItem class.
 *
 * @author Stephen Riesenberg
 */
class RssItem {
	private $api;
	private $item;
	
	/**
	 * Wrap an xml feed.
	 *
	 * @param object api
	 * @param string item
	 */
	public function __construct(RssApi $api, $item) {
		$this->api = $api;
		$this->item = $item;
	}
	
	/**
	 * Retrieve the item title.
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->item->title;
	}
	
	/**
	 * Retrieve the item description.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->item->description;
	}
	
	/**
	 * Retrieve the item link.
	 *
	 * @return string
	 */
	public function getLink() {
		return $this->item->link;
	}
	
	/**
	 * Retrieve the item category.
	 *
	 * @return string
	 */
	public function getCategory() {
		return $this->channel->category;
	}
	
	/**
	 * Retrieve the published date.
	 *
	 * @return string
	 */
	public function getPublishedDate($format = 'Y-m-d H:i:s') {
		return date($format, strtotime($this->item->pubDate));
	}
}

?>
