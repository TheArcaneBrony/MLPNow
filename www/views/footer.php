	</div>

	<footer>
		<p>MLP Now <?php
			include "includes/Updates.ex.php";
			$updateKeys = array_keys($updates);
			echo $updateKeys[0];
		?> &copy; DJDavid98</p>
		<p>My Little Pony: Friendship is Magic &copy; Hasbro, Inc. | <a href="http://fav.me/d6pwttf" target="blank">Notitfications icon</a> (modified) by <a href="http://crystalvectors.deviantart.com/" target="blank">CrystalVectors</a></p>
	</footer>

<script>var MLPNow;
parent.$('title').html($('title').html());
parent.$('link[rel="shortcut icon"]').attr('href',$('link[rel="shortcut icon"]').attr('href'));

if (typeof parent.MLPNow === 'object') MLPNow = parent.MLPNow;
else MLPNow = {pref:{<?php
	if ($signedIn){
		$pref = $Database->where('usrid',$currentUser['uid'])->getOne('userdata');
		echo 'name:"'.$pref['name'].'"';
	}
?>}};</script>
<script src="<?=djpth('js>Pony.js.php')?>">/*MLPNow.Pony*/</script>
<?php if (isset($customJS) && is_array($customJS)) foreach ($customJS as $js){ ?>
	<script src="<?=djpth($js)?>"></script>
<?php
	}
	if ($signedIn){ ?>
<script src="<?=djpth('<shared>js>favico.js')?>"></script>
<script src="<?=djpth('<shared>js>notify.js')?>"></script>
<?php } ?>
<script src="<?=djpth('<shared>js>jquery.nicescroll.js')?>"></script>
<script src="<?=djpth('<shared>js>metro-dialog.js')?>"></script>
<script src="<?=djpth('<shared>js>dyntime.js')?>"></script>
<script>var kickedOut = <?=json_encode($kickedOut)?>, banned = <?=json_encode($banned)?>, notVerified = <?=json_encode($notVerified)?>, respond = <?=isset($respond)?json_encode($respond):'false'?>;</script>
<script>var RELPATH = '<?=RELPATH?>', ABSPATH = '<?=ABSPATH?>';</script>
<script src="<?=djpth('<js>actions.js')?>"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-35773360-2', 'auto');
ga('set', 'contentGroup2', 'My Group Name');
ga('send', 'pageview');

$(function(){
	if (isframed){
		$('header nav ul:last-child li:first-child').children('a').text('Bezárás').addClass('typcn typcn-times').on('click',function(){ parent.$.fancybox.close(); return false }).attr('href','#close');
		if ($('.sign-in').length > 0 && parent.$('#charsel').length > 0) parent.location.reload();
		else if (($links = $('.right-col .logged-in .links')).length > 0) $links.children('li').last().remove();
	}
});
</script>

</body>
</html>