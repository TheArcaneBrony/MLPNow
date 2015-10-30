<!DOCTYPE html>
<!-- CHANGELOG -->
<html lang="hu">
<head>
	<title id="title">Változási napló - MLP Now</title>

<?php
	$customCSS = array('css>changelog.css');
	include "header.php";
?>
	<div class="grid-parent grid-100 clearfix">
		<div class="grid-70 grid-parent left-col">
			<div class="title">
				<h1>Változási napló</h1>
			</div>
			<ul class="changelog">
<?php
	$ponyadd = array("új","póni","póni","hozzáadva","várható még",'Nincs');
	
	$counter = 0;
	foreach ($updates as $key => $entry){
		$latest = $counter++ === 0;
		
		if (floatval($key) < 1) $beta = 'β ';
		else $beta = '';
		unset($entry['beta']); ?>
				<li class="log-entry grid-parent clearfix">
					<div class="version grid-20"><?=$beta.'v'.$key?></div>
					<ul class="grid-80">
<?php	foreach ($entry as $i => $log){
			if (is_array($log)){
				switch ($i){
					case "ponies":
						$onhold = 0;
						$holdstr = '';
						if ($latest){
							// Remove yet to be added characters
							foreach ($log as $key => $longName){
								if (!in_array($longName,$Pony->longNames())){
									unset($log[$key]);
									$onhold++;
								}
							}
							
							if ($onhold > 0){
								$hold = $ponyadd;
								array_splice($hold, ($onhold != 1 ? 1 : 2), 1);
								$holdstr = " ($onhold ".$hold[1].' '.$hold[3].')';
							}
						}
						
						$number = count($log);
						$add = $ponyadd;
						array_splice($add, ($number != 1 ? 1 : 2), 1);
						array_splice($add, count($add)-2, 1);
						sort($log);
						
						if ($number == 1){ ?>
					<li><?=$log[0].' '.$add[2].$holdstr?></li>
<?php					}
						else if ($number > 1){
							array_pop($add);
							$str = $number.' '.implode(' ',$add).$holdstr; ?>
					<li><?=$str."\n"?>
						<ol class="newPonyNames">
							<?='<li>'.implode('</li>'."\n\t\t\t\t".'<li>',$log).'</li>'."\n"?>
						</ol>
					</li>
<?php					}
						else {
							$str = implode(' ',array($add[3],$add[0],$add[1],$add[2])).$holdstr; ?>
					<li><?=$str?></li>
<?php					}
					break;
					case "rename":
						$P = $Database->where('shortName',$log['shortName'])->getOne('ponies'); ?>
					<li><span class="pony-icon"><?=$P['longName']?></span> át lett nevezve (<?php
						if ($P['longName'] === $log['to']){
		?>előző név: <u><?=$log['from']?></u><?php	
						}
						else {
		?><u title="Előző név"><?=$log['from']?></u> &gt; <u title="Új név"><?=$log['to']?></u><?php
						}
		?>)</li>
<?php
					break;
					case "colorreplace": ?>
					<li><?=textify($log)?> színe lecserélve</li>
<?php				break;
					case "imgreplace": ?>
					<li><?=textify($log)?> képe lecserélve</li>
<?php				break;
				}
			}
			else { ?>
					<li><?=$log?></li>
<?php		}
		} ?>
					</ul>
				</li>
<?php
	}
?>
			</ul>
		</div>
		<div class="grid-parent grid-30 right-col">
<?php include "views/sidebar.php"; ?>
		</div>
	</div>

<?php
	$customJS = array('js>changelog.js');
	include "footer.php";
?>
