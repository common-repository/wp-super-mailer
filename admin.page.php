<?php

/**
	* @package WP SuperMailer
	* @author Joachim Kudish | stresslimit
	* @since 1.4
	* This file generates the admin/settings page for the plugin
	*/

class WPSuperMailerAdmin {

  public function __construct() {
    global $wpsmlr, $modules, $location, $wpsmlr_options;
    add_action('admin_menu', array(&$this, 'admin_menu'));
    add_action('admin_init', array(&$this, 'admin_init'));
    $location = admin_url('options-general.php?page=wp-super-mailer');
  }

  public function admin_init() {

    register_setting('wpsmlr_options','wpsmlr_driver');
    register_setting('wpsmlr_options','wpsmlr_driver_creds');
    register_setting('wpsmlr_options','wpsmlr_lists');
    register_setting('wpsmlr_options','wpsmlr_templates');
    register_setting('wpsmlr_options','wpsmlr_details');
    register_setting('wpsmlr_options','wpsmlr_test_mode');
    register_setting('wpsmlr_options','wpsmlr_test_list');

  }

  public function admin_menu() {

  	add_options_page('WP SuperMailer', 'WP SuperMailer', 'manage_options', 'wp-super-mailer', array(&$this, 'settings_page'));

  }

  public function template_options($option) {
    global $wpsmlr;
    foreach ($wpsmlr->retrieve_templates() as $template) {
      echo '<option value="'.$template['path'].'"';
      if ($option == $template['path']) echo ' selected';
      echo '>'.$template['name'].'</option>';
    }
  }

  public function settings_page() {
		global $wpsmlr, $modules, $location;
		$wpsmlr_driver_creds = get_option('wpsmlr_driver_creds');
		?>
		<style>.settings-error.success {background: lightGreen; border-color: Green;}</style>
		<div class="wrap">
			<h2><?php _e('WP Super Mailer', 'wp-super-mailer');?></h2>

			<?php
			// run the delete routine
			if (isset($_GET['action']) && $_GET['action'] == 'delete') {
				$wpsmlr->delete_options(); // only place we have to keep a redirect ?>
				<meta http-equiv="refresh" content="0;url=<?php echo $location; ?>&amp;deleted=true"/>
				<?php
			}

			// remove the delete $_GET var if we're saving
			if (isset($_GET['settings-updated']) && isset($_GET['deleted']))
				unset($_GET['deleted']);


			// setup messages
			$messages = array();

			if (isset($_GET['deleted']) && $_GET['deleted'] == 'true') {
				$messages[] = array(
					'class' => 'updated settings-error',
					'message' => __('All Options Deleted. You may now deactivate the plugin or start over.', 'wp-super-mailer'),
					'strong' => true,
				);
			}

			if (isset($_POST['action']) && $_POST['action'] == 'create') {
				$createid = $wpsmlr->create_list($_POST['wpsmlr_new_list']);
				if (isset($createid) && $createid) {
					$messages[] = array(
						'class' => 'updated settings-error',
						'message' => __('List successfully created', 'wp-super-mailer'),
						'strong' => true,
					);
				} else {
					$messages[] = array(
						'class' => 'settings-error',
						'message' => __('An error occurred when trying to create the list', 'wp-super-mailer'),
						'strong' => true,
					);
				}
			}

			if (!isset($wpsmlr->options['driver'])) {
				$messages[] = array(
					'class' => 'updated success settings-error',
					'message' => __('<strong>Authentication status:</strong> No mailing engine currently active.', 'wp-super-mailer'),
				);
			} elseif ($wpsmlr->is_authenticated()) {
				$messages[] = array(
					'class' => 'updated success settings-error',
					'message' => __('You are successfully authenticated with '.$wpsmlr->get_active_driver(), 'wp-super-mailer'),
				);
			} elseif (!$wpsmlr->is_authenticated()) {
				$messages[] = array(
					'class' => 'error settings-error',
					'message' => __('<strong>Authentication status:</strong> You are not currently authenticated into any mailing engine. Please check your credentials and try again.', 'wp-super-mailer'),
				);
			}

			if (defined('WP_SPRMLR_DISABLE_CAKEMAIL') && WP_SPRMLR_DISABLE_CAKEMAIL == 'disable') {
				$messages[] = array(
					'class' => 'error settings-error',
					'message' => __('Your server does not have mcrypt installed. You will not be able to use Cakemail as your mailing engine unless the PHP mcrypt extension is installed.', 'wp-super-mailer'),
				);
			}

			if (defined('WP_SPRMLR_DISABLE_NETRESULTS') && WP_SPRMLR_DISABLE_NETRESULTS == 'disable') {
				$messages[] = array(
					'class' => 'error settings-error',
					'message' => __('Your server does not have SOAP installed. You will not be able to use Net-Results as your mailing engine unless the PHP SOAP extension is installed.', 'wp-super-mailer'),
				);
			}

			// filter the messages for other plugins
			$messages = apply_filters('wpsmlr_admin_notices', $messages);

			// echo out the messages
			foreach ($messages as $message) {
				echo '<div class="'.$message['class'].'"><p>';
				if (isset($message['strong']) && $message['strong']) echo '<strong>';
				echo $message['message'];
				if (isset($message['strong']) && $message['strong']) echo '</strong>';
				echo '</p></div>';
			}

			?>

			<form method="post" action="options.php">

			<?php settings_fields('wpsmlr_options'); ?>

			<h4>Select the mailing engine you want to use</h4>

        <table class="form-table">
            <tr valign="top">
            	<th scope="row">Choose which service to use:</th>
    	        <td>
    					<?php
    						foreach ($wpsmlr->drivers as $driver) {
    							echo '<input type="radio" name="wpsmlr_driver" value="'.$driver['value'].'" id="'.$driver['id'].'"';
    		        	if ($driver['value'] == get_option('wpsmlr_driver')) echo 'checked';
    		        	echo '> <label for="'.$driver['id'].'">'.$driver['label'].'</label><br>';
    						}
    					?>
      				</td>
          	</tr>


      			<?php if (get_option('wpsmlr_driver') == 'cakemail' && (!defined('WP_SPRMLR_DISABLE_CAKEMAIL') || WP_SPRMLR_DISABLE_CAKEMAIL != 'disable')) { ?>

      			<tr valign="top">
          		<th scope="row"><h4><?php _e('Enter your API Settings for Cakemail', 'wp-super-mailer'); ?></h4></th>
          	</tr>

       	  	<tr valign="top">
							<th scope="row"><?php _e('Cakemail Login', 'wp-super-mailer');?></th>
							<td><input type="text" name="wpsmlr_driver_creds[cake_login]" id="wpsmlr_driver_creds['cake_login']" value="<?php echo $wpsmlr_driver_creds['cake_login']; ?>" /></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Cakemail Password', 'wp-super-mailer');?></th>
							<td><input type="password" name="wpsmlr_driver_creds[cake_password]" id="wpsmlr_driver_creds['cake_password']" value="<?php echo $wpsmlr_driver_creds['cake_password']; ?>" /></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Cakemail Interface Key', 'wp-super-mailer');?></th>
							<td><input type="text" name="wpsmlr_driver_creds[cake_intkey]" id="wpsmlr_driver_creds['cake_intkey']" value="<?php echo $wpsmlr_driver_creds['cake_intkey']; ?>" /></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Cakemail Interface ID', 'wp-super-mailer');?></th>
							<td><input type="text" name="wpsmlr_driver_creds[cake_intid]" id="wpsmlr_driver_creds['cake_intid']" value="<?php echo $wpsmlr_driver_creds['cake_intid']; ?>" /></td>
						</tr>



      <?php }	elseif (get_option('wpsmlr_driver') == 'netresults' && (!defined('WP_SPRMLR_DISABLE_NETRESULTS') || WP_SPRMLR_DISABLE_NETRESULTS != 'disable')) { ?>

						<tr valign="top">
							<th scope="row"><h4><?php _e('Enter your API Settings for Netresults', 'wp-super-mailer'); ?></h4></th>
						</tr>


						<tr valign="top">
							<th scope="row"><?php _e('Netresults Login', 'wp-super-mailer');?></th>
							<td><input type="text" name="wpsmlr_driver_creds[nr_login]" id="wpsmlr_driver_creds['nr_login']" value="<?php echo $wpsmlr_driver_creds['nr_login']; ?>" /></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Netresults Password', 'wp-super-mailer');?></th>
							<td ><input type="password" name="wpsmlr_driver_creds[nr_password]" id="wpsmlr_driver_creds['nr_password']" value="<?php echo $wpsmlr_driver_creds['nr_password']; ?>" /></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Netresults Account ID', 'wp-super-mailer');?></th>
							<td ><input type="text" name="wpsmlr_driver_creds[nr_actid]" id="wpsmlr_driver_creds['nr_actid']" value="<?php echo $wpsmlr_driver_creds['nr_actid']; ?>"/></td>
						</tr>

      <?php } else { ?>

						<tr valign="top">
							<th scope="row" colspan='3'><?php _e('Please select a mailing engine above and hit save. You will then be able to configure the rest of the plugin options.', 'wp-super-mailer');?></th>
						</tr>

			<?php	}
				if ($wpsmlr->is_authenticated()) :

					$lists = $wpsmlr->get_lists();
					$lists['none'] = array('name' => 'none/don\'t send', 'id' => 'none');
					$lists = array_reverse($lists);
			?>

					<tr valign="top">
						<th scope="row" colspan='3'><h4><?php _e('Test mode (will only send to selected list)', 'wp-super-mailer'); ?></h4></th>
					</tr>
					<tr valign="top">
						<td><?php _e('Check this box to enable'); ?> <input type="checkbox" name="wpsmlr_test_mode" value="on" <? if ( get_option('wpsmlr_test_mode') == 'on' ) echo 'checked' ?>/></td>
						<td>
							<?php _e('List to use for testing', 'wp-super-mailer')?>
							<select name="wpsmlr_test_list">
							<?php foreach ($lists as $l) {
									echo '<option value="'.$l['id'].'" ';
									if ( get_option('wpsmlr_test_list') == $l['id']) echo 'selected';
									echo '>'.$l['name'].'</option>';
								} ?>
							</select>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row" colspan='3'><h4><?php _e('Assign a mailing list & template to each content type', 'wp-super-mailer');?></h4>
							<p><?php _e('If you don\'t see any lists below, try refreshing this page and/or make sure that you have lists created in your '.get_option( 'wpsmlr_driver').' dashboard', 'wp-super-mailer');?></p></th>
						</tr>

						<?php
						foreach( get_post_types('', 'objects') as $post_type ) :
							if ( in_array( $post_type->name, $wpsmlr->escape_types ) ) continue;

							$loptions = (array) get_option('wpsmlr_lists');
							$toptions = (array) get_option('wpsmlr_templates');
							$doptions = (array) get_option('wpsmlr_details');
						?>

						<tr valign="top">
						<?php $name = ucwords($post_type->name); ?>
						<?php $name = (substr($name, -1) == 's') ? $name : $name.'s'; ?>
						<th scope="row" class="wpsmlr_post_type"><?php echo $name;?>

							<td>
								<small><?php _e('List to use for mailing', 'wp-super-mailer')?></small>
								<select id="<?php echo 'wpsmlr_lists['.$post_type->name.'_listid]'?>" name="<?php echo 'wpsmlr_lists['.$post_type->name.'_listid]'?>">
								<?php foreach ($lists as $l) {
									echo '<option value="'.$l['id'].'" ';
									if ( isset($loptions[$post_type->name.'_listid']) && $loptions[$post_type->name.'_listid'] == $l['id']) echo 'selected';
									echo '>'.$l['name'].'</option>';
								} ?>
								</select>
							</td>

							<td>
								<small><?php _e('Template to use for mailing', 'wp-super-mailer')?></small>
								<select id="<?php echo 'wpsmlr_templates['.$post_type->name.'_template]'?>" name="<?php echo 'wpsmlr_templates['.$post_type->name.'_template]'?>">
									<?php $topt = get_option('wpsmlr_templates'); $topt = $topt[$post_type->name.'_template'];
									$this->template_options($topt);
								?>
								</select>
							</td>

							<td>
								<small><?php _e('From Name', 'wp-super-mailer');?></small></th>
								<input type="text" name="<?php echo 'wpsmlr_details['.$post_type->name.'_sender_name]'?>" id="<?php echo 'wpsmlr_details['.$post_type->name.'_sender_name]'?>" value="<?php echo @$doptions[$post_type->name.'_sender_name'] ?>" /></td>
							</td>

							<td>
								<small><?php _e('From Email', 'wp-super-mailer');?></small></th>
								<input type="text" name="<?php echo 'wpsmlr_details['.$post_type->name.'_sender_email]'?>" id="<?php echo 'wpsmlr_details['.$post_type->name.'_sender_email]'?>" value="<?php echo @$doptions[$post_type->name.'_sender_email'] ?>" /></td>
							</td>

							<td>
								<small><?php _e('Subject', 'wp-super-mailer');?></small></th>
								<input type="text" name="<?php echo 'wpsmlr_details['.$post_type->name.'_subject]'?>" id="<?php echo 'wpsmlr_details['.$post_type->name.'_subject]'?>" value="<?php echo @$doptions[$post_type->name.'_subject'] ?>" />
								<br><small>You can use [sitename] to show the site's name and [title] for the entry name</small></td>
							</td>
						</tr>

					<?php
						endforeach; // foreach postype
					endif; // authentication
					?>

				</table>

				<div class="clear" style="height: 25px"></div>
				<div class="alignleft" style="width: 20%">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'wp-super-mailer') ?>" />
				</div>
			</form>

			<div class="clear" style="height: 25px"></div>

			<?php if ($wpsmlr->is_authenticated() && $wpsmlr->driver->supports['create_list']) : ?>

				<h4><?php _e('Create a list', 'wp-super-mailer') ?></h4>
				<form method="POST">
					<input type="text" name="wpsmlr_new_list" id="wpsmlr_new_list" value=""/>
					<input type="hidden" name="action" value="create">
					<input type="submit" class="button" value="<?php _e('Go', 'wp-super-mailer') ?>">
				</form>

				<div class="clear" style="height: 25px"></div>

			<?php endif; ?>

			<h4><?php _e('Reset the plugin', 'wp-super-mailer') ?></h4>
			<a href="<?php echo $location.'&amp;action=delete';?>" style="color: red"><?php _e('Delete all options', 'wp-super-mailer') ?></a>


</div> <?php // end wrap ?>

<?php
} // end function


}
