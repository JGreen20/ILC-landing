<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller
{

	/**
	* Portada principal For - Get
	*
	* @access public
	*/
	public function index()
	{
		$this->load->helper('form');
		$this->load->model('Main_model');
		$dptos = $this->Main_model->getDepartamentos();
		if ($dptos) {
			$this->template->set('_dptos', $dptos);
		}

		$cdtos = $this->Main_model->getCredits();
		if($cdtos){
			$this->template->set('_cdtos',$cdtos);
		}

		$this->template->set('_token', $this->user->token());

		//$this->template->set('_title', 'ILC');
		//$this->template->set('loginUrl', $this->facebook->login_url());
		$this->template->add_js('view', 'main/script');
		$this->template->render('main/index');
	}

	public function getIndex()
	{
		//$this->template->set('_title', 'ILC');
		$this->template->add_js('view', 'main/script');
		$this->template->renderAjax('main/index');
	}

	public function getMaps($city = 0, $limit = 6, $offset = 0)
	{
		$total = 0;

		$this->load->model('Main_model');

		$result = $this->Main_model->getAll($city, $limit, $offset);

		$agencias = $result['data'];
		$total = $result['num_rows'];

		if (count($agencias))
		{
			$this->template->set('agencias', $agencias);

			if ($total > $limit) {
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = 'main/getMaps/' . $city . '/' . $limit;
				//$config['base_url'] = site_url('main/getMaps/' . $city . '/' . $limit);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 5;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$ciudades = $this->Main_model->get('ciudad', 'id, ciudad');
		if ($ciudades)
		{
			$this->template->set('ciudades', $ciudades);
		}

		//Obtener nombre de ciudad
		if ((int)$city > 0)
		{
			$nameCity = $this->Main_model->getRow('ciudad', 'ciudad', array('id' => $city));
			if ($nameCity)
			{
				$this->template->set('nameCity', $nameCity);
			}
		}
		$this->template->set('city', $city);

		$this->template->add_js('view', 'main/script');
		$this->template->renderAjax('main/maps');
	}

	public function getJoyas()
	{
		$this->template->add_js('view', 'main/script');
		$this->template->renderAjax('main/joyas');
	}

	public function getPrendatodo()
	{
		$this->template->add_js('view', 'main/script');
		$this->template->renderAjax('main/prendatodo');
	}

	public function getVehicular()
	{
		$this->template->add_js('view', 'main/script');
		$this->template->renderAjax('main/vehicular');
	}

	/**
	* Método que realiza el login del usuario.
	*
	* @access public
	* @param  $login_email 		Str 	Email del usuario
	* @param  $login_password 	Str 	Password del usuario
	*/
	public function login()
	{
		if (!$this->user->is_logged_in()) {
			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');
				$rules = array(
					array(
						'field'	=>	'login_email',
						'label'	=>	'lang:cms_general_label_email',
						'rules'	=>	'trim|required|valid_email'
					),
					array(
						'field'	=>	'login_password',
						'label'	=>	'lang:cms_general_label_password',
						'rules'	=>	'required|min_length[6]|max_length[30]'
					),
				);

				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() === TRUE) {
					if ($this->user->loginGeneral($this->input->post('login_email'), $this->input->post('login_password')) === TRUE) {
						// Editamos el campo last_login para indicar cual ha sido su último logueo.
						$this->load->model('admin/Users_model');
						$this->session->set_userdata('user_id', $this->user->id);
						$this->Users_model->edit(NULL, array('id'=> $this->user->id), array('last_login' => date('Y-m-d H:i:s')));
						$this->_verifyDate();
						redirect('reminders');
					}
					$this->template->set_flash_message(array('error' => $this->user->errors()));
				}
				redirect();
			}
		} else {
			redirect('reminders');
		}
	}

	/**
	* Deslogueo de usuario
	*
	* Realizar deslogueo de usuario, para lo cual eliminamos las variables de sesión existentes. Verificamos que estemos logueado y redirigimos a la portada.
	*
	* @access public
	*/
	public function logout()
	{
		if ($this->user->is_logged_in()) {
			$this->session->sess_destroy();
		}
		redirect();
	}

	/**
	* Método para verificar si el DNI ya se encuentra registrado
	*
	*/
	public function verify()
	{
		$return = array('result' => false);

		// Cargar el modelo
		$this->load->model('Main_model');

		$field = 'dni';
		$dni = $this->input->post('customer_dni');

		if (!$this->Main_model->checkCustomer($field, $dni)) {
			// Finally, return a JSON
			$return['result'] = true;
		}

		echo json_encode($return);
		exit;
	}

	/**
	* Método para verificar si el Email ya se encuentra registrado
	*
	*/
	public function verifyEmail()
	{
		$return = array('result' => false);

		// Cargar el modelo
		$this->load->model('Main_model');

		$field = 'email';
		$email = $this->input->post('customer_email');

		if (!$this->Main_model->checkCustomer($field, $email)) {
			// Finally, return a JSON
			$return['result'] = true;
		}

		echo json_encode($return);
		exit;

	}

	/**
	* Método para insertar nuevo Cliente
	*
	*/
	public function insertCustomer()
	{
		$return = array('result' => false);

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$name = $this->input->post('name');
			$lastname = $this->input->post('lastname');
			$dni = $this->input->post('dni');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$mensaje = $this->input->post('mensaje');
			$credito = $this->input->post('credito');
			$dpto = $this->input->post('dpto');
			$emailAdmin = 'j.perez@adinspector.pe';

			// Cargar el modelo
			$this->load->model('Main_model');

			$data = array(
				'name'            => $name,
				'lastname'        => $lastname,
				'dni'             => $dni,
				'email'           => $email,
				'phone'           => $phone,
				'id_credito'      => $credito,
				'id_departamento' => $dpto,
				'mensaje'         => $mensaje
			);

			if($this->Main_model->insertaCustomer($data)){
				// Enviar correo a mismo cliente y al administrador
				$this->load->library('email');
				$this->load->helper('functions');

				// Enviar correo de confirmación a cliente
				$subject = 'Formulario de solicitud';
				$body = '<tr>'
						.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 50px 50px 50px 50px;">'
                		.'Gracias por su interés en nuestros servicios. En breve su solicitud esta siendo enviada a uno de nuestro ejectivos de ventas para que se contacte con usted y le de la inforamción que desea.'
                		.'</td>'
                		.'</tr>';
				$message = $this->bodyEmail($name, $body);
				if (send_email($email, $subject, $message))
				{
					// Enviar al administrador
					$body = '<tr>'
							.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 20px 50px 20px 50px;">'
                			.'Acaba de recibir una solicitud de uno de los servicios por parte del siguiente usuario:.'
                			.'</td>'
                			.'</tr>'
                			.'<tr>'
							.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 20px 50px 20px 50px;">'
                			.'<table width="150" align="left" border="0" cellpadding="0" cellspacing="0" bgcolor="#f7f7f7">'
              					.'<tr>'
                					.'<td style="font-size: 14px; font-family: Arial, sans-serif; padding: 0 0 10px 0; text-align: right; font-weight: bold;">'
                  						.'Nombre:'
                					.'</td>'
              					.'</tr>'
              					.'<tr>'
                					.'<td style="font-size: 14px; font-family: Arial, sans-serif; padding: 0 0 10px 0; text-align: right; font-weight: bold;">'
                  						.'Apellidos:'
                					.'</td>'
              					.'</tr>'
              					.'<tr>'
                					.'<td style="font-size: 14px; font-family: Arial, sans-serif; padding: 0 0 10px 0; text-align: right; font-weight: bold;">'
                  						.'DNI:'
                					.'</td>'
              					.'</tr>'
              					.'<tr>'
                					.'<td style="font-size: 14px; font-family: Arial, sans-serif; padding: 0 0 10px 0; text-align: right; font-weight: bold;">'
                  						.'Correo electrónico:'
                					.'</td>'
              					.'</tr>'
              					.'<tr>'
                					.'<td style="font-size: 14px; font-family: Arial, sans-serif; padding: 0 0 10px 0; text-align: right; font-weight: bold;">'
                  						.'Crédito de interés:'
                					.'</td>'
              					.'</tr>'
              					.'<tr>'
                					.'<td style="font-size: 14px; font-family: Arial, sans-serif; padding: 0 0 10px 0; text-align: right; font-weight: bold;">'
                  						.'Departamento:'
                					.'</td>'
              					.'</tr>'
            				.'</table>'
            				.'<!--[if (gte mso 9)|(IE)]>'
              				.'<table width="330" align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#f7f7f7">'
                				.'<tr>'
                  					.'<td>'
            				.'<![endif]-->'
            					.'<table class="col330" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 330px;" bgcolor="#f7f7f7">'
              						.'<tr>'
                						.'<td>'
                  							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
                    							.'<tr>'
                      								.'<td class="subhead" style="padding: 0  0 10px 5px; font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;">'
                      									. $name
                      								.'</td>'
                    							.'</tr>'
                    							.'<tr>'
                      								.'<td class="subhead" style="padding: 0  0 10px 5px; font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;">'
                      									. $lastname
                      								.'</td>'
                    							.'</tr>'
                    							.'<tr>'
                      								.'<td class="subhead" style="padding: 0  0 10px 5px; font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;">'
                      									. $dni
                      								.'</td>'
                    							.'</tr>'
                    							.'<tr>'
                      								.'<td class="subhead" style="padding: 0  0 10px 5px; font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;">'
                      									. $email
                      								.'</td>'
                    							.'</tr>'
                    							.'<tr>'
                      								.'<td class="subhead" style="padding: 0  0 10px 5px; font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;">'
                      									. $this->Main_model->getRow('creditos', 'name', array('id' => $credito))->name
                      								.'</td>'
                    							.'</tr>'
                    							.'<tr>'
                      								.'<td class="subhead" style="padding: 0  0 10px 5px; font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;">'
                      									. $this->Main_model->getRow('departamentos', 'name', array('id' => $dpto))->name
                      								.'</td>'
                    							.'</tr>'
                  							.'</table>'
                						.'</td>'
              						.'</tr>'
            					.'</table>'
            				.'<!--[if (gte mso 9)|(IE)]>'
                  					.'</td>'
                				.'</tr>'
            				.'</table>'
            				.'<![endif]-->'
                			.'</td>'
                			.'</tr>';

                	if (!empty($mensaje))
                	{
                		$body .= '<tr>'
								.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 20px 50px 20px 50px;">'
	                			.'Su mensaje es el siguiente:.'
	                			.'</td>'
	                			.'</tr>'
	                			.'<tr>'
								.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 20px 50px 20px 50px;">'
								.'<table width="100%" align="left" border="0" cellpadding="0" cellspacing="0" bgcolor="#f7f7f7">'
              					.'<tr>'
                					.'<td style="font-size: 14px; font-family: Arial, sans-serif; padding: 20px 20px 20px 20px; text-align: center; background-color: #f7f7f7;" bgcolor="#f7f7f7">'
                  						.$mensaje
                					.'</td>'
              					.'</tr>'
            					.'</table>'
	                			.'</td>'
	                			.'</tr>';
                	}

                	$message= $this->bodyEmail('Administrador', $body);

					if (send_email($emailAdmin, $subject, $message))
					{
						$return['result'] = TRUE;
					}
				}
			}
		}
		echo json_encode($return);
		exit;
	}

	private function bodyEmail($name, $body)
	{
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
				.'<html xmlns="http://www.w3.org/1999/xhtml">'
				.'<head>'
  				.'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'
  				.'<title>A Simple Responsive HTML Email</title>'
  				.'<style type="text/css">'
  				.'body {margin: 0; padding: 0; min-width: 100%!important;}'
				.'img {height: auto; margin: 0 auto;}'
				.'.content {width: 100%; max-width: 600px;}'
				.'.header {padding: 40px 30px 20px 30px;}'
				.'.innerpadding {padding: 30px 30px 30px 30px;}'
				.'.innermargin {margin: 20px 0 0 0;}'
				.'.bg {background-color: #f7f7f7;}'
				.'.borderbottom {border-bottom: 1px solid #f2eeed;}'
				.'.subhead {font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;}'
				.'.h1, .h2, .bodycopy {color: #484848; font-family: Arial, sans-serif;}'
				.'.h1 {font-size: 33px; line-height: 38px; font-weight: bold;}'
				.'.h2 {padding: 15px 10px 15px 10px; font-size: 18px; line-height: 20px;}'
				.'.hight {font-size: 18px}'
				.'.bodycopy {font-size: 14px; line-height: 22px; text-align: center; padding: 50px 50px 50px 50px;}'
				.'.button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}'
				.'.button a {color: #ffffff; text-decoration: none;}'
				.'.footer {padding: 20px 30px 15px 30px;}'
				.'.footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}'
				.'.footercopy a {color: #ffffff; text-decoration: underline;}'
				.'@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {'
    			.'body[yahoo] .hide {display: none!important;}'
    			.'body[yahoo] .buttonwrapper {background-color: transparent!important;}'
    			.'body[yahoo] .button {padding: 0px!important;}'
    			.'body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}'
    			.'body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}'
    			.'body[yahoo] .bodycopy { padding: 20px 10px 20px 10px !important;}'
  				.'}'
				.'@media only screen and (min-device-width: 601px) {'
    			.'.content {width: 600px !important;}'
    			.'.col330 {width: 330px !important;}'
    			.'.col380 {width: 380px !important;}'
  				.'}'
  				.'</style>'
				.'</head>'
				.'<body yahoo bgcolor="#ebeef0" style="margin: 0; padding: 0; min-width: 100%!important;">'
  				.'<table width="100%" bgcolor="#ebeef0" border="0" cellpadding="0" cellspacing="0">'
  				.'<tr>'
    			.'<td>'
      			.'<!--[if (gte mso 9)|(IE)]>'
        		.'<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">'
          		.'<tr>'
            	.'<td>'
      			.'<![endif]-->'
      			.'<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;">'
        		.'<tr>'
          		.'<td>'
            	.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
              	.'<tr>'
                .'<td class="h2" style="color: #153643; font-family: sans-serif; padding: 15px 10px 15px 10px; font-size: 24px; line-height: 20px; text-align: center;" bgcolor="#ebeef0">'
                .'<img src="https://ad-inspector.com/proyectos/app/ilcservicios/assets/images/logo-modal.png" margin: 0 auto; />'
                .'</td>'
              	.'</tr>'
            	.'</table>'
          		.'</td>'
        		.'</tr>'
        		.'<tr>'
          		.'<td>'
            	.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
              	.'<tr>'
                .'<td class="h2 bg" style="color: #484848; font-family: Arial, sans-serif; padding: 15px 10px 15px 10px; font-size: 18px; line-height: 28px; background-color: #f7f7f7;" bgcolor="#f7f7f7">'
                .'hola, <span class="hight" style="font-size: 18px">' . $name . '</span>'
                .'</td>'
              	.'</tr>'
                .$body
            	.'</table>'
          		.'</td>'
              	.'</tr>'
            	.'</table>'
          		.'</td>'
        		.'</tr>'
				.'</table>'
      			.'<!--[if (gte mso 9)|(IE)]>'
            	.'</td>'
          		.'</tr>'
      			.'</table>'
      			.'<![endif]-->'
      			.'</td>'
    			.'</tr>'
  				.'</table>'
				.'</body>'
				.'</html>';

		return $html;
	}
}

/* End of file portada.php */
/* Location: ./application/controllers/portada.php */
