var validDate = /^(19|20)\d\d[.](0[1-9]|1[012])[.](0[1-9]|[12][0-9]|3[01])$/;
var validDateStr = validDate.toString().substring(1,validDate.toString().length-1);
var scriptMatch = /<\s*\bscript\b[^>]*>([\s\S]*?)<\s*\/\s*script\s*>/img;
if (readCookie('reminderArray1') === "[]" || readCookie('reminderArray2') === "[]"){ eraseCookie('reminderArray1'); eraseCookie('reminderArray2') }
var reminderArray1 = ((readCookie('reminderArray1') && readCookie('reminderArray2')) ? JSON.parse(readCookie('reminderArray1')) : []);
var reminderArray2 = ((readCookie('reminderArray1') && readCookie('reminderArray2')) ? JSON.parse(readCookie('reminderArray2')) : []);
var reminder = {
	load: function(){
		var datestring = parent.MLPNow.timer.datestring;
		var whereIsIt = reminderArray2.indexOf(datestring);
		var todayConcat = MLPNow.timer.datestring.replace(/\./g,'');
		for (var i=0;i<reminderArray2.length;i++){
			if (reminderArray2[i].replace(/\./g,'') < todayConcat){
				reminderArray1.splice(i,1);
				reminderArray2.splice(i,1);
			}
		}
		
		if (whereIsIt != -1) $('#reminderDisplay').html(reminderArray1[whereIsIt]);
		else $('#reminderDisplay').html('');
		
		$('#reminderContainer').show();
		return $('#reminderDisplay').html();
	},
	check: function(){
		reminderArray1 = ((readCookie('reminderArray1')) ? JSON.parse(readCookie('reminderArray1')) : []);
		
		for (var i = 0;i < reminderArray1.length;i++){
			if (scriptMatch.test(reminderArray1[i])){
				reminderArray1[i].replace(scriptMatch,'');
			}
		}
		reminder.save('textOnly');
	},
	save: function(which){
		if (readCookie('reminderArray1') != JSON.stringify(reminderArray1) || "[]" != JSON.stringify(reminderArray1)){
			createCookie('reminderArray1',JSON.stringify(reminderArray1),longtime);
		}
		if (which != 'textOnly' && readCookie('reminderArray2') != JSON.stringify(reminderArray2) || "[]" != JSON.stringify(reminderArray2)){
			createCookie('reminderArray2',JSON.stringify(reminderArray2),longtime);
		}
	},
}

var remedit = {
	width		: '90%',
	height		: '90%',
	autoSize	: false,
	
	closeClick	: false,
	openEffect	: 'elastic',
	closeEffect	: 'fade',
	closeBtn	: true,
	helpers : {
		overlay : {
			css : {
				'background' : 'rgba(255, 255, 255, 0.6)'
			}
		},
		title : null,
	},
	padding 	: 0,
	href 		: MLPNow.lang+'/rem/',
	type 		: "iframe",
	afterLoad	: function(){ parent.MLPNow.fancybox.show() },
	beforeClose	: function(){ parent.MLPNow.fancybox.hide(); reminder.load('varCreator'); },
}

$(document).ready(function(){
	$('#editReminder').on('click',function(){
		$.fancybox.open(remedit);
	});
});