$(function(){
	if (!$.fn.powerTip) return;
	
	$('.timeformat.12').data('powertip',
		'<p class="name">12-órás időformátum</p>' +
		'<p class="description">Főleg Angliában és Amerikában használatos. A napot 2 nagy egységre bontja: délelőttre és délutánra. Éjféltől 11:59-ig délelőtt, 12:00-tól 23:59-ig délután. A kijelzet tidő mindig éjféltől vagy déltől számítva jelenik meg, d.e. illetve d.u. utótaggal, a helyzetnek megfelelően.</p>'
	);
	$('.timeformat.24').data('powertip',
		'<p class="name">24-órás időformátum</p>' +
		'<p class="description">A világ nagy részén használatos időformátum. Az időt éjféltől (0:00) számoljuk. 23:59 után a következő percben az idő visszaáll a kezdeti (éjféli) állapotra.</p>'
	);
	$('.timeformat.at').data('powertip',
		'<p class="name">.beat Internetidő</p>' +
		'<p class="description">A napot egy sajátos ".beat" nevű időegységgel 1000 részre osztja. Az idő értéke a világ bármely pontján megegyezik. Éjfélkor @000 .beat-ről indul, és a nap végén @999 .beat után visszaáll az éjféli állapotba.</p>'
	);
	$('.timeformat').each(function(){
		$(this).powerTip({
			popupId: 'timeformat',
			followMouse: false,
			placement: 's',
			offset: 6,
		});
	});
});