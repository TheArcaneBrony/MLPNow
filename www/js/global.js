/* global $w,$d,$head,$body,$header,$sidebar,$sbToggle,$main,$footer,console,prompt,HandleNav,getTimeDiff,one,createTimeStr */
(function($){
	'use strict';
	// document.createElement shortcut
	var mk = function(){ return document.createElement.apply(document,arguments) };
	window.mk = function(){return mk.apply(window,arguments)};

	// $(document.createElement) shortcut
	$.mk = function(){ return $(document.createElement.apply(document,arguments)) };

	// Return a random number between low and high
	$.rnd = function(low,high){return Math.floor(Math.random()*(high-low+1))+low};

	// Convert relative URL to absolute
	$.urlToAbsolute = function(url){
		var a = mk('a');
		a.href = url;
		return a.href;
	};

	// Globalize common elements
	window.$w = $(window);
	window.$d = $(document);
	window.CommonElements = function(){
		window.$body = $('body');
		window.$head = $('head');
		window.$main = $('#main');
		window.$slideout = $('#slideout');
		window.$footer = $('footer');
		window.$bgContainer = $('#bgContainer');
		window.$slideoutInner = $('#slideoutInner');
		var $dateTime = $('.dateTime');
		window.$dateDisplay = $dateTime.children('.dateDisplay');
		window.$timeDisplay = $dateTime.children('.timeDisplay');
	};
	window.CommonElements();

	// Common key codes for easy reference
	window.Key = {
		Enter: 13,
		Space: 32,
		LeftArrow: 37,
		RightArrow: 39,
		Tab: 9,
	};
	$.isKey = function(Key, e){
		return e.keyCode === Key;
	};

	// Make first character in string uppercase
	$.capitalize = function(str){
		return str.length === 1 ? str.toUpperCase() : str[0].toUpperCase()+str.substring(1);
	};

	// Array.includes (ES7) polyfill
	if (typeof Array.prototype.includes !== 'function')
		Array.prototype.includes = function(elem){ return this.indexOf(elem) !== -1 };

	$.pad = function(str, char, len, dir){
		if (typeof str !== 'string')
			str = ''+str;

		if (typeof char !== 'string')
			char = '0';
		if (typeof len !== 'number' || !isFinite(len) || isNaN(len))
			len = 2;
		if (typeof dir !== 'boolean')
			dir = true;

		while (str.length < len)
			str = dir ? char+str : str+char;

		return str;
	};

	// Create AJAX response handling function
	$w.on('ajaxerror',function(){
		$.Dialog.fail(false,'There was an error while processing your request. You may find additional details in the browser\'s console.');
	});
	$.mkAjaxHandler = function(f){
		return function(data){
			if (typeof data !== 'object'){
				//noinspection SSBasedInspection
				console.log(data);
				$w.trigger('ajaxerror');
				return;
			}

			if (typeof f === 'function') f.call(data);
		};
	};

	// Checks if a variable is a function and if yes, runs it
	// If no, returns default value (undefined or value of def)
	$.callCallback = function(func, params, def){
		if (typeof params !== 'object' || !$.isArray(params)){
			def = params;
			params = [];
		}
		if (typeof func !== 'function')
			return def;

		return func.apply(window, params);
	};

	// Convert .serializeArray() result to object
	$.fn.mkData = function(obj){
		var tempdata = $(this).serializeArray(), data = {};
		$.each(tempdata,function(i,el){
			data[el.name] = el.value;
		});
		if (typeof obj === 'object')
			$.extend(data, obj);
		return data;
	};

	// Get CSRF token from cookies
	$.getCSRFToken = function(){
		var n = document.cookie.match(/CSRF_TOKEN=([a-z\d]+)/i);
		if (n && n.length)
			return n[1];
		else throw new Error('Missing CSRF_TOKEN');
	};
	$.ajaxPrefilter(function(event, origEvent){
		if ((origEvent.type||event.type).toUpperCase() !== 'POST')
			return;

		var t = $.getCSRFToken();
		if (typeof event.data === "undefined")
			event.data = "";
		if (typeof event.data === "string"){
			var r = event.data.length > 0 ? event.data.split("&") : [];
			r.push("CSRF_TOKEN=" + t);
			event.data = r.join("&");
		}
		else event.data.CSRF_TOKEN = t;
	});
	$.ajaxSetup({
		statusCode: {
			401: function(){
				$.Dialog.fail(undefined, "Cross-site Request Forgery attack detected. Please notify the site administartors.");
			},
			404: function(){
				$.Dialog.fail(undefined, "Error 404: The requested endpoint could not be found");
			},
			500: function(){
				$.Dialog.fail(false, 'The request failed due to an internal server error. If this persists, please open an issue on GitHub using the link in the footer!');
			}
		}
	});

	window.URL = function(url){
		var a = document.createElement('a'),
			me = this;
		a.href = url;
		$.each(['hash','host','hostname','href','origin','pathname','port','protocol','search'],function(_,el){
			me[el] = a[el];
		});
		me.pathString = me.pathname+me.search+me.hash;
	};
})(jQuery);
