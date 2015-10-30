<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=(isset($title)?$title.' - ':'').SITE_TITLE?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?=ABSPATH?>img/fb_prev.png">
	<meta property="og:title" content="MLP Now">
	<meta property="og:url" content="<?=ABSPATH?>">
	<meta property="og:description" content="HTML clock with characters from the TV show My Little Pony: Friendship is Magic">
<?  if (isset($norobots)){ ?>
	<meta name="robots" content="noindex, nofollow">
<?  } ?>
	<link rel="shortcut icon" href="/favicon.ico">
<?  if (isset($customCSS)) foreach ($customCSS as $css){
		echo "\t<link rel='stylesheet' href='$css'>\n";
	} ?>
</head>
<body>

	<div id="main">
