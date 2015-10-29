<!DOCTYPE html>
<!-- INDEX PREVIEW -->
<html lang="hu">
<head>
<title>MLP Now PREVIEW</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable = no">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="<?=djpth('img>favicon>favicon.png')?>">
<link rel="shortcut icon" href="<?=djpth('img>favicon>favicon.ico')?>">
<link rel="stylesheet" href="<?=djpth('css>ponycolors.css.php')?>">
<link rel="stylesheet" href="<?=djpth('css>style.css')?>">
<script src="<?=djpth('<shared>js>prefixfree.min.js')?>">/* Prefix-free */</script>
<script src="<?=djpth('<shared>js>jquery.min.js')?>">/* jQuery */</script>
<script src="<?=djpth('js>timer.js')?>">/*Timer*/</script>
<script>
var longtime = 365*80+20, datestring;
(function(){
var MLPNow = {
	GET: {
		name: '',
		randomname: '',
		faq: '',
		timeformat: '',
	},
	locale: {
		show: 'mutat',
		hide: 'rejt',
		am: 'd.e.',
		pm: 'd.u.',
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
			var MLPTl = MLPNow.locale;
			return {
				weekday: MLPTl.weekday[d.getDay()]+', ',
				year: d.getFullYear()+".",
				month: MLPTl.month[d.getMonth()+1],
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
			
			startTime();
		},
	},
	interval: {},
	pref: {
		timeformat: '24',
	}
};
window.MLPNow = MLPNow;
window.RELPATH = '<?=RELPATH?>';
window.ABSPATH = '<?=ABSPATH?>';
})();
</script>
</head>
<body>
<div id="pageContainer">

<div id="main">
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
<?php
		$thePony = array(
			'shortName' => 'default',
			'color' => 'default',
		);
		$foundPony = false;
		if (in_array($data,$Pony->shortNames())){
			$_thePony = $Pony->get(array(
				"shortName" => $data.'',
			));
			if (isset($_thePony[0])){
				$thePony = $_thePony[0];
				$foundPony = true;
			}
		}
?>
		<p>Selected character:</p>
		<p class="<?=$foundPony?$thePony['shortName']:'datgrey'?>" style="font-size: 3em; line-height: 100%;"><?=($foundPony?$thePony['longName']:'N/A')?></p>
<?php	if ($foundPony){ ?>
		<p>Credit for the vector goes to:</p>
		<p style="font-size: 3em; line-height: 100%;"><?=parsePony($thePony,true,$thePony['vector'])?></p>
<?php	} ?>
		<p><i>You're on MLP Now's preview page, your settings are unavailable.</i></p>
	</div>
</div>

<div id="bgContainer">
<?php	if (in_array($data,$Pony->shortNames())){ ?>
	<img class="background" id="pony" style="display:block" src="<?=djpth('pony>'.($foundPony?$thePony['shortName']:'default'))?>">
<?php	} ?>
	<img class="background" id="splat" style="display:block" src="<?=djpth('splat>'.($foundPony?str_replace('#','',$thePony['textcolor'].'/'.$thePony['bgcolor']):'default'))?>">
</div>

</div>

<!-- JS Plug-ins -->
<script>MLPNow.timer.interval = setInterval(MLPNow.timer.func,500);</script>
<script>var loggedin = false, preview = true;</script>
<!-- <script src="<?=djpth('js>init.js')?>"			  		>/*Initialization*/</script> -->
</body>
</html>