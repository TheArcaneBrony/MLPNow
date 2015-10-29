	<link rel="stylesheet" href="<?=djpth('<shared>css>metro.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>grid.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>theme.css')?>">
	<link rel="stylesheet" href="<?=djpth('css>overrides.css.php')?>">
	<link rel="stylesheet" href="<?=djpth('css>ponycolors.css.php')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>typicons.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>ponyfont.css.php')?>">
<?php if (isset($customCSS) && is_array($customCSS)) foreach ($customCSS as $css){ ?>
	<link rel="stylesheet" href="<?=djpth($css)?>">
<?php } ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable = no">

	<script type="text/javascript">
	if (typeof top.location == 'DOMException'){
		window.location = "http://www.youtube.com/watch_popup?v=oHg5SJYRHA0";
	}
	var RELPATH = "<?=RELPATH?>", ROOTRELPATH = "<?=ROOTRELPATH?>",
		isframed = typeof parent.MLPNow !== 'undefined' && typeof parent.MLPNow.pref !== 'undefined'
	</script>
	<noscript>
		<meta name="refresh" content="0;http://www.youtube.com/watch_popup?v=oHg5SJYRHA0">
	</noscript>
	<script src="<?=djpth('<shared>js>jquery.min.js')?>"></script>
	<script src="<?=djpth('<shared>js>cookie.js')?>">/* Cookies */</script>
	<script src="<?=djpth('<shared>js>prefixfree.min.js')?>"></script>
	
	<meta name="application-name" content="MLP Now" />
	<meta name="msapplication-starturl" content="<?=ABSPATH?>" />
	<meta name="msapplication-navbutton-color" content="#cccccc">
	
</head>
<body>

	<header>
		<nav>
			<ul>
				<li id="facebook">
					<a href="https://www.facebook.com/MLPNow" target="_blank">MLP Now a Facebook-on</a>
					<span class="label"><?php
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
						echo $l;
					} ?></span>
				</li>
<?php 	            if ($signedIn){ ?>
				<li id="notif">
					<span class="label"></span>
				</li>
<?php	            } ?>
			</ul>
			<ul>
				<li><a href="<?=djpth()?>">MLP Now</a></li>
				<li>
					<a href="<?=djpth('változások')?>">Változási napló</a>
				</li>
				<li>
					<a href="<?=djpth('gyik')?>">Gyakran Ismételt Kérdések</a>
				</li>
				<li>
					<a href="<?=djpth('vektorok')?>">Felhasznált vektorok</a>
				</li>
			</ul>
	</header>

	<div id="wrap">