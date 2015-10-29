<?php
	include "init.php";
	
	function respond($m = 'Ezen funkció használatához be kell jelentkezned.', $s = 0, $x = array()){
		header('Content-Type: application/json');
		die(json_encode(array_merge(array(
			"message" => $m,
			"status" => $s,
		),$x)));
	}

	$HU_to_EN = array(
		"főoldal" => "home",
		"beállítások" => "settings",
		"gyik" => "faq",
		"változások" => "changelog",
		"előnézet" => "preview",
		"vektorok" => "vectors",
	);
	
	header('X-FRAME-OPTIONS: SAMEORIGIN');
	
	if (isset($_POST['do'])) $do = $_POST['do'];
	else if (!isset($_POST['do']) && isset($_GET['do'])) $do = $_GET['do'];
	
	if (isset($_POST['data'])) $data = $_POST['data'];
	else if (!isset($_POST['data']) && isset($_GET['data'])) $data = $_GET['data'];
	else $data = '';
	
	if (isset($do)) foreach ($HU_to_EN as $HU => $EN){
		if (isset($do) && $do === $HU)
			$do = str_replace($HU,$EN,$do);
		else if (isset($data) && strpos($data,$HU) !== false)
			$data = str_replace($HU,$EN,$data);
	};
	
	if (isset($do)){		
		switch ($do){
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
			case "prefcheck":
				if (!$signedIn) respond();
				if (!isset($_POST['name'])) respond('Hiányzó beállítás név');
				$prop = $_POST['name'];
				
				$targetUser = $Database->where('usrid',$currentUser['uid'])->getOne('userdata');
				unset($targetUser['usrid']);
				
				if ($prop !== '*' && !in_array($prop, array_keys($targetUser))) respond('Érvénytelen beállítás lekérése: "'.$prop.'"');
				
				respond('Lekérés sikeres',1,array('value' => ($prop === '*'?$targetUser:$targetUser[$prop])));
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
				$data = strtolower($data);
				$isGradient = preg_match('/^([a-f0-9]{3}|[a-f0-9]{6})\/([a-f0-9]{3}|[a-f0-9]{6})$/', $data);
				$isSimple = preg_match('/^([a-f0-9]{3}|[a-f0-9]{6}|default)$/', $data);
				
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
					else {
						$color = str_split($startColor,2);
						foreach ($color as $k=>$m){ $color[$k] = hexdec($color[$k]); }
						
						// Read & recolor
						$img = preserveAlpha(imageCreateFromPng("img/splat-base.png"));
						imagefilter($img, IMG_FILTER_COLORIZE, hexdec($color[0] - 255), $color[1], $color[2]);
					}
				}
				else {
					// Parse color
					if ($isSimple){
						if ($data == "default") $color = '777777';
						else $color = clrpad($data);
					}
					else $color = '000000';
					
					$color = str_split($color,2);
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
			  //////////////////////////////////
			 // CHECK LAST MODIFICATION TIME //
			//////////////////////////////////
			case "lastmodified":
				if (!$signedIn) respond();
				
				if (!isset($_POST['targetid']) || !is_numeric($_POST['targetid'])) respond('Nincs cél id.');
				$targetID = intval($_POST['targetid']);
				
				if (!isset($_POST['table']) || !in_array($_POST['table'],$LASTMODIFIED_SUPPORTED_DBS)) respond('Hiányzó vagy nem támogatott cél tábla.');
				$table = $_POST['table'];
				
				if (!isset($_POST['loadedtime']) || !is_time_string($_POST['loadedtime'])) respond('Nincs vagy érvénytelen cél tábla.');
				$loadedtime = strtotime($_POST['loadedtime']);
				
				$targetTbl = 'uid';
				switch ($table){
					case "userdata": $targetTbl = 'usrid'; break;
					case "badges": $targetTbl = 'bid'; break;
				}
				
				$select = $hpAccDB->where($targetTbl,$targetID)->getOne($table);
				if (!isset($select) && $select['lastmodified'] !== NULL) respond('Nem elérhető az oldal utolsó módosításának ideje. Lehetséges, hogy az oldal elavult információkat tartalmaz.<br>Ha nem szeretnéd, hogy keveredés legyen, javaslom, hogy töltsd újr a az oldalt és próbálkozz még egyszer.<br><strong>Minden áron folytatod?</strong>',0,array('check'=>true));
				
				if (strtotime($select['lastmodified']) > $loadedtime) respond('Az adatokban változás történt az oldal betöltése óta. Lehetséges, hogy egyes információk pontatlanok.<br>Ha nem szakítod meg ezt a kérést, lehetséges, hogy adatvesztés történik. Kérlek, töltsd újra az oldalt, és úgy végezd el a módosításokat.<br>Amennyiben folytatod, tudomásul veszed hogy amit beírtál, visszafordíthatatlanul felülírja az általad nem ismert változásokat.<br><strong>Minden áron folytatod?</stron>',0,array('check'=>true));
				respond('Nem történt változás az utolsó oldalbetöltés óta.',1);
			break;
		  ///////////
		 // Pages //
		///////////
			case "home":
				if ($signedIn){
					if (isset($_GET['name']) && preg_match('/^[a-z]{1,2}$/',$_GET['name']) && in_array($_GET['name'],$Pony->shortNames()))
						$Database->where('usrid',$currentUser['uid'])->update('userdata',array("name" => $_GET['name']));
					if (isset($_GET['timeformat']) && preg_match('/^(12|24|at)$/',$_GET['timeformat']))
						$Database->where('usrid',$currentUser['uid'])->update('userdata',array("timeformat" => $_GET['timeformat']));
				}
				include "views/{$do}.php";
			break;
			case "settings":
				if ($signedIn){
					$targetUser = $Database->where('usrid',$currentUser['uid'])->getOne('userdata');
					
					if (!isset($targetUser)){
						$Database->insert('userdata',array('usrid',$currentUser['uid']));
						$targetUser = $Database->where('usrid',$currentUser['uid'])->getOne('userdata');
					}
				}
				else $_MSG = 'A beállítások eléréséhez be kell jelentkezned.';
				include "views/{$do}.php";
			break;
			case "changelog":
				include "includes/Updates.ex.php";
				
				if (isset($_REQUEST['latest'])){
					header('Content-Type: application/json;');
					$ver = array_keys($updates);
					die(json_encode(array("version" => $ver[0])));
				}
				else include "views/{$do}.php";
			break;
			// Just simply load corresonding view
			//case "reminders":
			case "faq":
			case "preview":
			case "vectors":
				if (file_exists("views/{$do}.php")) die(include "views/{$do}.php");
			default:
				if (RQMTHD == 'POST') respond("Nem tudom hogy kell csinálni: ".$do);
				header('HTTP/1.1 404 Not Found');
				include "views/404.php";
			break;
		}
	}
	else header('HTTP/1.1 400 Bad Request');