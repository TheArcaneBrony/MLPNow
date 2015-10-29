<?php
	
	// Pony Data Paresr for Vector credits
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
