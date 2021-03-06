<?php header('Content-Type: text/css;') ?>
@font-face {
	font-family: Celestia;
	src: url('../font/Celestia Medium Redux.ttf') format('truetype');
}

header nav ul:last-child li:first-child a {
	font-family: Celestia, cursive;
	font-weight: normal;
<?php
	$yay = false;
	$pref = array();
	
	if (isset($_GET['name']) && preg_match('/^[a-z]{1,2}$/',$_GET['name'])) $pref['name'] = $_GET['name'];
	else if ($signedIn){
		$pref = $Database->where('usrid',$currentUser['uid'])->getOne('userdata');
	}
		
	if (isset($pref['name']) && strlen($pref['name']) > 0 && in_array($pref['name'],$Pony->shortNames())){
		$yay = true;
		$P = $Database->where('shortName',$pref['name'])->getOne('ponies');
	}
	if ($yay){ ?>
	color: <?=$P['color']?>;
<?php } ?>
}
<?php if ($yay){ ?>
#wrap, .right-col .logged-in .links {
	background: linear-gradient(to bottom, <?=$P['color']?> 0%,<?=$P['textcolor']?> 100%);
}
header .menubar nav a, .right-col .logged-in .links a,
header .menubar nav li:after, .right-col .logged-in .links li:after {
	text-shadow:
		0 1px 2px rgba(0,0,0,0.4),
		0 -1px 2px rgba(0,0,0,0.4),
		1px 0 2px rgba(0,0,0,0.4),
		-1px 0 2px rgba(0,0,0,0.4);
<?php
	}
	else { ?>
#wrap, .right-col .logged-in .links {
	background: linear-gradient(to bottom, #787878 0%,#6b6b6b 100%);
<?php } ?>
}
footer {
	color: #000 !important;
}
.left-col.grid-70 .title > h1 {
	font-family: Celestia !important;
	font-size: 2em !important;
	margin: 10px 0 5px !important;
}
div[id^="powerTip"] .name {
	font-family: Celestia;
}
/* Links */
a:link,a:visited { color: #050505 }
a:link:hover, a:visited:hover { color: #595959 }
