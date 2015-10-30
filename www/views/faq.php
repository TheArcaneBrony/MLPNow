<!DOCTYPE html>
<!-- FAQ -->
<html lang="hu">
<head>
	<title id="title">Gyakran Ismételt Kérdések - MLP Now</title>

<?php
	$customCSS = array('css>faq.css','<css>listing.global.css');
	include "header.php";
?>
	<div class="grid-parent grid-100 clearfix">
		<div class="grid-70 grid-parent left-col">
			<ul class="listing">
				<li class="noleft">
					<div class="right">
						<h1 class="que" id=1>Nem látom a kedvenc karakterem, hozzá tudod adni?</h1>
						<div class="ans">
							<h2>Sorozatban szereplő, ismert karaktereknél:</h2>
							<p>Persze. Írj egy e-mail-t az <a href="mailto:djdavid98+mlpnow@gmail.com?subject=MLP Now Karakterkérés" target="_blank">mlpnow@hunpony.hu</a> címre a karakter nevével.</p>
							<p>Ha az adott karakterhez nem található vektor, nem garantált, hogy bekerül.</p>
							<br>
							
							<h2>OC, azaz <i>saját</i> póni kérése:</h2>
							<p>Írj egy e-mail-t az <a href="mailto:djdavid98+mlpnow@gmail.com?subject=MLP Now Karakterkérés" target="_blank">djdavid98@gmail.com</a> címre, melyben megadod az OC-d <b>teljes nevét</b>, és melyhez link vagy csatolmány formájában elküldöd a képet az OC-d ról.</p>
							<p>Fontos, hogy a kép mérete nem lehet kisebb, mint 350 képpont sem szélességben, sem magasságban és csak AI, PNG vagy PSD fájl lehet, átlátszó háttérrel.</p>
							<p>Emellet még meg kell adnod egy (bece)nevet ami a vektor mellé a GYIK-be kikerül, és egy linket ami egy hozzád tartozó weboldalra mutat.</p>
							<p class="red">Azok az OC-k, amelyek nem a sorozathoz hűen vannak rajzolva, nem kerülnek be.<br>Kérj meg valakit, hogy vektorozza le neked az OC-d ha papírra vagy más stílusban rajzoltad.</p>
							<p>A kérések <i>1-2 nap</i> alatt elkészülnek.<br>
							<span class="red">Felnőtt karakterek filly változatai és fillyk felnőtt változatai NEM lesznek hozzáadva!</span></p>
						</div>
					</div>
				</li>
				
				<li class="noleft">
					<div class="right">
						<h1 class="que" id=3>Mi ennek az oldalnak a célja/alapötlete?</h1>
						<div class="ans">
							<p>Eredetileg a <a href="http://milyennapvan.hu/" target="_blank">milyennapvanma.hu</a> weboldal pónisított változataként indult, de azóta már nagy mértékben továbbfejlődött az oldal.</p>
						</div>
					</div>
				</li>

				<li class="noleft">
					<div class="right">
						<h1 class="que" id=5>Ki áll az oldal mögött?</h1>
						<div class="ans">
							<p>Az oldalt <a href="http://djdavid98.eu/" target="_blank">DJDavid98</a>, azaz én kódoltam, a háttér festék effektjét pedig <a href="http://aman692.deviantart.com/" target="_blank">aman692</a>-nek köszönhetem. <a href="http://bit.ly/SupportAman692" target="_blank" class="dyn">Támogasd Őt!</a></p>

							<div class="fb-like" data-href="https://www.facebook.com/MLPNow" data-width="450" data-show-faces="true" data-send="false"></div>
						</div>
					</div>
				</li>
				
				<li class="noleft">
					<div class="right">
						<h1 class="que" id=7>Mik az URL paraméterek, és hogy használhatom őket?</h1>
						<div class="ans">
							<p>Az URL paramétereket nagyon egyszerű használni. Hogy könnyeb legyen elmagyarázni, mutatok egy példát. Itt a projekt URL-je:</p>
							<p style="font-size:22px;"><span style="color:grey;"><?=ABSPATH?></span><span style="color:red">?</span><span style="color:green">name</span><span style="color:#880">=</span><span style="color:blue">vs</span><span style="color:red">&amp;</span><span style="color:green">timeformat</span><span style="color:#880">=</span><span style="color:blue">24</span></p>
							<p>Amint láthatod, az <span style="color:grey;">oldal elérési útvonala</span> után került egy <span style="color:red">kérdőjel</span>, utána a <span style="color:green">paraméter neve</span>, egy <span style="color:#880">egyenlőségjel</span>, végül pedig a <span style="color:blue">paraméter értéke</span>. Ha több paraméter is van, akkor <span style="color:red">&amp;</span> jellel választjuk el őket.</p>
							<p class="underlined">Az MLP Now-ban az alábbi paraméterek használhatóak:</p>
							<ol>
								<li>
									<p><span style="color:red">?</span><span style="color:green">name</span><span style="color:#880">=</span><span style="color:blue">[póni rövid neve]</span> Megadható a pónik rövid neve gyors linkeléshez. (pl. <span style="color:red">?</span><span style="color:green">name</span><span style="color:#880">=</span><span style="color:blue">vs</span>)</p>
									<p>Rövid nevek: <span id="shortnames"><?php
		foreach ($Pony->data() as $P){
			?><span class="pony-icon <?=$P['shortName']?>" title="<?=$P['longName']?>"><?=$P['shortName']?></span> <?php
		}
									?></span></p>
								</li>
								<li>
									<span style="color:red">?</span><span style="color:green">timeformat</span><span style="color:#880">=</span><span style="color:blue">[12|24|at]</span> Megadható egy időformátum azonosító, amelyhez tartozó időformátum beállításra kerül. (pl. <span style="color:red">?</span><span style="color:green">timeformat</span><span style="color:#880">=</span><span style="color:blue">at</span>)
								</li>
								<li>
									<span style="color:red">?</span><span style="color:green">faq</span><span style="color:#880">=</span><span style="color:blue">[szám]</span> Linkelés a GY.I.K. egy adott kérdéséhez. (pl. <span style="color:red">?</span><span style="color:green">faq</span><span style="color:#880">=</span><span style="color:blue">7</span>)
								</li>
							</ol>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="grid-parent grid-30 right-col">
<?php include "views/sidebar.php"; ?>
		</div>
	</div>

<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/hu_HU/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
var ans = '<?php if (isset($data)) echo $data; ?>';</script>
<?php include "footer.php"; ?>
