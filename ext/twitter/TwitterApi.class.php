<?php

/**
 * TwitterApi class.
 *
 * @author Stephen Riesenberg
 */
class TwitterApi {
	private $format;
	
	/**
	 * Access the Twitter API.
	 *
	 */
	public function __construct($format = 'xml') {
		$this->format = $format;
	}
	
	/**
	 * Build a url, based on an api method.
	 *
	 * @param string method
	 * @param array params
	 * @return string
	 */
	public function buildUrl($method, $params = array()) {
		switch ($method) {
			case 'twitter.users.getUserByName':
				return "http://www.twitter.com/users/show/{$params['name']}.{$this->format}";
			case 'twitter.users.getTimelineByName':
				return "http://www.twitter.com/statuses/user_timeline.{$this->format}?screen_name={$params['name']}";
			case 'twitter.users.update':
				return "http://www.twitter.com/statuses/update.{$this->format}";
			case 'twitter.users.destroy':
				return "http://www.twitter.com/statuses/destroy/{$params['id']}.{$this->format}";
		}
	}
	
	/**
	 * Execute this Blogger API request.
	 *
	 * @param string method
	 * @param array params
	 * @param integer timeout
	 * @return object BloggerFeed
	 */
	public function executeMethod($method, $params = array(), $timeout = 30) {
		$cache = new FileCache(array(
			'dir' => Config::getInstance()->get('cache_dir').'/twitter',
			'timeout' => Config::getInstance()->get('cache_timeout', 60) * 60 * 24,
			'ext' => '.cache',
		));
		$url = $this->buildUrl($method, $params);
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
		
		return new TwitterFeed($this, simplexml_load_string($data));
	}
	
	/**
	 * Retrieve a twitter user by name.
	 *
	 * @param string name
	 */
	public function getUserByName($name) {
		return $this->executeMethod('twitter.users.getUserByName', array(
			'name' => $name,
		))->getUser();
	}
	
	/**
	 * Retrieve a list of twitter updates by user name.
	 *
	 * @param string name
	 */
	public function getTimelineByName($name) {
		return $this->executeMethod('twitter.users.getTimelineByName', array(
			'name' => $name,
		))->getUpdates();
	}
	
	/**
	 * Update a twitter timeline by issuing an authenticated POST with a status.
	 *
	 * @param string name
	 * @param string password
	 * @param string status
	 */
	public function addUpdate($name, $password, $status) {
		$url = $this->buildUrl('twitter.users.update');
		
		// post a status
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Basic '.base64_encode($name.':'.$password),
			'Expect:',
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'status' => $status,
		));
		
		$data = curl_exec($ch);
		curl_close($ch);
		
		// TODO return data?
	}
	
	/**
	 * Destroy a twitter update by issuing an authenticated POST (or DELETE).
	 *
	 * @param string name
	 * @param string password
	 * @param int statusId
	 */
	public function destroy($name, $password, $statusId) {
		$url = $this->buildUrl('twitter.users.destroy', array(
			'id' => $statusId,
		));
		
		// post a status
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Basic '.base64_encode($name.':'.$password),
			'Expect:',
		));
		
		$data = curl_exec($ch);
		curl_close($ch);
		
		// TODO return data?
	}
}

?>
