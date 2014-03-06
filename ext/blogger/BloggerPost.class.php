<?php

/**
 * BloggerPost class.
 *
 * @author Stephen Riesenberg
 */
class BloggerPost {
	private $entry;
	
	/**
	 * Wrap a blogger post entry.
	 *
	 * @param object entry
	 */
	public function __construct($entry) {
		$this->entry = $entry;
	}
	
	/**
	 * Retrieve the post id of this post.
	 *
	 * @return integer
	 */
	public function getPostID() {
		return substr($this->entry->id, strpos($this->entry->id, 'post-')+5);
	}
	
	/**
	 * Retrieve the post title.
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->entry->title;
	}
	
	/**
	 * Retrieve the a normalized post title.
	 *
	 * @return string
	 */
	public function getNormalizedTitle() {
		$title = strtolower($this->getTitle());
		$title = preg_replace('/\W/', '-', $title);
		
		// trim off trailing "-"
		while (substr($title, strlen($title) - 1, 1) == '-') {
			$title = substr($title, 0, strlen($title) - 1);
		}
		
		return $title;
	}
	
	/**
	 * Retrieve the content from this post.
	 *
	 * @return string
	 */
	public function getContent() {
		$content = $this->entry->content;
		$content = str_replace('<br /><br />', '</p><p>', $content);
		$content = str_replace('<br /><ul>', '</p><ul>', $content);
		$content = str_replace('</ul><br />', '</ul><p>', $content);
		
		return $content;
	}
	
	/**
	 * Retrieve the summary of this post.
	 *
	 * @return string
	 */
	public function getSummary() {
		return $this->entry->summary;
	}
	
	/**
	 * Retrieve the author name of this post.
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->entry->author->name;
	}
	
	/**
	 * Get a list of labels/categories tagged on this post.
	 *
	 * @return array
	 */
	public function getLabels() {
		// get category array from entry
		if (!isset($this->entry->category)) {
			$ar = array();
		} else if (!isset($this->entry->category[0])) {
			$ar = array($this->entry->category);
		} else {
			$ar = $this->entry->category;
		}
		
		// build simple category array
		$categories = array();
		foreach ($ar as $elem) {
			$categories[] = ((string) $elem['term']);
		}
		
		return $categories;
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