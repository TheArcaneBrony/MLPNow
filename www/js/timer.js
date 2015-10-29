/*
    Contains code from:
	===================
	
	Date class extention
    v1.1.0, 2010-06-03
    Copyright: Paul Philippov, paul@ppds.ws
    Homepage: http://themactep.com/beats
    License: BSD
*/
Date.prototype.toInternetTime = function (n) {
    var BIS = 86.4;
    var s = this.getUTCSeconds();
    var m = this.getUTCMinutes();
    var h = this.getUTCHours();
    h = (h == 23) ? 0 : h + 1;
    var BMT = (h * 60 + m) * 60 + s;
    var bts = Math.abs(BMT / BIS).toFixed(parseInt(n));
    var length = (n > 0) ? 1 + n : 0;
    return '@'.concat('000'.concat(bts).slice(bts.length - length))
};

function startTime() {
    var format = MLPNow.pref.timeformat;
    var d = new Date();
    var h = d.getHours();
    var m = d.getMinutes();
    var s = d.getSeconds();
    if (format == '12') {
        var ampm = " " + MLPNow.locale.pm;
        if (h < 12) {
            ampm = " " + MLPNow.locale.am
        }
        if (h > 12) {
            h -= 12
        }
        m = checkTime(m);
        s = checkTime(s);
        h = checkTime(h);
        var time = h + ":" + m + ":" + s + ampm;
    }
	else if (format == 'at') {
        var time = d.toInternetTime(2)
    }
	else {
        m = checkTime(m);
        s = checkTime(s);
        h = checkTime(h);
        var time = h + ":" + m + ":" + s;
    }
    $('#time').html(time);
    $('title').text(time + " - MLP Now" + ((typeof preview !== 'undefined' && preview === true) ? ' PREVIEW' : ''))
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i
    }
    return i
}