<!DOCTYPE html>
<!-- INDEX LOADER -->
<html lang="hu">
<head>
<title>MLP Now</title>
<meta charset="utf-8">
<meta property="og:title" content="MLP Now">
<meta property="og:description" content="HTML óra és dátum, szereplőkkel az Én Kicsi Pónim: Varázslatos Barátság című sorozatból.">
<meta property="og:image" content="img/fb_prev.png">
<meta name="description" content="HTML óra és dátum, szereplőkkel az Én Kicsi Pónim: Varázslatos Barátság című sorozatból.">
<meta name="keywords" content="mlp,today,time,date,clock,web clock,milyennapvanma,ponies,love,javascript,hu,hungary">
<meta name="google-site-verification" content="ci3TlhFyvpNMM0oeLEyaKd9I_wsPgEGvWVZB_Ff9wx4">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable = no">

<meta name="application-name" content="MLP Now" />
<meta name="msapplication-starturl" content="<?=RELPATH?>" />
<meta name="msapplication-navbutton-color" content="<?php
 	if (isset($_GET['name'])) echo $_GET['name'];
	
	$shortNames = $Pony->shortNames();
	$colors = $Pony->colors();
	$longNames = $Pony->longNames();
	
	if (isset($_GET['name']) && in_array($_GET['name'],$shortNames)){
		$ponyShortName = $shortNames[array_search($_GET['name'],$shortNames)];
		echo $colors[array_search($_GET['name'],$shortNames)];
	}
	else if (isset($currentUser['siteData']['name'])){
		if (in_array($currentUser['siteData']['name'],$shortNames)){
			$ponyShortName = $shortNames[array_search($currentUser['siteData']['name'],$shortNames)];
			echo $colors[array_search($currentUser['siteData']['name'],$shortNames)];
		}
		else if ($currentUser['siteData']['name'] == 'random'){
			$_GET['randomname'] = array_random($shortNames);
			$ponyShortName = $_GET['randomname'];
			echo $colors[array_search($ponyShortName,$shortNames)];
		}
		else echo '#777777';
	}
	else echo '#777777';
?>"/>
<meta name="msapplication-tooltip" content="MLP Now" />
<link rel="shortcut icon" href="<?=djpth('favicon.ico')?>">
<link rel="stylesheet" href="<?=djpth('css>ponycolors.css.php')?>">
<link rel="stylesheet" href="<?=djpth('<shared>css>ponyfont.css.php')?>">
<link rel="stylesheet" href="<?=djpth('css>style.css')?>">
<link rel="stylesheet" href="<?=djpth('<shared>css>typicons.css')?>">
<link rel="stylesheet" href="<?=djpth('css>fancybox.css')?>" media="screen">
<script src="<?=djpth('<shared>js>prefixfree.min.js')?>">/* Prefix-free */</script>
<script src="<?=djpth('<shared>js>jquery.min.js')?>">/* jQuery */</script>
<script src="<?=djpth('js>fancybox.js')?>">/* Fancybox */</script>
<script src="<?=djpth('<shared>js>cookie.js')?>">/*Cookie Manager*/</script>
<script src="<?=djpth('js>timer.js')?>">/*Timer*/</script>
<script>
var longtime = <?=365*80+20?>, datestring;
(function(){

if (window.top.location === window.location) window.top.$.fancybox.close();

var MLPNow = {
	GET: {
		name: '<?php if (isset($_GET['name'])) echo $_GET['name'];	?>',
		randomname: '<?php if (isset($_GET['randomname'])) echo $_GET['randomname']; ?>',
		faq: '<?php if (isset($_GET['faq'])) echo $_GET['faq']; ?>',
		timeformat: '<?php if (isset($_GET['timeformat'])) echo $_GET['timeformat']; ?>',
	},
	locale: {
		show: 'mutat',
		hide: 'rejt',
		am: 'd.e.',
		pm: 'd.u.',
		randomTile: 'Véletlen<br>karakter',
		month: [undefined,
			"január",
			"február",
			"március",
			"április",
			"május",
			"június",
			"július",
			"augusztus",
			"szeptember",
			"október",
			"november",
			"december"
		],
		weekday: [
			"Vasárnap",
			"Hétfő",
			"Kedd",
			"Szerda",
			"Csütörtök",
			"Péntek",
			"Szombat"
		],
		formatDay: function(d){
			var MLPNl = MLPNow.locale;
			return {
				weekday: MLPNl.weekday[d.getDay()]+', ',
				year: d.getFullYear()+".",
				month: MLPNl.month[d.getMonth()+1],
				day: d.getDate()+'.',
			};
		},
	},
	timer: {
		func: function(){
			if (!MLPNow.locale.formatDay) return;
			var d = new Date();
			var timeObj = MLPNow.locale.formatDay(d);
			
			MLPNow.timer.year = d.getFullYear();
			MLPNow.timer.month = checkTime(d.getMonth()+1);
			MLPNow.timer.day = checkTime(d.getDate());
			MLPNow.timer.datestring = MLPNow.timer.year+"."+MLPNow.timer.month+"."+MLPNow.timer.day;
			
			$.each(timeObj,function(i,el){
				$('#'+i).text(el);
			});
			
			//reminder.load();
			startTime();
		},
	},
	interval: {},
<?php 	if ($signedIn){ ?>
	pref: <?=json_encode($currentUser['siteData'])?>,
<?php 	} else { ?>
	pref: {},
<?php 	} ?>
};
window.MLPNow = MLPNow;
window.RELPATH = '<?=RELPATH?>';
window.ABSPATH = '<?=ABSPATH?>';
})();
</script>
<script src="<?=djpth('js>Pony.js.php')?>">/*MLPNow.Pony*/</script>
<script>
$(document).ready(function(){	
	$('#slideoutTitle .order-by > *').on('click',function(){sort($(this).attr('id'),true)});
<?php if ($signedIn){ ?>
	$('#version').on('click',function(){$.fancybox.open($.extend(true,fancyboxDefault,{href:RELPATH+'változások'}))});
	$('#toggle').on('click',function(){bottom()});
 	$('#faq').on('click',function(){$.fancybox.open($.extend(true,fancyboxDefault,{href:RELPATH+'gyik'}))});
	$('#creators').on('click',function(){$.fancybox.open($.extend(true,faqfancybox,{href:RELPATH+'gyik/#5'}))});
	$('#vectors').on('click',function(){$.fancybox.open($.extend(true,faqfancybox,{href:RELPATH+'vektorok'}))});
	$('#timeformat').on('click',function(){$.fancybox.open($.extend(true,faqfancybox,{href:RELPATH+'beállítások'}))});
	$('#charsel').on('click',sidebarToggle);
<?php } else { ?>
	$('#login').on('click',sidebarToggle);
<?php } ?>
});</script>
</head>
<body>
<div id="pageContainer">
	<div id="pinNotify">
		<a class="close" href="#" onclick="$(this).parent().slideUp('fast'); return false;">X</a><a href="" onclick="return false;" onmousedown="return false;"><img src="<?=RELPATH?>img/logo.png"></a> <span class="supports_pin">Psszt! A böngésződ támogatja a weboldalak tálcára rögzítését! Próbáldki most, húzd ezt az ikont a tálcára a böngésző címsávjából!</span>
	</div>
	
	<div id="slideoutWrapper">
		<div id="slideout" class="sort-abc">
<?php 	if ($signedIn){ ?>
			<div id="slideoutTitle">
				<h1 class="dyn"><?=count($shortNames)?> db</h1>
				<div class="order-by dyn"><!--
				 --><span id="abc" title="Ábécé sorrendben" class="typcn typcn-sort-alphabetically"></span><!--
				 --><span id="colour" title="Szín szerint" class="typcn typcn-brush"></span><!--
				 --><span id="newabc" title="Újak előre" class="typcn typcn-star"></span>
				</div>
			</div>
<?php 	} ?>
			<div id="slideoutInner">
<?php 	include "includes/Updates.ex.php";
		$updateKeys = array_keys($updates);
		$keyCount = count($updateKeys);
		$i = 0;
		while (!isset($updates[$updateKeys[$i]]['ponies']) && $i <= $keyCount) $i++;
		$recentPonies = array();
		if ($i <= $keyCount) $recentPonies = $updates[$updateKeys[$i]]['ponies'];
		
		if ($signedIn){ ?>
				<div id="randomTile" class="tile">
					<a class="permalink typcn typcn-link" data-title="<?=ABSPATH?>?name="></a>
					<span class="pony-icon random"></span>
					<h4>Véletlen<br>Karakter</h4>
				</div>
<?php	foreach ($Pony->data() as $p){ ?>
				<div class="tile tile-<?=$p['shortName'].(in_array($p['longName'],$recentPonies) ? ' new':'')?>" style="color:<?=$p['color']?>;background:linear-gradient(to bottom, <?=$p['bgcolor']?> 0%,<?=$p['textcolor']?> 100%);" data-shortname="<?=$p['shortName']?>" data-longname="<?=$p['longName']?>"  data-color="<?=$p['color']?>"<?=$p['oc']?' data-oc':''?>>
					<a class="permalink typcn typcn-link" href="?name=<?=$p['shortName']?>" title="<?=ABSPATH.'?name='.$p['shortName']?>"></a>
					<span class="pony-icon <?=$p['shortName']?>"></span>
					<h4><?php
						$name = explode(' ',$p['longName']);
						if (!isset($name[1])) $name[1] = '&nbsp;';
						echo implode('<br>',$name);
					?></h4>
				</div>
<?php	} ?>
<?php } else { ?>
				<iframe allowTransparency="true" src="../extlogin/1"></iframe>
<?php } ?>
			</div>
		</div>
	</div>

	<div id="main">
		<div class="saveIndicator">
			<span class="icon"></span>
			<span class="text">Módosítások mentése</span>
		</div>
		
		<div class="topPart">
			<div class="dateTime">
				<div class="dateDisplay">
					<span id="weekday"></span> <span id="year"></span> <span id="month"></span> <span id="day"></span>	
				</div>
				<div class="timeDisplay" id="time"></div>
			</div>
			<?php if ($signedIn && 1 == 2){ ?>
			<div id="reminderContainer" style="display:none;">
				<span id="reminderDisplay"></span> 
				<span id="editReminder" class="dyn typcn typcn-spanner" title="Emlékeztető Beállítások..."></span>
			</div>
			<?php } ?>
		</div>

		<div class="bottomPart">
<?php 	if ($signedIn){ ?>
			<p><a id="toggle" class="dyn"><span class="typcn typcn-minus"></span><span class="text">rejt</span></a></p>
<?php 	} ?>
			<div class="tbh" <?php if ($signedIn){ ?>style="display:none;"<?php } ?>>
<?php 	if ($signedIn){ ?>
				<p><a class="dyn" id="timeformat">Időformátum</a></p>
<?php 	}
			if ($signedIn){ ?>
				<p class="collapse"><a id="creators" class="dyn">Készítők</a> - <a id="vectors" class="dyn">Vektorok</a></p>
				<p class="collapse">
					<a id="contact" class="dyn typcn typcn-mail" title="djdavid98+mlpnow@gmail.com" href="mailto:djdavid98+mlpnow@gmail.com" target="_blank">Kapcsolat</a> - <!--
				 --><a id="faq" class="dyn typcn typcn-lightbulb" title="Gyakran Ismételt Kérdések">GY.I.K.</a>
				</p>
<?php 	} ?>
				<p>
					<a id="version" class="dyn typcn typcn-cog"><?php
						$_k = array_keys($updates);
						echo $_k[0];
						unset($_k);
					?></a> - <!--
<?php	if ($signedIn){ ?>
				 --><a id="charsel" class="dyn">Karakterválasztó</a>
<?php 	} else { ?>
				 --><a id="login" class="dyn">Belépés</a>
<?php 	} ?>
				</p>
			</div>
		</div>
	</div>

	<div id="bgContainer">
		<div class="loading"></div>
		<img class="background" id="pony">
		<img class="background" id="splat">
	</div>

</div>

<!-- JS Plug-ins -->
<script src="<?=djpth('js>pusher.color.min.js')?>"  		>/*Colors*/</script>
<script>
MLPNow.timer.func();
MLPNow.timer.interval = setInterval(MLPNow.timer.func,500);
</script>
<!-- <script src="<?=djpth('js>reminder.js')?>"		  		>/*Reminders*/</script> -->
<?php 	if ($signedIn){ ?><script src="<?=djpth('<shared>js>konami.js')?>"	>/*Initialization*/</script>
<script src="<?=djpth('js>formembers.js')?>"	>/*Initialization*/</script>
<script>var loggedin = true;</script><?php 
		}
		else { ?><script>
	var changeBGImage, bottom, sort, ampm;
		changeBGImage= bottom= sort= ampm= function(){};
	
	var loggedin = false;

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-35773360-2', 'auto');
ga('set', 'contentGroup2', 'My Group Name');
ga('send', 'pageview');
</script><? } ?>
<script src="<?=djpth('js>init.js')?>"			  		>/*Initialization*/</script>
</body>
</html>