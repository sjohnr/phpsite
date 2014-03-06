<?php

/**
 * TwitterUpdate class.
 *
 * @author Stephen Riesenberg
 */
class TwitterUpdate {
	private $api;
	private $status;
	
	/**
	 * Construct a Twitter update wrapper.
	 *
	 * @param object api
	 * @param string user
	 */
	public function __construct(TwitterApi $api, $status) {
		$this->api = $api;
		$this->status = $status;
	}
	
	/**
	 * Retrieve the update id.
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->status->id;
	}
	
	/**
	 * Retrieve the text of this update.
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->status->text;
	}
	
	/**
	 * Retrieve the source of this update.
	 *
	 * @return string
	 */
	public function getSource() {
		return $this->status->source;
	}
	
	/**
	 * Retrieve the created on time.
	 *
	 * @return string
	 */
	public function getPublishedDate($format = 'Y-m-d H:i:s') {
		return date($format, strtotime($this->status->created_at));
	}
}

?>
