<?php include_once "config.php"; header('HTTP/1.1 404 Not Found') ?>
<!DOCTYPE html>
<html>
<head>
	<title>404 - MLP Now</title>
	
	<link rel="shortcut icon" href="<?=djpth('favicon.ico')?>">
	<link rel="stylesheet" href="<?=djpth('<css>metro.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>grid.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>theme.css')?>">
	<link rel="stylesheet" href="<?=djpth('css>overrides.css.php')?>">
	<link rel="stylesheet" href="<?=djpth('css>404.css')?>">

<?php include "header.php"; ?>

	<div class="grid-parent grid-100 clearfix">
		<div class="grid-70 grid-parent left-col">
			<h1>A keresett oldal nem található.</h1>
			<p>Ha egy külső weboldalról jutottál ide, akkor valószínűleg nemrég volt egy változás az oldalban, és még nem frissítették a linkeket.</p>
			<p>Ha az oldalon belülről jutottál erre az oldalra, akkor valamit nagyon elszúrtunk.</p>
		</div>
		<div class="grid-parent grid-30 right-col">
<?php include "sidebar.php"; ?>
		</div>
	</div>

<?php include "footer.php"; ?>