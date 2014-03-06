<?php

/**
 *
 * DateTime class.
 * <p>
 * A class for representing a date/time value as an object.
 * <p>
 * This class allows for chainable calculations that return
 * new instances of this class.
 *
 * @author Stephen Riesenberg
 */
class DateTime {
	/**
	 * Units of time
	 */
	const SECOND	= 0;
	const MINUTE	= 1;
	const HOUR		= 2;
	const DAY		= 3;
	const WEEK		= 4;
	const MONTH		= 5;
	const QUARTER	= 6;
	const YEAR		= 7;
	const DECADE	= 8;
	const CENTURY	= 9;
	const MILLENIUM	= 10;
	
	/**
	 * Days of the week
	 */
	const SUNDAY	= 0;
	const MONDAY	= 1;
	const TUESDAY	= 2;
	const WEDNESDAY	= 3;
	const THURSDAY	= 4;
	const FRIDAY	= 5;
	const SATURDAY	= 6;
	
	/**
	 * Months of the year
	 */
	const JANUARY	= 1;
	const FEBRUARY	= 2;
	const MARCH		= 3;
	const APRIL		= 4;
	const MAY		= 5;
	const JUNE		= 6;
	const JULY		= 7;
	const AUGUST	= 8;
	const SEPTEMBER	= 9;
	const OCTOBER	= 10;
	const NOVEMBER	= 11;
	const DECEMBER	= 12;
	
	/**
	 * The timestamp for this Date instance.
	 */
	private $ts = null;
	
	/**
	 * Retrieves a new instance of this class.
	 *
	 * NOTE: This is not the singleton pattern. Instead, it is for chainability ease-of-use.
	 *
	 * <b>Example:</b>
	 * <code>
	 *   echo Date::getInstance()->getFirstDayOfWeek()->addDay()->format('Y-m-d');
	 * </code>
	 *
	 * @param mixed	timestamp, string, or Date object
	 * @return DateTime
	 */
	public static function getInstance($value = null) {
		return new Date($value);
	}
	
	/**
	 * Construct an Date object.
	 *
	 * @param mixed	timestamp, string, or Date object
	 */
	public function __construct($value = null) {
		$this->set($value);
	}
	
	/**
	 * Format the date according to the <code>date</code> function.
	 *
	 * @return string
	 */
	public function format($format) {
		return date($format, $this->ts);
	}
	
	/**
	 * Format the date as a datetime value.
	 *
	 * @return string
	 */
	public function dump() {
		return date('Y-m-d H:i:s', $this->ts);
	}
	
	/**
	 * Retrieves the given unit of time from the timestamp.
	 *
	 * @param int unit of time (accepts DateTime constants).
	 * @return int the unit of time
	 */
	public function retrieve($unit = DateTime::DAY) {
		switch ($unit) {
			case DateTime::SECOND:
				return date('s', $this->ts);
			case DateTime::MINUTE:
				return date('i', $this->ts);
			case DateTime::HOUR:
				return date('H', $this->ts);
			case DateTime::DAY:
				return date('d', $this->ts);
			case DateTime::WEEK:
				return date('W', $this->ts);
			case DateTime::MONTH:
				return date('m', $this->ts);
			case DateTime::QUARTER:
				return ceil(date('m', $this->ts) / 3);
			case DateTime::YEAR:
				return date('Y', $this->ts);
			case DateTime::DECADE:
				return ceil((date('Y', $this->ts) % 100) / 10);
			case DateTime::CENTURY:
				return ceil(date('Y', $this->ts) / 100);
			case DateTime::MILLENIUM:
				return ceil(date('Y', $this->ts) / 1000);
			default:
				throw new Exception(sprintf('The unit of time provided is not valid: %s', $unit));
		}
	}
	
	/**
	 * Retrieve the timestamp value of this Date instance.
	 *
	 * @return timestamp
	 */
	public function get() {
		return $this->ts;
	}
	
	/**
	 * Sets the timestamp value of this Date instance.
	 *
	 * This function accepts several froms of a date value:
	 * - timestamp
	 * - string, parsed with <code>strtotime</code>
	 * - Date object
	 *
	 * @return Date	the modified object, for chainability
	 */
	public function set($value = null) {
		$this->ts = DateTimeToolkit::getTimestamp($value);
		
		return $this;
	}
	
	/**
	 * Compares two date values.
	 *
	 * @param mixed	timestamp, string, or Date object
	 * @return int a negative number for "less than", 0 for "equals",
	 *         and a positive number for "greater than"
	 */
	public function cmp($value) {
		$ts = DateTimeToolkit::getTimestamp($value);
		if ($this->ts == $ts) {
			return 0;
		}
		
		return $this->ts > $ts ? 1 : -1;
	}
	
	/**
	 * Gets the difference of two date values in seconds.
	 *
	 * @param mixed timestamp, string, or Date object
	 * @param int the difference in seconds
	 */
	public function diff($value) {
		$ts = DateTimeTools::getTimestamp($value);
		
		return $this->ts - $ts;
	}
	
/* ==== BEGIN CALCULATION METHODS =========================================== */
	
	/**
	 * Adds the specified number of given units of time to the given date.
	 * <p>
	 * <b>Example:</b>
	 * <code>
	 *   // day after
	 *   $dt = $dt->add();
	 *   // 5 days after
	 *   $dt = $dt->add(5);
	 *   // 2 months after
	 *   $dt = $dt->add(2, DateTime::MONTH);
	 *   // 4 weeks after
	 *   $dt = $dt->add(4, DateTime::WEEK);
	 * </code>
	 *
	 * @param timestamp	a timestamp for the calculation
	 * @param int the number of units to add to the given date
	 * @param int the unit to add by
	 * @return timestamp	the timestamp result of the calculation
	 */
	public function add($num = 1, $unit = DateTime::DAY) {
		// gather individual variables for readability and maintainability
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		// determine which unit of time to add by
		switch ($unit) {
			case DateTime::SECOND:
				$tmp = mktime($H, $i, $s + $num, $m, $d, $Y);
				break;
			case DateTime::MINUTE:
				$tmp = mktime($H, $i + $num, $s, $m, $d, $Y);
				break;
			case DateTime::HOUR:
				$tmp = mktime($H + $num, $i, $s, $m, $d, $Y);
				break;
			case DateTime::DAY:
				$tmp = mktime($H, $i, $s, $m, $d + $num, $Y);
				break;
			case DateTime::WEEK:
				$tmp = mktime($H, $i, $s, $m, $d + (7 * $num), $Y);
				break;
			case DateTime::MONTH:
				$tmp = mktime($H, $i, $s, $m + $num, $d, $Y);
				break;
			case DateTime::QUARTER:
				$tmp = mktime($H, $i, $s, $m + (3 * $num), $d, $Y);
				break;
			case DateTime::YEAR:
				$tmp = mktime($H, $i, $s, $m, $d, $Y + $num);
				break;
			case DateTime::DECADE:
				$tmp = mktime($H, $i, $s, $m, $d, $Y + (10 * $num));
				break;
			case DateTime::CENTURY:
				$tmp = mktime($H, $i, $s, $m, $d, $Y + (100 * $num));
				break;
			case DateTime::MILLENIUM:
				$tmp = mktime($H, $i, $s, $m, $d, $Y + (1000 * $num));
				break;
			default:
				throw new Exception(sprintf('The unit of time provided is not valid: %s', $unit));
		}
		
		return new DateTime($tmp);
	}
	
	/**
	 * Subtracts the specified number of given units of time from the given date.
	 * <p>
	 * <b>Example:</b>
	 * <code>
	 *   // day before
	 *   $dt = $dt->subtract();
	 *   // 5 days before
	 *   $dt = $dt->subtract(5);
	 *   // 2 months before
	 *   $dt = $dt->subtract(2, DateTime::MONTH);
	 *   // 4 weeks before
	 *   $dt = $dt->subtract(4, DateTime::WEEK);
	 * </code>
	 *
	 * @param timestamp	a timestamp for the calculation
	 * @param int the number of units to add to the given date
	 * @param int the unit to add by
	 * @return timestamp the timestamp result of the calculation
	 * @see add
	 */
	public function subtract($num = 1, $unit = DateTime::DAY) {
		return $this->add($num * -1, $unit);
	}
	
	/**
	 * Returns the timestamp with the date but without the time of day.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function clearTime() {
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		return new DateTime(mktime(0, 0, 0, $m, $d, $Y));
	}
	
	/**
	 * Set the second value of this timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function setSeconds($second = 0) {
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		return new DateTime(mktime($H, $i, $second, $m, $d, $Y));
	}
	
	/**
	 * Set the minute value of this timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function setMinutes($minute = 0) {
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		return new DateTime(mktime($H, $minute, $s, $m, $d, $Y));
	}
	
	/**
	 * Set the hour value of this timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function setHourOfDay($hour = 0) {
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		return new DateTime(mktime($hour, $i, $s, $m, $d, $Y));
	}
	
	/**
	 * Set the day value of this timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function setDayOfMonth($day = 1) {
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		return new DateTime(mktime($H, $i, $s, $m, $day, $Y));
	}
	
	/**
	 * Set the month value of this timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function setMonthOfYear($month = 1) {
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		return new DateTime(mktime($H, $i, $s, $month, $d, $Y));
	}
	
	/**
	 * Set the year value of this timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function setYear($year = 1970) {
		list($H, $i, $s, $m, $d, $Y) = DateTimeToolkit::breakdown($this->ts);
		
		return new DateTime(mktime($H, $i, $s, $m, $d, $year));
	}
	
	/**
	 * Returns the timestamp for tomorrow.
	 *
	 * Alias for DateTime::addDay
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function tomorrow() {
		return $this->add();
	}
	
	/**
	 * Returns the timestamp for yesterday.
	 *
	 * Alias for DateTime::subtractDay
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function yesterday() {
		return $this->subtract();
	}
	
	/**
	 * Adds one second to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addSecond() {
		return $this->add(1, DateTime::SECOND);
	}
	
	/**
	 * Adds the specified number of seconds to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addSeconds($num = 1) {
		return $this->add($num, DateTime::SECOND);
	}
	
	/**
	 * Subtracts the specified number of seconds from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractSecond() {
		return $this->subtract(1, DateTime::SECOND);
	}
	
	/**
	 * Subtracts the specified number of seconds from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractSeconds($num = 1) {
		return $this->subtract($num, DateTime::SECOND);
	}
	
	/**
	 * Adds one minute to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addMinute() {
		return $this->add(1, DateTime::MINUTE);
	}
	
	/**
	 * Adds the specified number of minutes to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addMinutes($num = 1) {
		return $this->add($num, DateTime::MINUTE);
	}
	
	/**
	 * Subtracts the specified number of minutes from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractMinute() {
		return $this->subtract(1, DateTime::MINUTE);
	}
	
	/**
	 * Subtracts the specified number of minutes from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractMinutes($num = 1) {
		return $this->subtract($num, DateTime::MINUTE);
	}
	
	/**
	 * Adds one hour to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addHour() {
		return $this->add(1, DateTime::HOUR);
	}
	
	/**
	 * Adds the specified number of hours to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addHours($num = 1) {
		return $this->add($num, DateTime::HOUR);
	}
	
	/**
	 * Subtracts the specified number of hours from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractHour() {
		return $this->subtract(1, DateTime::HOUR);
	}
	
	/**
	 * Subtracts the specified number of hours from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractHours($num = 1) {
		return $this->subtract($num, DateTime::HOUR);
	}
	
	/**
	 * Adds one day to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addDay() {
		return $this->add(1, DateTime::DAY);
	}
	
	/**
	 * Adds the specified number of days to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addDays($num = 1) {
		return $this->add($num, DateTime::DAY);
	}
	
	/**
	 * Subtracts the specified number of days from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractDay() {
		return $this->subtract(1, DateTime::DAY);
	}
	
	/**
	 * Subtracts the specified number of days from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractDays($num = 1) {
		return $this->subtract($num, DateTime::DAY);
	}
	
	/**
	 * Adds one week to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addWeek() {
		return $this->add(1, DateTime::WEEK);
	}
	
	/**
	 * Adds the specified number of weeks to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addWeeks($num = 1) {
		return $this->add($num, DateTime::WEEK);
	}
	
	/**
	 * Subtracts the specified number of weeks from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractWeek() {
		return $this->subtract(1, DateTime::WEEK);
	}
	
	/**
	 * Subtracts the specified number of weeks from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractWeek($num = 1) {
		return $this->subtract($num, DateTime::WEEK);
	}
	
	/**
	 * Adds one month to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addMonth() {
		return $this->add(1, DateTime::MONTH);
	}
	
	/**
	 * Adds the specified number of months to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addMonths($num = 1) {
		return $this->add($num, DateTime::MONTH);
	}
	
	/**
	 * Subtracts the specified number of months from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractMonth() {
		return $this->subtract(1, DateTime::MONTH);
	}
	
	/**
	 * Subtracts the specified number of months from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractMonths($num = 1) {
		return $this->subtract($num, DateTime::MONTH);
	}
	
	/**
	 * Adds one quarter to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addQuarter() {
		return $this->add(1, DateTime::QUARTER);
	}
	
	/**
	 * Adds the specified number of quarters to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addQuarters($num = 1) {
		return $this->add($num, DateTime::QUARTER);
	}
	
	/**
	 * Subtracts the specified number of quarters from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractQuarter() {
		return $this->subtract(1, DateTime::QUARTER);
	}
	
	/**
	 * Subtracts the specified number of quarters from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractQuarters($num = 1) {
		return $this->subtract($num, DateTime::QUARTER);
	}
	
	/**
	 * Adds one year to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addYear() {
		return $this->add(1, DateTime::YEAR);
	}
	
	/**
	 * Adds the specified number of years to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addYears($num = 1) {
		return $this->add($num, DateTime::YEAR);
	}
	
	/**
	 * Subtracts the specified number of years from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractYear() {
		return $this->subtract(1, DateTime::YEAR);
	}
	
	/**
	 * Subtracts the specified number of years from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractYears($num = 1) {
		return $this->subtract($num, DateTime::YEAR);
	}
	
	/**
	 * Adds one decade to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addDecade() {
		return $this->add(1, DateTime::DECADE);
	}
	
	/**
	 * Adds the specified number of decades to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addDecades($num = 1) {
		return $this->add($num, DateTime::DECADE);
	}
	
	/**
	 * Subtracts the specified number of decades from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractDecade() {
		return $this->subtract(1, DateTime::DECADE);
	}
	
	/**
	 * Subtracts the specified number of decades from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractDecades($num = 1) {
		return $this->subtract($num, DateTime::DECADE);
	}
	
	/**
	 * Adds one century to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addCentury() {
		return $this->add(1, DateTime::CENTURY);
	}
	
	/**
	 * Adds the specified number of centuries to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addCenturies($num = 1) {
		return $this->add($num, DateTime::CENTURY);
	}
	
	/**
	 * Subtracts the specified number of centuries from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractCentury() {
		return $this->subtract(1, DateTime::CENTURY);
	}
	
	/**
	 * Subtracts the specified number of centuries from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractCenturies($num = 1) {
		return $this->subtract($num, DateTime::CENTURY);
	}
	
	/**
	 * Adds one millennium to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addMillennium() {
		return $this->add(1, DateTime::MILLENIUM);
	}
	
	/**
	 * Adds the specified number of millennia to the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function addMillennia($num = 1) {
		return $this->add($num, DateTime::MILLENIUM);
	}
	
	/**
	 * Subtracts the specified number of millennia from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractMillennium() {
		return $this->subtract(1, DateTime::MILLENIUM);
	}
	
	/**
	 * Subtracts the specified number of millennia from the timestamp.
	 *
	 * @param timestamp
	 * @param int
	 * @return timestamp
	 */
	public function subtractMillennia($num = 1) {
		return $this->subtract($num, DateTime::MILLENIUM);
	}
	
	/**
	 * Returns the timestamp for first day of the week for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function firstDayOfWeek() {
		return $this->subtractDays(date('w', $this->ts));
	}
	
	/**
	 * Returns the timestamp for last day of the week for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function finalDayOfWeek() {
		return $this->firstDayOfWeek()->addWeek()->subtractDay();
	}
	
	/**
	 * Returns the timestamp for first day of the month for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function firstDayOfMonth() {
		return $this->subtractDays(date('d', $this->ts) - 1);
	}
	
	/**
	 * Returns the timestamp for last day of the month for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function finalDayOfMonth() {
		return $this->firstDayOfMonth()->addMonth()->subtractDay();
	}
	
	/**
	 * Returns the timestamp for first day of thequarter for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function firstDayOfQuarter() {
		return $this->firstDayOfMonth()->subtractMonths((date('m', $this->ts) - 1) % 3);
	}
	
	/**
	 * Returns the timestamp for last day of the quarter for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function finalDayOfQuarter() {
		return $this->firstDayOfQuarter()->addQuarter()->subtractDay();
	}
	
	/**
	 * Returns the timestamp for first day of the year for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function firstDayOfYear() {
		return $this->subtractDays(date('z', $this->ts));
	}
	
	/**
	 * Returns the timestamp for last day of the year for the given date.
	 *
	 * @param timestamp
	 * @return timestamp
	 */
	public function finalDayOfYear() {
		return $this->firstDayOfYear()->addYear()->subtractDay();
	}
	
	/**
	 * Returns the timestamp for the next occurance of [day].
	 *
	 * @param timestamp
	 * @param int 		the day of week
	 * @return timestamp
	 */
	public function nextOccuranceOfDay($day = DateTime::SUNDAY) {
		// get offsets from sunday
		$offset1 = date('w', $this->ts);
		$offset2 = $day;
		
		// adjust if date wraps into next week
		$offset2 += ($offset2 > $offset1) ? 0 : 7;
		
		return $this->addDays($offset2 - $offset1);
	}
	
	/**
	 * Returns the timestamp for the most recent (previous) occurance of [day].
	 *
	 * @param timestamp
	 * @param int 		the day of week
	 * @return timestamp
	 */
	public function previousOccuranceOfDay($day = DateTime::SUNDAY) {
		// get offsets from sunday
		$offset1 = date('w', $this->ts);
		$offset2 = $day;
		
		// adjust if date wraps into last week
		$offset1 += $offset1 > $offset2 ? 0 : 7;
		
		return $this->subtractDays($offset1 - $offset2);
	}
	
	/**
	 * Returns the timestamp for the next occurance of [month].
	 *
	 * @param timestamp
	 * @param int 		the month of year
	 * @return timestamp
	 */
	public function nextOccuranceOfMonth($month = DateTime::JANUARY) {
		// get offsets from january
		$offset1 = date('m', $this->ts);
		$offset2 = $month;
		
		// adjust if date wraps into next year
		$offset2 += $offset2 > $offset1 ? 0 : 12;
		
		return $this->addMonths($offset2 - $offset1);
	}
	
	/**
	 * Returns the timestamp for the most recent (previous) occurance of [month].
	 *
	 * @param timestamp
	 * @param int 		the month of year
	 * @return timestamp
	 */
	public function previousOccuranceOfMonth($month = DateTime::JANUARY) {
		// get offsets from january
		$offset1 = date('m', $this->ts);
		$offset2 = $month;
		
		// adjust if date wraps into last year
		$offset1 += $offset1 > $offset2 ? 0 : 12;
		
		return $this->subtractMonths($offset1 - $offset2);
	}
}
