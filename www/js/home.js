/* global $body,$bgContainer,$slideout,pusher,MLPNow,$slideoutInner,Konami,moment,$timeDisplay,$dateDisplay */
$(function(){
	'use strict';

	// Sign in button handler
	var consent = false;
	try {
		consent = 1 === parseInt(localStorage.getItem('cookie_consent'), 10);
	} catch(e){}
	$('#login').off('click').on('click',function(){
		var $this = $(this),
			opener = function(sure){
				if (!sure) return;

				try {
					localStorage.setItem('cookie_consent', 1);
				} catch(e){}

				$this.attr('disabled', true);
				var $ProviderDiv =
					$.mk('div')
						.attr('id','provider-selector')
						.attr('class','align-center')
						.append(
							$.mk('p').text('Choose a service to sign in with'),
							$.mk('div').append(
								$.mk('input').attr({'class':'btn deviantart',type:'button'}).val('DeviantArt')
							)
						);
				$.Dialog.info('Sign-in process', $ProviderDiv, function(){
					$('#provider-selector').on('click', 'input[class]', function(){
						$.Dialog.wait(false, 'Redirecing you to '+this.innerHTML);
						window.location.href = '/oauth/start/'+this.className.replace(/^btn\s/,'');
					});
				});
			};

		if (!consent) $.Dialog.confirm('EU Cookie Policy Notice','In compliance with the <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm">EU Cookie Policy</a> we must inform you that our website will store cookies on your device to remember your logged in status between browser sessions.<br><br>If you would like to avoid these completly harmless pieces of information required to use this website, click "Decline" and continue browsing as a guest.<br><br>This warning will not appear again if you accept our use of persistent cookies.',['Accept','Decline'],opener);
		else opener(true);
	});

	// Start timer

	MLPNow.timer.func = function(){
		var now = moment();
		$dateDisplay.html(now.format('LL'));

		var time = now.format('LTS');
		$timeDisplay.html(time);
		$('title').text(time + " - MLP Now");
	};
	MLPNow.timer.func();
	MLPNow.timer.interval = setInterval(MLPNow.timer.func,500);

	var $toggle = $('#toggle').on('click', Bottom),
		$toggleText = $toggle.children('.text'),
		$toggleIcon = $toggle.children('.typcn');
	$('#slideoutTitle').find('.order-by').children().on('click',function(){ Sort($(this).attr('id'),true) });
	$('#version').on('click',function(){ });
	// TODO
	//$('#vectors').on('click',function(){ });
	//$('#timeformat').on('click',function(){ });
	$('#charsel').on('click',function(){
		$body.toggleClass('opened');
	});

	// Sign out button handler
	$('#signout').off('click').on('click',function(){
		$.Dialog.confirm('Sign out','Are you sure you want to sign out?',function(sure){
			if (!sure) return;

			$.Dialog.wait(false,'Signing out');

			$.post('/signout',$.mkAjaxHandler(function(){
				if (!this.status) return $.Dialog.fail(false, this.message);

				$.Dialog.success(false, 'You have been signed out successfully');
				$.Dialog.wait(false, 'Reloading');
				setTimeout(function(){
					window.location.reload();
				},1000);
			}));
		});
	});

	var $pony = $.mk('img').attr({id:'pony','class':'background'}),
		$splat = $.mk('img').attr({id:'splat','class':'background'});
	$bgContainer.append($splat, $pony);

	function doDefault(){
		$pony.attr('src','/pony/default');
		$bgContainer.addClass('loading').find('.loading').css('background-image','url("/loader/default")');
		$splat.attr('src','/splat/default').off('load').on('load',function(){
			$splat.fadeIn(500,function(){
				$bgContainer.removeClass('loading');
			});
		});
	}
	doDefault();

	function Sort(mode){
		mode = mode.toLowerCase();
		if ($slideout.attr('class') === 'sort-'+mode)
			return false;

		var $elements = $slideoutInner.children('.tile:not([id])'),
			inserter = function(){
				$(this).insertAfter($slideoutInner.children('.tile:visible').last());
			};
		switch (mode){
			case "colour":
				$elements.sort(function(a,b){
					var colorA = pusher.color($(a).data('color')),
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

		$slideout.attr('class','sort-'+mode);

		//if (this.handler !== false) updatePref('sort',mode);
	}

	var tempName;
	function changeBGImage(whichImage,reload){
		var random = false,
			scope = this;

		if (['boolean','undefined'].indexOf(typeof reload) === -1){
			random = true;
			whichImage = reload;
		}

		if (typeof whichImage === "string" ? MLPNow.shortnames.indexOf(whichImage) === -1 : typeof MLPNow.Pony[whichImage] === 'undefined')
			return false;

		whichImage = typeof whichImage === "string"
			? MLPNow.Pony[MLPNow.shortnames.indexOf(whichImage)]
			: MLPNow.Pony[whichImage];

		if (tempName === whichImage.shortname)
			return;

		//if (scope.handler !== false)
		//	updatePref.call({callback:scope.callback},'name',(random ? 'random' : whichImage.shortname));

		$bgContainer.addClass('loading').find('.loading').css('background-image','url("/loader/'+whichImage.color.substring(1)+'")');
		var imgLoaded = 0,
			$bgEls = $bgContainer.children('.background');
		$bgEls.fadeOut(500,function(){
			var $this = $(this),
				url;
			if ($this.attr('id') === 'pony')
				url = '/pony/'+whichImage.shortname;
			else
				url = '/splat/'+whichImage.textcolor.substring(1)+'/'+whichImage.bgcolor.substring(1);

			$this.attr('src',url).off('load error').on('load error',function(){
				if (imgLoaded > 0){
					$('#pony,#splat').fadeIn(1000,function(){
						$('#bgContainer').removeClass('loading');
					});
					return $(this).off('load error');
				}
				imgLoaded++;
			});
		});

		$('.dyn').removeClass(tempName).addClass(whichImage.shortname);

		$slideoutInner.find('.heading').css('color',whichImage.color);
		$slideoutInner.find('.tile.force-hover:not(".tile-'+(random?'random':whichImage.shortname)+'")').removeClass('force-hover');
		$slideoutInner.find('.tile:not(".force-hover").tile-'+(random?'random':whichImage.shortname)).addClass('force-hover');

		tempName = whichImage.shortname;
	}

	function AmPm(x){
		if (['at','12','24'].indexOf(x) !== -1){
			if (this.handler === false)
				MLPNow.pref.timeformat = x;
			//else updatePref('timeformat',x);
		}
	}

	function Bottom(x) {
		var current = $toggleText.text();

		var hide = (!isNaN(x) && !x) || current === MLPNow.locale.hide;
		$toggleText.html(hide ? MLPNow.locale.show : MLPNow.locale.hide);
		$('.tbh').slideToggle('fast');
		$toggleIcon.toggleClass('typcn-minus typcn-plus');
	}

	var konami = new Konami(function(){
		MLPNow.shortnames.push('oc');
		MLPNow.Pony.push({
			textcolor: "#AF70CC",
			bgcolor: "#D19FE3",
			color: "#C087D7",
			shortname: "oc",
		});
		changeBGImage.call({handler: false},'oc');
		clearTimeout(window.prfchk);
		delete window.prfchk;
		var style = document.createElement("style");
		style.innerHTML = '.oc {color:#C087D7}	/* The Ultimate OC */';
		$(style).insertAfter('head style:last');
	});

	var rndCharNumber;
	$('#randomTile').on('click',function(){
		changeBGImage(rndCharNumber);
	}).hover(function(){
		rndCharNumber = $.rnd(0,MLPNow.Pony.length-1);
		var chr = MLPNow.Pony[rndCharNumber],
			$this = $(this);
		$this.css('background','linear-gradient(to bottom, '+chr.bgcolor+' 0%,'+chr.textcolor+' 100%)');
		$this.children('span').attr('class','pony-icon '+chr.shortname);
		var lns = chr.longname.split(' ');
		if (typeof lns[1] === "undefined") lns[1] = '&nbsp;';
		$this.children('h4').html(lns.join('<br>'));
		$this.children('.permalink').attr({
			title: $this.children('.permalink').data('title')+chr.shortname,
			href: './?name='+chr.shortname,
		});
	},function(){
		var $this = $(this);
		$this.css('background','linear-gradient(to bottom, #777 0%, #777 100%)');
		$this.children('h4').html(MLPNow.locale.randomTile);
		$this.children('span').attr('class','pony-icon random');
		$this.children('.permalink').removeAttr('title href');
	});
	
	var $tiles = $slideoutInner.children('.tile:not([id])');
	$tiles.on('click',function(){
		var $this = $(this),
			shortname = $this.data('shortname');

		if ($(this).hasClass('force-hover') || tempName === shortname){
			$bgContainer.addClass('loading').find('.loading').css('background-image','url("/loader/default")');

			var loaded = 0,
				$bgEls = $bgContainer.children('.background');
			$bgEls.animate({opacity:0},500,function(){
				var url = '/'+$(this).attr('id')+'/default';
				$(this).attr('src',url).off('load error').on('load error',function(){
					loaded++;
					if (loaded === 2) $bgEls.animate({opacity:1},500,function(){
						$('#bgContainer').removeClass('loading');
						$bgEls.off('load error');
					});
				});
			});

			$('.dyn').removeClass(tempName);
			$('#slideoutTitle .heading').css('color','#777');
			$tiles.removeClass('force-hover');
			tempName = undefined;
			//updatePref('name','');
		}
		else changeBGImage.call({handler:true},shortname);
	});
});
