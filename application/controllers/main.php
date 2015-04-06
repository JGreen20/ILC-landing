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

	public function getMaps()
	{
		//$this->template->set('_title', 'ILC');
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
			$email = $this->input->post('email');
			$emailAdmin = 'j.perez@adinspector.pe';

			// Cargar el modelo
			$this->load->model('Main_model');

			$data = array(
			   'name'             => $this->input->post('name'),
			   'lastname'         => $this->input->post('lastname'),
			   'dni'              => $this->input->post('dni'),
			   'email'            => $email,
			   'phone'            => $this->input->post('phone'),
			   'id_credito'       => $this->input->post('credito'),
			   'id_departamento'  => $this->input->post('dpto')
			);

			if($this->Main_model->insertaCustomer($data)){
				// Enviar correo a mismo cliente y al administrador
				$this->load->library('email');
				$this->load->helper('functions');

				// Enviar correo de confirmación a cliente
				$subject = 'Formulario de solicitud';
				$message = '<h2>Gracias por contactarnos. Nos estaremos poniendo en contacto con usted muy pronto</h2>';
				if (send_email($email, $subject, $message))
				{
					// Enviar al administrador
					$message = '<h2>Has sido contactado por: </h2>'
								.'<ul>'
								.'<li>Nombre: ' . $name . ' ' . $lastname . '</li>'
								.'<li>Correo: ' . $email . '</li>'
								.'</ul>';

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
}

/* End of file portada.php */
/* Location: ./application/controllers/portada.php */
