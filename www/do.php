<?php
	include "init.php";
	header('X-Frame-Options: DENY');

	# Chck activity
	$do = !empty($_GET['do']) ? $_GET['do'] : 'home';

	# Get additional details
	$data = !empty($_GET['data']) ? $_GET['data'] : '';

	switch ($do){
		case GH_WEBHOOK_DO:
			if (empty(GH_WEBHOOK_DO)) redirect('/', AND_DIE);

			if (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'GitHub-Hookshot/') === 0){
				if (empty($_SERVER['HTTP_X_GITHUB_EVENT']) || empty($_SERVER['HTTP_X_HUB_SIGNATURE']))
					do404();

				$payloadHash = hash_hmac('sha1', file_get_contents('php://input'), GH_WEBHOOK_SECRET);
				if ($_SERVER['HTTP_X_HUB_SIGNATURE'] !== "sha1=$payloadHash")
					do404();

				switch (strtolower($_SERVER['HTTP_X_GITHUB_EVENT'])) {
					case 'push':
						$output = array();
						exec("$git reset HEAD --hard",$output);
						exec("$git pull",$output);
						echo implode("\n", $output);
						break;
					case 'ping':
						echo "pong";
						break;
					default: do404();
				}

				exit;
			}
			do404();
		break;
		case "timeformatchange":
			if (!isset($_POST['ntimeformat']) || !in_array($_POST['ntimeformat'],$POSSIBLE_TIMES_FORMATS)) respond('Érvénytelen vagy hiányzó időformátum!');
			$_POST['name'] = 'timeformat';
			$_POST['value'] = $_POST['ntimeformat'];
			$_MSG = 'Időformátum sikeresen módosítva.';
		case "prefupdate":
			if (!$signedIn) respond();
			if (!isset($_POST['name']) || !isset($_POST['value'])) respond('Hiányzó POST értékek');

			list($prop,$value) = array($_POST['name'],$_POST['value']);

			$targetUser = $Database->where('usrid',$currentUser['uid'])->getOne('userdata');

			if (!isset($targetUser)){
				$Database->insert('userdata',array('usrid',$currentUser['uid']));
				$targetUser = $Database->where('usrid',$currentUser['uid'])->getOne('userdata');
			}

			if (!in_array($prop, $POSSIBLE_PREFS)) respond('Érvénytelen beállítás megváltoztatása: "'.$prop.'"');

			$newData = array();
			$newData[$prop] = $value;

			if (!$Database->where('usrid',$targetUser['usrid'])->update('userdata', $newData)) respond('Nincs változás',1);
			else respond(isset($_MSG)?$_MSG:'Beállítások mentve',1);
		break;
		case "pony":
			if (preg_match('/^[a-z]{1,2}$/',$data)){
				$imgPath = 'img/bg/'.$data.'.png';
				header('Content-type: image/png');
				if (!file_exists($imgPath)) die(header('HTTP/1.1 404 Not Found'));
			}
			else {
				$imgPath = 'img/blank.gif';
				header('Content-type: image/gif');
			}
			echo file_get_contents($imgPath);
		break;
		case "vector":
			$_match = array();
			if (preg_match('/^([a-z]{1,2})(?:\/([a-fA-F0-9]{3}|[a-fA-F0-9]{6}))?$/',$data,$_match)){
				array_splice($_match,0,1);
				list($shortName,$color) = $_match;


				if (!file_exists($path = 'img/small/'.$shortName.'.svg')) die(header('HTTP/1.1 404 Not Found'));

				if (!isset($color) || strlen($color) === 0) $color = 'ffffff';
				else if (strlen($color) === 3) $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];

				$file_contents = file_get_contents($path);
				$file_contents = str_ireplace('fill="#ffffff"','fill="#'.$color.'"',$file_contents);
				header('Content-Type: image/svg+xml');
				echo $file_contents;
			}
			else die(header('HTTP/1.1 404 Not Found'));
		break;
		case "splat":
		case "loader":
			$match = array();
			preg_match('/^([a-f0-9]{3}|[a-f0-9]{6}|default)(?:\/([a-f0-9]{3}|[a-f0-9]{6}))?$/', $data, $_match);
			$isSimple = empty($_match[2]);
			$isGradient = !$isSimple;

			if ($isGradient && $do == 'splat'){
				$colors_split = explode('/',$data);
				$startColor = clrpad($colors_split[1]);
				$endColor = clrpad($colors_split[0]);

				if ($startColor !== $endColor) {
					$startColorArray = array(
						hexdec(substr($startColor, 0, 2)),
						hexdec(substr($startColor, 2, 2)),
						hexdec(substr($startColor, 4, 2))
					);
					$endColorArray = array(
						hexdec(substr($endColor, 0, 2)),
						hexdec(substr($endColor, 2, 2)),
						hexdec(substr($endColor, 4, 2))
					);

					$open = preserveAlpha(imageCreateFromPng("img/splat-base.png"));
					$width = imagesx($open);
					$height = imagesy($open);

					$final = imageCreateTransparent($width, $height);

					for ($i = 0; $i < $height; $i++) {
						//grab the $ith row off of the image
						$pixelRow = preserveAlpha(imagecreatetruecolor($width, 1));
						imagecopy($pixelRow, $open, 0, 0, 0, $i, $width, 1);
						//calculate the next color in the gradient
						$r = intval($startColorArray[0] - ((($startColorArray[0] - $endColorArray[0]) / $height) * $i));
						$g = intval($startColorArray[1] - ((($startColorArray[1] - $endColorArray[1]) / $height) * $i));
						$b = intval($startColorArray[2] - ((($startColorArray[2] - $endColorArray[2]) / $height) * $i));
						//apply the filter
						imagefilter($pixelRow, IMG_FILTER_COLORIZE, $r - 255, $g, $b);
						//append it to our new iamge
						imagecopy($final, $pixelRow, 0, $i, 0, 0, $width, 1);
					}

					$img = &$final;
				}
				else $color = str_split($startColor,2);
			}
			else {
				// Parse color
				if ($isSimple){
					if ($data == "default") $color = '777777';
					else $color = clrpad($data);
				}
				else $color = '000000';
				$color = str_split($color,2);
			}

			if (empty($img)){
				foreach ($color as $k=>$m){ $color[$k] = hexdec($color[$k]); }

				// Read & recolor
				$img = preserveAlpha(imageCreateFromPng("img/{$do}-base.png"));
				imagefilter($img, IMG_FILTER_COLORIZE, $color[0] - 255, $color[1], $color[2]);
			}

			// Output
			header('Content-type: image/png');
			imagePng($img);
			imagedestroy($img);
			exit;
		break;
		case "signout":
			if (!$signedIn)
				respond(true);
			detectCSRF();

			if(!$Database->rawQuery(
				"DELETE FROM sessions
				WHERE id = ? OR lastvisit < NOW() - INTERVAL '30 DAY'",
				array($currentUser['Session']['id']))
			) respond(ERR_DB_FAIL);

			respond(true);
		break;
		case "oauth":
			$_match = array();
			$data = strtolower($data);
			if (!preg_match('~^(start|finish)/([a-z]{1,15})$~', $data, $_match))
				do404();

			$start = $_match[1] === 'start';
			$provider = $_match[2];
			$PROVIDER = strtoupper($provider);

			$oAuthTable = "oauth__$provider";
			if (!$Database->tableExists($oAuthTable))
				do404();

			$oAuthRedirectURI = "/$do/finish/$provider";
			$Client = constant("OAUTH_{$PROVIDER}_CLIENT");
			$Secret = constant("OAUTH_{$PROVIDER}_SECRET");
			$ClassName = "{$provider}_oAuth";
			require_once "includes/$ClassName.php";
			$oAuth = new $ClassName($Client, $Secret, $oAuthRedirectURI);

			if ($start)
				$oAuth->getCode();
			else {
				if (empty($_GET['code']))
					do404();
				$code = $_GET['code'];

				$Auth = $oAuth->getTokens($code, 'authorization_code');
				$User = $oAuth->getUserInfo($Auth['access_token']);
			}

			$LocalCopy = $Database->where('remote_id', $User['remote_id'])->getOne($oAuthTable);
			if (empty($LocalCopy)){
				$makeDev = !$Database->has('users');
				$LocalUser = array(
					'name' => $User['remote_name'],
					'avatar_url' => $User['remote_avatar'],
					'role' => $makeDev ? 'developer' : 'user',
				);

				if ($makeDev && !$Database->has('roles')){
					$STATIC_ROLES = array(
						array(0, 'ban', 'Banned User'),
						array(1, 'user', 'User'),
						array(255, 'developer', 'Developer'),
					);
					foreach ($STATIC_ROLES as $role)
						$Database->insert('roles', array(
							'value' => $role[0],
							'name' => $role[1],
							'label' => $role[2],
						));
				}
				$LocalUser['local_id'] = $Database->insert('users', $LocalUser, 'local_id');

				$User['local_id'] = $LocalUser['local_id'];
				$Database->insert($oAuthTable, $User);
				unset($User);
			}
			else $LocalUser = $Database->where('local_id', $LocalCopy['local_id'])->getOne('users');
			unset($LocalCopy);

			generate_session($LocalUser);

			redirect('/', AND_DIE);
		break;
	  ///////////
	 // Pages //
	///////////
		case "home":
			fix_path('/');

			$Ponies = $Database->get('ponies');
			include "includes/Updates.php";

			loadPage(array(
				'do-css',
				'js' => array('pusher.color','konami',$do)
			));
		break;
		default:
			do404();
	}
