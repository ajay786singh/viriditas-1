<?php
function get_practioners() {
	global $wpdb, $table_prefix;
	$output="";
	$table=$table_prefix."app_workers";
	$query="SELECT ID FROM $table";
	$current_value=$_REQUEST['app_select_workers'];
	$practioners=$wpdb->get_results($query);
	if(count($practioners)>0) {
		$output="<select name='app_select_workers' class='app_select_workers'>";
			if($current_value =='') {
				$output.="<option value=''>Please select a practioner</option>";	
			}
		foreach($practioners as $practioner) {
			$user_name="";
			$userdata = get_userdata( $practioner->ID );
			if (is_object($userdata) && !empty($userdata->app_name)) {
				$user_name = $userdata->app_name;
			}
			if (empty($user_name)) {
				if ( !$php )
					$user_name = $userdata->user_login;
				else
					$user_name = $userdata->display_name;

				if ( !$user_name ){
                                        $first_name = get_user_meta($worker, 'first_name', true);
                                        $last_name = get_user_meta($worker, 'last_name', true);
					$user_name = $first_name . " " . $last_name;
                                }
				if ( "" == trim( $user_name ) )
					$user_name = $userdata->user_login;
			}
			if($current_value ==$practioner->ID) {
				$output.="<option selected value='".$practioner->ID."'>".$user_name."</option>";
			} else {
				$output.="<option value='".$practioner->ID."'>".$user_name."</option>";
			}
		}
		$output.="</select>";
	}
	return $output;
}
//app_service_id

function get_services() {
	global $wpdb, $table_prefix;
	$output="";
	$table=$table_prefix."app_services";
	$query="SELECT ID,name FROM $table";
	$current_value=$_REQUEST['app_service_id'];
	$practioner=$_REQUEST['app_select_workers'];
	if($practioner) {
		$table=$table_prefix."app_workers";
		$query="SELECT * FROM $table where ID=".$practioner;
		$app_services=$wpdb->get_results($query);
			foreach($app_services as $services) {
				$services=$services->services_provided;
				$services = array_filter( explode( ':' , ltrim( $services , ":") ) );
				if($services) {
					$output="<select name='app_service_id' class='app_service_id'>";
					foreach($services as $service) {
							$service=get_service_by_id($service);
							if($current_value ==$service->ID) {
								$output.="<option value='".$service->ID."' selected>".$service->name."</option>";
							}else {
								$output.="<option value='".$service->ID."'>".$service->name."</option>";
							}
					}	
					$output.="</select>";
				}
			}
	} else {
		$app_services=$wpdb->get_results($query);
			if(count($app_services)>0) {
				$output="<select name='app_service_id' class='app_service_id'>";
					if($current_value =='') {
						$output.="<option value=''>Please select a service</option>";	
					}
					foreach($app_services as $service) {
						if($current_value ==$service->ID) {
							$output.="<option value='".$service->ID."' selected>".$service->name."</option>";
						}else {
							$output.="<option value='".$service->ID."'>".$service->name."</option>";
						}
					}
				$output.="</select>";
			}
	}
	return $output;
}

function get_service_by_id($id) {
	global $wpdb, $table_prefix;
	$output="";
	$table=$table_prefix."app_services";
	$query="SELECT ID,name FROM $table where ID=".$id;
	$service=$wpdb->get_row($query);
	if(count($service)>0) {
		$output = $service;
	}
	return $output;
}

add_action( 'wp_ajax_get_services_by_practioner', 'get_services_by_practioner' );
add_action( 'wp_ajax_nopriv_get_services_by_practioner', 'get_services_by_practioner' );

function get_services_by_practioner() {
	global $wpdb, $table_prefix;
	$output="";
	$table=$table_prefix."app_workers";
	$current_value=$_REQUEST['app_select_workers'];
	$query="SELECT * FROM $table where ID=".$current_value;
	$app_services=$wpdb->get_results($query);
	if(count($app_services)>0) {
		foreach($app_services as $services) {
			$services=$services->services_provided;
			$services = array_filter( explode( ':' , ltrim( $services , ":") ) );
			if($services) {
				//$output="<select name='app_service_id'>";
				foreach($services as $service) {
						$service=get_service_by_id($service);
						$output.="<option value='".$service->ID."'>".$service->name."</option>";
				}	
				//$output.="</select>";
			}
		}
	}
	echo $output;
	die(0);
}
?>