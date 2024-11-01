<?php

/**
	* @package WP SuperMailer
	* @author Joachim Kudish | stresslimit
	* @since 0.1
	* This file is the Net-Results driver
	* @todo document the methods in this class
	*/


/* ==================================================
 		set NetResults constants
==================================================== */

// make sure that we are not caching in case net-results changes their API
ini_set('soap.wsdl_cache_enabled', '0');

if (!class_exists('wp_supermailer_netresults_driver')) {
 
	$creds = get_option('wpsmlr_driver_creds');

  define('WP_WPSMLR_NR_SUBJECT_PREFIX', 'There is a new post at '.get_bloginfo('name').': '); // default subject prefix
  define('WP_WPSMLR_NR_ACCOUNT_ID', trim($creds['nr_actid']));
  define('WP_WPSMLR_NR_LOGIN', $creds['nr_login']);
  define('WP_WPSMLR_NR_PASSWORD', $creds['nr_password']);


  /* ==================================================
   		put the tracking embed code in the footer
  ==================================================== */
	function wp_netresults_footer() {
		echo '
	<!-- START Net-Results Capture Code -->
	<script id="__maSrc" type="text/javascript" data-pid="'.WP_WPSMLR_NR_ACCOUNT_ID.'">
		(function () {
		var d=document,t=\'script\',c=d.createElement(t),s=(d.URL.indexOf(\'https:\')==0?\'s\':\'\'),p;
		c.type = \'text/java\'+t;
		c.src = \'http\'+s+\'://\'+s+\'c.cdnma.com/apps/capture.js\';
		p=d.getElementsByTagName(t)[0];p.parentNode.insertBefore(c,p);
		}());
	</script>
	<!-- END Net-Results Capture Code -->';
	}
	add_action('wp_footer', 'wp_netresults_footer');


  /* ==================================================
  	Class that wraps the SOAP requests to Net-Results API

  	Documentation at:
  	https://apps.net-results.com/soap/v1/documentation.php
  ==================================================== */

  class wp_supermailer_netresults_driver extends wp_supermailer_driver {

  	protected $soap;
  	protected $url = 'https://apps.net-results.com/soap/v1/NRAPI.wsdl';
  	public $authentication = false;
  	public $credentials;
		public $supports;
  	
	
  	function __construct() {	

			$this->credentials = array(
				array('name' => 'Net Results Login', 'id' => 'nr_login', 'type' => 'text'),
				array('name' => 'Net Results Password', 'id' => 'nr_password', 'type' => 'password'),
				array('name' => 'Net Results Account ID', 'id' => 'nr_actid', 'type' => 'text'),
			);
			
			$this->supports = array(
				'create_list' => true,
			);
			
  	}
	
  	protected function call($method, $args=false, $args2=false) {
  		if(!$this->authentication)
  			$this->login();
  		if ($args && $args2) {
  			return $this->soap->$method($args,$args2); 
  		}	
  		elseif ($args) {
  			return $this->soap->$method($args);
  		}	
  		else { 
  			return $this->soap->$method();
  		}	
  	}

  	public function login() {
  		if (WP_WPSMLR_NR_PASSWORD != '') {
  			$this->soap = new SoapClient($this->url, array(
  			'login' => WP_WPSMLR_NR_LOGIN,
  			'password' => WP_WPSMLR_NR_PASSWORD,
  			'features' => SOAP_SINGLE_ELEMENT_ARRAYS + SOAP_USE_XSI_ARRAY_TYPE,
  			'exceptions' => false,
  			'trace' => 1,
  			));
			
  			$check = $this->soap->getSessionId();
  					
  			if (is_soap_fault($check)) {
  				$this->authentication = false;
  			}
  			else {
  				$this->authentication = true;
  			}
  		}
  	}

		public function subscribe($email, $list_id=null, $args = array(), $remove_list_ids = array()) {
			$defaults = array(
				'contact_email_address' => $email,
				// 'contact_email_address' => '',
				// 'contact_first_name' => '',
				// 'contact_last_name' => '',
				// 'company_name' => '',
				// 'contact_address_1' => '',
				// 'contact_address_2' => '',
				// 'contact_mobile_phone' => '',
				// 'contact_home_phone' => '',
				// 'contact_work_phone' => '',
				// 'contact_fax' => '',
				// 'city_name' => '',
				// 'contact_postalcode' => '',
				// 'country_name' => '',
				// 'state_name' => '',
				// 'contact_lead_score' => '',
				// 'contact_twitter_username' => ''
				);	

			// pull the name of form args [for tracking], and unset to leave only the contact fields
			if(isset($args['form_name'])) {
				$form_name = $args['form_name'];
				unset($args['form_name']);
			} else {
				$form_name = 'wpsmlr_dstoutput';
			}

			// get contact id of the current visitor from cookie [set by net-results js]
			if(!empty($_COOKIE['__mauuid'])) {
				$args['UUID'] = $_COOKIE['__mauuid'];
			}

			// merge args into default values [API function crashes without company_name]
			if(count($args)>0)
				$args = array_merge($defaults, $args);
			else
				$args = $defaults;

			// parse fields to send as contact import mappings
			foreach($args as $k=>$v) {
				$mappings[] = array(
					'csv_header' => $k,
					'nr_contact_attribute' => ( $k=='UUID' ? 'Unique Visitor UUID' : ucwords(str_replace(array('contact_','_'),array('',' '),$k)) )
				);
			}

			$remove_list_ids = (count($remove_list_ids)>0 ? $remove_list_ids : null); // make remove list ids null if empty array
			$list_id = (isset($list_id) ? array($list_id) : null); // make remove list ids null if empty array

			// set up args keys and values into separate arrays to be parsed into our pseudo-csv file
			$arrHeadings = array_keys($args);
			$arrContact = array_values($args);
			$arrRows = array($arrContact);
			array_unshift($arrRows, $arrHeadings);

			// write csv into memory so we can fputcsv and fread
			$hanMem = fopen('php://memory', 'w+');
			foreach ($arrRows as $csvLine) {
				fputcsv($hanMem, $csvLine);
			}
			rewind($hanMem);
			$strCSV = fread($hanMem, 8388608); // Up to 8MB

			//now we have a string of a csv, base64 encode it
			$strBase64CSVContents = base64_encode($strCSV);
			$xml = array(
				'file' => array(
					'filename' => $form_name.'_'.time().'.csv', // filename could be the form name for later segmenting
					'contents' => $strBase64CSVContents
					),
				'add_email_list_ids' => $list_id,
				'remove_email_list_ids' => $remove_list_ids,
				'overwrite_duplicates' => 1,
				'clobber_lists' => 0,
				'contact_import_mappings' => array(
					'mappings' => $mappings
					),
				'notification_recipients' => array(
					'logs@stresslimitdesign.com'
					)
				);
			$return = $this->call('submitContactImport',$xml);
	    return $return;
		}

  	public function create_contact($args) {
      $return = $this->call('createContact', $args);
      return $return;
    } 
  
    public function update_contact($contact_id, $args) {
      $return = $this->call('updateContact', $contact_id, $args);
      return $return;
    } 
  
    public function check_contact($email) {
      $check = $this->call('getContactIdByContactEmailAddress', $email);
      $return = (is_int($check->contactId) ? $check->contactId : false);
      return $return;
    }
  
    public function import_status($import_id) {
      $return = $this->call('getContactImportStatus', $import_id);
      return $return;
    }  

  	public function unsubscribe($email, $list_id) {
  		$intContactId = $this->call('getContactIdByContactEmailAddress',$emails);
  		$this->call('unassociateContactIdsFromEmailListIds',array($intContactId), array($list_id));
  	}

  	public function process_template($plaintext) {
  		return $plaintext;
  	}

  	public function publish($list_ids, $from_email, $from_name, $subject, $title, $plaintext, $html, $timestamp=null) {
  		// if list_ids is a string, make it into an array, else it's already an array
  		if (is_string($list_ids) || is_int($list_ids)) {
  		  $list_id = $list_ids;
  		  $list_ids = array();
  		  $list_ids[0] = $list_id;
  		}  
		
  		// create the campaign
  		$xml = array(
  			'campaign_name' => $title.' '.date("r"),
  			'campaign_description' => $title,
  			'campaign_launch_date' => ( $timestamp!=null ? date("c",$timestamp) : date("c") )
  			);
  		$intCampaignId = $this->call('createCampaign',$xml);

  		// create action group with many lists
  		$xml = array(
  			'campaign_action_group_name' => 'Send Email',
  			'campaign_id' => $intCampaignId,
  			'email_list_ids' => $list_ids,
  			);
  		$intCampaignActionGroupId = $this->call('createRootCampaignActionGroupWithEmailListIdsCondition',$xml);
    
  		// create email
  		$xml = array(
  			'email_name' => $title.' '.date("r"),
  			'email_content_text' => $plaintext,
  			'email_content_html' => $html,
  			'email_reply_to_address' => $from_email,
  			'email_reply_to_label' => $from_name,
  			'email_subject' => $subject,
  			'role_id' => null,
  			);
  		$intEmailId = $this->call('createEmail',$xml);

  		// pair the email with the action group
  		$xml = array(
  			'campaign_action_group_id' => $intCampaignActionGroupId,
  			'email_id' => $intEmailId
  			);
  		$intCampaignActionId = $this->call('associateEmailIdWithCampaignActionGroupId',$xml);

  		// launch the campaign
  		$launch = $this->call('launchCampaign',$intCampaignId);

  	}

  	public function get_lists() {
  		$lists = $this->call('getEmailLists');
  		foreach ($lists as $list) { 
  			$return[] = array('id' => $list->email_list_id, 'name' => $list->email_list_name);
  		}
  		return $return;
  	}

  	public function create_list( $name, $sender_name=null, $sender_email=null ) {
  		$xml = array(
  			'email_list_name' => $name,
  			'role_id' => null,
  		);
  		$list_id = $this->call('createEmailList',$xml);
  		return $list_id;
  	}
	
  	public function switch_list($contact_id, $new_list_id, $keep_in_old_lists=false) {
  		if ($keep_in_old_lists === false) {
  		  $lists = $this->call('getContactEmailLists', $contact_id);
  		  $remove = $this->call('unassociateContactIdsFromEmailListIds', array($contact_id), $lists);
  		} 
  		$add = $this->call('associateContactIdsWithEmailListIds', array($contact_id), array($new_list_id)); 
  		$return = array('added' => $add, 'removed' => $remove);
  		return $return;
  	}


  } // end class

} // end if class does not exist