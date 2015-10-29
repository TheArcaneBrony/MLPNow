<?php 	if (!$signedIn){ ?>
			<div class="input-form login-form">
				<h1 class="logo"><span>hun</span><span>pony</span> fiók<br>bejelentkezés</h1>
				<p>A fiókod segítségével a website miden területén végrehajtott változások megmaradnak, akárhol is lépsz be!</p>
				<form class="metrouicss" id="login-form" method="POST" action="<?=RELPATH?>../login">
					<div class="input-control text">
						<input type="text" name="username" tabindex="1" placeholder="Felhasználónév" autocomplete="off" maxlength="20" required pattern="^[a-z][a-z0-9-_.]{3,19}$" autofocus="autofocus">
						<input type="password" name="password" tabindex="2" placeholder="Jelszó" required pattern="^.{5,}$">
						<label><input type="checkbox" name="remember" tabindex="3" checked> Belépve szeretnék maradni 30 napig</label>
						<?php if ($_SERVER['SERVER_NAME'] == 'localhost'){ ?>
						<label><input type="checkbox" name="livedb" tabindex="3"<?=isset($_COOKIE['livedb'])?' checked':''?>> Elő adatbázishoz csatlakozás</label>
						<?php } ?>
					</div>
					<p class="regnotify fg-color-red"></p>
					<input type="submit" class="hiddensubmit">
				</form>
				<button data-form="login-form" tabindex="4" class="stylish-button"><span>be</span><span>lépés</span></button>
				<a href="<?=RELPATH?>../register" tabindex="5" class="stylish-button"><span>új </span><span>fiók</span></a>
			</div>
<?php 	} else { ?>
			<div class="logged-in">
				<h1 class="logo"><span>hun</span><span>pony</span> fiók</h1>
				<h2>Üdv, <a class="role-<?=$currentUser['role']?>" href="<?=djpth('<profile>'.$currentUser['uid'])?>"><?=$currentUser['displayname']?></a>!</h2>
				<p>Jelenleg <?php
					$proj = $hpAccDB->get('projects');
					$vproj = 0;
					$hproj = 0;
					foreach ($proj as $p){
						$p['madeby'] = array_merge(
							array($p['owner']),
							explode(',',$p['contributors'])
						);
						if (in_array(strval($currentUser['uid']),$p['madeby'])){
							if ($p['private']) $hproj++;
							else $vproj++;
						}
					}
					
					if ($vproj + $hproj === 0) echo "egyetlen projectnek sem vagy része";
					else {
						if ($vproj > 0 && $hproj === 0) echo "$vproj nyilvános projectnek vagy része";
						else if ($hproj > 0 && $vproj === 0) echo "$hproj rejtett projectnek vagy része";
						else echo "$vproj nyilvános és $hproj rejtett projectnek vagy része";
					}
				?>.</p>
				<p>Ma <strong><?=preg_replace('/(,\s| és )/','</strong>$1<strong>',$NAMEDAY)?></strong> névnapja van.<?php
					$isNDay2Day = false;
					foreach ($NAMEDAY_ARRAY as $n){
						if (strpos(strtolower($n),strtolower($currentUser['displayname'])) !== false){
							$isNDay2Day = true;
							break;
						}
					}
					if ($isNDay2Day){ ?>
<br><em>Boldog névnapot!</em>
<?php
					}
				?></p>
				<ul class="links">
					<li>
						<a class="logout-btn">Kijelentkezés</a>
					</li>
					<li>
						<a href="<?=djpth('beállítások')?>">Beállítások</a>
					</li>
					<li>
						<a href="<?=djpth('<')?>">Vissza a hunpony-ra</a>
					</li>
				</ul>
			</div>
<?php 	} ?>