<?php

/**
 * BloggerComment class.
 *
 * @author Stephen Riesenberg
 */
class BloggerComment {
	private $entry;
	
	/**
	 * Wrap a blogger comment entry.
	 *
	 * @param object entry
	 */
	public function __construct($entry) {
		$this->entry = $entry;
	}
	
	/**
	 * Retrieve the comment id.
	 *
	 * @return integer
	 */
	public function getCommentID() {
		return substr($this->entry->id, strpos($this->entry->id, 'post-')+5);
	}
	
	/**
	 * Retrieve the comment title.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->entry->title;
	}
	
	/**
	 * Retrieve the content from this comment.
	 *
	 * @return string
	 */
	public function getContent() {
		if (isset($this->entry->content))
			return $this->entry->content;
		else
			return $this->entry->summary;
	}
	
	/**
	 * Retrieve the author name of this comment.
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->entry->author->name;
	}
	
	/**
	 * Published date.
	 *
	 * @return string
	 */
	public function getPublishedDate($format = 'Y-m-d H:i:s') {
		return date($format, strtotime($this->entry->published));
	}
	
	/**
	 * Updated date.
	 *
	 * @return string
	 */
	public function getUpdatedDate($format = 'Y-m-d H:i:s') {
		return date($format, strtotime($this->entry->updated));
	}
}

?>