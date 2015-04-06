var j = jQuery.noConflict();

(function ($){
	j(document).on("ready", function(){
		//NiceScroll
		j("html").niceScroll({
			cursorwidth      : "5px",  // - cursor width in pixel, default is 5 (you can write "5px" too)
			enablemousewheel : true,  //nicescroll can manage mouse wheel events (default:true)
			enablekeyboard   : false,  //nicescroll can manage keyboard events (default:true)
		});

		opts = {
			lines: 13, // The number of lines to draw
			length: 20, // The length of each line
			width: 10, // The line thickness
			radius: 30, // The radius of the inner circle
			corners: 1, // Corner roundness (0..1)
			rotate: 0, // The rotation offset
			direction: 1, // 1: clockwise, -1: counterclockwise
			color: '#000', // #rgb or #rrggbb or array of colors
			speed: 1, // Rounds per second
			trail: 60, // Afterglow percentage
			shadow: false, // Whether to render a shadow
			hwaccel: false, // Whether to use hardware acceleration
			className: 'spinner', // The CSS class to assign to the spinner
			zIndex: 2e9, // The z-index (defaults to 2000000000)
			top: '50%', // Top position relative to parent
			left: '50%' // Left position relative to parent
    	};

    	target = document.getElementById('spin');
    	spinner = new Spinner(opts);
	});
})(jQuery);

function facebookShare(message, picture){
	spinner.spin(target);
	var wallPost = {
		'message'        : 	message,
		'picture'        :  picture,
		'link'           : "http://ad-inspector.com/proyectos/app/ilcapp/",
		'name'           : "!Conoce más sobre Inversiones la Cruz!",
		'description'    : "¡La forma más fácil de conocer y obtener nuestros 4 servicios de crédito están aquí!",
		'caption'        : "Inversiones la Cruz"
	}

	FB.login(function(){
		FB.api('/me/feed', 'post', wallPost, function(response) {
			spinner.stop();
			if (!response || response.error) {
				alert('¡Lo sentimos en este momento no podemos compartir esto. Por favor vuelva a intentarlo más tarde!');
			} else {
				alert('¡Acabas de compartir esto en tu muro!');
			}
		});
	}, {scope: 'publish_actions'});
}
