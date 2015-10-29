function updatePref(name,value){
	var $saveInd = $('#main .saveIndicator'), scope = this;
	if (!loggedin) return;
	$('#main .saveIndicator').show();
	var origValue = window.MLPNow.pref[name];
	if (window.MLPNow.pref[name] !== value) window.MLPNow.pref[name] = value;
	clearInterval(window.prfchk);
	$.ajax({
		type: 'POST',
		url: 'prefupdate',
		data: {name:name,value:value},
		success: function(data){
			if (typeof data === 'string') return console.log(data) === $(window).trigger('ajaxerror');
			var classStr = 'typcn typcn-'+(data.status?'tick':'times'),
				origHTML = $saveInd.children('.text').html();
			$saveInd.attr('data-status',data.status).children('.text').addClass(classStr).html(data.message);
			if (!data.status) window.MLPNow.pref[name] = origValue;
			if (typeof scope.callback === 'function') scope.callback(name,value);
			setTimeout(function(){
				$saveInd.fadeOut(500,function(){
					$saveInd.removeAttr('data-status');
					$saveInd.children('.text').removeClass(classStr).html(origHTML);
					window.prfchk = setInterval(window.prfchkF,5000);
				});
			},1000);
		}
	});
}

function changeBGImage(whichImage,reload){
	var tempName = window.tempName,
		random = false,
		wP = MLPNow.Pony,
		scope = this;
	
	if (['boolean','undefined'].indexOf(typeof reload) == -1){
		random = true;
		whichImage = reload;
		delete reload;
	}
	
	if (typeof whichImage === "string" ? wP.shortNames.indexOf(whichImage) == -1 : typeof wP.list[whichImage] == 'undefined') return false;
	if (typeof whichImage === "string") whichImage = wP.list[wP.shortNames.indexOf(whichImage)];
	else whichImage = wP.list[whichImage];
	
	if (tempName === whichImage.shortName) return;
	
	$('.datgrey').removeClass('datgrey');
	
	if (scope.handler !== false) updatePref.call({callback:scope.callback},'name',(random ? 'random' : whichImage.shortName));
	if (typeof window.prfchk === 'undefined' && typeof window.prfchkF !== 'undefined'){
		window.prfchk = setInterval(window.prfchkF,5000);
	}
	
	$('#bgContainer .loading').css('background-image','url("'+RELPATH+'loader/'+whichImage.color.substring(1)+'")');
	$('#ponyscreen').filter(':visible').fadeOut(800);
	
	var imgLoaded = 0;
	$('#bgContainer:not(.loading)').addClass('loading');
	$('#pony,#splat').fadeOut(500,function(){
		var url;
		if ($(this).attr('id') == 'pony')
			url = RELPATH+'pony/'+whichImage.shortName;
		else
			url = RELPATH+'splat/'+whichImage.textcolor.substring(1)+'/'+whichImage.bgcolor.substring(1);
		
		$(this).attr('src',url).off('load error').on('load error',function(){
			if (imgLoaded > 0){
				$('#pony,#splat').fadeIn(1000,function(){
					$('#bgContainer').removeClass('loading');
				});
				return $(this).off('load error');
			}
			imgLoaded++;
		});
	});
	
	$('.dyn').removeClass(tempName).addClass(whichImage.shortName);
	$('#pinNotify').removeClass(tempName).addClass(whichImage.shortName).css('border-bottom','3px solid '+whichImage.color);

	$('#slideoutInner .heading').css('color',whichImage.color);
	$('#slideout').css('border-right','3px solid '+whichImage.color);
	
	$('#slideoutInner .tile.force-hover:not(".tile-'+(random?'random':whichImage.shortName)+'")').removeClass('force-hover');
	$('#slideoutInner .tile:not(".force-hover").tile-'+(random?'random':whichImage.shortName)).addClass('force-hover');
	
	window.tempName = whichImage.shortName;
}

function ampm(x){
	if (['at','12','24'].indexOf(x) !== -1){
		if (this.handler === false) MLPNow.pref.timeformat = x;
		else updatePref('timeformat',x);
	}
}

function bottom(x) {
	var current = $('#toggle span.text').text();
    
	if (!isNaN(x)){
		if (!x){
			$('#toggle span.text').html(MLPNow.locale.show);
			$('#toggle span.typcn.typcn-minus').removeClass('typcn-minus').addClass('typcn-plus');
			$('.tbh').slideUp();
		}
		else {
			$('#toggle span.text').html(MLPNow.locale.hide);
			$('#toggle span.typcn.typcn-plus').removeClass('typcn-plus').addClass('typcn-minus');
			$('.tbh').slideDown();
		}
	}
	else {
		if (current == MLPNow.locale.hide){
			$('#toggle span.text').html(MLPNow.locale.show);
			$('#toggle span.typcn.typcn-minus').removeClass('typcn-minus').addClass('typcn-plus');
			updatePref('bottom',0);
		}
		else if (current == MLPNow.locale.show) {
			$('#toggle span.text').html(MLPNow.locale.hide);
			$('#toggle span.typcn.typcn-plus').removeClass('typcn-plus').addClass('typcn-minus');
			updatePref('bottom',1);
		}
		$('.tbh').toggle('slow');
	}
}

function sort(mode){
	mode = mode.toLowerCase();
	if ($('#slideout').attr('class') == 'sort-'+mode) return false;
	
	var $elements = $('#slideoutInner .tile:not([id])'),
		inserter = function(i,el){
			$(this).insertAfter($('#slideoutInner .tile:visible').last());
		};
	switch (mode){
		case "colour":
			$elements.sort(function(a,b){
				colorA = pusher.color($(a).data('color'));
				colorB = pusher.color($(b).data('color'));
				return colorA.hue() - colorB.hue();
			});
			$elements.each(inserter);
		break;
		case "abc":
			$elements.sort(function(a,b){
				return $(a).data('longname').localeCompare($(b).data('longname'));
			});
			$elements.each(inserter);
		break;
		case "newabc":
			var $new = $elements.filter('.new').sort(function(a,b){
					return $(a).data('longname').localeCompare($(b).data('longname'));
				}),
				$other = $elements.filter(':not(.new)').sort(function(a,b){
					return $(a).data('longname').localeCompare($(b).data('longname'));
				});
			$new.each(inserter);
			$other.each(inserter);
		break;
		default: return false;
	}

	$('#slideout').attr('class','sort-'+mode);

	if (this.handler !== false) updatePref('sort',mode);
}

$(document).ready(function(){
	var rndCharNumber;
	$('#randomTile').on('click',function(){
		changeBGImage(rndCharNumber);
	}).hover(function(){
		rndCharNumber = $.rnd(0,MLPNow.Pony.shortNames.length-1);
		var chr = MLPNow.Pony.list[rndCharNumber],
			$this = $(this);
		$this.css('background','linear-gradient(to bottom, '+chr.bgcolor+' 0%,'+chr.textcolor+' 100%)');
		$this.children('span').attr('class','pony-icon '+chr.shortName);
		var lns = chr.longName.split(' ');
		if (typeof lns[1] === "undefined") lns[1] = '&nbsp;';
		$this.children('h4').html(lns.join('<br>'));
		$this.children('.permalink').attr({
			title: $this.children('.permalink').data('title')+chr.shortName,
			href: './?name='+chr.shortName,
		});
	},function(){
		var $this = $(this);
		$this.css('background','linear-gradient(to bottom, #777 0%, #777 100%)');
		$this.children('h4').html(MLPNow.locale.randomTile);
		$this.children('span').attr('class','pony-icon random');
		$this.children('.permalink').removeAttr('title href');
	});
		
	$('#slideoutInner .tile:not([id])').on('click',function(){
		var shortName = $(this).data('shortname');
		
		if ($(this).hasClass('force-hover') || window.tempName === shortName){
			$('#bgContainer:not(.loading)').addClass('loading');
			$('#bgContainer .loading').css('background-image','url("'+RELPATH+'loader/default")');
			
			var loaded = 0;
			$('#pony,#splat').animate({opacity:0},500,function(){
				var url = RELPATH+$(this).attr('id')+'/default';
				$(this).attr('src',url).off('load error').on('load error',function(){
					if (loaded > 0) $('#pony,#splat').animate({opacity:1},500,function(){
						$('#bgContainer').removeClass('loading');
					});
					loaded++;
					return $(this).off('load error');
				});
			});
			
			$('.dyn').removeClass(tempName).addClass('datgrey');
			$('#lang_title .ddTitleText').removeClass(tempName).addClass('datgrey');
			$('#pinNotify').removeClass(tempName).addClass('datgrey').css('border-bottom','3px solid #777');
			
			$('.dd .ddChild a.selected, #ponyscreen .heading').css('color','#777');
			
			$('#ponyscreen .tile.force-hover').removeClass('force-hover');
			
			try { if (window.external.msIsSiteMode() && reload !== false) window.location.reload(); return true } catch(e){};
			
			$('meta[name="msapplication-navbutton-color"]').attr('content','#777');
			window.tempName = '';
			updatePref('name','');
		}
		else changeBGImage.call({handler:true},shortName);
	}).find('.permalink').on('click',function(e){
		var $this = $(this);
		
		setTimeout(function(){
			window.location.href = $this.attr('href');
		},1);
		
		return false;
	});
	
	$('#differentTile').on('click',function(){ changeBGImage('random'); return false });
	
	var funcBind = {
		sort: sort,
		bottom: bottom,
		timeformat: ampm,
		name: changeBGImage,
	};
	var prefchecker = function(){
		$.post(RELPATH+'prefcheck','name=*',function(data){
			if (data.status) $.each(data.value,function(i,el){
				if (typeof funcBind[i] === 'function') funcBind[i].call({handler:false},el);
			});
		})
	};
	window.prfchkF = prefchecker;
	window.prfchk = setInterval(prefchecker,5000);
	prefchecker();
	
	var konami = new Konami(function(){
		MLPNow.Pony.shortNames.push('oc');
		MLPNow.Pony.list.push({
			textcolor: "#AF70CC",
			bgcolor: "#D19FE3",
			color: "#C087D7",
			shortName: "oc",
		});
		changeBGImage.call({handler: false},'oc');
		clearTimeout(window.prfchk);
		delete window.prfchk;
		var style = document.createElement("style");
		style.innerHTML = '.oc {color:#C087D7}	/* The Ultimate OC */';
		$(style).insertAfter('head style:last');
	});
});