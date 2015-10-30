<?php

	// Time Constants \\
	define('EIGHTY_YEARS',2524556160);
	define('ONE_YEAR',31536000);
	define('THIRTY_DAYS',2592000);
	define('ONE_HOUR',3600);

	/**
	 * Sends replies to AJAX requests in a universal form
	 * $s respresents the request status, a truthy value
	 *  means the request was successful, a falsey value
	 *  means the request failed
	 * $x can be used to attach additional data to the response
	 *
	 * @param string $m
	 * @param bool|int $s
	 * @param array $x
	 */
	define('ERR_DB_FAIL','There was an error while saving to the database');
	function respond($m = 'Insufficent permissions.', $s = false, $x = null){
		header('Content-Type: application/json');
		if ($m === true) $m = array();
		if (is_array($m) && $s == false && empty($x)){
			$m['status'] = true;
			die(json_encode($m, JSON_UNESCAPED_SLASHES));
		}
		if ($m === ERR_DB_FAIL){
			global $Database;
			$m .= ": ".$Database->getLastError();
		}
		$r = array(
			"message" => $m,
			"status" => $s,
		);
		if (!empty($x)) $r = array_merge($r, $x);
		echo json_encode($r, JSON_UNESCAPED_SLASHES);
		exit;
	}

	// Page loading function
	function loadPage($settings){
		// Page <title>
		if (isset($settings['title']))
			$GLOBALS['title'] = $settings['title'];

		// SE crawlign disable
		if (in_array('no-robots',$settings))
			$norobots = true;

		# CSS
		$DEFAULT_CSS = array('theme');
		$customCSS = array();
		// Only add defaults when needed
		if (array_search('no-default-css',$settings) === false)
			$customCSS = array_merge($customCSS, $DEFAULT_CSS);

		# JavaScript
		$DEFAULT_JS = array('global','moment','dyntime','dialog');
		$customJS = array();
		// Only add defaults when needed
		if (array_search('no-default-js',$settings) === false)
			$customJS = array_merge($customJS, $DEFAULT_JS);

		# Check assests
		assetCheck($settings, $customCSS, 'css');
		assetCheck($settings, $customJS, 'js');

		# Add status code
		if (isset($settings['status-code']))
			statusCodeHeader($settings['status-code']);

		# Import global variables
		foreach ($GLOBALS as $nev => $ertek)
			if (!isset($$nev))
				$$nev = $ertek;

		# Putting it together
		/* Together, we'll always shine! */
		$view = empty($settings['view']) ? $do : $settings['view'];
		$viewPath = "views/{$view}.php";

		header('Content-Type: text/html; charset=utf-8;');

		$pageHeader = array_search('no-page-header',$settings) === false;

		if (empty($_GET['via-js'])){
			require 'views/header.php';
			require $viewPath;
			require 'views/footer.php';
			die();
		}
		else {
			$_SERVER['REQUEST_URI'] = rtrim(preg_replace('/via-js=true/','',remove_csrf_query_parameter($_SERVER['REQUEST_URI'])), '?&');
			ob_start();
			require 'views/sidebar.php';
			$sidebar = ob_get_clean();
			ob_start();
			require $viewPath;
			$content = ob_get_clean();
			respond(array(
				'css' => $customCSS,
				'js' => $customJS,
				'title' => $title,
				'content' => remove_indentation($content),
				'sidebar' => remove_indentation($sidebar),
				'footer' => get_footer(),
				'avatar' => $signedIn ? $currentUser['avatar_url'] : GUEST_AVATAR,
				'responseURL' => $_SERVER['REQUEST_URI'],
			));
		}
	}
	function assetCheck($settings, &$customType, $type){
		// Any more files?
		if (isset($settings[$type])){
			$$type = $settings[$type];
			if (!is_array($$type))
				$customType[] = $$type;
			else $customType = array_merge($customType, $$type);
			if (array_search("do-$type",$settings) !== false){
				global $do;
				$customType[] = $do;
			}
		}
		else if (array_search("do-$type",$settings) !== false){
			global $do;
			$customType[] = $do;
		}

		$pathStart = APPATH."$type/";
		foreach ($customType as $i => $item){
			if (file_exists("$pathStart$item.min.$type")){
				$customType[$i] = format_filepath("$item.min.$type");
				continue;
			}
			$item .= ".$type";
			if (!file_exists($pathStart.$item)){
				array_splice($customType,$i,1);
				trigger_error("File /$type/$item does not exist");
			}
			else $customType[$i] = format_filepath($item);
		}
	}
	function format_filepath($item){
		$type = preg_replace('/^.*\.(\w+)$/','$1', $item);
		$pathStart = APPATH."$type/";
		return "/$type/$item?".filemtime($pathStart.$item);
	}


	// HTTP Status Codes \\
	$HTTP_STATUS_CODES = array(
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Moved Temporarily',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
	);
	define('AND_DIE', true);
	function statusCodeHeader($code, $die = false){
		global $HTTP_STATUS_CODES;

		if (!isset($HTTP_STATUS_CODES[$code]))
			trigger_error('Érvénytelen státuszkód: '.$code,E_USER_ERROR);
		else
			header($_SERVER['SERVER_PROTOCOL'].' '.$code.' '.$HTTP_STATUS_CODES[$code]);

		if ($die === AND_DIE) die();
	}

	// CSRF Check \\
	function detectCSRF($CSRF = null){
		if (!isset($CSRF)) global $CSRF;
		if (isset($CSRF) && $CSRF)
			die(statusCodeHeader(401));
	}

	// Adds browser info to a session \\
	function browser(){
		require_once "includes/Browser.php";
		$browser = new Browser();
		$Return = array(
			'user_agent' => $_SERVER['HTTP_USER_AGENT']
		);
		$name = $browser->getBrowser();
		if ($name !== Browser::BROWSER_UNKNOWN){
			$Return['browser_name'] = $name;

			$ver = $browser->getVersion();
			if ($ver !== Browser::VERSION_UNKNOWN)
				$Return['browser_ver'] = $ver;
		}
		$platform = $browser->getPlatform();
		if ($platform !== Browser::PLATFORM_UNKNOWN)
			$Return['platform'] = $platform;
		return $Return;
	}
	function add_browser(&$Array){
		$browser = browser();
		foreach (array_keys($browser) as $v)
			if (isset($browser[$v]))
				$Array[$v] = $browser[$v];
	}

	// Generates a session for a given user \\
	function generate_session($LocalUser){
		global $Database;

		$cookie = openssl_random_pseudo_bytes(64);
		$Session = array('token' => sha1($cookie));
		add_browser($Session);

		$Database->insert('sessions', array_merge($Session, array('user' => $LocalUser['local_id'])));

		Cookie::set('access', $cookie, ONE_YEAR);
	}


	// Global cache for storing user details
	$_USER_CACHE = array();

	/**
	 * User Information Retriever
	 * --------------------------
	 * Gets a single row from the 'users' database
	 *  where $coloumn is equal to $value
	 * Returns null if user is not found
	 *
	 * If $cols is set, only specified coloumns
	 *  will be fetched
	 *
	 * @param string $value
	 * @param string $coloumn
	 * @param string $dbcols
	 *
	 * @return array|null
	 */
	function get_user($value, $coloumn = 'local_id', $dbcols = null){
		global $Database, $_USER_CACHE;

		if ($coloumn === "token"){
			$Auth = $Database->where('token', $value)->getOne('sessions');

			if (empty($Auth)) return null;
			$coloumn = 'local_id';
			$value = $Auth['user'];
		}

		if ($coloumn === 'local_id' && isset($_USER_CACHE[$value]))
			return $_USER_CACHE[$value];

		if (empty($dbcols)){
			$User = $Database->rawQuerySingle(
				"SELECT
					users.*,
					roles.label as rolelabel
				FROM users
				LEFT JOIN roles ON roles.name = users.role
				WHERE users.$coloumn = ?",array($value));

			if (!empty($User) && isset($Auth)) $User['Session'] = $Auth;
		}
		else $User = $Database->where($coloumn, $value)->getOne('users',$dbcols);

		if (isset($User['local_id']))
			$_USER_CACHE[$User['local_id']] = $User;

		return $User;
	}

	// Get likes of Facebook page
	function getLikeCount(){
		$data = json_decode(file_get_contents('http://graph.facebook.com/MLPNow?fields=likes'), true);
		if (isset($data['likes']) && is_numeric($data['likes']) && $data['likes'] > 0){
			$l = $data['likes']*1;
			foreach (array('m','k') as $i => $append){
				$worth = pow(1000,3-($i+1));
				if ($l >= $worth){
					$l = round($l*10/$worth)/10 . $append;
					if (strpos($l,'.') !== false) $l = str_replace('.',',',$l);
					break;
				}
			}
			return $l;
		}
		return false;
	}

	// Display a 404 page
	function do404($debug = null){
		if (RQMTHD == 'POST' || isset($_GET['via-js'])){
			$RQURI = rtrim(preg_replace('/via\-js=true/','',$_SERVER['REQUEST_URI']),'?&');
			respond("HTTP 404: ".(RQMTHD=='POST'?'Endpoint':'Page')." ($RQURI) does not exist",0, is_string($debug) ? array('debug' => $debug) : null);
		}
		statusCodeHeader(404);
		loadPage(array(
			'title' => '404',
			'view' => '404',
		));
	}

	// Redirection \\
	define('STAY_ALIVE', false);
	function redirect($url = '/', $die = true, $http = 301){
		header("Location: $url",$die,$http);
		if ($die !== STAY_ALIVE) die();
	}

	// Redirect to fix path \\
	function fix_path($fix_uri, $http = 301){
		list($path, $query) = explode('?', "{$_SERVER['REQUEST_URI']}?");
		$query = empty($query) ? '' : "?$query";

		list($fix_path, $fix_query) = explode('?', "$fix_uri?");
		$fix_query = empty($fix_query) ? '' : "?$fix_query";

		if (empty($fix_query))
			$fix_query = $query;
		else {
			$query_assoc = query_string_assoc($query);
			$fix_query_assoc = query_string_assoc($fix_query);
			$merged = $query_assoc;
			foreach ($fix_query_assoc as $key => $item)
				$merged[$key] = $item;

			$fix_query_arr = array();
			foreach ($merged as $key => $item)
				$fix_query_arr[] = "$key".(!empty($item)?"=$item":'');
			$fix_query = implode('&', $fix_query_arr);
			$fix_query = empty($fix_query) ? '' : "?$fix_query";
		}

		if ($path !== $fix_path || $query !== $fix_query)
			redirect("$fix_path$fix_query", STAY_ALIVE, $http);
	}

	// Turn query string into an associative array
	function query_string_assoc($query){
		$assoc = array();
		if (!empty($query)){
			$query_arr = explode('&', substr($query, 1));
			foreach ($query_arr as $el){
				$el = explode('=', $el);
				$assoc[$el[0]] = empty($el[1]) ? '' : $el[1];
			}
		}
		return $assoc;
	}

	// Random Array Element \\
	function array_random($array){ return $array[array_rand($array, 1)]; }

	// Color padder \\
	function clrpad($c){
		if (strlen($c) === 3) $c = $c[0].$c[0].$c[1].$c[1].$c[2].$c[2];
		return $c;
	}

	// Preseving alpha
	function preserveAlpha($img) {
		$background = imagecolorallocate($img, 0, 0, 0);
		imagecolortransparent($img, $background);
		imagealphablending($img, false);
		imagesavealpha($img, true);
		return $img;
	}

	# Make any absolute URL HTTPS
	function makeHttps($url){
		return preg_replace('~^(https?:)?//~','https://',$url);
	}

	// Pony Data Parser for Vector credits
	$htmlString = array(
		'<a href="http://fav.me/{favme}" class="{short_name}" target="_blank" class="{short_name}">{pony}</a>',
		'<a href="http:{favme}" class="{short_name}" target="_blank" class="{short_name}">{pony}</a>'
	);
	// Parse pony information
	function parsePony($a,$linkOnly = false,$pony = null){
		global $htmlString;
		global $Database;
		
		if (!$pony) $pony = $a['longName'];
		$favme = $a['favme'];
		// Get creator user name if missing
		if (preg_match('/^d[a-z0-9]{6}$/',$a['favme']) && strlen($a['vector']) === 0){
			// Dealing with deviantART links without an author name
			$_get = json_decode(file_get_contents('http://backend.deviantart.com/oembed?url=http://fav.me/'.$a['favme']), true);
			$username = $_get['author_name'];
			$Database->where('shortName',$a['shortName'])->update('ponies',array(
				"vector" => $username,
			));
		}
		else $username = $a['vector'];
		$username_lower = strtolower($username);
		$short_name = $a['shortName'];
		$color = substr($a['color'],1);
		
		return preg_replace('/\{([A-Za-z_]+)\}/e', "$$1", $htmlString[$linkOnly?1:0]);
	}
	
	// Settings
	$POSSIBLE_PREFS = array('name','bottom','timeformat','sort','pinNotify');
	$POSSIBLE_TIMES_FORMATS = array('12','24','at');
	$POSSIBLE_SORT_ORDERS = array('abc','colour','new');

	// Remove CSRF query parameter from request URL
	function remove_csrf_query_parameter($url, $viajsToo = false){
		return rtrim(preg_replace('/CSRF_TOKEN=[^&]+(&|$)/','',$url),'?&');
	}
