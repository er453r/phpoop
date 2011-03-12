$(document).ready(function(){
	prefix = 'http://127.0.0.1:8888/eclipse/POOP/example';

	$('a').ajaxize({
		loading:'Wczytywanie...',
		reajaxize:'a',
		callback: function(){
			//
		}
	});
	
	$('#menu a').mouseenter(function(){
		  $(this).animate({'letter-spacing': 2}, 500);
	});
	
	$('#menu a').mouseleave(function(){
		  $(this).animate({'letter-spacing': -2}, 500);
	});
});