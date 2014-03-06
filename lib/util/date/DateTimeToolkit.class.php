<?php

/**
 * DateTimeTools class.
 *
 * @author Stephen Riesenberg
 */
class DateTimeToolkit {
	/**
	 * Breaks down the individual components of the timestamp.
	 *
	 * @param timestamp
	 * @return array
	 */
	public static function breakDownTimestamp($ts = null) {
		// default to now
		if ($ts === null)
			$ts = self::now();
		
		// gather individual variables
		$H = date('H', $ts); // hour
		$i = date('i', $ts); // minute
		$s = date('s', $ts); // second
		$m = date('m', $ts); // month
		$d = date('d', $ts); // day
		$Y = date('Y', $ts); // year
		
		return array($H, $i, $s, $m, $d, $Y);
	}
	
	/**
	 * Returns the current timestamp.
	 *
	 * @return timestamp
	 * @see time
	 */
	public static function now() {
		return time();
	}
	
	/**
	 * Retrieve the timestamp from a number of different formats.
	 *
	 * @param mixed value to use for timestamp retrieval
	 */
	public static function getTimestamp($value = null) {
		if ($value === null)
			return time(); // now()
		else if ($value instanceof Date)
			return $value->get();
		else if (!is_numeric($value))
			return strtotime($value);
		else if (is_numeric($value))
			return $value;
		
		throw new Exception(sprintf('A timestamp could not be retrieved from the value: %s', $value));
	}
}
