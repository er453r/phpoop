var global_ajaxized = 0;

$.fn.ajaxize = function(options){
	var defaults = {
		from: '#content',
		to: '#content',
		loading: 'Loading...',
		callback: 0,
		reajaxize: 0
	};
	
	var options = $.extend(defaults, options);
	
	if(!global_ajaxized){
		$(options.to).wrapInner('<div id="container"></div>');
		$(options.to).append('<div id="loading"><span>'+options.loading+'</span></div>');
		$('#loading').hide();
		
		$('.ajaxized').live('click',function(){
			var tmp = $(this).attr('alt');
		
			$('#container').fadeOut('slow',function(){
				$('#loading').show(0,function(){
					$('#container').load(tmp + ' ' + options.from + '>*',function(){
						$('#loading').hide();
						$('#container').fadeIn('slow');
						
						if(options.reajaxize)
							$(options.to).find(options.reajaxize).ajaxize(options);
						
						if(options.callback)
							options.callback.call();
					});
				});
			});
		});
		
		global_ajaxized++;
	}
	
	return this.each(function(){
		if(!$(this).hasClass('ajaxized')){
			$(this).attr('alt',$(this).attr('href'));
			$(this).attr('href',options.to);
			$(this).addClass('ajaxized');
		}
	});
};
