<!DOCTYPE html>
<!-- REMINDER MANAGER -->
<html lang="hu">
<head>
<title id="title">MLP Now REM</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="icon" type="image/png" href="<?=djpth('img>favicon>favicon.png')?>">
<meta name="robots" content="none">
<style>
<?php
foreach ($Pony->data() as $p)	echo '.'.$p['shortName'].'{color:'.$p['color'].'}	/* '.$p['longName'].' */
';?>
</style>
<link rel="stylesheet" href="<?=djpth('<shared>css>typicons.css')?>">
<link rel="stylesheet" href="<?=djpth('css>rem.css')?>">
<link rel="stylesheet" href="<?=djpth('css>style.css')?>">
<link rel="stylesheet" href="<?=djpth('css>bootstrap.min.css')?>">
</head>
<body style="display:none;">

	<h1>MLP Now</h1>
	<h2>Emlékeztetők módosítása</h2>
	<a href="javascript:parent.$.fancybox.close();" class="typcn typcn-times">Bezárás</a> | <a href="#" onclick="window.location = window.parent.$('.fancybox-inner iframe').attr('src');" class="typcn typcn-refresh">Újratöltés</a>
<br>

<table id="reminders">
	<colgroup>
		<col width="40%">
		<col width="40%">
		<col width="20%">
	</colgroup>
	<thead>
		<tr>
			<td>Emlékeztető szövege</td>
			<td>Időpont (Ma: <time id="dateInfo"></time>)</td>
			<td>Műveletek</td>
		<tr>
	</thead>
	<tbody>
		<tr id="remdef"><form>
			<td class="remtxt">
				<label>
					<textarea rows="5" required></textarea>
				</label>
			</td>
			<td class="remtim">
				<label>
					<input type="text" required>
				</label>
			</td>
			<td class="remope">
				<div class="btn-group">
					<button class="btn btn-large btn-success action-add"></button>
				</div>
			</td>
		</form></tr>
	</tbody>
</table>

<div class="modal hide fade" id="modal">
	<div class="modal-header alert alert-error"><h4></h4>	</div>
	<div class="modal-body"><div class="alert alert-block alert-error setErrorText"></div></div>
	<div class="modal-footer">
		<span class="onlyDisplayOnError"></span>
		<button class="btn btn-large" data-dismiss="modal"></button>
	</div>
</div>

<!-- JS Plug-ins -->
<script src="/jquery.min.js">/* jQuery */</script>
<script src="../../js/bootstrap.min.js">/* Twitter Bootstrap */</script>
<script src="../../js/cookie.js">/* Cookies */</script>
<script>
var remLocale = parent.MLPNow.locale.reminder, lang = parent.MLPNow.lang;
var browser = "<?php $u_agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/MSIE/i',$u_agent) && 
      !preg_match('/Opera/i',$u_agent))			{echo 'ie';}
elseif(preg_match('/webkit/i',$u_agent))		{echo 'ch';}
elseif(preg_match('/mozilla/i',$u_agent) && 
      !preg_match('/compatible/', $u_agent))	{echo 'ff';}
elseif(preg_match('/Opera/i',$u_agent))			{echo 'op';}
elseif(preg_match('/Netscape/i',$u_agent))		{echo 'ns';}?>";
var mET = { //modalErrorText
	title: '<h4>Az emlékeztető beállítása közben hiba történt</h4>',
	unknownError: 'Ismeretlen hiba',
	ok: 'Oké',
	pastDate: 'A beírt dátum múltbeli időpontra mutat.',
	invalidDate: 'A beírt dátum nem megfelelő formátumú.<br>Tartsd magad az <pre class="inline">'+remLocale.dateFormat+'</pre> formához.<br>é - év, h - hónap, n - nap',
	nonExistantDate: 'A beírt dátum nem megfelelő.<br>A dátum érvénytelen időpontra mutat.',
};
var mIT = { //modalInfoText
	title: '<h4>Tájékoztató információ</h4>',
	unknownInfo: 'Nincs meghatározva információ.',
	ok: 'Tudomásul vettem',
	containsCode: 'A beírt szöveg taratlamz elemeket, melyek a HTML nyelvben szkriptek futtatására alkalmasak.<br>Az emlékeztetőben ilyen HTML kódot <b>NEM</b> használhatsz, biztonsági okokból.<br>A beírt kód el lett távolítva a beállított emlékeztetőből.',
	sameDate: 'Erre a napra már van beállítva emlékeztető.<br>Ha 2 vagy több van beállítva egy napra, csak az első fog megjelenni.',
};</script>
<script src="../../js/rem_init.js">/* Initialization */</script>
</body>
</html>