<?php

	// Database Access Info \\
	define('DB_HOST','');
	define('DB_USER','');
	define('DB_PASS','');

	// Google Analytics Tracking Code \\
	define('GA_TRACKING_CODE','');

	/**
	 * Get latest commit version & time from Git
	 * -----------------------------------------
	 * Windows (without using PATH):
	 *   $git = '<drive>:\path\to\git.exe';
	 *
	 * Linux/Unix or Windows (using PATH):
	 *   $git = 'git';
	 */
	$git = 'git';
	define('LATEST_COMMIT_ID',rtrim(shell_exec("$git rev-parse --short=4 HEAD")));
	define('LATEST_COMMIT_TIME',date('c',strtotime(shell_exec("$git log -1 --date=short --pretty=format:%ci"))));
