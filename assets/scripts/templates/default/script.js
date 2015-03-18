var j = jQuery.noConflict();

(function ($){
	j(document).on('ready',function(){

	//Call to widthBrowser Function
	widthBrowser();

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
		normalScrollElementTouchThreshold: 5,
		scrollingSpeed                   : 900,
		scrollOverflow                   : false,

		//Accessibility
        keyboardScrolling                : false,
        animateAnchor                    : false,
        recordHistory                    : false,

		//Custom selectors
		sectionSelector                  : '.page-section',
		slideSelector                    : '.slide',

		//Design
		fixedElements                    : '#formularyModal,.modal,#result',
        responsive                       : 2000,
	});

	//Click Navigation to Slide
	j('a.main-nav__producto').on('click',function(){

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
		},700);
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

	//Hold click
	j('a.page-section__nav__item,a.page-section__steps__item').on('click',function(event){
		event.preventDefault();
	});

	//ToolTip
	j('[data-toggle="tooltip"]').tooltip({
		trigger : 'hover focus',
		delay   : { "show": 50, "hide": 50 }
	});


	/*******************************************************************/
	/* SHARE FACEBOOK BUTTON  ******************************************/
	/*******************************************************************/
	j('#share-fb').on('click',function(event){
		event.preventDefault();
		facebookShare();
	});


	/*******************************************************************/
	/* FORM VALIDATION *************************************************/
	/*******************************************************************/

	j('#js-frm-register')
		.formValidation({
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
		    				regexp: /^[a-z\sñÑ]+$/i,
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
		    				regexp: /^[a-z\sñÑ]+$/i,
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
		    			}
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

				if (data.result) {
					alert('Se agregó correctamente el cliente.', 'Aviso');

					//Ocultar el modal
					j('#formularyModal').modal('hide');

					//Resetear los campos en el modal
					j('#formularyModal').on('hidden.bs.modal', function(){
					    $(this).find('form')[0].reset();
					});

					//Resetear el formulario
					j('#js-frm-register').data('formValidation').resetForm();

				} else {
					alert('No se agregó el cliente. Por favor vuelva a intentarlo.', 'Aviso');
				}
			},"json");
        });

	
	/*******************************************************************/
	/* Resetear los campos en el modal**********************************/
	/*******************************************************************/
	j('#formularyModal').on('hide.bs.modal', function() {
		$(this).find('form')[0].reset();
		j('#js-frm-register').formValidation('resetForm', true);
	});

	j('#formularyModal').on('shown.bs.modal', function() {
		j('#js-frm-register').formValidation('resetForm', true);
	});


	//On resiize Window
	j(window).on('resize',function(){
		widthBrowser();
	});


/************************************************************************/


    });

	//FACEBOOK LOGIN
	function facebookShare(){
		var wallPost = {
			'message'        : 		'¡A cuidar nuestros #DetallesqueSeducen!',
			'picture'        :    'http://adinspector.com.co/app/preguntalejh/images/post.jpg'
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

function widthBrowser(){
	if (j(window).width() <= 800) {
		j('.main-nav li').removeClass('col-xs-3').addClass('col-xs-6');
		//j('.page-section__steps li').removeClass('col-xs-3').addClass('col-xs-6');
	}
	else{
		j('.main-nav li').removeClass('col-xs-6').addClass('col-xs-3');
		//j('.page-section__steps li').removeClass('col-xs-6').addClass('col-xs-3');
	}
}
