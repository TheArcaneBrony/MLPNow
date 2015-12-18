<?  if ($signedIn){ ?>
<div id="slideoutWrapper" class="dyn">
	<div id="slideout" class="sort-abc">
		<div id="slideoutTitle">
			<h1 class="dyn"><?=count($Ponies)?> db</h1>
			<div class="order-by dyn"><!--
			 --><span id="abc" title="Ábécé sorrendben" class="typcn typcn-sort-alphabetically"></span><!--
			 --><span id="colour" title="Szín szerint" class="typcn typcn-brush"></span><!--
			 --><span id="newabc" title="Újak előre" class="typcn typcn-star"></span>
			</div>
		</div>
		<div id="slideoutInner">
<?php   $updateKeys = array_keys($updates);
		$keyCount = count($updateKeys);
		$i = 0;
		while (!isset($updates[$updateKeys[$i]]['ponies']) && $i < $keyCount) $i++;
		$recentPonies = array();
		if ($i < $keyCount)
			$recentPonies = $updates[$updateKeys[$i]]['ponies']; ?>
			<div id="randomTile" class="tile">
				<span class="pony-icon random"></span>
				<h4>Random<br>character</h4>
			</div>
		</div>
	</div>
</div>
<?  } ?>

<div id="gui">
	<div class="saveIndicator">
		<span class="icon"></span>
		<span class="text">Saving settings&hellip;</span>
	</div>

	<div class="topPart">
		<div class="dateTime">
			<div class="dateDisplay"></div>
			<div class="timeDisplay"></div>
		</div>
	</div>

	<div class="bottomPart">
<?php 	if ($signedIn){ ?>
		<p><a id="toggle" class="dyn"><span class="typcn typcn-minus"></span><span class="text">hide</span></a></p>
<?php 	} ?>
		<div class="tbh">
<?php 	// TODO
		/* if ($signedIn){ ?>
			<p><a class="dyn" id="timeformat">Time format</a></p>
			<p class="collapse"><a id="vectors" class="dyn">Vector Credits</a></p>
<?php   } */
	if ($signedIn){ ?>
			<p>Signed in as <span class="dyn"><?=$currentUser['name']?></span></p>
			<p><a id="charsel" class="dyn">Character select</a> - <a id="signout" class="dyn">Sign Out</a> - <a id="link" class="dyn">Link</a></p>
<?  }
	else { ?>
			<p><a id="login" class="dyn">Log in</a></p>
<?  } ?>
			<p><a id="version" class="dyn">MLP Now <?=array_keys($updates)[0].'.0.'.LATEST_COMMIT_ID?></a> by <a class="dyn" href="http://djdavid98.hu/">DJDavid98</a></a></p>
		</div>
	</div>
</div>

<div id="bgContainer">
	<div class="loading"></div>
</div>

<script>
var longtime = <?=365*80+20?>, datestring;
(function(){
	var MLPNow = {
		locale: {
			show: 'show',
			hide: 'hide',
			am: 'a.m.',
			pm: 'p.m.',
			month: [undefined,
				"january",
				"february",
				"march",
				"april",
				"may",
				"june",
				"july",
				"august",
				"september",
				"october",
				"november",
				"december"
			],
			weekday: [
				"Sunday",
				"Monday",
				"Tuesday",
				"Wednesday",
				"Thursday",
				"Friday",
				"Saturday"
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
			randomTile: 'Random<br>character',
		},
		timer: {},
		pref: <?=JSON::Encode(array_replace($DEFAULT_SETTINGS, get_prefs(true)))?>,
		signedIn: <?=$signedIn?'true':'false'?>,
	};
<?  if ($signedIn){ ?>
	MLPNow.username = <?=json_encode($currentUser['name'])?>;
	MLPNow.Pony = <?=json_encode($Ponies, JSON_UNESCAPED_SLASHES)?>;
	MLPNow.shortnames = [];
	MLPNow.recentPonies = <?=json_encode($recentPonies, JSON_UNESCAPED_SLASHES)?>;

	var PonyfontTag = document.createElement('style'),
		ponyfontStyleString = ".pony-icon.random:before{content:'\\a000'}",
		slideoutInner = document.getElementById('slideoutInner');
	for (var i = 0, l = MLPNow.Pony.length; i < l; i++)
		(function(p){
			MLPNow.shortnames.push(p.shortname);

			// Create icon font styles
			var content = parseInt(p.shortname, 36).toString(16);
			while (content.length < 3)
				content = '0'+content;
			ponyfontStyleString += '.pony-icon.' + p.shortname + ":before{content:'\\a" + content + "'}";
			ponyfontStyleString += '.dyn.' + p.shortname + "{color:"+p.color+"}";

			// Create tiles
			var tile = document.createElement('div');
			tile.className = 'tile tile-'+p.shortname+(MLPNow.recentPonies.indexOf(p.longname) !== -1 ? ' new':'');
			tile.style.color = p.color;
			tile.style.backgroundImage = 'linear-gradient(to bottom, '+p.bgcolor+' 0%, '+p.textcolor+' 100%)';
			tile.setAttribute('data-shortname', p.shortname);
			tile.setAttribute('data-longname', p.longname);
			tile.setAttribute('data-color', p.color);
			if (p.oc)
				tile.setAttribute('data-oc', '');
			var name = p.longname.split(' ');
			if (typeof name[1] !== 'string')
				name[1] = '&nbsp;';
			name = name.join('<br>');

			var span = document.createElement('span');
			span.className = 'pony-icon '+p.shortname;

			var h4 = document.createElement('h4');
			h4.innerHTML = name;

			tile.appendChild(span);
			tile.appendChild(h4);
			slideoutInner.appendChild(tile);
		})(MLPNow.Pony[i]);
	PonyfontTag.innerHTML = ponyfontStyleString;
	document.head.appendChild(PonyfontTag);
<?  } ?>

	window.MLPNow = {
		signedIn: MLPNow.signedIn,
		locale: MLPNow.locale,
		Pony: MLPNow.Pony,
		shortnames: MLPNow.shortnames,
		timer: MLPNow.timer,
		pref: MLPNow.pref,
	};
})();
</script>
