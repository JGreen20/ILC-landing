var j = jQuery.noConflict();

(function ($){
  var $body    = j('body');
  var $wrapper = j('.page-wrapper');
  var $loading = j('#js-loading');

  //j('[data-toggle="tooltip"]').tooltip('show');

	j(document).on('ready',function(){
		//Open modal Like
		j('#js-likeModal').modal('show');

    $body.on('click', '.js-links', function(ev){
      ev.preventDefault();
      var url = j(this).attr('href');
      loadWrapper(url);
    });

    $body.on('click', '.js-share-fb', function(event){
			event.preventDefault();

			var option  = j(this).data('post');
			var message = "";
			var picture = "";

			switch (option)
			{
				case 'post-1':
					message = "¡El mejor valor por gramo el mercado sólo en #InversionesLaCruz!";
					picture = _root_ + "assets/images/post-joyasoro.png";
				break;
				case 'post-2':
					message = "¡El dinero que necesitas de forma rápida y segura con #PrendaTodo!";
					picture = _root_ + "assets/images/post-prendatodo.png";
				break;
				case 'post-3':
					message = "¡Evaluación rápida, préstamos adquirido!";
					picture = _root_ + "assets/images/post-vehicular.png";
				break;
				default:
					message = "esperanos";
					picture = "http://lorempixel.com/400/200";
			}

			facebookShare(message, picture);
		});

    // Validación de formulario de registro
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
            remote : {
              url     : _root_ + 'main/verifyEmail',
              message : "Email ya registrado",
              type    : 'POST',
              delay   : 1000,
              validKey: "result"
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
      e.preventDefault();
      $loading.css('display', 'block');

      // Get the form instance
      var $form = j(e.target);

      var $this = j(this);
      var dataArray = $form.serializeArray();

      var token    = dataArray[0].value;
      var name     = dataArray[1].value;
      var lastname = dataArray[2].value;
      var dni      = dataArray[3].value;
      var email    = dataArray[4].value;
      var phone    = dataArray[5].value;
      var credito  = dataArray[6].value;
      var dpto     = dataArray[7].value;
      var mensaje  = dataArray[8].value;

      j.post( _root_ + 'main/insertCustomer', {
        token         : token,
        name          : name,
        lastname      : lastname,
        dni           : dni,
        email         : email,
        phone         : phone,
        credito       : credito,
        dpto          : dpto,
        mensaje : mensaje
      }, function(data) {
        $loading.css('display', 'none');
        if (data.result) {
          //Ocultar el modal
          j('#formularyModal').modal('hide');

          //Resetear los campos en el modal
          j('#formularyModal').on('hidden.bs.modal', function(){
            $(this).find('form')[0].reset();
          });

          //Resetear el formulario
          j('#js-frm-register').data('formValidation').resetForm();
          j('#frmRegister').modal('hide');

          //alert('Se agregó correctamente el cliente.', 'Aviso');
          j('#js-gracias').modal('show');
        } else {
          alert('No se pudo completar la acción. Por favor vuelva a intentarlo.', 'Aviso');
        }
      },"json");
    });

    j('#frmRegister').on('hide.bs.modal', function() {
			j(this).find('form')[0].reset();
			j('#js-frm-register').formValidation('resetForm', true);
		});

		j('#frmRegister').on('shown.bs.modal', function() {
			j('#js-frm-register').formValidation('resetForm', true);
		});

    //Mostrar agencias en base al departamento o ciudad seleccionada
    $body.on('change', '#agencia', function(ev){
      var $this = j(this);
      var city = $this.val();

      if (city === '0')
      {
        return false;
      }

      loadWrapper('main/getMaps/' + city);
    });

    j(document).on('click', ".pagination-digg li a", function(e) {
      e.preventDefault();
      var href = j(this).attr("href");
      loadWrapper(href);
    });
	});

  function loadWrapper(url)
  {
    $wrapper.fadeOut(300, function() {
      $loading.css('display', 'block');
      $wrapper.load(_root_ + url, function(){
        $wrapper.fadeIn(300);
        $loading.css('display', 'none');
      });
    });
  }
})(jQuery);
