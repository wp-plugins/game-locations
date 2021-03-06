<?php
/*
 * mstw-admin-utils.php
 * 	Utility functions for MSTW Plugin Admin
 *
 *	MSTW Wordpress Plugins
 *	Copyright (C) 2013 Mark O'Donnell
 *	Contact me at http://shoalsummitsolutions.com
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
 
 /*----------------------------------------------------------------	
 *	MSTW_ADMIN_UTILS_LOADED - DO NOT REMOVE THIS FUNCTION!
 *		This function is used by the require_once statement to figure
 *		out whether or not to load the utils.
 *---------------------------------------------------------------*/
if( !function_exists( 'mstw_admin_utils_loaded' ) ) {
 function mstw_admin_utils_loaded( ) {
		return( true );
	}
}

 /*----------------------------------------------------------------	
 *	MSTW_UTL_BUILD_CSS_RULE
 *		Simple convenience function to build css rules from $options.
 *---------------------------------------------------------------*/
if( !function_exists( 'mstw_utl_build_css_rule' ) ) { 
	function mstw_utl_build_css_rule( $options_array, $option_name, $css_rule ) {
		if ( isset( $options_array[$option_name] ) and !empty( $options_array[$option_name] ) ) {
			return $css_rule . ":" . $options_array[$option_name] . "; \n";	
		} 
		else {
			return "";
		}
	}
}
 /*----------------------------------------------------------------	
 *	MSTW_UTL_COLOR_CTRL
 *	Builds color selector controls for the admin UI
 *
 * 	Arguments:
 *		$args['id'] 	(string) ID of input field 
 *		$args['name'] 	(string) Name of input field
 *		$args['class'] 	(string) Name of input field
 *		$args['value'] 	(string) Current value of input field
 *		$args['label'] 	(string) Instructions displayed after the field
 *
 *	return - none. Control is displayed.
 *---------------------------------------------------------------*/
if( !function_exists( 'mstw_utl_color_ctrl' ) ) {	
	function mstw_utl_color_ctrl( $args ) { 
		$id = $args['id'];
		$name = $args['name'];
		//$class = $args['class'];
		$value = $args['value'];
		$label = $args['label'];
		
		echo "<input type='text' id='$id' name='$name' value='$value' /> \n";
		//echo "<label for='$id'>$label</label> \n";
		echo ( $label != '' ? "<br /><span class='description'>$label</span>" : "" );

	} //End: mstw_utl_color_ctrl
 }
 /*----------------------------------------------------------------	
 *	MSTW_UTL_TEXT_CTRL
 *	Builds text format controls for the admin UI
 *
 * 	Arguments:
 *		$args['id'] 	(string) ID of input field 
 *		$args['name'] 	(string) Name of input field
 *		$args['value'] 	(string) Current value of input field
 *		$args['label'] 	(string) Instructions displayed after the field
 *
 *	return - none. Control is displayed.
 *---------------------------------------------------------------*/
if( !function_exists( 'mstw_utl_text_ctrl' ) ) {	
	function mstw_utl_text_ctrl( $args ) { 
		$id = $args['id'];
		$name = $args['name'];
		$value = $args['value'];
		$label = $args['label'];
		
		echo "<input type='text' id='$id' name='$name' value='$value' /> \n";
		//echo "<label for='$id'>$label</label> \n";
		echo ( $label != '' ? "<br /><span class='description'>$label</span>" : "" );
		
	} //End: mstw_utl_text_ctrl
}	
/*----------------------------------------------------------------	
 *	MSTW_UTL_CHECKBOX_CTRL
 *	Builds checkbox format controls for the admin UI
 *
 * 	Arguments:
 *		$args['id'] 	(string) ID of input field 
 *		$args['name'] 	(string) Name of input field
 *		$args['value'] 	(string) Current value of input field
 *		$args['label'] 	(string) Instructions displayed after the field
 *
 *	NOTE that the checked value is always '1'.
 *
 *	Return - none. Control is displayed.
 *---------------------------------------------------------------*/
if( !function_exists( 'mstw_utl_checkbox_ctrl' ) ) {	
	function mstw_utl_checkbox_ctrl( $args ) { 
		$id = 		$args['id'];
		$name = 	$args['name'];
		$value = 	$args['value'];
		$label = 	$args['label'];
		
		echo "<input type='checkbox' id='$id' name='$name' value='1' " . 
				checked( '1', $value, false ) . "/> \n";  
		//echo "<label for='$id'>$label</label> \n";
		echo ( $label != '' ? "<br /><span class='description'>$label</span>" : "" );
		
	}	//End: mstw_utl_checkbox_ctrl
}	
/*----------------------------------------------------------------	
 *	Shortcut to build 'Show-Hide' Select-Option controls for the admin UI
 *	Just like mstw_select_option_ctrl with hard-wired options - 
 *	Show => 1, Hide => 0
 *
 * 	Arguments: 
 *	 $args['id'] (string)		Setting name from option array
 *	 $args['name'] (string)		Name of input field
 *	 $args['value'] (string)	Current value of setting
 *	 $args['label'] (string)	Default to use of setting is blank
 *
 *	Return - none. Output is echoed.
 *---------------------------------------------------------------*/	
if( !function_exists( 'mstw_utl_show_hide_ctrl' ) ) {	
	function mstw_utl_show_hide_ctrl( $args ) {
	
		$new_args = array(	'options' => array(	__( 'Show', 'mstw-loc-domain' ) => 1, 
											__( 'Hide', 'mstw-loc-domain' ) => 0, 
											),
						'id' => $args['id'],
						'name' => $args['name'],
						'value' => $args['value'],
						'label' => $args['label']
						);
		
		mstw_utl_select_option_ctrl( $new_args );
		
	}  //End: mstw_utl_show_hide_ctrl
}
/*----------------------------------------------------------------	
 *	Builds Select-Option controls for the admin UI
 *
 * 	Arguments:
 *	 $args['options'] (array)	Key/value pairs for the options 
 *	 $args['id'] (string)		Setting name from option array
 *	 $args['name'] (string)		Name of input field
 *	 $args['value'] (string)	Current value of setting
 *	 $args['label'] (string)	Default to use of setting is blank
 *
 *	Return - none. Output is echoed.
 *---------------------------------------------------------------*/
if( !function_exists( 'mstw_utl_select_option_ctrl' ) ) {	
	function mstw_utl_select_option_ctrl( $args ) {
		
		$options = $args['options'];
		$name = $args['name'];
		$id = $args['id'];
		$curr_value = $args['value'];
		$label = $args['label'];
		
		echo "<select id='$id' name='$name' style='width: 160px' >";
		foreach( $options as $key=>$value ) {
			//echo '<p> key: ' . $key . ' value: ' . $value .'</p>';
			$selected = ( $curr_value == $value ) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$key</option>";
		}
		echo "</select> \n";
		//echo "<label for='$id'>". $label . "</label> \n";
		echo ( $label != '' ? "<br /><span class='description'>$label</span>" : "" );
		//echo "<label for='$id'>$label</label> \n";
		
	}  //End: mstw_utl_select_option_ctrl
}
//----------------------------------------------------------------	
//	Builds date format controls for the admin UI
//
// 	Arguments:
//	string $args['name']: 		name of option 
//	string $args['id']: 		id of option
//	string $args['curr_value']:	current value of option
//	string $args['dtg']: 		show entire date-time group or only the date
//								'date-time' -> use date-time, 
//								'date-only' -> use date only
//
//	return - none. Output is echoed.
//---------------------------------------------------------------
if( !function_exists( 'mstw_utl_date_format_ctrl' ) ) {	
	function mstw_utl_date_format_ctrl( $args ) {
	
		//extract( $args );
		$name = $args['name'];
		$id = $args['id'];
		$curr_value = $args['curr_value'];
		$dtg = $args['dtg'];
		
		echo '<pre>'; print_r( $args ); echo '</pre>';

		$mstw_utl_date_formats = array ( 
			'2013-04-07' => 'Y-m-d',
			'13-04-07' => 'y-m-d',
			'04/07/13' => 'm/d/y',
			'4/7/13' => 'n/j/y',
			__( '7 Apr 2013', 'mstw-loc-domain' ) => 'j M Y',
			__( '07 Apr 2013', 'mstw-loc-domain' ) => 'd M Y',
			__( '7 Apr 2013', 'mstw-loc-domain' ) => 'j M Y',
			__( '07 Apr 2013', 'mstw-loc-domain' ) => 'd M Y',
			__( '07 Apr 2013', 'mstw-loc-domain' ) => 'j M Y',
			__( 'Tues, 07 Apr 2013', 'mstw-loc-domain' ) => 'D, d M Y',
			__( 'Tues, 7 Apr 13', 'mstw-loc-domain' ) => 'D, j M y',
			__( 'Tuesday, 07 April 2013', 'mstw-loc-domain' ) => 'l, d F Y',
			__( 'Tuesday, 07 April 2013', 'mstw-loc-domain' ) => 'l, j F Y',
			__( 'Tuesday, 07 Apr', 'mstw-loc-domain' ) => 'l, d M',
			__( 'Tues, 7 Apr', 'mstw-loc-domain' ) => 'D, j M',
			__( '7 Apr', 'mstw-loc-domain' ) => 'j M',
			__( '07 Apr', 'mstw-loc-domain' ) => 'd M',
			);
			
	$mstw_utl_date_time_formats = array ( 
			__( 'Tuesday, 07 April 01:15 pm', 'mstw-loc-domain' ) => 'l, d M h:i a',
			__( 'Tuesday, 7 April 1:15 pm', 'mstw-loc-domain' ) => 'l, j M g:i a',
			__( 'Tuesday, 07 April 13:15', 'mstw-loc-domain' ) => 'l, d M H:i',
			__( 'Tuesday, 7 April 13:15', 'mstw-loc-domain' ) => 'l, j M H:i',
			__( '07 April 13:15', 'mstw-loc-domain' ) => 'd M H:i',
			__( '7 April 13:15', 'mstw-loc-domain' ) => 'j M H:i',
			__( '07 April 01:15 pm', 'mstw-loc-domain' ) => 'd M g:i a',
			__( '7 April 01:15 pm', 'mstw-loc-domain' ) => 'j M g:i a',		
			);
		
		if ( $dtg == 'date-time' ) {
			$loop_array = $mstw_utl_date_time_formats;
			$label = __( 'Formats for', 'mstw-loc-domain' ) . " " . __( '7 April 2013 13:15', 'mstw-loc-domain' );
		}
		else {
			$loop_array = $mstw_utl_date_formats;
			$label =  __( 'Formats for', 'mstw-loc-domain' ) . " " . __( '7 April 2013', 'mstw-loc-domain' );
		}
		
		echo "<select id='$id' name='$name' style='width: 160px' >";
		foreach( $loop_array as $key=>$value ) {
			//echo '<p> key: ' . $key . ' value: ' . $value .'</p>';
			$selected = ( $curr_value == $value ) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$key</option>";
		}
		echo "</select> \n";
		//echo "<label for='$id'>". $label . "</label> \n";
		//echo "<label for='$id'>$label</label> \n";
		echo ( $label != '' ? "<br /><span class='description'>$label</span>" : "" );
		
		// get option value from the database
		/* OLD STUFF
		$options = get_option( $opt_name );
		if ( ( $dtg_format = $options[$set_name] ) == '' )
			$dtg_format = $set_default;
			
		echo "<select id=$set_name name='mstw_gs_options[$set_name]'>";
		foreach( $loop_array as $key=>$value ) {
			//echo '<p> key: ' . $key . ' value: ' . $value .'</p>';
			$selected = ( $dtg_format == $value ) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$key</option>";
		}
		if ( $cdt ) {
			echo "</select>" . __( 'Formats for', 'mstw-loc-domain' ) . " " . __( '7 April 2013 13:15', 'mstw-loc-domain' );
		}
		else {
			echo "</select>" . __( 'Formats for', 'mstw-loc-domain' ) . " " . __( '7 April 2013', 'mstw-loc-domain' );
		}
		*/
		
	}
}
/*----------------------------------------------------------------	
 *	Builds time format controls for the admin UI
 *
 * 	Arguments:
 *	$args['opt_name'] (string) name of option (array) 
 *	$args['set_name'] (string) setting name  from option array
 *	$args['set_default'] (string) default to use of setting is blank
 *
 *	return - none. Output is echoed.
 *---------------------------------------------------------------*/
if( !function_exists( 'mstw_utl_time_format_ctrl' ) ) {		
	function mstw_utl_time_format_ctrl( $args ) {
		// need the $mstw_time_formats array
		$mstw_utl_time_formats = array ( 
			__( 'Custom', 'mstw-loc-domain' )			=> 'custom',
			__( '08:00 (24hr)', 'mstw-loc-domain' ) 	=> 'H:i',
			__( '8:00 (24hr)', 'mstw-loc-domain' ) 		=> 'G:i',
			__( '08:00 am', 'mstw-loc-domain' ) 		=> 'h:i a',
			__( '08:00 AM', 'mstw-loc-domain' ) 		=> 'h:i A',
			__( '8:00 am', 'mstw-loc-domain' ) 			=> 'g:i a',
			__( '8:00 AM', 'mstw-loc-domain' ) 			=> 'g:i A',
			);
		
		/*if ( !isset( $mstw_time_formats ) ) {
			include 'mstw-time-format-array.php';
		}*/
		
		$opt_name = $args['opt_name'];
		$set_name = $args['set_name'];
		$set_default = $args['set_default'];
		
		// get option value from the database
		$options = get_option( $opt_name );
		if ( ( $time_format = $options[$set_name] ) == '' )
			$time_format = $set_default;
			
		echo "<select id=$set_name name='mstw_gs_options[$set_name]' style='width: 160px' >";
		foreach( $mstw_utl_time_formats as $key=>$value ) {
			//echo '<p> key: ' . $key . ' value: ' . $value .'</p>';
			$selected = ( $time_format == $value ) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$key</option>";
		}
		
		echo "</select>" . __( 'Formats for', 'mstw-loc-domain' ) . " 08:00";
		
	}
}	
/*----------------------------------------------------------------	
 *	Sanitization Functions
 *---------------------------------------------------------------*/	
if( !function_exists( 'mstw_utl_sanitize_hex_color' ) ) {	
	function mstw_utl_sanitize_hex_color( $color ) {
		// Check $color for proper hex color format (3 or 6 digits) or the empty string.
		// Returns corrected string if valid hex color, returns null otherwise
		
		if ( '' === $color )
			return '';

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return null;
	}
}
if( !function_exists( 'mstw_utl_sanitize_number' ) ) {
	function mstw_utl_sanitize_number( $number ) {

	}
}	
//----------------------------------------------------------------	
// Helper function for registering admin form field settings
// This is new and will aggregate all the mstw_utl__ctrl() functions (someday!)
//----------------------------------------------------------------
if( !function_exists( 'mstw_utl_build_form_field' ) ) {
	function mstw_utl_build_form_field( $args ) {
		// default array to overwrite when calling the function
		/*
		$defaults = array(
			'id'      => 'default_field', // the ID of the setting in our options array, and the ID of the HTML form element
			'title'   => 'Default Field',  // the label for the HTML form element
			'desc'    => 'This is a default description.', // the description displayed under the HTML form element
			'std'     => '',  // the default value for this setting
			'type'    => 'text', // the HTML form element to use
			'section' => 'main_section', // settings section to which this setting belongs
			'choices' => array(), // (optional): the values in radio buttons or a drop-down menu
			'class'   => ''  // the HTML form element class. Also used for validation purposes!
		);
		*/
		// "extract" to be able to use the array keys as variables in our function output below
		//extract( wp_parse_args( $args, $defaults ) );
		extract( $args );
		
		// additional arguments for use in form field output in the function wptuts_form_field_fn!
		/*
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'value'     => $value,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class,
			'name'		=> $name,
		);
		
		echo '<p>Build form field</p>';
		return;

		/*
		add_settings_field( $id, 
							$title, 
							'mstw_utl_display_form_field', 
							$display_on_page, 
							$page_section, 
							$field_args 
							);
		*/
	}
}
//----------------------------------------------------------------	
// Helper function for building HTML for all admin form fields
// All fields will share this function (someday!)
// Called by mstw_utl_build_ctrl() and echoes output
//----------------------------------------------------------------
if( !function_exists( 'mstw_utl_display_form_field' ) ) {
	function mstw_utl_display_form_field( $args ) {
		
		extract( $args );
		/*
		// get the settings sections array
		/*
		$settings_output 	= wptuts_get_settings();
		
		$wptuts_option_name = $settings_output['wptuts_option_name'];
		$options 			= get_option($wptuts_option_name);
		
		// pass the standard value if the option is not yet set in the database
		if ( !isset( $options[$id] ) && 'type' != 'checkbox' ) {
			$options[$id] = $std;
		}
		
		// Additional field class. Output only if the class is defined in the $args()
		$field_class = ($class != '') ? ' ' . $class : '';
		
		
		// switch html display based on the setting type.
		switch ( $type ) {
			/*
			case 'text':
				$options[$id] = stripslashes($options[$id]);
				$options[$id] = esc_attr( $options[$id]);
				echo "<input class='regular-text$field_class' type='text' id='$id' name='" . $wptuts_option_name . "[$id]' value='$options[$id]' />";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
			break;
			
			case "multi-text":
				foreach($choices as $item) {
					$item = explode("|",$item); // cat_name|cat_slug
					$item[0] = esc_html__($item[0], 'wptuts_textdomain');
					if (!empty($options[$id])) {
						foreach ($options[$id] as $option_key => $option_val){
							if ($item[1] == $option_key) {
								$value = $option_val;
							}
						}
					} else {
						$value = '';
					}
					echo "<span>$item[0]:</span> <input class='$field_class' type='text' id='$id|$item[1]' name='" . $wptuts_option_name . "[$id|$item[1]]' value='$value' /><br/>";
				}
				echo ($desc != '') ? "<span class='description'>$desc</span>" : "";
			break;
			
			case 'textarea':
				$options[$id] = stripslashes($options[$id]);
				$options[$id] = esc_html( $options[$id]);
				echo "<textarea class='textarea$field_class' type='text' id='$id' name='" . $wptuts_option_name . "[$id]' rows='5' cols='30'>$options[$id]</textarea>";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : ""; 		
			break;
			
			case 'select':
				echo "<select id='$id' class='select$field_class' name='" . $wptuts_option_name . "[$id]'>";
					foreach($choices as $item) {
						$value 	= esc_attr($item, 'wptuts_textdomain');
						$item 	= esc_html($item, 'wptuts_textdomain');
						
						$selected = ($options[$id]==$value) ? 'selected="selected"' : '';
						echo "<option value='$value' $selected>$item</option>";
					}
				echo "</select>";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : ""; 
			break;
			
			case 'select2':
				echo "<select id='$id' class='select$field_class' name='" . $wptuts_option_name . "[$id]'>";
				foreach($choices as $item) {
					
					$item = explode("|",$item);
					$item[0] = esc_html($item[0], 'wptuts_textdomain');
					
					$selected = ($options[$id]==$item[1]) ? 'selected="selected"' : '';
					echo "<option value='$item[1]' $selected>$item[0]</option>";
				}
				echo "</select>";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
			break;
			
			
			// This is live
			case 'checkbox':
				echo "<input class='checkbox$field_class' type='checkbox' id='$id' name='$name' value='$value' " . checked( $value, 1, false ) . " />";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
			break;
			
			/*
			case "multi-checkbox":
				foreach($choices as $item) {
					
					$item = explode("|",$item);
					$item[0] = esc_html($item[0], 'wptuts_textdomain');
					
					$checked = '';
					
					if ( isset($options[$id][$item[1]]) ) {
						if ( $options[$id][$item[1]] == 'true') {
							$checked = 'checked="checked"';
						}
					}
					
					echo "<input class='checkbox$field_class' type='checkbox' id='$id|$item[1]' name='" . $wptuts_option_name . "[$id|$item[1]]' value='1' $checked /> $item[0] <br/>";
				}
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
			break;
			
		}
		*/
	}
}
?>
