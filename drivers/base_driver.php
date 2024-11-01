<?php

/**
* @package WP SuperMailer
* @author stresslimitdesign
* @version 0.3
* This file is the Base Driver which is extended with other drivers for different mailing engines
*/

/* ==================================================
 		util set-up
==================================================== */

// extra security check
if ( !function_exists( 'add_action' ) ) {
	wp_die('You cannot access this file directly.');
	exit;
}

if (!class_exists('wp_supermailer_driver')) {
  class wp_supermailer_driver {

    public function login() {
      return null;
    }

  	public function subscribe($email, $list_id=null, $args = array(), $remove_list_ids = array()) {
  	  return null;
  	}

  	public function create_contact($args) {
  	  return null;
  	}

    public function update_contact($contact_id, $args) {
      return null;
    }

    public function check_contact($email) {
      return null;
    }

    public function import_status($import_id) {
      return null;
    }

  	public function unsubscribe($email, $list_id) {
  	  return null;
  	}

  	public function process_template($plaintext) {
  	  return null;
  	}

  	public function publish($list_ids, $from_email, $from_name, $subject, $title, $plaintext, $html, $timestamp=null) {
  	  return null;
  	}

  	public function get_lists() {
  	  return null;
  	}

  	public function create_list( $name, $sender_name=null, $sender_email=null ) {
  	  return null;
  	}

  	public function switch_list($contact_id, $new_list_id, $keep_in_old_lists=false) {
  	  return null;
  	}

  }

}