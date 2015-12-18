<?php
	/*//////////////////////////
		
		'' => array(
			'ponies' => array(
				'',
			),
			'',
		),
	
	//////////////////////////*/

	$updates = array(
		'3.0' => array(
			'ponies' => array(
				'Paamayim Nekudotayim'
			),
			'Nagy részben újrairt kódbázis',
			'oAuth bejelentkezés',
			'Beállítások mentése a szerveren',
		),
		'2.4' => array(
			'ponies' => array(
				'Apple Cobbler',
				'Crescent Moon',
			),
		),
		'2.3' => array(
			'ponies' => array(
				'Lightning Thunder',
				'Princess Twilight',
				'Thunderlane',
				'Coco Pommel',
				'Flutterbat',
				'Maud Pie',
				'Apple Fritter',
				'Cheese Sandwich',
				'Snips',
				'Snails',
				'Mayor Mare',
				'Hair Zippy',
				'Blossomforth',
				'Nightmare Moon',
			),
			'rename' => array(
				'shortName' => 't',
				'from' => 'T.G.A.P. Trixie',
				'to' => 'Trixie',
			),
			'colorreplace' => array(
				'Flitter',
				'Trixie',
			),
			'imgreplace' => array('Coke'),
			'Az emlékeztetők funkció ideiglenesen kikapcsolva',
			'hunpony integráció',
			'Angol és Német fordítás eltávolítva',
			'A "Más minden betöltéskor" lehetőség eltávolítva a karakterválasztó menüből',
			'Új rendezési mód a karakterválasztó menüben: Újak előre',
			'Megújult színezési mód: Színátmenetes hátterek támogatása',
			'Megújult karakterválasztó menü; színátmenetes hátterek, árnyékolás',
		),
		'2.2' => array(
			'ponies' => array(
				'Shade',
			),
			'Változási napló fordítás javítva',
			'Vektorok készítőinek listája átdolgozva',
			'Háttérkép beöltés jelző',
		),
		'2.1' => array(
			'ponies' => array(
				'Glaze',
				'Living Tombstone',
				'Lauren Faust'
			),
			'Újonnan hozzáadott pónikat csillag jelöli a karakterválasztóban',
		),
		'2.0' => array(
			'A project új nevet kapott: "MLP Now"',
		),
		'1.48' => array(
			'ponies' => array(
				'Lightning Dust',
			),
			'Közvetlen hivatkozás gomb hozzáadva a karakterválasztóhoz',
		),
		'1.47' => array(
			'ponies' => array(
				'Coke',
			),
			'Mostantól a karakterválasztó mutatja a kiválasztott pónit',
			'Mostantól megszűntetheted a karakterkiválasztást',
		),
		'1.46' => array(
			'Optimalizált rendezési algoritmus',
			'Sunset Shimmer képe kicserélve',
			'Rövidebb verziószámok',
		),
		'1.45' => array(
			'ponies' => array(
				'Dinky Hooves',
				'Wild Fire',
			),
		),
		'1.44' => array(
			'hu' => 'Dinamikusan változó háttérszín',
		),
		'1.43' => array(
			'ponies' => array(
				'Cross Fade',
			),
		),
		'1.42' => array(
			'A változási naplóban található póni nevek kattinhatóak',
			'Felújított ikonok',
			'CSS javítások',
			'Karakterválasztó rendezése ábécé sorrendd mellett színek szerint is',
		),
		'1.41' => array(
			'ponies' => array(
				'Featherweight',
				'Hoity Toity',
				'Pipsqueak',
				'Rumble',
				'Soarin',
				'Sunset Shimmer',
			),
		),
		'1.40' => array(
			'ponies' => array(
				'Firefly',
				'Pina Colada',
			),
		),
		'1.39' => array(
			'ponies' => array(
				'Gilda',
				'Noi',
			),
			'replace' => array(
				'Fluttershy'
			),
		),
		'1.38' => array(
			'ponies' => array(
				'Peachy Pie',
				'Cloud Kicker',
			),
			'replace' => array(
				'Raindrops',
				'Cloudchaser',
				'Flitter',
				'Twinkle',
			),
			'Német fordítás (újra) hozzáadva, jelenlegi fordító: M99moron',
		),
		'1.37' => array(
			'ponies' => array(
				'Prince Blueblood',
			),
		),
		'1.36' => array(
			'ponies' => array(
				'Amethyst Star',
				'Berry Punch',
				'Braeburn',
				'Carrot Top',
				'Cherilee',
				'Cloudchaser',
				'Daring Do',
				'Fiddlesticks',
				'Flitter',
				'Inkie Pie',
				'Lightning Bolt',
				'Medley Frown',
				'Nurse Redheart',
				'Photo Finish',
				'Raindrops',
				'Roseluck',
				'Seafoam',
				'Shining Armor',
				'Star Sparkle',
				'Twinkle',
			),
			'Emlékeztetők funkció kijavítva',
		),
		'1.35' => array(
			'ponies' => array(
				'Babs Seed',
			),
		),
		'1.34' => array(
			'A Véletlenszerű karakter választó átmozgatva az oldalsávba',
			'Új "Véletlen karakter minden betöltéskor" választási lehetőség hozzáadva',
		),
		'1.33' => array(
			'Applebloom javítva',
			'Változási napló normális kinézetet kapott',
			'Kissebb képernyőkön az oldal most már ténylegesen megváltozik',
		),
		'1.32' => array(
			'Az oldal mostantól a központi URL-t fogja használni. A régi linkek ide irányítanak majd át.',
		),
		'1.31' => array(
			'Internet Idő időformátum hozzáadva',
			'Új mód az időformátum beállítására',
			'Új rejt/mutat lehetőség',
			'Az oldal nem tárol sütiket ha nem módosítasz semmilyen beállítást',
		),
		'1.30' => array(
			'Pár hiba a Firefox-szal javítva',
		),
		'1.29' => array(
			'GY.I.K. linkek frissítve',
		),
		'1.28' => array(
			'Az oldal teljesen reszponzív (jól működik kis képernyőn is)',
			'Az oldalsáv átdolgozva, jobb érintőképernyő támogatás',
		),
		'1.27' => array(
			'A kinyíló karakterválasztó új kinézetet kapott',
		),
		'1.26' => array(
			'A kinyíló karakterválasztóban a karakterek mostantól ábécé sorrendben vannak rendezve',
		),
		'1.25' => array(
			'Megújult az emlékeztető funkció',
			'Német változat eltávolítva',
			'Kinyitható oldalsó karakterválasztó',
			'Szövegek mérete kijavítva',
		),
		'1.24' => array(
			'3 új kérdés hozzáadva a GYIK-hez',
			'Képnézegető plug-in eltávolítva (Karakterválasztásnál)',
			'A Karakterválasztás és a Változások mostmár az oldalon belül nyílnak meg',
		),
		'1.23' => array(
			'GY.I.K. hozzáadva',
			'Linkek elrendezése módosítva',
		),
		'1.22' => array(
			'Képnézegető plug-in lecserélve',
		),
		'1.21' => array(
			'ponies' => array(
				'Applebloom',
				'Scootaloo',
				'Sweetie Belle',
			),
		),
		'1.21' => array(
			'Karakterválasztás külön oldalra téve',
		),
		'1.20' => array(
			'Véletlenszerű Karakter funkció hozzáadva',
		),
		'1.19' => array(
			'Az emlékeztetőben mostmár nem használható HTML/JavaScript kód',
			'Új nyelvválasztó menü',
			'Linkek színe a kiválasztott karakteréhez igazodik',
		),
		'1.18' => array(
			'ponies' => array(
				'Queen Chrysalis',
			),
		),
		'1.17' => array(
			'Emlékeztető funkció felújítva',
			'3. évad visszaszámláló eltávoltitva',
		),
		'1.16' => array(
			'3. évad visszaszámláló',
			'Rejt/mutat funkció felújítva',
		),
		'1.15' => array(
			'Alsó rész elrejthető',
			'Favicon javítva',
		),
		'1.14' => array(
			'Szöveg igazítás javítva',
		),
		'1.13' => array(
			'ponies' => array(
				'Spike',
			),
			'Kisebb javítás a háttér megjelenésében',
		),
		'1.12' => array(
			'Emlékeztetők hozzáadva',
		),
		'1.11' => array(
			'Hiányzó üdvözlőképernyő javítva',
			'URL karakterválasztás frissítve',
			'Német nyelv hozzáadva',
		),
		'1.10' => array(
			'Colgate Minuette javítva',
		),
		'1.9' => array(
			'ponies' => array(
				'Bon Bon',
				'Doctor Whooves',
				'Lyra Heartstrings',
				'Octavia',
				'Pinkamena',
				'Princess Cadence',
				'Princess Celestia',
				'Spitfire',
			),
			'10 régi szereplő háttere megváltozott',
			'Nevek elrendezése ABC sorrendben',
		),
		'1.8' => array(
			'Teljesen újraírt kód',
			'Működő időformátum választó gomb',
		),
		'1.7' => array(
			'Választható időforma magyar és angol oldalon is',
		),
		'1.6' => array(
			'Angol változat a délelőtt/délután időformát használja',
		),
		'1.5' => array(
			'Évek mutatása hozzáadva',
		),
		'1.4' => array(
			'A címsávban és az oldalon lévő óra egyszerre változik (OCD)',
		),
		'1.3' => array(
			'Angol változat újra működik',
		),
		'1.2' => array(
			'Idő kiírás kód újraírva',
			'Idő megjelenik a címsávban',
		),
		'1.1' => array(
			'Angol változaton dátumkiírás tényleg javítva',
		),
		'1.0' => array(
			'Az oldal elhagyta a béta jelzést, mivel minden nagyobb hiba ki lett javítva',
			'Angol változaton dátumkiírás javítva',
		),
		'0.9' => array(
			'URL-beli karakterválasztás megint javítva',
			'Engedély megszerezve ~arman692-tól a Vinyl háttérhez, a neve feltűntetése ellenében',
			'Újraformázott változási napló',
		),
		'0.8' => array(
			'Kisebb kódbeli javítások',
		),
		'0.7' => array(
			'Lenyíló menü javítva',
			'Formázásbeli módosítások',
		),
		'0.6' => array(
			'Lenyíló menü a fordításoknak',
		),
		'0.5' => array(
			'Kód javítva',
			'URL-beli karakterválasztás javítva',
			'Mostmár 20%-al királyabb',
		),
		'0.4' => array(
			'Angol fordítás',
		),
		'0.3' => array(
			'ponies' => array(
				'Vinyl Scratch',
			),
			'Link a képhez hozzáadva',
		),
		'0.2' => array(
			'ponies' => array(
				'Derpy Hooves',
				'Princess Luna',
				'Trixie',
			),
			'Kód javítások',
		),
		'0.1' => array(
			'Első Publikus béta "MLP Today" néven',
			'ponies' => array(
				'Applejack',
				'Fluttershy',
				'Pinkie Pie',
				'Rainbow Dash',
				'Rarity',
				'Twilight Sparkle',
			),
			'Működő kód',
		),
	);
