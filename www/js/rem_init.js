var wlhref = parent.window.location.href.substring(0,parent.window.location.href.lastIndexOf('/')+1);
var tempName = parent.window.tempName;
var currentcolor = parent.MLPNow.Pony.colors[parent.MLPNow.Pony.shortNames.indexOf(tempName)];
var ftoupdclrs = function(){t=setTimeout(updclrs,1); $('.ui-datepicker').css('border',function(){return '1px solid '+currentcolor})};
var defhtml,remText,remTime,remTimeArray,remTextHasCodeChars,i,text;

if (typeof parent.reminderArray1 === "undefined"){parent.reminderArray1 = []}
if (typeof parent.reminderArray2 === "undefined"){parent.reminderArray2 = []}

if (!parent.longtime){ window.location = "../" }

$(document).ready(function(){
	if (typeof browser !== 'undefined' && browser !== '') $('#reminders').addClass(browser);
	$('#modal').modal({
		keyboard: false,
		show	: false
	});
	
	updateList();
	
	$('body').show();
});

function updclrs(){
	if (readCookie('nev')){
		if (parent.MLPNow.Pony.shortNames.indexOf(readCookie('nev')) != -1){
			$('a,#reminders thead td').addClass(readCookie('nev'))
			$('#reminders td,table').css('border','1px solid '+currentcolor);
		}
		else if (readCookie('nev') == 'random'){
			$('a,#reminders thead td').addClass(tempName);
			$('#reminders td,table').css('border','1px solid '+currentcolor);
		}
		else {
			$('a').addClass('datgrey');
			$('#reminders td,table').css('border','1px solid #777');
		}
	}
	else {
		$('a').addClass('datgrey');
		$('#reminders td,#reminders table').css('border','1px solid #777');
	}
}
	
function updateList(){
	$('#reminders tbody tr:not(#remdef)').remove();

	for (i = 0; i < parent.reminderArray1.length;i++){
		$('#reminders tbody').append(
		'<tr id="remno'+i+'">'+
		'	<td class="remtxt">'+
		'		<label>'+
		'			<textarea rows="5" disabled required>'+parent.reminderArray1[i]+'</textarea>'+
		'		</label>'+
		'	</td>'+
		'	<td class="remtim">'+
		'		<label>'+
		'			<input type="text" disabled required value="'+parent.reminderArray2[i]+'" placeholder="'+remLocale.dateFormat[lang]+'" pattern="'+parent.validDateStr+'">'+
		'		</label>'+
		'	</td>'+
		'	<td class="remope">'+
		'		<div class="btn-group">'+
		'			<button class="btn btn-large btn-success action-accept" disabled></button>'+
		'			<button class="btn btn-large btn-danger action-remove"></button>'+
		'			<button class="btn btn-large btn-info action-modify"></button>'+
		'		</div>'+
		'	</td>'+
		'<tr>'
		);
	}
	
	$('textarea,input[type="text"]').attr('autocomplete','off');
	$('#remdef textarea').attr('placeholder',remLocale.addNewText[lang]);
	$('tr input').attr('placeholder',remLocale.dateFormat[lang]).attr('pattern',parent.validDateStr);
	$('#dateInfo').html(parent.MLPNow.timer.datestring);
	$('#remdef td.remope button.btn-success').html(remLocale.add[lang]);
	$('tr:not(#remdef) td.remope button.btn-success').html(remLocale.accept[lang]);
	$('td.remope button.btn-danger').html(remLocale.remove[lang]);
	$('td.remope button.btn-info').html(remLocale.modify[lang]);
	
	bindHandlers();
	updclrs();
	parent.reminder.load();
}

function bindHandlers(){
	$('td.remope .action-remove').off('click').on('click',function(){
		var id = $(this).parent().parent().parent().attr('id');
		var n0 = id.substring(5)*1;
		var html = $(this).html();
		
		if (html == remLocale.cancel[lang]) updateList();
		else {
			if (confirm(remLocale.deleteConfirm[lang])){
				$('#'+id).hide('slow');
				
				parent.reminderArray1.splice(n0,1);
				parent.reminderArray2.splice(n0,1);
					
				parent.reminder.save();
				$(this).html(remLocale.remove[lang]);
			}
			else return true;
		}
	});
		
	$('td.remope .action-modify').off('click').on('click',function(){
		var id = $(this).parent().parent().parent().attr('id');
		
		$(this).parent().children('.action-accept').attr('disabled',false);
		$(this).parent().parent().parent().find('textarea[disabled]').attr('disabled',false);
		$(this).parent().parent().parent().find('.action-remove').html(remLocale.cancel[lang]);
		$(this).attr('disabled',true);
	});
		
	$('td.remope .action-accept').off('click').on('click',function(){
		var id = $(this).parent().parent().parent().attr('id');
		var n0 = parseInt(id.substring(5));
		
		var values = testInput(this,'modify');
		if (typeof values !== 'object'){ return false }

		parent.reminderArray1[n0] = values.text;
		parent.reminderArray2[n0] = values.time;

		parent.reminder.save();
		updateList();
	});
}

$('#remdef form').on('submit',function(e){
	e.preventDefault();
	
	var values = testInput(this,'add');
	if (typeof values !== 'object'){ return false }
	
	$('#remdef .remtxt label textarea').val('').html('');
	$('#remdef .remtim label input').val('');
	
	parent.reminderArray1.push(values.text);
	parent.reminderArray2.push(values.time);
	
	parent.reminder.save();
	updateList();
});

function testInput(obj,action){
	if (obj){
		var remTextHasCodeChars = parent.scriptMatch.test($(obj).parent().parent().parent().find('textarea').val());
		var remText = ($(obj).parent().parent().parent().find('textarea').val()).replace(parent.scriptMatch,'');
		var todayConcat = parent.MLPNow.timer.datestring.replace(/\./g,'');
		
		if (parent.validDate.test($(obj).parent().parent().parent().find('input').val())){
			remTime = $(obj).parent().parent().parent().find('input').val();
		}
		else {
			modalInit("invalidDate");
			return false;
		}
		remTimeArray = remTime.split('.');
		var remTimeConcat = remTime.replace(/\./g, "");
		if (remTimeConcat < todayConcat) {
			modalInit("pastDate");
			return false;
		}
		else if ((parent.reminderArray2.indexOf(remTime) != -1) && action != 'modify' ){
			if (remTextHasCodeChars == true){
				infoModalInit(["sameDate","containsCode"]);
			}
			else infoModalInit("sameDate");
		}
		else if (remTextHasCodeChars == true){
			infoModalInit('containsCode');
		}
		return {
			text: remText,
			time: remTime,
		};
	}
	return false;
}

function modalInit(errID){
	$('#modal .alert-info').removeClass('alert-info').addClass('alert-error');
	$('#modal .modal-header').html(mET.title);
	$('.onlyDisplayOnError').show();
	$('button[data-dismiss="modal"]').html(mET.ok).removeClass('btn-info').addClass('btn-danger').attr('onclick','');
	
	if (typeof mET[errID] !== "undefined"){
		$('#modal .setErrorText').html(mET[errID]);
	}
	else {
		$('#modal .setErrorText').html(mET.unknownError);
	}
	$('#modal').modal('show');
}

function infoModalInit(infID){
	$('#modal .alert-error').removeClass('alert-error').addClass('alert-info');
	$('#modal .modal-header').html(mIT.title);
	$('.onlyDisplayOnError').hide();
	$('button[data-dismiss="modal"]').html(mIT.ok).removeClass('btn-danger').addClass('btn-info');

	if (typeof mIT[infID] !== "undefined"){
		$('#modal .setErrorText').html(mIT[infID]);
	}
	else if (typeof infID === 'object' && typeof infID.length !== 'undefined'){
		var fullHTML='';
		for (var i=0,l=infID.length;i<l;i++){
			fullHTML += mIT[infID[i]]+(i+1 < l ? '<hr>' : '');
		}
		$('#modal .setErrorText').html(fullHTML);
	}
	else {
		$('#modal .setErrorText').html(mIT.unknownInfo);
	}
	$('#modal').modal('show');
}
