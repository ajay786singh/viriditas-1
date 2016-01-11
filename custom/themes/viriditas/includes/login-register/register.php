<?php
class Hype_registration_form {
    private $username;
    private $email;
    //private $password;
    private $website;
    private $first_name;
    private $last_name;
    private $nickname;
    private $bio;
    function __construct() {
		add_shortcode('hype_registration_form', array($this, 'shortcode'));
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
    }
    function scripts() {
		//wp_enqueue_script('countries-js', get_template_directory_uri()."/includes/login-register/js/countries.js", __FILE__);
    }
    public function registration_form()	{
	?>
		
        <form method="post" class="account-form"  autocomplete="off" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
            <div class="login-form">
				<div class="form-heading">
					<h3>Login Information</h3>
				</div>
                <div class="form-group">
					<div class="secondary">
						<label for="reg-fname">First Name</label>
						<input name="reg_fname" type="text" class="form-control login-field" value="<?php echo(isset($_POST['reg_fname']) ? $_POST['reg_fname'] : null); ?>" id="reg-fname"/>
					</div>
					<div class="secondary">
						<label for="reg-lname">Last Name</label>
						<input name="reg_lname" type="text" class="form-control login-field" value="<?php echo(isset($_POST['reg_lname']) ? $_POST['reg_lname'] : null); ?>" id="reg-lname"/>
					</div>
                </div>

                <div class="form-group">
					<label for="reg-name">Username</label>
					<input name="reg_name" type="text" class="form-control login-field"	value="<?php echo(isset($_POST['reg_name']) ? $_POST['reg_name'] : null); ?>" id="reg-name" required/>
                </div>
                <div class="form-group">
					<div class="secondary">
						<label for="reg-email">Email Address</label>
						<input name="reg_email" type="email" autocomplete="off"  class="form-control" value="<?php echo(isset($_POST['reg_email']) ? $_POST['reg_email'] : null); ?>" id="reg-email" required/>
					</div>
					<div class="secondary">
						<label for="reg-c-email">Confirm Email Address</label>
						<input name="reg_c_email" type="email" class="form-control" value="<?php echo(isset($_POST['reg_c_email']) ? $_POST['reg_c_email'] : null); ?>" id="reg-c-email" required/>
					</div>
                </div>
				<hr>
				<div class="form-heading">
					<h3>Personal Information</h3>
				</div>
				<div class="form-group">
					<label for="reg-clinic-name">Clinic Name</label>
					<input name="billing_company" type="text" class="form-control login-field"	value="<?php echo(isset($_POST['billing_company']) ? $_POST['billing_company'] : null); ?>" id="reg-clinic-name" />
                </div>
				<div class="form-group">
					<label for="reg-address-1">Address</label>
					<input name="billing_address_1" type="text" class="form-control" value="<?php echo(isset($_POST['billing_address_1']) ? $_POST['billing_address_1'] : null); ?>" id="reg-address-1" />
					<input name="billing_address_2" type="text" class="form-control" value="<?php echo(isset($_POST['billing_address_2']) ? $_POST['billing_address_2'] : null); ?>" id="reg-address-2" />
                </div>
				<div class="form-group">
					<label for="reg-country">Country</label>
					<select name="billing_country" id="reg-country">
						<?php if(!isset($_POST['billing_country'])){ ?>
							<option value="">Select Country</option>
							<option value="Canada">Canada</option>
						<?php }else {
							echo '<option value="Canada">Canada</option>';
						} 
						?>	
					</select>
                </div>
				<div class="form-group">
					<div class="secondary">
						<label for="reg-city">City</label>
						<input name="billing_city" type="text" value="<?php echo(isset($_POST['billing_city']) ? $_POST['billing_city'] : null); ?>" id="reg-city" />
					</div>
					<div class="secondary">
						<div class="secondary">
							<label for="reg-province">Province/State</label>
							<select name="billing_state" id="billing_state">
								<?php 
									$states=array("Alberta","British Columbia","Manitoba","New Brunswick","Newfoundland","Northwest Territories","Nova Scotia","Nunavut","Ontario","Prince Edward Island","Quebec","Saskatchewan","Yukon Territory");
									if(!isset($_POST['billing_state'])){
										echo '<option value="">Select Province/State</option>';
									}
									foreach($states as $province){
										if(isset($_POST['billing_state']) && $_POST['billing_state']==$province){	
											echo '<option selected value="'.$province.'">'.$province.'</option>';
										} else {
											echo '<option value="'.$province.'">'.$province.'</option>';
										}
									} 
								?>
								<?php if(isset($_POST['billing_state'])){ ?>
									<option value="<?php echo $_POST['billing_state'];?>"><?php echo $_POST['billing_state'];?></option>
								<?php } ?>
							</select>
						</div>
						<div class="secondary">
							<label for="reg-postal">Postal/Zip Code</label>
							<input name="billing_postcode" type="text" value="<?php echo(isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : null); ?>" id="reg-postal" />
						</div>
					</div>
                </div>
				<hr>
				<div class="form-heading">
					<h3>Educational Information</h3>
				</div>
				<div class="form-group">
					<div class="secondary">
						<label for="reg-degree">Degree Speciality</label>
						<input name="reg_degree_speciality" type="text" value="<?php echo(isset($_POST['reg_degree_speciality']) ? $_POST['reg_degree_speciality'] : null); ?>" id="reg-degree" />
					</div>
					<div class="secondary">
						<label for="reg-school">School Attended</label>
						<input name="reg_school_attended" type="text" value="<?php echo(isset($_POST['reg_school_attended']) ? $_POST['reg_school_attended'] : null); ?>" id="reg-school" />
					</div>
				</div>	
				<div class="form-group">
					<div class="secondary">
						<label for="reg-year">Year Graduated</label>
						<input name="reg_year_graduated" type="text" value="<?php echo(isset($_POST['reg_year_graduated']) ? $_POST['reg_year_graduated'] : null); ?>" id="reg-year" />
					</div>
					<div class="secondary">
						<label for="reg-license">License</label>
						<input name="reg_license" type="text" value="<?php echo(isset($_POST['reg_license']) ? $_POST['reg_license'] : null); ?>" id="reg-license" />
					</div>
				</div>	
				<input class="btn btn-primary btn-lg btn-block" type="submit" name="reg_submit" value="Register"/>
        </form>
        </div>
    <?php
    }
    function validation() {
       // if (empty($this->username) || empty($this->password) || empty($this->email)) {
		if (empty($this->username) || empty($this->email)) {
            return new WP_Error('field', 'Required form field is missing');
        }
        if (strlen($this->username) < 4) {
            return new WP_Error('username_length', 'Username too short. At least 4 characters is required');
        }
        $details = array('Username' => $this->username,
            'First Name' => $this->first_name,
            'Last Name' => $this->last_name
        );
        foreach ($details as $field => $detail) {
            if (!validate_username($detail)) {
                return new WP_Error('name_invalid', 'Sorry, the "' . $field . '" you entered is not valid');
            }
        }
        if (!is_email($this->email)) {
            return new WP_Error('email_invalid', 'Email is not valid');
        }
        if (email_exists($this->email)) {
            return new WP_Error('email', 'Email already in use');
        }
		if ($this->email !='' && ($this->email != $this->c_email)) {
            return new WP_Error('email_invalid', "Email doesn't match with confirm email.");
        }
        if (!empty($website)) {
            if (!filter_var($this->website, FILTER_VALIDATE_URL)) {
                return new WP_Error('website', 'Website is not a valid URL');
            }
        }
    }
    function registration()
    {
        $userdata = array(
            'user_login' => esc_attr($this->username),
            'user_email' => esc_attr($this->email),
            //'user_pass' => esc_attr($this->password),
            'first_name' => esc_attr($this->first_name),
            'last_name' => esc_attr($this->last_name)
        );	
		$usermeta =array(
			'billing_first_name' => esc_attr($this->first_name),
			'billing_last_name' => esc_attr($this->last_name),
			'billing_company' => esc_attr($this->billing_company),
			'billing_address_1' => esc_attr($this->billing_address_1),
			'billing_address_2' => esc_attr($this->billing_address_2),
			'billing_country' => esc_attr($this->billing_country),
			'billing_city' => esc_attr($this->billing_city),
			'billing_state' => esc_attr($this->billing_state),
			'billing_postcode' => esc_attr($this->billing_postcode),
			'degree_speciality' => esc_attr($this->degree_speciality),
			'school_attended' => esc_attr($this->school_attended),
			'year_graduated' => esc_attr($this->year_graduated),
			'license' => esc_attr($this->license),
		);
        if (is_wp_error($this->validation())) {
            echo '<div class="btn btn-block btn-lg btn-danger">';
            echo '<p>' . $this->validation()->get_error_message() . '</p>';
            echo '</div>';
        } else {
            $register_user = wp_insert_user($userdata);
			foreach($usermeta as $key => $value) {
				add_user_meta( $register_user, $key, $value, true ); 
			}
            if (!is_wp_error($register_user)) {
					echo '<div class="btn btn-block btn-lg small-loader">';
						//echo '<p><span class="">Please wait, We are processing you request..</span> </p>';
					echo '</div>';
				?>
				<script>
					window.location=welcomePageURL;
				</script>
				<?php
				$_POST = array();
            } else {
                echo '<div class="btn btn-block btn-lg btn-danger">';
                echo '<p>' . $register_user->get_error_message() . '</p>';
                echo '</div>';
            }
        }
    }
    function shortcode() {
        ob_start();
        if ($_POST['reg_submit']) {
            $this->username = $_POST['reg_name'];
            $this->email = $_POST['reg_email'];
            $this->c_email = $_POST['reg_c_email'];
            //$this->password = $_POST['reg_password'];
			//$this->c_password = $_POST['reg_c_password'];
            $this->first_name = $_POST['reg_fname'];
            $this->last_name = $_POST['reg_lname'];
            $this->billing_company = $_POST['billing_company'];
			$this->billing_address_1 = $_POST['billing_address_1'];
			$this->billing_address_2 = $_POST['billing_address_2'];
			$this->billing_country = $_POST['billing_country'];
			$this->billing_city = $_POST['billing_city'];
			$this->billing_state = $_POST['billing_state'];
			$this->billing_postcode = $_POST['billing_postcode'];
			$this->degree_speciality = $_POST['reg_degree_speciality'];
			$this->school_attended = $_POST['reg_school_attended'];
			$this->year_graduated = $_POST['reg_year_graduated'];
			$this->license = $_POST['reg_license'];
            $this->validation();
            $this->registration();
        }
        $this->registration_form();
        return ob_get_clean();
    }
}
new Hype_registration_form;
/* Function to add user fields on admin */
function add_custom_user_profile_fields( $user ) {
	//echo "<pre>";print_r( $user);echo "</pre>";
?>
	<h3><?php _e('Educational Information', 'viriditas'); ?></h3>
	
	<table class="form-table">
		<tr>
			<th>
				<label for="degree-speciality"><?php _e('Degree Speciality', 'viriditas'); ?>
			</label></th>
			<td>
				<input type="text" name="degree_speciality" id="degree_speciality" value="<?php echo esc_attr( get_the_author_meta( 'degree_speciality', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter your Degree Speciality.', 'viriditas'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="degree-speciality"><?php _e('School Attended', 'viriditas'); ?></label>
			</th>
			<td>
				<input type="text" name="school_attended" id="school_attended" value="<?php echo esc_attr( get_the_author_meta( 'school_attended', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter school attended.', 'viriditas'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="year-graduated"><?php _e('Year Graduated', 'viriditas'); ?></label>
			</th>
			<td>
				<input type="text" name="year_graduated" id="year_graduated" value="<?php echo esc_attr( get_the_author_meta( 'year_graduated', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter year graduated.', 'viriditas'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="license"><?php _e('License', 'viriditas'); ?></label>
			</th>
			<td>
				<input type="text" name="license" id="license" value="<?php echo esc_attr( get_the_author_meta( 'license', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter license.', 'viriditas'); ?></span>
			</td>
		</tr>
	</table>
<?php }

function save_custom_user_profile_fields( $user_id ) {	
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	update_usermeta( $user_id, 'degree_speciality', $_POST['degree_speciality'] );
	update_usermeta( $user_id, 'school_attended', $_POST['school_attended'] );
	update_usermeta( $user_id, 'year_graduated', $_POST['year_graduated'] );
	update_usermeta( $user_id, 'license', $_POST['license'] );
}
add_action( 'show_user_profile', 'add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_custom_user_profile_fields' );