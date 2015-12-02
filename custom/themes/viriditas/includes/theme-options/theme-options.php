<?php
/**
 * Tabbed Settings Page
 */

add_action( 'init', 'hype_admin_init' );
add_action( 'admin_menu', 'hype_settings_page_init' );

function hype_admin_init() {
	$settings = get_option( "dosage_information" );
	if ( empty( $settings ) ) {
		$settings = array(
			'hype_intro' => 'Some intro text for the home page',
			'hype_tag_class' => false,
			'hype_ga' => false
		);
		add_option( "dosage_information", $settings, '', 'yes' );
	}	
}

function hype_settings_page_init() {
	$settings_page = add_options_page( ' Dosage Information', ' Dosage Information', 'administrator', 'dosage-information', 'hype_settings_page' );
	add_action( "load-{$settings_page}", 'hype_load_settings_page' );
}

function hype_load_settings_page() {
	if ( $_POST["hype-settings-submit"] == 'Y' ) {
		check_admin_referer( "hype-settings-page" );
		hype_save_theme_settings();
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('options-general.php?page=dosage-information&'.$url_parameters));
		exit;
	}
}

function hype_save_theme_settings() {
	global $pagenow;
	$settings = get_option( "dosage_information" );
	if ( $pagenow == 'options-general.php' && $_GET['page'] == 'dosage-information' ){ 
		if ( isset ( $_GET['tab'] ) )
	        $tab = $_GET['tab']; 
	    else
	        $tab = 'settings'; 

	    switch ( $tab ){ 
			case 'settings' : 
				$settings['dosage_chart']	  = stripslashes($_POST['dosage_chart']);
				$settings['dosage_permission']	  = stripslashes($_POST['dosage_permission']);
				$settings['safety_chart']	  = stripslashes($_POST['safety_chart']);
			break;
	    }
	}
	
	if( !current_user_can( 'unfiltered_html' ) ){
		if ( $settings['dosage_chart'] ) {
			$settings['dosage_chart'] = stripslashes( esc_textarea( wp_filter_post_kses( $settings['dosage_chart'] ) ) );
		}
			
		if ( $settings['dosage_permission'] ) {
			$settings['dosage_permission'] = stripslashes( esc_textarea( wp_filter_post_kses( $settings['dosage_permission'] ) ) );
		}
		
		if ( $settings['safety_chart'] ) {
			$settings['safety_chart'] = stripslashes( esc_textarea( wp_filter_post_kses( $settings['safety_chart'] ) ) );
		}
			
		
	}

	$updated = update_option( "theme_options", $settings );
}

function hype_admin_tabs( $current = 'homepage' ) { 
    $tabs = array( 'settings' => 'Settings'); 
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=theme-settings&tab=$tab'>$name</a>";
        
    }
    echo '</h2>';
}

function hype_settings_page() {
	global $pagenow;
	$settings = get_option( "theme_options" );
	?>
	<div class="wrap">
		<h2>Dosage Information</h2>
		
		<?php
			if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Dosage Information Settings updated.</p></div>';
			
			if ( isset ( $_GET['tab'] ) ) hype_admin_tabs($_GET['tab']); else hype_admin_tabs('settings');
		?>
 
		<div id="poststuff">
			<form method="post" action="<?php admin_url( 'options-general.php?page=dosage-information' ); ?>">
				<?php
				wp_nonce_field( "hype-settings-page" ); 
				
				if ( $pagenow == 'options-general.php' && $_GET['page'] == 'dosage-information' ){ 
				
					if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
					else $tab = 'settings'; 
					
					echo '<table class="form-table">';
					switch ( $tab ){
						case 'settings' : 
							?>
							<tr>
								<th><label for="dosage_chart">Dosage Chart URL</label></th>
								<td>
									<input type="text" size="50" name="dosage_chart" id="dosage_chart" value="<?php echo esc_html( stripslashes( $settings["dosage_chart"] ) ); ?>"/><br>
									<span class="description">Enter dosage chart url.</span>
								</td>
							</tr>
							<tr>
								<th><label for="dosage_permission">Enter where to show dosage chart link</label></th>
								<td>
									<input type="text" size="50" name="dosage_permission" id="dosage_permission" value="<?php echo esc_html( stripslashes( $settings["dosage_permission"] ) ); ?>"/><br>
									<span class="description">Please enter product categories separated with comma(,) to show dosage chart.</span>
									<br>
									<?php
										$categories = get_terms( 'product_cat', 'orderby=count&hide_empty=0' );
										if($categories) {
											$array="";
											foreach($categories as $category) {
												$array[]=$category->name." (".$category->term_id.")";
											}
											echo implode(" <br>",$array);
										}
									?>
								</td>	
							</tr>
							
							<tr>
								<th><label for="safety_chart">Safety Chart URL</label></th>
								<td>
									<input type="text" size="50" name="safety_chart" id="safety_chart" value="<?php echo esc_html( stripslashes( $settings["safety_chart"] ) ); ?>"/><br>
									<span class="description">Enter safety chart url.</span>
								</td>
							</tr>
							<?php
						break;
					}
					echo '</table>';
				}
				?>
				<p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
					<input type="hidden" name="hype-settings-submit" value="Y" />
				</p>
			</form>
		</div>

	</div>
<?php
}
?>