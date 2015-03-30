var j = jQuery.noConflict();

(function ($){
	j(document).on('ready',function(){

		//Open modal Like
		j('#likeModal').modal('show');

		//Full Page script
		j('#landing-page').fullpage({
			//Navigation
			menu                             : false,
			anchors                          : ['landingPage', 'sectionProducts'],
			navigation                       : false,
			navigationTooltips               : ['firstSlide', 'secondSlide'],
			slidesNavigation                 : false,

			//Scrolling
			autoScrolling                    : false,
			fitToSection                     : false,
			scrollingSpeed                   : 900,
			scrollOverflow                   : false,
			scrollBar                        : false,

			//Accessibility
		    keyboardScrolling                : false,

			//Custom selectors
			sectionSelector                  : '.page-section',
			slideSelector                    : '.slide',

			//Design
			//fixedElements                    : '#likeModal,#formularyModal,.modal,#result',
	        responsive                       : 2000,
		});

		//NiceScroll
		j("html").niceScroll({
			cursorwidth      : "5px",  // - cursor width in pixel, default is 5 (you can write "5px" too)
			enablemousewheel : true,  //nicescroll can manage mouse wheel events (default:true)
			enablekeyboard   : false,  //nicescroll can manage keyboard events (default:true)
		});

		//Hide second section
		j('section.page-section:eq(1)').css({'display':'none'});

		//Click Navigation to Slide
		j('a.main-nav__producto').on('click',function(){

			//Show second section
			j('section.page-section:eq(1)').css({'display':'block'});

			var widthPage = j('.page-wrapper').width();
			var slideto = j(this).data('index');
			var movepx = -widthPage*(slideto-1);
			//alert(movepx);

			j('.fp-slidesContainer').css({
				'-webkit-transform' : 'translate3d('+movepx+'px, 0px, 0px)',
				'-moz-transform'    : 'translate3d('+movepx+'px, 0px, 0px)',
				'-o-transform'      : 'translate3d('+movepx+'px, 0px, 0px)',
				'transform'         : 'translate3d('+movepx+'px, 0px, 0px)'
			});

			j('.slide').siblings().removeClass('active');
			j('[data-anchor=slide'+slideto+']').addClass('active');

			setTimeout(function(){
				$.fn.fullpage.moveSectionDown();
				$( "#html" ).scroll(function() {
					return false;
				});
			},700);

		});

		//Hold click by default href
		j('a.page-section__nav__item,a.page-section__steps__item').on('click',function(event){
			event.preventDefault();
		});

		//Click arrow up
		j('a.page-section__form__arrow').on('click',function(e){
			e.preventDefault();
			$.fn.fullpage.moveSectionUp();
		});

		//Controls for Page and Navigation
		j('.js-slide-one').on('click',function(){
			$.fn.fullpage.moveTo(2, 'slide1');
		});

		j('.js-slide-two').on('click',function(){
			$.fn.fullpage.moveTo(2, 'slide2');
		});

		j('.js-slide-three').on('click',function(){
			$.fn.fullpage.moveTo(2, 'slide3');
		});

		j('.js-slide-four').on('click',function(){
			$.fn.fullpage.moveTo(2, 'slide4');
		});

		// Class active in page-section__color-lines (for lines colors)
		j('a.main-nav__producto,a.page-section__nav__item').on('click',function(){
			var line = "#js-page-section__color-lines li.bg-"+j(this).data('line');
			//alert(line);

			j('#js-page-section__color-lines li').switchClass( "active", "", 900, "easeInOutQuad" );
			j(line).switchClass( "", "active", 900, "easeInOutQuad" );
		});

		//ToolTip
		j('[data-toggle="tooltip"]').tooltip({
			trigger : 'hover focus',
			delay   : { "show": 50, "hide": 50 }
		});



		/*******************************************************************/
		/* FORM VALIDATION *************************************************/
		/*******************************************************************/
		j('#js-frm-register').formValidation({
	    // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
	    feedbackIcons: {
	    	valid: 'glyphicon glyphicon-ok',
	    	invalid: 'glyphicon glyphicon-remove',
	    	validating: 'glyphicon glyphicon-refresh'
	    },
	    live: 'enabled',
	    fields: {
	    	customer_name: {
	    		validators: {
	    			notEmpty: {
	    				message: 'Campo requerido'
	    			},
	    			regexp: {
	    				regexp: /^[a-z\sñÑáéíóúÁÉÍÓÚ]+$/i,
	    				message: 'El nombre sólo puede contener caracteres alfabéticos y espacios'
	    			},
	    		}
	    	},
	    	customer_lastname: {
	    		validators: {
	    			notEmpty: {
	    				message: 'Campo requerido'
	    			},
	    			regexp: {
	    				regexp: /^[a-z\sñÑáéíóúÁÉÍÓÚ]+$/i,
	    				message: 'El nombre sólo puede contener caracteres alfabéticos y espacios'
	    			},
	    		}
	    	},
	    	customer_dni: {
	    		validators: {
	    			notEmpty: {
	    				message: 'Campo requerido'
	    			},
	    			stringLength: {
	    				min: 8,
	    				max: 8,
	    				message: 'DNI contiene 8 digitos'
	    			},
	    			regexp: {
	    				regexp: /^[0-9]+$/,
	    				message: 'Solo caracteres numéricos'
	    			},
	    			remote : {
	    				url     : _root_ + 'main/verify',
	    				message : "DNI ya registrado",
	    				type    : 'POST',
	    				delay   : 1000,
	    				validKey: "result"
	    			}
	    		}
	    	},
	    	customer_email: {
	    		validators: {
	    			notEmpty: {
	    				message: 'Campo requerido'
	    			},
	    			emailAddress: {
                        message: 'Por favor ingrese una direccion email válida'
                    },
	    		}
	    	},
	    	customer_phone: {
	    		validators: {
	    			regexp: {
	    				regexp: /^[0-9]+$/,
	    				message: 'Solo caracteres numéricos'
	    			},
	    			stringLength: {
	    				min: 7,
	    				max: 10,
	    				message: 'Debe contener como mínimo 7 caracteres y máximo 10'
	    			},
	    		}
	    	},
	    },
		})
		.on('success.form.fv', function(e) {
      // Prevent form submission
      e.preventDefault();
			spinner.spin(target);

      // Get the form instance
      	var $form = j(e.target);

			var $this = j(this);
			var dataArray = $form.serializeArray();

			var token     = dataArray[0].value;
			var name      = dataArray[1].value;
			var lastname  = dataArray[2].value;
			var dni       = dataArray[3].value;
			var email     = dataArray[4].value;
			var phone     = dataArray[5].value;
			var credito   = dataArray[6].value;
			var dpto      = dataArray[7].value;

			j.post( _root_ + 'main/insertCustomer', {
				token         : token,
				name          : name,
				lastname      : lastname,
				dni           : dni,
				email         : email,
				phone         : phone,
				credito       : credito,
				dpto          : dpto
			}, function(data) {
				spinner.stop();
				if (data.result) {
					//Ocultar el modal
					j('#formularyModal').modal('hide');

					//Resetear los campos en el modal
					j('#formularyModal').on('hidden.bs.modal', function(){
					    $(this).find('form')[0].reset();
					});

					//Resetear el formulario
					j('#js-frm-register').data('formValidation').resetForm();

					alert('Se agregó correctamente el cliente.', 'Aviso');
				} else {
					alert('No se agregó el cliente. Por favor vuelva a intentarlo.', 'Aviso');
				}
			},"json");
        });

		/*******************************************************************/
		/* Resetear los campos en el modal         *************************/
		/*******************************************************************/
		j('#formularyModal').on('hide.bs.modal', function() {
			$(this).find('form')[0].reset();
			j('#js-frm-register').formValidation('resetForm', true);
		});

		j('#formularyModal').on('shown.bs.modal', function() {
			j('#js-frm-register').formValidation('resetForm', true);
		});

		//On load Window
		j(window).on('load',function(){
			//widthBrowser();
		});

		//On resize Window
		j(window).on('resize',function(){
			//widthBrowser();
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

	/*******************************************************************/
	/* SHARE FACEBOOK BUTTON  ******************************************/
	/*******************************************************************/
		j('.js-share-fb').on('click',function(event){
			event.preventDefault();

			var option  = j(this).attr('data-post');
			var message = "";
			var picture = "";

			switch (option)
			{
				case 'post-1': 
					message = "¡El mejor valor por gramo el mercado sólo en #InversionesLaCruz.";
					picture = _root_ + "assets/images/post-joyasoro.png";
				break;
				case 'post-2': 
					message = "El dinero que necesitas de forma rápida y segura con #PrendaTodo.";
					picture = _root_ + "assets/images/post-prendatodo.png";
				break;
				case 'post-3': 
					message = "¡Evaluación rápida, préstamos adquirido!";
					picture = _root_ + "assets/images/post-vehicular.png";
				break;
				case 'post-4': 
					message = "¡La oportunidad para hacer crecer tu negocio más cerca!";
					picture = _root_ + "assets/images/post-negocio.png";
				break;
				default:  
					message = "esperanos";
					picture = "http://lorempixel.com/400/200";
			}

			facebookShare(message,picture);
			console.log(picture);
		});


	//FACEBOOK LOGIN
	function facebookShare(message,picture){
		var wallPost = {
			'message'        : 	message,
			'picture'        :  picture,
			'link'           : "http://ad-inspector.com/proyectos/app/ilcapp/",
			'name'           : "!Conoce más sobre Inversiones la Cruz!",
			'description'    : "¡La forma más fácil de conocer y obtener nuestros 4 servicios de crédito están aquí!",
			'caption'        : "Inversiones la Cruz",


		};
		FB.login(function(){
			FB.api('/me/feed', 'post', wallPost, function(response) {
            	if (!response || response.error) {
                	alert('¡Lo sentimos en este momento no podemos compartir esto. Por favor vuelva a intentarlo más tarde!');
             	} else {
                	alert('¡Acabas de compartir esto en tu muro!');
             	}
         	});
		}, {scope: 'publish_actions'});
	}


})(jQuery);


/*************************************************************************/
/***********************   FUNCTIONS   ***********************************/
/*************************************************************************/

