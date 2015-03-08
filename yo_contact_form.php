<?php
/**
 * Plugin Name: Yo Contact Form
 * Plugin URI: http://www.yogadz.com
 * Description: This plugin adds a contact form.
 * Version: 1.0.0
 * Author: Gurjyot Singh
 * Author URI: http://www.yogadz.com
 * License: GPL2
 */

$yocf_plugin  = __('Yo Contact Form', 'yocf');
$yocf_options = get_option('yocf_options');
$yocf_path    = plugin_basename(__FILE__); // 'yo_contact_form/yo_contact_form.php';
$yocf_homeurl = 'http://www.yogadz.com/yo_contact_form/';
$yocf_version = '1.0.0';

// display settings link on plugin page
add_filter ('plugin_action_links', 'yocf_plugin_action_links', 10, 2);
function yocf_plugin_action_links($links, $file) {
	global $yocf_path;
	if ($file == $yocf_path) {
		$yocf_links = '<a href="' . get_admin_url() . 'options-general.php?page=' . $yocf_path . '">' . __('Settings', 'yocf') .'</a>';
		array_unshift($links, $yocf_links);
	}
	return $links;
}

//process form
function yocf_input_filter() {
	global $errors, $yocf_input_name, $yocf_input_email, $yocf_input_phone, $yocf_input_website, $yocf_input_message, $yocf_options;
	if(isset ($_POST['submit'])) {
		$errors = array();

		if(!empty ($_POST ['yocf_input_name'])) {
			$yocf_input_name = $_POST ['yocf_input_name'];
			$pattern = "/^[a-zA-Z._'-]{2,20}/";// This is a regular expression that checks if the name is valid characters.
	  if (!preg_match($pattern,$yocf_input_name)) 
	  		{ $errors[] = 'Your Name can only contain "_, A-Z or a-z" 2-20 characters long.';}
			} else {
				$errors[] = "You forgot to enter your Name.";
			}

		if(!empty ($_POST ['yocf_input_email'])) {
			$yocf_input_email = $_POST ['yocf_input_email'];
			$pattern = "/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/";// This is a regular expression that checks if the email is valid characters.
	  if (!preg_match($pattern,$yocf_input_email)) 
	  		{ $errors[] = 'Your Email is invalid, please enter a correct email.';}
			} else {
				$errors[] = "You forgot to enter your Email.";
			}

		if(!empty ($_POST ['yocf_input_message'])) {
			$yocf_input_message = $_POST ['yocf_input_message'];
			if(strlen($yocf_input_message) < 12) // This is a regular expression that checks that the message should contain a limit of characters.
	  		{ $errors[] = 'Your Message should contain more than 12 characters.';}
			} else {
				$errors[] = "You forgot to enter your Message.";
			}
		$yocf_input_phone = $_POST['yocf_input_phone'];
		$yocf_input_website = $_POST['yocf_input_website'];
		if(isset($_POST['submit'])) {
		if(!empty($errors)) {
			foreach ($errors as $msg) 
				{ 
					echo '<li>'. $msg . '</li>';
				}
			}
		}
	}
}


function yocf_process_contact_form() {
	global $errors, $yocf_input_name, $yocf_input_email, $yocf_input_phone, $yocf_input_website, $yocf_input_message, $yocf_options;
	if(empty($errors)) {

			$formcontent = "From: $yocf_input_name \n Email: $yocf_input_email \n Phone Number: $yocf_input_phone \n Website: $yocf_input_website \n Message: $yocf_input_message";
			$recipient = $yocf_options['yocf_adminmail'];
			$subject = "Contact Form";
			$mailheader = "From: $yocf_email \r\n";
			mail($recipient, $subject, $formcontent, $mailheader);
			echo "Thank You"; //Thank You Message Displayed
			echo "<br>";

			echo "<h2>Your Input:</h2>"; //Submitted form details displayed
			echo $yocf_input_name;
			echo "<br>";
			echo $yocf_input_email;
			echo "<br>";
			echo $yocf_input_phone;
			echo "<br>";
			echo $yocf_input_website;
			echo "<br>";
			echo $yocf_input_message;
	}
}

//Form Settings
register_activation_hook (__FILE__, 'yocf_add_headings');
function yocf_add_headings() {
	$user_info = get_userdata(1);
	if ($user_info == true) {
		$admin_name = $user_info->user_login;
	} else {
		$admin_name = 'Gurjyot Singh';
	}
	$site_name = get_bloginfo('name');
	$admin_mail = get_bloginfo('admin_email');
	$tmp = get_option('yocf_options');
	if(($tmp['default_options'] == '1') || (!is_array($tmp))) {
		$arr = array(
			'default_options'       => 0,
			'yocf_name'             => $admin_name,
			'yocf_website'          => $site_name,
			'yocf_adminmail'        => $admin_mail,
			'yocf_subject'          => "Message sent from your contact form."
			/*'yocf_nametext'         => __('Name (Required)', 'yocf'),
			'yocf_emailtext'        => __('Email (Required)', 'yocf'),
			'yocf_phone_numbertext' => __('Phone Number (Optional)', 'yocf'),
			'yocf_websitetext'      => __('Website (Optional)', 'yocf'),
			'yocf_messagetext'      => __('Message (Required)', 'yocf'),*/
		);
		update_option('yocf_options', $arr);
	}
}

// shortcode to display contact form
add_shortcode('yo_contact_form','yocf_shortcode');
function yocf_shortcode() {
	if (yocf_input_filter()) {
		return yocf_process_contact_form();
	} else {
		return yocf_display_contact_form();
	}
}

// template tag to display contact form
function yo_contact_form() {
	if (yocf_input_filter()) {
		echo yocf_process_contact_form();
	} else {
		echo yocf_display_contact_form();
	}
}

//display contact form
function yocf_display_contact_form() {
	global $yocf_options;
	
	echo '<form action="" method="POST">
		<label>Name (Required)</label> <input type="text" name="yocf_input_name" placeholder="Your Name"><br/><br/>
		<label>Email (Required)</label> <input type="email" name="yocf_input_email" placeholder="Your Email"><br/><br/> 
		<label>Phone Number (Optional)</label> <input type="text" name="yocf_input_phone" placeholder="Your Phone Number"><br/><br/>
		<label>Website (Optional)</label> <input type="text" name="yocf_input_website" placeholder="Your Website"><br/><br/>
		<label>Message (Required)</label> <textarea name="yocf_input_message" rows="6" cols="25" placeholder="Your Message"></textarea><br/><br/> 
		<input type="submit" name="submit" value="Submit">
		</form>';	
}



// add the options page
add_action ('admin_menu', 'yocf_add_options_page');
function yocf_add_options_page() {
	global $yocf_plugin;
	add_options_page($yocf_plugin, 'YOCF', 'manage_options', __FILE__, 'yocf_render_form');
}

// whitelist settings
add_action ('admin_init', 'yocf_init');
function yocf_init() {
	register_setting('yocf_plugin_options', 'yocf_options', 'yocf_validate_options');
}

//validate input in options
function yocf_validate_options($input) {

	if (!isset($input['default_options'])) $input['default_options'] = null;
	$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);

	$input['yocf_name']     = wp_filter_nohtml_kses($input['yocf_name']);
	$input['yocf_website']           = wp_filter_nohtml_kses($input['yocf_website']);
	$input['yocf_adminmail']    = wp_filter_nohtml_kses($input['yocf_adminmail']);
	$input['yocf_subject']        = wp_filter_nohtml_kses($input['yocf_subject']);
	
	return $input;
}

function yocf_render_form() {
	global $yocf_plugin, $yocf_options, $yocf_path, $yocf_homeurl, $yocf_version, $admin_mail; ?>

		<h2><?php echo $yocf_plugin; ?> <small><?php echo 'Version: ' . $yocf_version; ?></small></h2>

		<form method="post" action="options.php">
			<?php $yocf_options = get_option('yocf_options'); settings_fields('yocf_plugin_options'); ?>


			<h3><?php _e('Options', 'yocf'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p><?php _e('Configure the contact form..', 'yocf'); ?></p>
							<h4><?php _e('General options', 'yocf'); ?></h4>

								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="yocf_options[yocf_name]"><?php _e('Your Name', 'yocf'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="yocf_options[yocf_name]" value="<?php echo $yocf_options['yocf_name']; ?>" />
										<div class="mm-item-caption"><?php _e('How would you like to be addressed in messages sent from the contact form?', 'yocf'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php _e('Your Email', 'yocf'); ?></label></th>
										<td> <?php echo $admin_mail; ?> <input type="text" size="50" maxlength="200" name="yocf_options[yocf_adminmail]" value="<?php echo $admin_mail; ?>" />
										<div class="mm-item-caption"><?php _e('Where would you like to receive messages sent from the contact form?', 'yocf'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="yocf_options[yocf_website]"><?php _e('Your Site', 'yocf'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="" value="<?php echo $yocf_options['yocf_website']; ?>" />
										<div class="mm-item-caption"><?php _e('From where should the contact messages indicate they were sent?', 'yocf'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="yocf_options[yocf_subject]"><?php _e('Default Subject', 'yocf'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="yocf_options[yocf_subject]" value="<?php echo $yocf_options['yocf_subject']; ?>" />
										<div class="mm-item-caption"><?php _e('What should be the default subject line for the contact messages?', 'yocf'); ?></div></td>
									</tr>
								</table>
								<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'yocf'); ?>" />	
								</div>
								</form>
	
<?php } ?>
