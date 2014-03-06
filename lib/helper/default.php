<?php

/**
 * Make a helper group available, by include.
 *
 * @param string name
 */
function use_helper($name) {
	include_once('lib/helper/'.$name.'.php');
}

?>
