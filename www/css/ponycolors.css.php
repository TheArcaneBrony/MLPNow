<?php
	header('Content-Type: text/css');
	include "../config.php";
	foreach ($Pony->data() as $i => $p) echo ($i>0?'
':'').'.'.$p['shortName'].'{color:'.(strlen($p['color'])>0?$p['color']:'#000').'}	/* '.$p['longName'].' */';