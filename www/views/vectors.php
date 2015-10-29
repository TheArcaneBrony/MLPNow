<!DOCTYPE html>
<html>
<head>
	<title>Felhasznált vektorok - MLP now</title>
	
	<link rel="shortcut icon" href="<?=djpth('favicon.ico')?>">
<?php
	$customCSS = array(
		'<css>listing.global.css',
		'<css>limit-list-width.global.css.php?width=100&twocol',
		'css>vectors.css',
	);
	include "views/header.php"; 
/*
foreach($Pony->data() as $a){
	echo parsePony($a);
}
*/
?>
	<div class="grid-parent grid-100 clearfix">
		<div class="grid-parent grid-70 left-col">
			<ul class="listing">
<?php	foreach ($Pony->data() as $P){
			$isDeviation = $P['favme'][0] == 'd';
			$sourceURL = 'http:'.($isDeviation?'//fav.me/':'').$P['favme'];
			if (!$isDeviation){
				$creatorURL = $sourceURL;
				$sourceDomain = parse_url($sourceURL);
				$sourceDomain = $sourceDomain['host'];
			}
			else {
				$sourceDomain = 'fav.me';
				if (preg_match('/^d[a-z0-9]{6}$/',$P['favme']) && !strlen($P['vector'])){
					// Dealing with deviantART links without an author name
					$_get = json_decode(file_get_contents('http://backend.deviantart.com/oembed?url=http://fav.me/'.$P['favme']), true);
					$P['vector'] = $_get['author_name'];
					$Database->where('shortName',$P['shortName'])->update('ponies',array(
						"vector" => $username,
					));
				}
				$creatorURL = 'http:'.($isDeviation?'//'.strtolower($P['vector']).'.deviantart.com/':$P['vector']);
			}
			
			?>
				<li>
					<div class="left clearfix">
						<img style="background-image: url('<?=djpth('vector>'.$P['shortName'].'>'.substr($P['color'],1))?>')" src="<?=djpth('<img>blank100.gif')?>">
					</div>
					<div class="right">
						<h1 class="<?=$P['shortName']?>"><?=$P['longName']?></h1>
						<p>Forrás: <a href="<?=$sourceURL?>" target="_blank"><?=$sourceDomain?></a></p>
<?php		if (strlen($P['vector'])){ ?>
						<p>Készítette: <a href="<?=$creatorURL?>" target="_blank"><?=$P['vector']?></a></p>
<?php		} ?>
					</div>
				</li>
<?php 	} ?>
			</ul>
		</div>
		<div class="grid-parent grid-30 right-col">
<?php include "views/sidebar.php"; ?>
		</div>
	</div>
	
<?php include "footer.php"; ?>