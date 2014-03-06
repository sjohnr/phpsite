<?php

/**
 * Formats a number by injecting nonnumeric characters in a specified format
 * into the string in the positions they appear in the format.
 *
 * <b>Examples:</b>
 * <code>echo format_string('1234567890', '(000) 000-0000');</code>
 * -> (123) 456-7890
 * <code>echo format_string('1234567890', '000.000.0000');</code>
 * -> 123.456.7890
 *
 * @param string s the string to format
 * @param string format the format to apply
 * @return string the formatted string
 */
function format_string($s, $format) {
	if ($format == '' || $s == '') {
		return $s;
	}
	
	$result = '';
	$fpos = 0;
	$spos = 0;
	while ((strlen($format) - 1) >= $fpos) {
		if (is_alphanumeric(substr($format, $fpos, 1))) {
			$result .= substr($s, $spos, 1);
			$spos++;
		} else {
			$result .= substr($format, $fpos, 1);
		}
		
		$fpos++;
	}
	
	return $result;
}

/**
 * Transforms a number by masking characters in a specified mask format,
 * and ignoring characters that should be injected into the string without
 * matching a character from the original string (defaults to space).
 *
 * <b>Examples:</b>
 * <code>echo mask_string('1234567812345678', '************0000');</code>
 * -> ************5678
 * <code>echo mask_string('1234567812345678', '**** **** **** 0000');</code>
 * -> **** **** **** 5678
 * <code>echo mask_string('1234567812345678', '**** - **** - **** - 0000', ' -');</code>
 * -> **** - **** - **** - 5678
 *
 * @param string s the string to transform
 * @param string format the mask format
 * @param string ignore a string (defaults to a single space) containing characters to ignore in the format
 * @return string the masked string
 */
function mask_string($s, $format, $ignore = ' ') {
	if ($format == '' || $s == '') {
		return $s;
	}
	
	$result = '';
	$fpos = 0;
	$spos = 0;
	while ((strlen($format) - 1) >= $fpos) {
		if (is_alphanumeric(substr($format, $fpos, 1))) {
			$result .= substr($s, $spos, 1);
			$spos++;
		} else {
			$result .= substr($format, $fpos, 1);
			if (strpos($ignore, substr($format, $fpos, 1)) === false) {
				$spos++;
			}
		}
		
		$fpos++;
	}
	
	return $result;
}

/**
 * Formats a phone number.
 *
 * @param string s the unformatted phone number to format
 * @param string format the format to use, defaults to '(000) 000-0000'
 * @return string the formatted string
 */
function format_phone($s, $format = '(000) 000-0000') {
	return format_string($s, $format);
}

/**
 * Formats a variable length phone number, using a standard format.
 *
 * <strong>Examples:</strong>
 * <code>echo smart_format_phone('1234567');</code>
 * -> 123-4567
 * <code>echo smart_format_phone('1234567890');</code>
 * -> (123) 456-7890
 * <code>echo smart_format_phone('91234567890');</code>
 * -> 9 (123) 456-7890
 * <code>echo smart_format_phone('123456');</code>
 * -> 123456
 *
 * @param string s the unformatted phone number to format
 */
function smart_format_phone($s) {
	switch (strlen($s)) {
		case 7:
			return format_string($s, '000-0000');
		case 10:
			return format_string($s, '(000) 000-0000');
		case 11:
			return format_string($s, '0 (000) 000-0000');
		default:
			return $s;
	}
}

/**
 * Formats a U.S. Social Security Number.
 *
 * <b>Example:</b>
 * <code>echo format_ssn('123456789');</code>
 * -> 123-45-6789
 *
 * @param string s the unformatted ssn to format
 * @param string format the format to use, defaults to '000-00-0000'
 */
function format_ssn($s, $format = '000-00-0000') {
	return format_string($s, $format);
}

/**
 * Formats a credit card expiration string. Expects 4-digit string (MMYY).
 *
 * @param string s the unformatted expiration string to format
 * @param string format the format to use, defaults to '00-00'
 */
function format_exp($s, $format = '00-00') {
	return format_string($s, $format);
}

/**
 * Formats (masks) a credit card.
 *
 * @param string s the unformatted credit card number to format
 * @param string format the format to use, defaults to '**** **** **** 0000'
 */
function mask_credit_card($s, $format = '**** **** **** 0000') {
	return mask_string($s, $format);
}

/**
 * Formats a USD currency value with two decimal places and a dollar sign.
 *
 * @param string money the unformatted amount to format
 * @param string format the format to use, defaults to '%0.2f'
 *
 * @see sprintf
 */
function format_usd($money, $format = '%0.2f') {
	return '$' . sprintf($format, $money);
}

/**
 * Determines if a string has only alpha characters.
 *
 * @param string s the string to check as alpha
 *
 * @see is_numeric
 * @see preg_match
 */
function is_alpha($s) {
	return preg_match('/^[a-zA-Z]+$', $s);
}

/**
 * Determines if a string has only alpha/numeric characters.
 *
 * @param string s the string to check as alpha/numeric
 *
 * @see is_numeric
 * @see preg_match
 */
function is_alphanumeric($s) {
	return preg_match('/^[0-9a-zA-Z]+$/', $s);
}

/**
 * Convert string to hex encoded string.
 *
 * @param string s
 * @return string
 */
function str2hex($s) {
    $hex = '';
    for ($i = 0; $i < strlen($s); $i++) {
        $hex .= '&#' . str_pad(ord($s[$i]), 3, '0', STR_PAD_LEFT) . ';';
	}
	
    return $hex;
}

/**
 * Convert a hex encoded string to a string.
 *
 * @param string hex
 * @return string
 */
function hex2str($hex) {
    $s = '';
    for ($i = 0; $i < strlen($hex)-1; $i += 2) {
        $s .= chr(hexdec($hex[$i] . $hex[$i + 1]));
	}
	
    return $s;
}

?>
