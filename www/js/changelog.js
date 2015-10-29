$(function(){
	var MLPony = MLPNow.Pony,
		ponyNamesRegExp = new RegExp('('+MLPony.longNames.join('|')+')','g'),
		cssfixer = function(name, value){
			var href = RELPATH+'css/overrides.css.php?name='+value;
			if ($('header style[href="'+href+'"]').length === 0) $(document.createElement('link')).attr({
				rel:'stylesheet',
				href:RELPATH+'css/overrides.css.php?name='+value,
			}).appendTo('head').load(function(){
				$('head style[data-href*="overrides.css.php"],head link[rel="stylesheet"][href*="overrides.css.php"]').filter(':not([href="'+href+'"])').remove();
			});
			$('.changelog > li > .version, .left-col.grid-70 .title > h1').each(function(){
				var $this = $(this),
					origClass = $this.data('orig-class');
				
				if (typeof origClass !== 'string') $this.data('orig-class',$this.attr('class').trim());
				
				$this.attr('class',origClass+' '+value);
			});
		};
	$('.changelog > li > ul > li').each(function(){
		var $this = $(this);
		
		if ($this.children('.newPonyNames').length > 0) $this = $this.children('.newPonyNames').children();
		
		if ($this.children(':not(.li)').length === 0) $this.each(function(){
			var content = $(this).html();
			if (ponyNamesRegExp.test(content)) $(this).html(content.replace(ponyNamesRegExp,'<a class="pony-icon">$1</a>'));
		});
	});
	
	$('.changelog > li > .version, .left-col.grid-70 .title > h1').each(function(){
		var $this = $(this),
			origClass = $this.data('orig-class');
		
		if (typeof origClass !== 'string'){
			$this.data('orig-class',($this.attr('class')||'').trim());
			origClass = $this.data('orig-class');
		}
		
		$this.attr('class',(origClass.length > 0 ? origClass+' ':'')+MLPNow.pref.name);
	});
	
	if (parent.$('#version').text() !== $('.log-entry:first > .version').text()) parent.$('#version').text($('.log-entry:first > .version').text().substring(1));
	
	$('.pony-icon').each(function(){
		var $this = $(this),
			fullName = $this.html(),
			ln = MLPony.longNames,
			sn = MLPony.shortNames,
			index = ln.indexOf(fullName);
		
		if (typeof MLPony.colors[index] !== 'undefined') $this.css('color',MLPony.colors[index]).addClass(MLPony.shortNames[index]);
		
		$this.attr('href', RELPATH + '?name='+MLPony.shortNames[index]);
	}).on('click',function(e){
		e.preventDefault();
		
		var fullName = $(this).text().trim();
		
		if (e.wich === 2) window.open($(this).attr('href'),'_blank');
		else if (!isframed){
			var shortName = MLPony.shortNames[MLPony.longNames.indexOf(fullName)];
			$.post('prefupdate',{name:'name',value:shortName},function(data){
				if (data.status) cssfixer('name',shortName);
			});
		}
		else parent.changeBGImage.call({ callback: cssfixer },MLPony.longNames.indexOf(fullName));
		
		return false;
	});
});
