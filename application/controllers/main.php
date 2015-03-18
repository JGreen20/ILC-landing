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
		// Verificar que ya esteemos logueados
		/*if ($this->user->is_logged_in()) {
			redirect('reminders');
		}*/

		// Cargar el modelo
		$this->load->model('Main_model');
		$dptos = $this->Main_model->getDepartamentos();
		if ($dptos) {
			$this->template->set('_dptos', $dptos);
		}

		$cdtos = $this->Main_model->getCredits();
		if($cdtos){
			$this->template->set('_cdtos',$cdtos);
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', 'Main');
		$this->template->set('loginUrl', $this->facebook->login_url());
		//$this->template->add_js('view', 'main/script');
		$this->template->render('main/index');
	}


	/**
	* Regitro de usuario a través de nuestro formulario de registro
	*
	* @access public
	* @param  $data 		Data con la información del usuario a agregar (name, email, birthday, phone)
	* @return boolean 		True en caso de registrar y enviar el email de activación. Devolvemos 3 si el email ingresado ya se encuentra registrada. Cualquier otro problema devuelve FALSE
	*/
	public function registerUser()
	{
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$name = trim($this->input->post('user_name'));
			$email = trim($this->input->post('user_email'));
			//$birthday = (!empty($this->input->post('user_birthday'))) ? $this->input->post('user_birthday') : '0000-00-00';

			// Comprobamos que name e email no estan vacio y el email es válido
			if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->load->model('admin/Users_model');
				$this->load->helper('functions');
				$this->load->library(array('encrypt', 'email'));

				// Verificar si email ya esta registrado
				$verify_email = $this->Users_model->get(NULL, 'id, birthday, uid', array('email' => $email));

				// Generar código de activación
				$code = randomString(6);
				$encrypt_code = $this->encrypt->password($code, 'sha256');

				if ($verify_email && count($verify_email) > 0) {
					// Verificar que ya haya activado o no su cuenta
					$active_account = $this->Users_model->get(NULL, 'id', array('id' => $verify_email[0]->id, 'status' => 1, 'active' => 1));

					// Si ya activó debemos actualizar con los datos de facebook y luego mandar a vista de agregar password
					if ($active_account && count($active_account) > 0) {
						$data = array(
							'name'				=>	$name,
							'modified'			=>	1,
							'modified_at'		=>	date('Y-m-d H:i:s')
						);

						if ($verify_email[0]->birthday == '0000-00-00') {
							$data['birthday'] = $birthday;
						}

						$this->Users_model->edit(NULL, array('id' => $verify_email[0]->id), $data);
						echo '3'; // Ya estamos registrados, activo, pedir al usuario que se loguee
					} else {
						// Si no activó debemos reenviar el correo con código de activación
						$data = array(
							'activation_code'	=>	$encrypt_code,
							'modified'			=>	1,
							'modified_at'		=>	date('Y-m-d H:i:s')
						);

						if ($verify_email[0]->birthday == '0000-00-00') {
							$data['birthday'] = $birthday;
						}

						if ($this->Users_model->edit(NULL, array('id' => $verify_email[0]->id), $data)) {
							// Enviar correo con código de activación
							$subject = 'Activación de cuenta en For - Get';
							$message = '<h2>Gracias por registrarte en For - Get. Necesitamos validar tu subscripción por favor hacer '
										.'<a href="' . base_url() . 'portada/confirm/' . $encrypt_code . '">Click aquí</a></h2>';
							if (send_email($email, $subject, $message)) {
								// Redigir a portada
								$this->template->set_flash_message(array('success' => $this->lang->line('user_verify_email')));
								echo '4'; // Enviamos de nuevo código de verificación, debemos recargar a página portada
							}
						}
					}
				} else {
					// Agregamos el usuario
					$data = array(
						'name'				=>	$name,
						'email'				=>	$email,
						'birthday'			=>	$birthday,
						'role'				=>	5,
						'activation_code'	=>	$encrypt_code,
						'created'			=>	1,
						'created_at'		=>	date('Y-m-d H:i:s'),
					);

					$last_id = $this->Users_model->add(NULL, $data);
					if ((int)$last_id > 0) {
						// Enviar correo con código de activación
						$subject = 'Activación de cuenta en For - Get';
						$message = '<h2>Gracias por registrarte en For - Get. Necesitamos validar tu subscripción por favor hacer '
									.'<a href="' . base_url() . 'portada/confirm/' . $encrypt_code . '">Click aquí</a></h2>';
						if (send_email($email, $subject, $message)) {
							echo '1';
						} else {
							echo '2';
						}
					} else {
						echo FALSE;
					}
				}
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}

		exit;
	}

	/**
	* Activar cuenta
	*
	* Activar cuenta de usuario. Para ello debemos verificar código de activación ingresado en url, y que el campo status y active deben estar en 0.
	*
	* @access public
	* @param  $activation_code 		STR 	Código de activación a verificar
	* @return 								En caso de que los datos son correctos mostramos la vista para que el usuario registre su password. Caso contrario muestra página de error.
	*/
	public function confirm($activation_code = '')
	{
		if (strlen($activation_code) <= 0 && $activation_code == '') {
			show_error('¡Acceso restringido!');  // Generar vista de error cuando tratamos de entrar a la página sin código de activación
		}

		// Colocar código si cuando nos hemos logueado nos redirija a la vista después de login
		if ($this->user->is_logged_in()) {
			redirect('dashboard');
		}

		$this->load->model('admin/Users_model');
		$row = $this->Users_model->get(NULL, 'id, name, email, birthday', array('activation_code' => $activation_code, 'status' => 0, 'active' => 0));

		if ($row && count($row) > 0) {
			// Mostrar formulario de registro de password
			$this->load->helper('form');
			$this->template->set('_token', $this->user->token());
			$this->template->set('_title', 'Activación de cuenta');
			$this->template->set('_id', $row[0]->id);
			$this->template->add_js('view', 'portada/script');
			$this->template->render('portada/confirm');
		} else {
			show_error('¡Código de verificación incorrecto!');
		}
	}

	/**
	* Registrar password de usuario.
	*
	* A travé de ajax registramos password de nuevo usuario, actualizamos campos status y active y cambiamos el role a "3"
	*
	* @access public
	* @param  $id 			INT 		Id del usuario
	* @param  $password 	STR 		Password del usuario
	* @return 				BOOL 		Si actualizamos los datos del usuario deveolvemos TRUE, caso contrario devolvemos FALSE
	*/
	public function registerPassUser()
	{
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('encrypt');
			$id = $this->input->post('user_id');
			$password = $this->encrypt->password($this->input->post('user_password'), 'sha256');
			$repassword = $this->encrypt->password($this->input->post('user_repassword'), 'sha256');
			$this->load->model('admin/Users_model');

			if (!empty($password) && ($password == $repassword)) {
				$data = array(
					'password'		=>	$password,
					'role'			=>	3,
					'status'		=>	1,
					'active'		=>	1,
					'modified'		=>	1,
					'modified_at'	=>	date('Y-m-d H:i:s')
				);

				if ($this->Users_model->edit(NULL, array('id' => $id), $data)) {
					echo TRUE;
				} else {
					echo FALSE;
				}
			}
		} else {
			echo FALSE;
		}
	}

	/**
	* Registro de usuario a través de Facebook
	*
	* Registrode usuario a través de Facebook, para lo cual debemos realizar tres verificaciones.
	* Verificar que email ya está registrado.
	* De estar registrado verificamos que ya este activado.
	* Si no esta activado la cuenta volvemos a enviar email con nuevo código de activación.
	* Si ya esta activada verificamos que si ya actualizamos el uid del usuario.
	* Si no está registrado uid de usuario lo agregamos
	* Si ya esta registrado uid de usuario redirigimos a dashboard
	*
	* @access public
	*/
	public function registerFacebook()
	{
		if ($this->session->userdata('fb_token')) {
			$user = $this->facebook->get_user();

			$uid = $user['id'];
			$name = $user['name'];
			$email = $user['email'];
			$birthday = (isset($user['birthday'])) ? $user['birthday'] : '00/00/0000';
			$birthday = date('Y-m-d', strtotime(str_replace('-', '/', $birthday)));

			// Traemos la imagen de facebook
			$picture = $this->facebook->getPicture();
			$avatar = $picture['url'];

			// Comprobamos que name e email no estan vacio y el email es válido
			if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->load->model('admin/Users_model');
				$this->load->helper('functions');
				$this->load->library(array('encrypt', 'email'));

				// Verificar si email ya esta registrado
				$verify_email = $this->Users_model->get(NULL, 'id, birthday, avatar', array('email' => $email));

				// Generar código de activación
				$code = randomString(6);
				$encrypt_code = $this->encrypt->password($code, 'sha256');

				if ($verify_email && count($verify_email) > 0) {
					// Ya se registro con el registro de la aplicación
					// Debemos verificar que ya activo su cuenta
					$active_account = $this->Users_model->get(NULL, 'id, uid', array('id' => $verify_email[0]->id, 'status' => 1, 'active' => 1));
					// Si ya activó debemos actualizar con los datos de facebook y luego mandar a vista de agregar password
					if ($active_account && count($active_account) > 0) {
						// Verificamos que ya tenemos su uid para actualizar sus datos, sino pasamos a loguearlo y mandamos a dashboard
						if ($active_account[0]->uid == '' && empty($active_account[0]->uid)) {
							$data = array(
								'uid'			=>	$uid,
								'modified'		=>	1,
								'modified_at'	=>	date('Y-m-d H:i:s')
							);

							if ($verify_email[0]->birthday == '0000-00-00') {
								$data['birthday'] = $birthday;
							}

							if ($verify_email[0]->avatar == '') {
								$data['avatar'] = $avatar;
							}

							$this->Users_model->edit(NULL, array('id' => $verify_email[0]->id), $data);
							//echo 'Bien ya actualizamos tus datos con tu cuenta de facebook, a través de los dos medios puedes loguearte.';
						}
						// Redirigir a dashboard logueando al usuario
						if ($this->user->loginFacebook($uid) === TRUE) {
							$this->session->set_userdata('user_id', $this->user->id);
							$this->Users_model->edit(NULL, array('id'=> $this->user->id), array('last_login' => date('Y-m-d H:i:s')));
							$this->_verifyDate();
							redirect('reminders');
						}
					} else {
						// Si no activó debemos reenviar el correo con código de activación
						$data = array(
							'uid'				=>	$uid,
							'activation_code'	=>	$encrypt_code,
							'avatar'			=>	$avatar,
							'modified'			=>	1,
							'modified_at'		=>	date('Y-m-d H:i:s')
						);

						if ($verify_email[0]->birthday == '0000-00-00') {
							$data['birthday'] = $birthday;
						}

						if ($this->Users_model->edit(NULL, array('id' => $verify_email[0]->id), $data)) {
							// Enviar correo con código de activación
							$subject = 'Activación de cuenta en For - Get';
							$message = '<h2>Gracias por registrarte en For - Get. Necesitamos validar tu subscripción por favor hacer '
										.'<a href="' . base_url() . 'portada/confirm/' . $encrypt_code . '">Click aquí</a></h2>';
							if (send_email($email, $subject, $message)) {
								// Redigir a portada
								$this->template->set_flash_message(array('success' => $this->lang->line('user_verify_email')));
								redirect();
							}
						}
					}
				} else {
					// Agregamos el usuario
					$data = array(
						'uid'				=>	$uid,
						'name'				=>	$name,
						'email'				=>	$email,
						'birthday'			=>	$birthday,
						'role'				=>	5,
						'activation_code'	=>	$encrypt_code,
						'avatar'			=>	$avatar,
						'created'			=>	1,
						'created_at'		=>	date('Y-m-d H:i:s'),
					);

					$last_id = $this->Users_model->add(NULL, $data);
					if ((int)$last_id > 0) {
						// Enviar correo con código de activación
						$subject = 'Activación de cuenta en For - Get';
						$message = '<h2>Gracias por registrarte en For - Get. Necesitamos validar tu subscripción por favor hacer '
									.'<a href="' . base_url() . 'portada/confirm/' . $encrypt_code . '">Click aquí</a></h2>';
						if (send_email($email, $subject, $message)) {
							// Redigir a portada // Verificar
							$this->template->set_flash_message(array('success' => $this->lang->line('user_verify_email')));
							redirect();
						}
					}
				}
			}
		}
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
	* Login a través de JS
	*
	* Realizar login de usuario a través de javascript, luego de realizar el registro de password. A través del id del usuario obtenemos el email.
	*
	* @access public
	* @param  $id 			Int 		Id del usuario
	* @param  $pass 		Str 		Password del usuario
	* @return 				Bool 		Si realizamos el login correctamente devolvemos TRUE, caso contrario devolvemos FALSE.
	*/
	public function loginJs()
	{
		$id = $this->input->post('id');
		$pass = $this->input->post('pass');

		if ((int)$id > 0) {
			$this->load->model('admin/Users_model');

			$user = $this->Users_model->get(NULL, 'email', array('id' => $id));

			if ($user && count($user) > 0) {
				if ($this->user->loginGeneral($user[0]->email, $pass) === TRUE) {
					// Editamos el campo last_login para indicar cual ha sido su último logueo.
					$this->session->set_userdata('user_id', $this->user->id);
					$this->Users_model->edit(NULL, array('id'=> $this->user->id), array('last_login' => date('Y-m-d H:i:s')));
					echo TRUE;
				}
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
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
	* Método para verificar cuando nos logueamos si algún recordatorio ya se cumplió. Si es de tipo holiday y weekend cambiamos el status a trash.
	* Si es birthday o anniversary actualizamos el año.
	*
	* @access private
	*/
	private function _verifyDate()
	{
		$this->load->model('Reminders_model');
		$day = date('d') - 1;
		$day = ($day < 10) ? '0' . $day : $day;
		$nextDay = date('Y') . '-' . date('m') . '-' . $day;
		$result = $this->Reminders_model->get(NULL, 'id', array('LEFT(`published_at`, 10) =' => $nextDay, 'created' => $this->user->id), 'post_type', array('holiday', 'weekend'));

		if ($result && count($result)) {
			foreach ($result as $row) {
				$this->Reminders_model->edit(NULL, array('id' => $row->id), array('post_status' => 'trash'));
			}
		}

		$result = $this->Reminders_model->get(NULL, 'id, published_at', array('LEFT(`published_at`, 10) =' => $nextDay, 'created' => $this->user->id), 'post_type', array('birthday', 'anniversary'));

		if ($result && count($result)) {
			foreach ($result as $row) {
				$newDate = date('Y', strtotime($row->published_at)) + 1;
				$newDate = $newDate . '-' . date('m-d', strtotime($row->published_at));

				$this->Reminders_model->edit(NULL, array('id' => $row->id), array('published_at' => $newDate . ' 23:59:59'));
			}
		}

		return;
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
										.'<li>Nombre: ' . $name . ' ' . $lastaname . '</li>'
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
