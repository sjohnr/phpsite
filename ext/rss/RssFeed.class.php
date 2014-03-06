<?php

/**
 * RssFeed class.
 *
 * @author Stephen Riesenberg
 */
class RssFeed {
	private $api;
	private $channel;
	
	/**
	 * Wrap an xml feed.
	 *
	 * @param object api
	 * @param string feed
	 */
	public function __construct(RssApi $api, $feed) {
		$this->api = $api;
		$this->channel = $feed->channel;
	}
	
	/**
	 * Retrieve the channel title.
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->channel->title;
	}
	
	/**
	 * Retrieve the channel description.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->channel->description;
	}
	
	/**
	 * Retrieve the channel link.
	 *
	 * @return string
	 */
	public function getLink() {
		return $this->channel->link;
	}
	
	/**
	 * Retrieve the published date.
	 *
	 * @return string
	 */
	public function getPublishedDate($format = 'Y-m-d H:i:s') {
		return date($format, strtotime($this->channel->pubDate));
	}
	
	/**
	 * Retrieve a list of items for this channel.
	 *
	 * @return array [object RssItem]
	 */
	public function getItems() {
		$items = array();
		foreach ($this->channel->item as $item) {
			$items[] = new RssItem($this->api, $item);
		}
		
		return $items;
	}
}

?>
