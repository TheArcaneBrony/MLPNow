<!DOCTYPE html>
<html>
<head>
	<title>Beállítások - MLP Now</title>
	
	<link rel="shortcut icon" href="<?=djpth('favicon.ico')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>metro.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>grid.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>theme.css')?>">
	<link rel="stylesheet" href="<?=djpth('css>overrides.css.php')?>">
	<link rel="stylesheet" href="<?=djpth('<css>settings.global.css')?>">
	<link rel="stylesheet" href="<?=djpth('css>settings.css')?>">
	<link rel="stylesheet" href="<?=djpth('<shared>css>typicons.css')?>">
	<link rel="stylesheet" href="<?=djpth('<css>jquery.powertip.min.css')?>">
	
<?php include "views/header.php"; ?>

	<div class="grid-parent grid-100 clearfix">
		<div class="grid-parent grid-70 left-col">
<?php	if (!isset($_MSG)){ ?>
			<div class="title">
				<h1>MLP Now beállítások</h1>
			</div>
			<ul class="settings metrouicss">
				<li class="timeformatchange">
					<form method="POST">
						<label>Időformátum megváltoztatása</label>
						<div class="input-control text">
							<div class="fancy-select timeformats">
<?php		foreach ($POSSIBLE_TIMES_FORMATS as $tf){ ?>
								<label for="tf_<?=$tf?>">
									<input type="radio" name="ntimeformat" id="tf_<?=$tf?>" value="<?=$tf?>"<?=$tf === $targetUser['timeformat']?' checked':''?>>
									<div class="timeformat <?=$tf?>" style="background-image: url('<?=djpth("img>timeformats>{$tf}.png")?>');"></div>
								</label>
<?php		} ?>
							</div>
						</div>
						<input type="hidden" name="uid" value="<?=$userDetails['uid']?>">
					</form>
				</li>
			</ul>
<?php	} else { ?>
			<div class="title fail">
				<h1><?=isset($_MSG)?$_MSG:''?></h1>
			</div>
<?php	} ?>
		</div>
		<div class="grid-parent grid-30 right-col">
<?php
		include "views/sidebar.php";
		echo profComplCalc(false,true); ?>
		</div>
<?php /*
	</MIDDLE COL>
*/ ?>
	</div>
	
<script src="<?=djpth('<js>settings.global.js')?>"></script>
<script src="<?=djpth('<shared>js>jquery.powertip.min.js')?>"></script>
<script src="<?=djpth('js>settings.js')?>"></script>
<script>var loadedtime = new Date(),
	targetid = <?=$signedIn?$currentUser['uid']:0?>,
	table = 'userdata',
	noroot = true;</script>
<?php include "footer.php"; ?>