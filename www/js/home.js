/* global $body,$bgContainer,$slideout,pusher,MLPNow,$slideoutInner,Konami,moment,$timeDisplay,$dateDisplay */
$(function(){
	'use strict';

	// Sign in button handler
	var consent = false;
	try {
		consent = 1 === parseInt(localStorage.getItem('cookie_consent'), 10);
	} catch(e){}
	$('#login').off('click').on('click',function(){
		var opener = function(sure){
				if (!sure) return;

				try {
					localStorage.setItem('cookie_consent', 1);
				} catch(e){}

				var $ProviderDiv =
					$.mk('div')
						.attr('id','provider-selector')
						.attr('class','align-center')
						.append(
							$.mk('p').text('Choose a service to sign in with'),
							$.mk('div').attr('class','providers').append(
								$.mk('input').attr({'class':'btn deviantart',type:'button'}).val('DeviantArt'),
								$.mk('input').attr({'class':'btn google',type:'button'}).val('Google')
							)
						);
				$.Dialog.info('Sign-in process', $ProviderDiv, function(){
					$('#provider-selector').on('click', 'input[class]', function(){
						$.Dialog.wait(false, 'Redirecing you to '+this.value);
						window.location.href = '/oauth/start/'+this.className.replace(/^btn\s/,'');
					});
				});
			};

		if (!consent) $.Dialog.confirm('EU Cookie Policy Notice','In compliance with the <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm">EU Cookie Policy</a> we must inform you that our website will store cookies on your device to remember your logged in status between browser sessions.<br><br>If you would like to avoid these completly harmless pieces of information required to use this website, click "Decline" and continue browsing as a guest.<br><br>This warning will not appear again if you accept our use of persistent cookies.',['Accept','Decline'],opener);
		else opener(true);
	});
	$('#link').on('click',function(){
		var $LinkForm =
			$.mk('form')
				.attr('id','link-form')
				.attr('class','align-center')
				.append(
					$.mk('p').text("Linking your different accounts will allow you to use the same settings across multiple external logins"),
					$.mk('p').text("Choose an account which you'd like to link to your current login"),
					$.mk('label').attr('class','providers').append(
						$.mk('select').attr({
							name:'provider',
							required:true,
						}).append(
							$.mk('option').attr({value:'',selected:true}).text('(click here to select)').hide(),
							$.mk('option').attr('value','deviantart').text('DeviantArt'),
							$.mk('option').attr('value','google').text('Google')
						)
					),
					$.mk('div').attr('class','what-to-keep').append(
						$.mk('p').text('Use the settings of'),
						$.mk('label').append(
							$.mk('input').attr({
								type:'radio',
								name:'migrate',
								value: '',
								checked: true,
								required:true,
							}),
							$.mk('span').text('this account')
						),
						"&nbsp;",
						$.mk('label').append(
							$.mk('input').attr({
								type:'radio',
								name:'migrate',
								value: '1',
								required:true,
							}),
							$.mk('span').text('target account (if exists)')
						)
					)
				);
		$.Dialog.request('Link acounts',$LinkForm,'link-form','Link account',function($form){
			$form.on('submit',function(e){
				e.preventDefault();

				var data = $form.mkData();

				$.Dialog.wait(false,'Redirecting you to '+$form.find('select').children(':selected').text());
				window.location.href = '/link/start/'+(data.migrate?'migrate/':'')+data.provider;
			});
		});
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
	$('#slideoutTitle').find('.order-by').children().on('click',function(){ Sort($(this).attr('id')) });
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

	function Sort(mode, noupdate){
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

		if (!noupdate)
			updatePref('sort',mode);
	}
	Sort(MLPNow.pref.sort, true);

	var tempName;
	function changeBGImage(ponyName,noupdate){
		var random = false;

		if (typeof ponyName === "string" ? MLPNow.shortnames.indexOf(ponyName) === -1 : typeof MLPNow.Pony[ponyName] === 'undefined')
			return false;

		ponyName = typeof ponyName === "string"
			? MLPNow.Pony[MLPNow.shortnames.indexOf(ponyName)]
			: MLPNow.Pony[ponyName];

		if (tempName === ponyName.shortname)
			return;

		if (!noupdate)
			updatePref('pony', (random ? 'random' : ponyName.shortname));

		$bgContainer.addClass('loading').find('.loading').css('background-image','url("/loader/'+ponyName.color.substring(1)+'")');
		var imgLoaded = 0,
			$bgEls = $bgContainer.children('.background');
		$bgEls.fadeOut(500,function(){
			var $this = $(this),
				url;
			if ($this.attr('id') === 'pony')
				url = '/pony/'+ponyName.shortname;
			else
				url = '/splat/'+ponyName.textcolor.substring(1)+'/'+ponyName.bgcolor.substring(1);

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

		$('.dyn').removeClass(tempName).addClass(ponyName.shortname);

		$slideoutInner.find('.heading').css('color',ponyName.color);
		$slideoutInner.find('.tile.force-hover:not(".tile-'+(random?'random':ponyName.shortname)+'")').removeClass('force-hover');
		$slideoutInner.find('.tile:not(".force-hover").tile-'+(random?'random':ponyName.shortname)).addClass('force-hover');

		tempName = ponyName.shortname;
	}
	changeBGImage(MLPNow.pref.pony, true);

	function AmPm(setTo, noupdate){
		if (['at','12','24'].indexOf(setTo) !== -1){
			if (!noupdate)
				updatePref('timeformat',setTo);
		}
	}
	AmPm(MLPNow.pref.timeformat, true);

	function Bottom(visible, noupdate) {
		var hide = !(typeof visible === 'boolean' ? visible : !MLPNow.pref.bottom);

		$toggleText.html(hide ? MLPNow.locale.show : MLPNow.locale.hide);
		var $tbh = $('.tbh').stop();
		if (noupdate)
			$tbh[hide?'hide':'show']();
		else $tbh[hide?'slideUp':'slideDown']('fast');
		$toggleIcon
			[(hide?'remove':'add')+'Class']('typcn-minus')
			[(hide?'add':'remove')+'Class']('typcn-plus');

		if (!noupdate)
			updatePref('bottom', !hide);
	}
	Bottom(MLPNow.pref.bottom, true);

	var konami = new Konami(function(){
		MLPNow.shortnames.push('oc');
		MLPNow.Pony.push({
			textcolor: "#AF70CC",
			bgcolor: "#D19FE3",
			color: "#C087D7",
			shortname: "oc",
		});
		changeBGImage('oc', true);
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
		/*$this.children('.permalink').attr({
			title: $this.children('.permalink').data('title')+chr.shortname,
			href: './?name='+chr.shortname,
		});*/
	},function(){
		var $this = $(this);
		$this.css('background','linear-gradient(to bottom, #777 0%, #777 100%)');
		$this.children('h4').html(MLPNow.locale.randomTile);
		$this.children('span').attr('class','pony-icon random');
		//$this.children('.permalink').removeAttr('title href');
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
			updatePref('name','');
		}
		else changeBGImage(shortname);
	});

	var $saveInd = $('#gui').children('.saveIndicator');
	function updatePref(name, value, callback){
		if (!MLPNow.signedIn)
			return;
		$saveInd.stop().show();
		var origValue = window.MLPNow.pref[name];
		if (window.MLPNow.pref[name] !== value)
			window.MLPNow.pref[name] = value;
		clearInterval(window.prfchk);
		$.post('/prefupdate', {name:name,value:value}, $.mkAjaxHandler(function(){
			if (!this.status){
				window.MLPNow.pref[name] = origValue;
				return $.Dialog.fail(false, this.message);
			}

			var classStr = 'typcn typcn-'+(this.status?'tick':'times'),
				origHTML = $saveInd.children('.text').html();
			$saveInd.attr('data-status', true).children('.text').addClass(classStr).html(this.message);
			if (typeof callback === 'function')
				callback(name,value);
			setTimeout(function(){
				$saveInd.fadeOut(500,function(){
					$saveInd.removeAttr('data-status');
					$saveInd.children('.text').removeClass(classStr).html(origHTML);
					window.prfchk = setInterval(window.prfchkF,5000);
				});
			},1000);
		}));
	}
});
