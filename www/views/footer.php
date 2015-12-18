	</div>

<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script>if(!window.jQuery)document.write('\x3Cscript src="/js/jquery-2.1.4.min.js">\x3C/script>');<?php
	if (!$signedIn || $currentUser['role'] !== 'developer'){ ?>

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '<?=GA_TRACKING_CODE?>', 'auto');
ga('require', 'displayfeatures');
ga('send', 'pageview');
<?php } ?>
</script>
<?  if (isset($customJS)) foreach ($customJS as $js){
		echo "<script src='$js'></script>\n";
	} ?>
</body>
</html>
