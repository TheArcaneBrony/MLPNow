<?php

	include "conf.php";

	// Global constants \\
	define('ABSPATH',(!empty($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['SERVER_NAME'].'/');
	define('APPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
	define('RQMTHD',$_SERVER['REQUEST_METHOD']);
	define('REWRITE_REGEX','~^/(?:([\w\.\-]+|-?\d+)(?:/((?:[\w\-]+|-?\d+)(?:/(?:[\w\-]+|-?\d+))?))?/?)?$~');
	define('SITE_TITLE', 'MLP Now');

	// Time control
	define('NOW', time());
	define('NOW_ISO', date('c', NOW));

	// Imports \\
	require 'includes/PostgresDb.php';
	$Database = new PostgresDb('mlpnow');
	require 'includes/Cookie.php';
	require 'includes/Utils.php';
	require 'includes/AuthCheck.php';

	if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
		require 'includes/CloudFlare.php';
		if (CloudFlare::CheckUserIP())
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
	}

