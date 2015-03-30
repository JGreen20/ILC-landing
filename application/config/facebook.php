<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	$config['facebook']['api_id']       = '382358125269432';
	$config['facebook']['app_secret']   = '501633c13582794f0d1bc47dc8fa9a32';
	$config['facebook']['redirect_url'] = 'http://localhost/ilcapp/main/registerFacebook';
	$config['facebook']['permissions']  = array(
		'email',
		'user_birthday',
		'user_friends',
		//'read_friendlists'
	);