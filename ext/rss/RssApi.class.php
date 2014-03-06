<?php

/**
 * RssApi class.
 *
 * @author Stephen Riesenberg
 */
class RssApi {
	/**
	 * Access an RSS 2.0 feed.
	 */
	public function __construct() {
		
	}
	
	/**
	 *
	 */
	public function getFeed($url, $timeout = 30) {
		$cache = new FileCache(array(
			'dir' => Config::getInstance()->get('cache_dir').'/rss',
			'timeout' => Config::getInstance()->get('cache_timeout', 60) * 60 * 24,
			'ext' => '.cache',
		));
		
		$data = $cache->get(md5($url));
		if (!$data) {
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			
			// bump PHP's timeout
			set_time_limit($timeout + $timeout + 5);
			
			$data = curl_exec($ch);
			curl_close($ch);
			
			$cache->set(md5($url), $data);
		}
		
		return new RssFeed($this, simplexml_load_string($data));
	}
}

?>
