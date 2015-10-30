/*
	Date class extention
	v1.1.0, 2010-06-03
	Copyright: Paul Philippov, paul@ppds.ws
	Homepage: http://themactep.com/beats
	License: BSD
*/
Date.prototype.toInternetTime = function (n) {
	'use strict';
	var BIS = 86.4;
	var s = this.getUTCSeconds();
	var m = this.getUTCMinutes();
	var h = this.getUTCHours();
	h = (h === 23) ? 0 : h + 1;
	var BMT = (h * 60 + m) * 60 + s;
	var bts = Math.abs(BMT / BIS).toFixed(parseInt(n));
	var length = (n > 0) ? 1 + n : 0;
	return '@'.concat('000'.concat(bts).slice(bts.length - length));
};
