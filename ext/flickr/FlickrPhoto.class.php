<?php

/**
 * FlickrPhoto class.
 *
 * @author Stephen Riesenberg
 */
class FlickrPhoto {
	/**
     * Small square, 75x75px photo size.
     *
     * @var string
    */
    const SIZE_75PX = 's';
	
    /**
     * Thumbnail, 100px on longest side
     *
     * @var string
    */
    const SIZE_100PX = 't';
	
    /**
     * Small, 240px on longest side
     *
     * @var string
    */
    const SIZE_240PX = 'm';
	
    /**
     * Medium, 500px on longest side
     *
     * @var string
    */
    const SIZE_500PX = '-';
	
    /**
     * Large, 1024px on longest side (only exists for very large original images)
     *
     * @var string
    */
    const SIZE_1024PX = 'b';
	
    /**
     * Original image size.
	 *
     * @var string
    */
    const SIZE_ORIGINAL = 'o';
	
	private $api;
	private $photo;
	
	/**
	 * Construct a Flickr photo wrapper.
	 *
	 * @param object api
	 * @param string photo
	 */
	public function __construct(FlickrApi $api, $photo) {
		$this->api = $api;
		$this->photo = $photo;
	}
	
	/**
	 * Retrieve the photo id for this Flickr post.
	 *
	 * @return string
	 */
	public function getId() {
		return (string) $this->photo['id'];
	}
	
	/**
	 * Retrieve the owner for this Flickr post.
	 *
	 * @return string
	 */
	public function getUserId() {
		return (string) $this->photo->owner['nsid'];
	}
	
	/**
	 * Retrieve the secret for this Flickr post.
	 *
	 * @return string
	 */
	public function getSecret() {
		return (string) $this->photo['secret'];
	}
	
	/**
	 * Retrieve the server for this Flickr post.
	 *
	 * @return string
	 */
	public function getServer() {
		return (string) $this->photo['server'];
	}
	
	/**
	 * Retrieve the farm for this Flickr post.
	 *
	 * @return string
	 */
	public function getFarm() {
		return (string) $this->photo['farm'];
	}
	
	/**
	 * Retrieve the title for this Flickr post.
	 *
	 * @return string
	 */
	public function getTitle() {
		return (string) $this->photo->title;
	}
	
	/**
	 * Build the public (flickr) url for this photo.
	 *
	 * @return string
	 */
	public function getPublicUrl() {
		return "http://flickr.com/photos/{$this->getUserId()}/{$this->getId()}/";
	}
	
	/**
	 * Build the image (src) url for this photo.
	 *
	 * <b>Alt:</b>
	 * <code>return sprintf("http://farm%d.static.flickr.com/%d/%d_%s%s.%s", $this->getFarm(), $this->getServer(), $this->getId(), $this->getSecret(), ($size == self::SIZE_500PX ? '' : '_'.$size), 'jpg');</code>
	 *
	 * @return string
	 */
	public function getImageUrl($size = self::SIZE_240PX) {
		if ($size == self::SIZE_500PX) {
			return "http://farm{$this->getFarm()}.static.flickr.com/{$this->getServer()}/{$this->getId()}_{$this->getSecret()}.jpg";
		} else {
			return "http://farm{$this->getFarm()}.static.flickr.com/{$this->getServer()}/{$this->getId()}_{$this->getSecret()}_{$size}.jpg";
		}
	}
	
	/**
	 * Retrieve a list of comments for this photo.
	 *
	 * @return array [object FlickrComment]
	 */
	public function getComments() {
		return $this->api->getCommentsByPhotoId($this->getId());
	}
	
	/**
	 * Retrieve the labels tagged on this photo.
	 *
	 * @return array [string]
	 */
	public function getTags() {
		$tags = array();
		foreach ($this->photo->tags->tag as $tag) {
			$tags[] = (string) $tag;
		}
		
		return $tags;
	}
	
	/**
	 * Retrieve the owner of this photo.
	 *
	 * @return object FlickrUser
	 */
	public function getUser() {
		return $this->api->getUserById($this->getUserId());
	}
}

?>
