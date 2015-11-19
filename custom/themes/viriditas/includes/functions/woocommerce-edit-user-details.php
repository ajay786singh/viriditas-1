<?php
 
add_action( 'woocommerce_edit_account_form', 'my_woocommerce_edit_account_form' );
add_action( 'woocommerce_save_account_details', 'my_woocommerce_save_account_details' );
 
function my_woocommerce_edit_account_form() {

	$user_id = get_current_user_id();
	$user = get_userdata( $user_id );

	if ( !$user )
		return;

	$billing_company = $user->billing_company;
	$billing_address_1 = $user->billing_address_1;
	$billing_address_2 = $user->billing_address_2; 
	$billing_city = $user->billing_city; 
	$billing_state = $user->billing_state; 
	$billing_postcode = $user->billing_postcode; 
	$degree_speciality = $user->degree_speciality; 
	$school_attended = $user->school_attended; 
	$year_graduated = $user->year_graduated; 
	$license = $user->license; 
  ?>
	<hr>
	<!-- Personal Information Starts -->
	<div class="form-heading">
		<h3>Personal Information</h3>
	</div>
	<div class="form-group">
		<label for="reg-clinic-name"><?php _e( 'Clinic Name', 'woocommerce' ); ?></label>
		<input name="billing_company" type="text" class="form-control login-field"	value="<?php echo esc_attr( $billing_company ); ?>" id="reg-clinic-name" />
	</div>
	<div class="form-group">
		<label for="reg-address-1">Address</label>
		<input name="billing_address_1" type="text" class="form-control" value="<?php echo esc_attr( $billing_address_1 ); ?>" id="reg-address-1" />
		<input name="billing_address_2" type="text" class="form-control" value="<?php echo esc_attr( $billing_address_2 ); ?>" id="reg-address-2" />
	</div>
	<div class="form-group">
		<label for="reg-billing-city"><?php _e( 'City', 'woocommerce' ); ?></label>
		<input name="billing_city" type="text" class="form-control login-field"	value="<?php echo esc_attr( $billing_city ); ?>" id="reg-billing-city" />
	</div>
	
	<div class="form-group">
		<div class="secondary">
			<label for="reg-province">Province/State</label>
			<select name="billing_state" id="billing_state">
				<?php 	
					$states=array("Alberta","British Columbia","Manitoba","New Brunswick","Newfoundland","Northwest Territories","Nova Scotia","Nunavut","Ontario","Prince Edward Island","Quebec","Saskatchewan","Yukon Territory");
					if(esc_attr($billing_state)==''){
						echo '<option value="">Select Province/State</option>';
					}
					foreach($states as $province){
						if(esc_attr($billing_state)==$province){	
							echo '<option selected value="'.$province.'">'.$province.'</option>';
						} else {
							echo '<option value="'.$province.'">'.$province.'</option>';	
						}
					} 
				?>
			</select>
		</div>
		<div class="secondary">
			<label for="reg-postal">Postal/Zip Code</label>
			<input name="billing_postcode" type="text" value="<?php echo esc_attr($billing_postcode);?>" id="reg-postal" />
		</div>
	</div>
	<!-- Personal Information Ends -->
	<hr>
	<!-- Educational Information Starts -->
	<div class="form-heading">
		<h3>Educational Information</h3>
	</div>
	<div class="form-group">
		<div class="secondary">
			<label for="reg-degree-speciality">Degree Speciality</label>
			<input name="degree_speciality" type="text" value="<?php echo esc_attr($degree_speciality); ?>" id="reg-degree-speciality" />
		</div>
		<div class="secondary">
			<label for="reg-school">School Attended</label>
			<input name="school_attended" type="text" value="<?php echo esc_attr($school_attended); ?>" id="reg-school" />
		</div>
	</div>	
	<div class="form-group">
		<div class="secondary">
			<label for="reg-year">Year Graduated</label>
			<input name="year_graduated" type="text" value="<?php echo esc_attr($year_graduated); ?>" id="reg-year" />
		</div>
		<div class="secondary">
			<label for="reg-license">License</label>
			<input name="license" type="text" value="<?php echo esc_attr($license); ?>" id="reg-license" />
		</div>
	</div>	
	<!-- Educational Information Ends -->
	
	
<?php 
}

function my_woocommerce_save_account_details( $user_id ) {
	update_user_meta( $user_id, 'billing_company', htmlentities( $_POST[ 'billing_company' ] ) );
	update_user_meta( $user_id, 'billing_first_name', htmlentities( $_POST[ 'account_first_name' ] ) );
	update_user_meta( $user_id, 'billing_last_name', htmlentities( $_POST[ 'account_last_name' ] ) );
	update_user_meta( $user_id, 'billing_address_1', htmlentities( $_POST[ 'billing_address_1' ] ) );
	update_user_meta( $user_id, 'billing_address_2', htmlentities( $_POST[ 'billing_address_2' ] ) );
	update_user_meta( $user_id, 'billing_city', htmlentities( $_POST[ 'billing_city' ] ) );
	update_user_meta( $user_id, 'billing_state', htmlentities( $_POST[ 'billing_state' ] ) );
	update_user_meta( $user_id, 'billing_postcode', htmlentities( $_POST[ 'billing_postcode' ] ) );
	update_user_meta( $user_id, 'degree_speciality', htmlentities( $_POST[ 'degree_speciality' ] ) );
	update_user_meta( $user_id, 'school_attended', htmlentities( $_POST[ 'school_attended' ] ) );
	update_user_meta( $user_id, 'year_graduated', htmlentities( $_POST[ 'year_graduated' ] ) );
	update_user_meta( $user_id, 'license', htmlentities( $_POST[ 'license' ] ) );
}