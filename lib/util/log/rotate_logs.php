<?php

/*
 * This script rotates log files,
 * and is invoked via a cron job.
 */

$handle = @fopen("logs/rotate_logs.lock", "a");
@flock($handle, LOCK_EX);
@rename("logs/default.log", "logs/default_".date("Y-m-d").".log");
@flock($handle, LOCK_UN);
@fclose($handle);

?>
