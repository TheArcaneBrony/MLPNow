(function(){



var wlhref = window.location.href,total,/*timer,*/fancybox_href;

window.MLPNow.fancybox = {
	show: function(){
		clearInterval(MLPNow.timer.interval);
	},
	hide: function(){
		$('link[rel$="icon"]').attr('href',RELPATH+'favicon.ico');
		MLPNow.timer.func();
		MLPNow.timer.interval = setInterval(MLPNow.timer.func,500);
	},
};

function doDefault(){
	$('#pony').attr('src',RELPATH+'pony/default');
	$('#splat').attr('src',RELPATH+'splat/default');
	$('#bgContainer .loading').css('background-image','url("'+RELPATH+'loader/default")');
	$('#bgContainer').addClass('loading');
	$('#splat').off('load').on('load',function(){
		$(this).fadeIn(500,function(){
			$('#bgContainer').removeClass('loading');
		});
	});
	
	$('.dyn').addClass('datgrey');
	$('#slideout').css('border-right','3px solid #777');
}

function urlcheck(){
	if (loggedin == true){
		if (MLPNow.Pony.shortNames.indexOf(MLPNow.GET.randomname) != -1) changeBGImage.call({handler:false},'random',MLPNow.GET.randomname);
		else if (MLPNow.pref.name == 'random') changeBGImage.call({handler:false},'random');
		else if (MLPNow.Pony.shortNames.indexOf(MLPNow.GET.name) != -1) changeBGImage.call({handler:false},MLPNow.GET.name);
		else if (MLPNow.Pony.shortNames.indexOf(MLPNow.pref.name) != -1) changeBGImage.call({handler:false},MLPNow.pref.name);
		else doDefault();
	}
	else if (!preview) doDefault();
}

$(document).ready(function(e) {
	if (MLPNow.GET.faq){$.fancybox.open(faqfancybox)}
	
	urlcheck();
	
	if (loggedin == true){
		bottom(MLPNow.pref.bottom);
		sort.call({handler:false},MLPNow.pref.sort);
		ampm(MLPNow.GET.timeformat);
		
		if (!MLPNow.pref.pinNotify && (!!window.external) && ("msIsSiteMode" in window.external)) $('#pinNotify').slideDown('slow',function(){
			updatePref('pinNotify',1);
		});
	}
});

window.sidebarToggle = function(){
	$(document.body).toggleClass('opened');
};

RegExp.prototype.escape = function(s){ return s.replace(/[-\&\=\:\/\\^$*+?.()|[\]{}]/g, '\\$&') };

})();
