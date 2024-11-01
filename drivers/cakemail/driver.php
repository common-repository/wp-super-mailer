<?php
/*
-----------------------------------------------------------
 / ____|   /\   | |/ /  ____|  \/  |   /\   |_   _| |     
| |       /  \  | ' /| |__  | \  / |  /  \    | | | |     
| |      / /\ \ |  < |  __| | |\/| | / /\ \   | | | |     
| |____ / ____ \| . \| |____| |  | |/ ____ \ _| |_| |____ 
 \_____/_/    \_\_|\_\______|_|  |_/_/    \_\_____|______|
-----------------------------------------------------------
*/

/**
*  @package WP SuperMailer
*  @author stresslimitdesign
*  This file is the Cakemail Driver
*/

/* ==================================================
 		Set Cakemail Constants
==================================================== */
 if (!class_exists('wp_supermailer_cakemail_driver')) {
	
define('CAKE_INTERFACE_KEY', get_option('wpsmlr_cake_interface_key'));
define('CAKE_INTERFACE_ID', get_option('wpsmlr_cake_interface_id'));
define('CAKE_LOGIN', get_option('wpsmlr_cake_login'));
define('CAKE_PASSWORD', get_option('wpsmlr_cake_password'));


// contstants
define('API_CAKE_URL', 'http://api.cakemail.com');
define('LIB_CAKE', WP_SPRMLR_PATH . 'drivers/cakemail/API/');
define('WP_SPMLR_CAKEMAIL_UNSUB_URL', '[UNSUBSCRIBE]');

require(LIB_CAKE . 'global.php');
require(LIB_CAKE . 'cake_User.php');
require(LIB_CAKE . 'cake_List.php');
require(LIB_CAKE . 'cake_Mailing.php');


class wp_supermailer_cakemail_driver extends wp_supermailer_driver {

	public $user;
	public $authentication = false;
	
	function __construct() {
		
		$this->user = $this->login();	

	}

	protected function login() {
		if (get_option('wpsmlr_cake_login') != '') {
			$user = cake_user_Login(array(
				'email' => CAKE_LOGIN,
				'password' => CAKE_PASSWORD
			));
			$this->authentication = isset($user['user_key']);
			return $user;
		}	
	}
	
	


	public function publish($listId, $from_email, $from_name, $subject, $title, $plaintext, $html, $timestamp) {
		
		$user = $this->user;
		$mailingId = cake_mailing_Create(array(
			'user_key' => $user['user_key'],
			'name' => $subject
		));

		cake_mailing_SetInfo(array(
			'user_key' => $user['user_key'],
			'list_id' => $listId,
			'mailing_id' => $mailingId,
			'clickthru_html' => 'true', // track link clicks
			'opening_stats' => 'true', // track opens
			'unsub_bottom_link' => 'false', // include unsubscribe link
			'subject' => $subject,
			'html_message' => $html,
			'text_message' => $plaintext
		));

		cake_mailing_Schedule(array(
			'user_key' => $user['user_key'],
			'mailing_id' => $mailingId
		));

	}
	
	public function subscribe($email, $list_id, $args = array()) {
		cake_list_SubscribeEmail(array(
			'user_key' => $this->user['user_key'],
			'list_id' => $list_id,
			'email' => $email,
			//'data' => $args
		));
	}
	
	public function unsubscribe( $email, $list_id ) {
		cake_list_UnsubscribeEmail(array(
			'user_key' => $this->user['user_key'],
			'email' => $email,
			'list_id' => $list_id
		));
	
	}

	public function process_template($t) {
		return str_replace(WP_SPRMLR_TEMPLATE_THE_UNSUB_URL, WP_SPMLR_CAKEMAIL_UNSUB_URL, $t);
	}

	public function get_lists() {		
		$lists = cake_list_GetList( array(
			'user_key' => $this->user['user_key'],
		) );
		
		if( $lists && isset( $lists['lists'] ) )
			return $lists['lists'];
		
		return array();
	}
	
	public function create_list( $name, $sender_name, $sender_email ) {
		$list_id = cake_list_Create( array(
			'user_key' => $this->user['user_key'],
			'name' => $name,
			'sender_name' => $sender_name,
			'sender_email' => $sender_email
		));
		
		cake_list_SetInfo(array(
			'user_key'	=> $this->user['user_key'],
			'list_id'	=> $list_id,
			'policy'	=> 'accepted'
		));

		return $list_id;
	}

	public function delete_list($list_id) {
		cake_list_Delete(array(
			'user_key' => $this->user['user_key'],
			'list_id' => $list_id
		));
	}

} 
 } 