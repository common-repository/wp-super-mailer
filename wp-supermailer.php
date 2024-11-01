<?php
/*
Plugin Name: WP SuperMailer
Plugin URI: http://wordpress.org/plugins/wp-super-mailer
Description: WP SuperMailer gives you the power of external mailing services so that blog and custom content types can be subscribed to and mailings automatically sent.
Author: Joachim kudish, stresslimitdesign
Version: 1.4.3
Author URI: http://jkudish.com/
*/

/**
 * @package WP SuperMailer
 * @author Joachim Kudish | stresslimit
 * @version 1.4.3
 * This file is the main plugin file - loads everything else
 */

/* ------------------------------------------------------
  ARE YOU STUCK, GETTING A PHP ERROR OR A WHITE SCREEN?
--------------------------------------------------------- */
// uncomment this line if you need to reset the plugin
// WPSuperMailer::delete_options();

/* ==================================================
 		set default constants
==================================================== */

global $wpsmlr;
include('drivers/base_driver.php');

define('WP_SPRMLR_VERSION' , '1.4.3');
define('WP_SPRMLR_PATH' , dirname(__FILE__).'/' );
define('WP_SPRMLR_URL' , plugins_url(plugin_basename(dirname(__FILE__)).'/') );

define('WP_SPRMLR_SUBJECT_PREFIX', sprintf( 'New Entry Posted on %s ', get_bloginfo('name') ) );
define('WP_SPRMLR_TEMPLATE_THE_TITLE', '{the_title}');
define('WP_SPRMLR_TEMPLATE_THE_CONTENT', '{the_content}');
define('WP_SPRMLR_TEMPLATE_THE_AUTHOR', '{the_author}');
define('WP_SPRMLR_TEMPLATE_THE_DATE', '{the_date}');
define('WP_SPRMLR_TEMPLATE_THE_PERMALINK', '{the_permalink}');
define('WP_SPRMLR_TEMPLATE_THE_IMAGE', '{the_image}');
define('WP_SPRMLR_TEMPLATE_THE_UNSUB_URL', '{unsub_url}');


/* ==================================================
 		activation/deactivation hooks
==================================================== */
register_activation_hook( __FILE__, 'activate_wpsmlr');
function activate_wpsmlr() {
    // throw a message if PHP isn't v5 or higher & deactivate the plugin
    if( floatval(phpversion()) < 5 ) {
      wp_die(__('WP-Super-Mailer requires PHP 5 or higher. Please contact your web host to upgrade. Plugin will deactivate once you refresh', 'wp-super-mailer')); exit;
      deactivate_plugins(__FILE__);
      exit;
    }

    // start the moduleCheck class
    include_once('class.phpextensions.php');
    $modules = new moduleCheck();

    // check each driver and set the global variable & option if it can't run
		$disabled = array();
    if (!in_array('mcrypt', $modules->listModules())) $disabled['cakemail'] = 'cakemail';
    if (!in_array('soap', $modules->listModules())) $disabled['netresults'] = 'netresults';
    update_option('wpsmlr_disabled_drivers', $disabled);
}

register_deactivation_hook( __FILE__, 'deactivate_wpsmlr');
function deactivate_wpsmlr() {
    $disabled = array();  // reset the disabled drivers
    update_option('$disabled', $disabled);
    delete_transient('wpsmlr_disabled_drivers'); // reset authentication
    // note: we purposely don't delete all of the plugins' options here
    // instead we provide a button in the backend to do so & a function in this file that can be run manually.
}


/* ==================================================
 		main SuperMailer class
==================================================== */

if (!class_exists('WPSuperMailer')) {
  class WPSuperMailer {

		/**
		  * an array of all drivers
		  */
  	public $drivers;

		/**
		  * reference to the currently active driver
		  */
  	public $driver;

		/**
			* holds all the plugin's options
			*/
		public $options;

		/**
		  * excluded post types
		  */
    public $escape_types;

  	public function __construct() {

			$this->drivers =  array(
				'cakemail' => array('value' => 'cakemail', 'id' => 'wpsmlr_driver_cakemail', 'label' => 'Cakemail'),
				'netresults' => array('value' => 'netresults', 'id' => 'wpsmlr_driver_netresults', 'label' => 'Net Results'),
				'off' => array('value' => 'off', 'id' => 'wpsmlr_driver_none', 'label' => 'Turn the mailing off'),
			);

			$this->options = array(
				'driver' => get_option('wpsmlr_driver'),
				'driver_creds' => get_option('wpsmlr_driver_creds'),
				'disabled_drivers' => get_option('wpsmlr_disabled_drivers'),
				'lists' => get_option('wpsmlr_lists'),
				'templates' => get_option('wpsmlr_templates'),
				'details' => get_option('wpsmlr_details'),
				'test_mode' => get_option('wpsmlr_test_mode'),
				'test_list' => get_option('wpsmlr_test_list'),
			);

			$this->escape_types = array(
				'attachment',
				'mediapage',
				'revision',
				'nav_menu_item',
				'edit'
			);

  		// disable non-functional drivers
  		$disabled = $this->options['disabled_drivers'];
  		if (@in_array('cakemail', $disabled)) unset($this->drivers['cakemail']);
      if (@in_array('netresults', $disabled)) unset($this->drivers['netresults']); unset($this->driverinfo[1]);

  		// load current driver
  		$this->init_driver();

  		if( $this->is_authenticated()) {
        // hook into each posts' publish action
  		  add_action( 'transition_post_status', array( &$this, 'publish' ), 10, 3 );
  	  }

    }

   /**
  	 * get_active_driver()
		 * returns the active drive
		 * @return (string) driver slug
		 */
  	public function get_active_driver() {
			$driver = $this->options['driver'];
  	  if ($driver && array_key_exists($driver, $this->drivers)) return $driver;
			return null;
  	}

   /**
		 * init_driver()
  	 * initializes the active driver
  	 * @return (bool) true if active
  	 */
  	protected function init_driver() {
  	  // initialize the active driver
    		$driver_slug = $this->get_active_driver();
    		if( $driver_slug && $driver_slug != 'off' ) {
    				$driver_name = sprintf( 'wp_supermailer_%s_driver', $driver_slug );
    				include( sprintf( 'drivers/%s/driver.php', $driver_slug ) );
    				$this->driver = new $driver_name();
    				return true;
    		} else {
    			return null;
    		}
  	}

   /**
		 * is_authenticated()
  	 * checks wether the current driver is authenticated
  	 * stores success in a transient
  	 * @see http://codex.wordpress.org/Transients_API
  	 * @return (bool)
  	 */
  	public function is_authenticated() {
  		if( $this->driver ) {
  			$this->driver->login();
  			$auth = get_transient('wpsmlr_authentication');
  			// if the transient isn't set, set it.
  			if ($auth == false) {
    			$authentication = $this->driver->authentication;
    			// caching the result as a transient valid for 2 hours (only if successful)
    			if ($authentication) set_transient('wpsmlr_authentication', $authentication, 60*60*2);
    			else $auth = $authentication;
        }
        // either way, return the authentication status
  			return $auth;
  		} else {
  		  return false; // return false if there isn't a driver
  		}
  	}


  	/* ==================================================
  	 		Main plugin functions
  	==================================================== */

   /**
		 * publish()
  	 * main post publish function that hooks into post_transition and sends email with current driver
  	 * @param string new post status
  	 * @param string old post status
  	 * @param mixed $post variable
  	 * @return bool
  	 */
  	public function publish( $new_status, $old_status, $post ) {
  		$lists = $this->options['lists'];

  		// get the id of the list for the current post type
			if (isset($lists[$post->post_type.'_listid']))
  			$list_ids[$post->post_type] = $lists[$post->post_type.'_listid'];

      // if test mode is on, remove all lists and set testing list
      if (isset($this->options['test_mode']['status']) && $this->options['test_mode']['status'] == 'on') {
        $list_ids = null; // erase all others
        $lists_ids = array();
        $list_ids['testing'] = $this->options['test_mode']['list'];
      }

      // is this post eligible? yes? go on.
  		if( $new_status == 'publish' && $new_status != $old_status  && !in_array($post->post_type, $this->escape_types) && $post->post_type != 'revision' && isset($list_ids) && $list_ids != 'none' ) {

  	    // set the sender name [filterable per post type]
  			$details = $this->options['details'];
  			if ($details[$post->post_type.'_sender_name'])
  			  $from_name = apply_filters('wpsmlr_'.$post->post_type.'_sender_name', $details[$post->post_type.'_sender_name'], $details[$post->post_type.'_sender_name']);
  			else $from_name = get_option('blogname');

  			// set the sender email [filterable per post type]
  			if ($details[$post->post_type.'_sender_email'])
  			  $from_email = apply_filters('wpsmlr_'.$post->post_type.'_sender_email', $details[$post->post_type.'_sender_email'], $details[$post->post_type.'_sender_email']);
  			else $from_email = get_option('admin_email');

  			// set the post title [filterable per post type]
  			$title = apply_filters('wpsmlr_'.$post->post_type.'_title', $post->post_title, $post->post_title);

  			// get the post content [filterable per post type]
  			$get_content = apply_filters('the_content', $post->post_content);
  			$content = apply_filters('wpsmlr_'.$post->post_type.'_content', $get_content, $get_content);

        // get the user info + provide support for the coauthors
        if (isset($_POST['coauthors']) && is_array($_POST['coauthors'])) {
          $coauthors = $_POST['coauthors'];
          $author_string = '';
          $total = count($coauthors);
          $i = 0;
          foreach ($coauthors as $coauthor) {
            $i++;
            $userinfo = get_user_by('login', $coauthor);
            $author_string .= $userinfo->user_firstname.'&nbsp;'.$userinfo->user_lastname;
            $author_string .= ($i == $total) ? '' : ' and ';
          }
        } else {
          $user_id = $post->post_author;
          $userinfo = get_userdata($user_id);
          $author_string = $userinfo->user_firstname.'&nbsp;'.$userinfo->user_lastname;
        }

        // get the author name [filterable per post type]
  			$author = apply_filters('wpsmlr_'.$post->post_type.'_author', $author_string, $user_id);

        // set the date [filterable per post type]
  			$date = apply_filters('wpsmlr_'.$post->post_type.'_date', get_post_time('l F j Y', false, $post->ID), $post->ID);

        // get the post thumbnail [filterable per post type]
  			$image = apply_filters('wpsmlr_'.$post->post_type.'_image', get_the_post_thumbnail($post->ID, 'medium', array('class' => 'aligncenter'), get_the_post_thumbnail($post->ID), $post->ID));

  			// get the permalink
  			$permalink = get_permalink($post->ID);

  			// get the subject or get the default one
  			$subject = ($details[$post->post_type.'_subject'] ? $details[$post->post_type.'_subject'] : WP_SPRMLR_SUBJECT_PREFIX);

        // replace shorcodes in subject or appent post title if default
  			if ( $details[$post->post_type.'_subject']) $subject = str_replace('[sitename]', get_option('blogname'), $subject);
  			if ( $details[$post->post_type.'_subject']) $subject = str_replace('[title]', $title, $subject);
        else $subject .= ' '.$title;

  			// get the plaintext version of this post [todo: polish this]
  			$plaintext = $this->get_plaintext($post->ID);

  			// apply content filters
  			$html = $this->get_template($post->post_type);
        $html = str_replace('{site_url}', site_url(), $html);
  			$html = str_replace(WP_SPRMLR_TEMPLATE_THE_TITLE, $title, $html);
  			$html = str_replace(WP_SPRMLR_TEMPLATE_THE_AUTHOR, $author, $html);
  			$html = str_replace(WP_SPRMLR_TEMPLATE_THE_DATE, $date, $html);
  			$html = str_replace(WP_SPRMLR_TEMPLATE_THE_IMAGE, $image, $html);
  			$html = str_replace(WP_SPRMLR_TEMPLATE_THE_PERMALINK, $permalink, $html);
  			$html = str_replace(WP_SPRMLR_TEMPLATE_THE_CONTENT, $content, $html);

        // strip tags normally
  			if( method_exists( $this->driver, 'process_template' ) )
  				$html = $this->driver->process_template($html);
        $html = strip_tags($html, '<html><body><style><div><a><span><br><font><p><h1><h2><h3><h4><h5><h6><img><hr>');

				// strip php comments
				$html = preg_replace('!/\*.*?\*/!s', '', $html);
				$html = preg_replace('/\n\s*\n/', "\n", $html);

        // set timestamp
  			$timestamp = strtotime($post->post_date);

  			// send the info to driver
  			$this->driver->publish( $list_ids, $from_email, $from_name, $subject, $title, $plaintext, $html, $timestamp );
  			return true;
  		} else {
  			return false;
  		}
  	}

   /**
		 * subscribe()
  	 * main subscibe function that adds an e-mail to a list with the current driver
  	 * @param string $email email address of subscriber
  	 * @param mixed $list_ids [string or array] list id(s) to put the user in
  	 * @param array $args extra args for subscriber
  	 * @return int id of import process or false if email is invalid
  	 */
  	public function subscribe( $email, $list_ids=null, $args=null ) {
  	  if (is_email($email)) {
      	if ($list_ids) {
      		$list_ids = (array) $list_ids;
      		foreach($list_ids as $list_id) {
      	    $return = $this->driver->subscribe( $email, $list_id, $args );
      		}
      	} else {
      	  $return = $this->driver->subscribe( $email, null, $args );
      	}
      } else {
        $return = false;
      }
      return $return;
    }

   /**
		 * create_contact()
  	 * create a contact (only available for net-results)
  	 * @param array $args subscriber arguments
  	 * @return mixed contact_id or null
  	 */
  	public function create_contact($args = array()) {
  	  if ($this->driver) {
  	    $existing_contact = $this->driver->check_contact($args['contact_email_address']); // check if the contact already exists, if yes, returns id
  	    if (is_int($existing_contact)) // if exists, update it
  	      $contact_id = $this->driver->update_contact($existing_contact, $args);
  	    else // if not, create it
  	      $contact_id = $this->driver->create_contact($args);

  	    return $contact_id;
  	  } else  {
  	    return null;
  	  }
  	}

   /**
		 * get_lists()
  	 * get list ids from driver
  	 * @return array with all lists
  	 */
  	public function get_lists() {
  		if($this->driver)
  			return $this->driver->get_lists();
  	}

   /**
		 * create_list()
  	 * create a list (only available for net-results)
  	 * @param string $name name of new list
  	 * @param string $from_name default from name
  	 * @param string $from_email default from email
  	 * @return mixed new list id or null
  	 */
  	public function create_list( $name, $from_name=null, $from_email=null ) {
  		if ($this->driver)
  			return $this->driver->create_list( $name, $from_name, $from_email );
  		else
  			return null;
  	}

   /**
		 * @todo: complete this method or get rid of it
		 * get_
  	 * get list id from list name
  	 * @param string $name name of list to get id
  	 * @return int list id
  	 * [todo: revert this one back]
  	 */
    // public function get_list_id_by_list_name($name) {

   /**
		 * switch_list()
  	 * put / move a contact into a new list
  	 * @param int $contact_id id of the contact to move
  	 * @param int $new_list_id new list id to put contact in
  	 * @param bool $keep_in_old_lists keep them in the old lists or not
  	 * @return int list id
  	 */
  	public function switch_list($contact_id, $new_list_id, $keep_in_old_lists=false) {
  	  if ($this->driver)
  	    return $this->driver->switch_list($contact_id, $new_list_id, $keep_in_old_lists);
  	  else
  	    return null;
  	}

   /**
		 * import_status()
  	 * get the status of an import process (only available for net-results)
  	 * @param int $import_id id of the import process
  	 * @return mixed import status
  	 */
  	public function import_status($import_id) {
  	  if ($this->driver)
  	    return $this->driver->import_status($import_id);
  	}


  	/* ==================================================
  	 	 	Utility functions
  	==================================================== */

   /**
		 * retrieve_templates()
  	 * get the templates to use for mailing
  	 * @return array templates with their info
  	 */
  	public function retrieve_templates() {
      $templates = array();
    	$default_headers = array(
				'WPSMLRtname' => 'WPSMLR Template Name',
				'WPSMLRttype' => 'WPSMLR Template Type',
			);
      $wpsmlr_temp_dir = TEMPLATEPATH.'/wpsmlr_templates/';
      $scandir = @scandir($wpsmlr_temp_dir);
      if ($scandir) {
        $names_to_ignore = array('.', '..', '.svn');
        $i = 1;
        foreach ($scandir as $file) {
          if (!in_array($file, $names_to_ignore)) { // ignore a few names
      	    $fdata = get_file_data( $wpsmlr_temp_dir.$file, $default_headers);
      	    $name = $fdata['WPSMLRtname'];
      	    $type = $fdata['WPSMLRttype'];
      	    $templates['custom-'.$i] = array('name' => $name, 'path' => $wpsmlr_temp_dir.$file, 'type' => $type);
      	    $i++;
      	  }
      	}
      }

      // set the 2 default
      $templates['html'] = array('name' => 'HTML Default', 'path' => WP_SPRMLR_PATH.'template-html.php', 'type' => 'html');
      $templates['plaintext'] = array('name' => 'Plaintext Default', 'path' => WP_SPRMLR_PATH.'template-plain.php', 'type' => 'plaintext');

      return $templates;
  	}

   /**
		 * template_type()
  	 * get the template type
  	 * @param array $current_template the template
  	 * @return string the type (html or plaintext)
  	 */
  	public function template_type($current_template) {
  	  $templates = $this->retrieve_templates();
  	  foreach ($templates as $template) {
  	    if ($template['path'] == $current_template)
  	      return $template['type']; // return the type
  	  }
  	}

   /**
		 * get_template()
  	 * get the template for post type
  	 * @param array $post_type
  	 * @return string the contents of the template
  	 */
  	public function get_template($post_type) {
  		$toption = $this->options['templates'];
  		$toption = $toption[$post_type.'_template'];
  		if($toption)
  		  $template = $toption;
  		elseif(is_file(TEMPLATEPATH.'/wpsmlr_templates/template-'.$post_type.'.php'))
  		  $template = TEMPLATEPATH.'/wpsmlr_templates/template-'.$post_type.'.php';
  		elseif(is_file(TEMPLATEPATH.'/wpsmlr_templates/template.php'))
  		  $template = TEMPLATEPATH.'/wpsmlr_templates/template.php';
  		else
  			$template = WP_SPRMLR_PATH.'template-html.php';
  		$template = file_get_contents($template);
  		return $template;
  	}

   /**
		 * get_plaintext()
  	 * get plaintext version of the post
  	 * @param int $post_id the id of the post
  	 * @return string the plaintext version of the post
  	 * @todo polish this up
  	 */
  	public function get_plaintext($post_id) {
  	  $post = get_post($post_id);
  		return $post->post_content;
  	}

   /**
		 * get_list_id_for_post_type()
  	 * get list id for post type
  	 * @param string $post_type the post type
  	 * @return int the id of the list
  	 */
  	public function get_list_id_for_post_type( $post_type ) {
  		$all_lists = $this->options['lists'];
  		if( isset( $all_lists[$post_type.'_listid'] ) && $all_lists[$post_type.'_listid'] != none )
  			return $all_lists[$post_type.'_listid'];
  		else return null;
  	}

   /**
		 * delete_options()
  	 * deletes all plugin options
  	 * called from admin page or manually
  	 * @return null
  	 */
  	public function delete_options() {
  		global $wpdb;
  		$wpdb->query(" DELETE FROM $wpdb->options WHERE `option_name` LIKE '%wpsmlr_%%'");
  	}

  } // end class

} // end !class_exists

if (class_exists('WPSuperMailer')) {
  // Init the main class - set at 999 to make sure other plugins run first
  add_action( 'wp_loaded' , 'wpsupermailer_init', 999 );
  function wpsupermailer_init() {
  	global $wpsmlr;
  	$wpsmlr = new WPSuperMailer();
		if (is_admin()) { // load the admin page
	  	require_once('admin.page.php');
			new WPSuperMailerAdmin();
		}
  }
} // end class_exists