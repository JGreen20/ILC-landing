<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( session_status() == PHP_SESSION_NONE ) {
	session_start();
}

// Autoload the required files
require_once( APPPATH . 'libraries/facebook/autoload.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

class Facebook
{
	protected $ci;
	protected $helper;
	protected $session;
	protected $permissions;

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->permissions = $this->ci->config->item('permissions', 'facebook');

		// Initialize the SDK
		FacebookSession::setDefaultApplication( $this->ci->config->item('api_id', 'facebook'), $this->ci->config->item('app_secret', 'facebook') );

		// Create the login helper and replace REDIRECT_URI with your URL
		// Use the same domain you set for the apps 'App Domains'
		// e.g. $helper = new FacebookRedirectLoginHelper( 'http://mydomain.com/redirect' );
		$this->helper = new FacebookRedirectLoginHelper( $this->ci->config->item('redirect_url', 'facebook') );

		if ( $this->ci->session->userdata('fb_token') ) {
			$this->session = new FacebookSession( $this->ci->session->userdata('fb_token') );

			// Validate the access_token to make sure it's still valid
			try {
				if ( ! $this->session->validate() ) {
					$this->session = null;
				}
			} catch ( Exception $e ) {
				// Catch any exceptions
				echo $e->getMessage();
				$this->session = null;
			}
		} else {
			// No session exists
			try {
				$this->session = $this->helper->getSessionFromRedirect();
			} catch( FacebookRequestException $ex ) {
				// When Facebook returns an error
				echo $ex->getMessage();
			} catch( Exception $ex ) {
				// When validation fails or other local issues
				echo $ex->getMessage();
			}
		}

		if ( $this->session ) {
			$this->ci->session->set_userdata( 'fb_token', $this->session->getToken() );

			$this->session = new FacebookSession( $this->session->getToken() );
		}
	}

	/**
	  * Returns the login URL.
	  */
	public function login_url()
	{
		return $this->helper->getLoginUrl( $this->permissions );
	}

	/**
	  * Returns the login URL.
	  */
	public function logout_url()
	{
		return $this->helper->getLogoutUrl( $this->session, base_url() );
	}

	/**
	  * Returns the current user's info as an array.
	  */
	public function get_user()
	{
		try {
			if ( $this->session ) {
				/**
				  * Retrieve User’s Profile Information
				  */
				// Graph API to request user data
				$request = ( new FacebookRequest( $this->session, 'GET', '/me' ) )->execute();

				// Get response as an array
				//$user = $request->getGraphObject(GraphUser::className());
				$user = $request->getGraphObject()->asArray();

				return $user;
			}
		} catch( FacebookRequestException $ex ) {
			// When Facebook returns an error
			echo $ex->getMessage();
		} catch( Exception $ex ) {
			// When validation fails or other local issues
			echo $ex->getMessage();
		}

		//return FALSE;
	}

	/**
	* Verificar que permisos acepto el usuario
	*
	* @access public
	* @param  string 		$page			Id de la página
	* @return array 		$response 		Array con información de la página
	*/
	public function get_like_page($page = '')
	{
		try {
			if (!empty($page)) {
				$param = '/me/likes/' . $page;
				$request = ( new FacebookRequest( $this->session, 'GET', $param))->execute();
				$response = $request->getGraphObject()->asArray();
			}
		} catch( FacebookRequestException $ex ) {
			// When Facebook returns an error
			return $ex->getMessage();
		} catch( Exception $ex ) {
			// When validation fails or other local issues
			return $ex->getMessage();
		}

		return $response;
	}

	/**
	* Verificar los permisos que ha dado el usuario
	*
	* @access public
	* @param  string 		$page			Id de la página
	* @return array 		$response 		Array con información de la página
	*/
	public function verifyPermissions()
	{
		try {
			$param = '/me/permissions';
			$request = ( new FacebookRequest( $this->session, 'GET', $param))->execute();
			$response = $request->getGraphObject()->asArray();
		} catch( FacebookRequestException $ex ) {
			// When Facebook returns an error
			$response = $ex->getMessage();
		} catch( Exception $ex ) {
			// When validation fails or other local issues
			$response = $ex->getMessage();
		}

		return $response;
	}

	/**
	* Obtener picture de usuario logueado con facebook
	*
	* @access public
	* @return $response 		Array 		En caso de obtener imagen de usuario devueve un array conteniendo la url de la imagen
	*/
	public function getPicture()
	{
		try {
			$request = ( new FacebookRequest( $this->session, 'GET', '/me/picture?type=normal&redirect=false'))->execute();
			$response = $request->getGraphObject()->asArray();
		} catch (FacebookRequestException $ex) {
			$response = $ex->getMessage();
		} catch ( Exception $ex) {
			$response = $ex->getMessage();
		}

		return $response;
	}

	public function listFriends()
	{
		try {
			$request = (new FacebookRequest($this->session, 'GET', '/me/friends'))->execute();
			$response = $request->getGraphObject()->asArray();
		} catch (FacebookRequestException $ex) {
			$response = $ex->getMessage();
		} catch (Exception $ex) {
			$response = $ex->getMessage();
		}

		return $response;
	}

	public function getFriendLists()
	{
		try {
			$request = (new FacebookRequest($this->session, 'GET', '/me/friendlists'))->execute();
			$response = $request->getGraphObject()->asArray();
		} catch (FacebookRequestException $ex) {
			$response = $ex->getMessage();
		} catch (Exception $ex) {
			$response = $ex->getMessage();
		}

		return $response;
	}

	public function getFriends()
	{
		try {
			$request = (new FacebookRequest($this->session, 'GET', '/1424335581156397'))->execute();
			$response = $request->getGraphObject()->asArray();
		} catch (FacebookRequestException $ex) {
			$response = $ex->getMessage();
		} catch (Exception $ex) {
			$response = $ex->getMessage();
		}

		return $response;
	}

	public function publishPost()
	{
		try {
			if ( $this->session ) {
				/**
				  * Retrieve User’s Profile Information
				  */
				// Graph API to request user data
				$request = new FacebookRequest(
					$this->session,
					'POST',
					'/me/feed',
					array(
						'message' => 'Este mensaje es de test',
					)
				);

				// Get response as an array
				//$user = $request->getGraphObject(GraphUser::className());
				$response = $request->execute();
				$graphObject = $response->getGraphObject();
				//return $user;
			}
		} catch( FacebookRequestException $ex ) {
			// When Facebook returns an error
			echo $ex->getMessage();
		} catch( Exception $ex ) {
			// When validation fails or other local issues
			echo $ex->getMessage();
		}
		//return FALSE;
	}
}
