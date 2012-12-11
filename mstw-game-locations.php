<?php
/*
Plugin Name: Game Locations
Plugin URI: http://wordpress.org/extend/plugins/game-locations/
Description: The Game Locations Plugin defines a custom type - Game Locations - for use in the MySportTeamWebite framework. Generations driving directions (from Google Maps) based on the address.
Version: 0.4
Author: Mark O'Donnell
Author URI: http://shoalsummitsolutions.com
*/

/*
Game Locations (Wordpress Plugin)
Copyright (C) 2012 Mark O'Donnell
Contact me: mark@shoalsummitsolutions.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/* ------------------------------------------------------------------------
// PLUGIN PREFIX:                                                          
// 'mstw_gl_'   derived from mysportsteamwebsite game locations

// 20120412 - MAO: 
//	- Added street address to Google Maps URL in function mstw_gl_build_loc_tab()
//		which is used by the shortcode that generates the locations table.
//
// 20120414 - MAO:
//	- Changed mstw_remove_view to mstw_gs_remove_view to avoid conflicts with other
//		mstw plugins
//
// 20120504 - MAO:
//	- Expanded maxlength of custom URL field to 120
//
// 20120928 - MAO:
//	- Expanded maxlength of custom URL field to 256
// -----------------------------------------------------------------------*/

?>
<?php 

// ----------------------------------------------------------------
// Deactivate, request upgrade, and exit if WP version is not right
add_action( 'admin_init', 'mstw_gl_requires_wp_ver' );

// ----------------------------------------------------------------
function mstw_gl_requires_wp_ver() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.3", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! 
				Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}

// --------------------------------------------------------------------------------------
// Set-up Action and Filter Hooks for the Settings on the admin side
// --------------------------------------------------------------------------------------
register_activation_hook(__FILE__, 'mstw_gl_set_defaults');
register_uninstall_hook(__FILE__, 'mstw_gl_delete_plugin_options');
add_action('admin_init', 'mstw_gl_register_settings' );
// add_action('admin_menu', 'mstw_gl_add_options_page'); Code is still in place
//add_filter( 'plugin_action_links', 'mstw_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------------------------
// Callback for: register_uninstall_hook(__FILE__, 'mstw_gl_delete_plugin_options')
// --------------------------------------------------------------------------------------
// It runs when the user deactivates AND DELETES the plugin. 
// It deletes the plugin options DB entry, which is an array storing all the plugin options
// --------------------------------------------------------------------------------------
function mstw_gl_delete_plugin_options() {
	delete_option('mstw_gl_options');
}

// --------------------------------------------------------------------------------------
// Callback for: register_activation_hook(__FILE__, 'mstw_gl_set_defaults')
// --------------------------------------------------------------------------------------
// This function runs when the plugin is activated. If there are no options currently set, 
// or the user has selected the checkbox to reset the options to their defaults,
// then the options are set/reset. Otherwise the options remain unchanged.
// --------------------------------------------------------------------------------------
function mstw_gl_set_defaults() {
	$tmp = get_option('mstw_gl_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('mstw_gl_options'); // so we don't have to reset all the 'off' checkboxes too! 
		$arr = array(	"mstw_gl_hdr_bkgd" => "#000000",
						"mstw_gl_hdr_text" => "#FFFFFF",
						"mstw_gl_even_bkgd" => "#DBE5F1",
						"mstw_gl_even_text" => "#000000",
						"mstw_gl_odd_bkgd" => "#FFFFFF",
						"mstw_gl_odd_text" => "#000000",
						"mstw_gl_brdr_width" => "2",  //px
						"mstw_gl_brdr_color" => "#F481BD",
						"mstw_gl_default_opts" => "",
		);
		update_option('mstw_gl_options', $arr);
	}
}

// --------------------------------------------------------------------------------------
// Callback for: add_action('admin_init', 'mstw_gl_register_settings' )
// --------------------------------------------------------------------------------------
// Registers plugin settings with the WP Setting API. Nothing works unless this happens.
// --------------------------------------------------------------------------------------
function mstw_gl_register_settings( ) { //whitelist options
	register_setting( 'mstw_gl_options_group', 'mstw_gl_options', 'mstw_gl_valid_options' );
	add_settings_section( 'mstw_gl_main_section', 'Game Locations Table Style', 'mstw_gl_main_section_text', 
							basename(__FILE__) );
	add_settings_field( 'mstw_gl_hdr_bkgd', 'Header Background Color', 'mstw_gl_hdr_bkgd_cb', 
						basename(__FILE__), 'mstw_gl_main_section');

}

// ------------------------------------------------------------------------------
// Callback for: add_action('admin_menu', 'mstw_gl_add_options_page');
// ------------------------------------------------------------------------------
// Adds a new Settings Page into the plugin menu.
// ------------------------------------------------------------------------------
function mstw_gl_add_options_page( ) { 
	add_submenu_page('edit.php?post_type=game_locations', 'Game Locations Settings', 'Settings',
					 'edit_posts', basename(__FILE__), 'mstw_gl_render_settings_ui');
}

/* Queue up the necessary CSS */
add_action( 'wp_enqueue_scripts', 'mstw_gl_enqueue_styles' );

// ------------------------------------------------------------------------------
// Callback for: add_action( 'wp_enqueue_scripts', 'mstw_gl_enqueue_styles' );
// ------------------------------------------------------------------------------
// Loads the Cascading Style Sheet for the [mstw-gl-table] shortcode
// ------------------------------------------------------------------------------
function mstw_gl_enqueue_styles () {
	
	/* Find the full path to the css file & register the style */
	$mstw_gl_style_url = plugins_url( '/css/mstw-gl-styles.css', __FILE__ );
	
	wp_register_style( 'mstw_gl_style', $mstw_gl_style_url );
	
	//$mstw_gl_style_file = WP_PLUGIN_DIR . '/mstw-game-locations/css/mstw-gl-styles.css';
	
	
	/* If cssfile exists, register & enqueue the style */
	$mstw_gl_style_file = dirname( __FILE__ ) . '/css/mstw-gl-styles.css';
	
	if ( file_exists( $mstw_gl_style_file ) ) {
		wp_enqueue_style( 'mstw_gl_style' );	
	}
}

// --------------------------------------------------------------------------------------
// GAME LOCATIONS CUSTOM POST TYPE STUFF
// --------------------------------------------------------------------------------------
// Set-up Action Hooks & Filters for the Game Locations custom post type
// ACTIONS
// 		'init'											mstw_gl_register_post_type
//		'add_metaboxes'									mstw_gl_add_meta
//		'save_posts'									mstw_gl_save_meta
//		'manage_game_locations_posts_custom_column'		mstw_gl_manage_columns

// FILTERS
// 		'manage_edit-game_locations_columns'			mstw_gl_edit_columns
//		'post_row_actions'								mstw_gs_remove_view
//		
// --------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------
add_action( 'init', 'mstw_gl_register_post_type' );
// --------------------------------------------------------------------------------------
function mstw_gl_register_post_type() {
	/* Set up the arguments for the Game Locations post type */
	$args = array(
    	'public' => true,
        'query_var' => 'game_locations',
        'rewrite' => array(
            'slug' => 'game-locations',
            'with_front' => false,
        ),
        'supports' => array(
            'title'
        ),
        'labels' => array(
            'name' => 'Game Locations',
            'singular_name' => 'Game Location',
            'add_new' => 'Add New Location',
            'add_new_item' => 'Add Game Location',
            'edit_item' => 'Edit Game Location',
            'new_item' => 'New Game Location',
			//'View Game Location' needs a custom page template that is of no value.
			'view_item' => null, 
            'search_items' => 'Search Game Locations',
            'not_found' => 'No Game Locations Found',
            'not_found_in_trash' => 'No Game Locations Found In Trash'
        	)
		);
		
	register_post_type( 'game_locations', $args);
}

// --------------------------------------------------------------------------------------
add_action( 'add_meta_boxes', 'mstw_gl_add_meta' );
// --------------------------------------------------------------------------------------
// Create the meta box for the Game Locations custom post type
// --------------------------------------------------------------------------------------
function mstw_gl_add_meta () {
	add_meta_box('mstw-gl-meta', 'Game Location', 'mstw_gl_create_ui', 
					'game_locations', 'normal', 'high' );
}

// --------------------------------------------------------------------------------------
// Callback for: add_meta_box('mstw-gl-meta', 'Game Location', 'mstw_gl_create_ui', ... )
// --------------------------------------------------------------------------------------
// Creates the UI form for entering a Game Location in the Admin page
// --------------------------------------------------------------------------------------
function mstw_gl_create_ui( $post ) {
	
	// Retrieve the metadata values if they exist
	$mstw_gl_street = get_post_meta($post->ID, '_mstw_gl_street', true );
	$mstw_gl_city  = get_post_meta($post->ID, '_mstw_gl_city', true );
	$mstw_gl_state = get_post_meta($post->ID, '_mstw_gl_state', true );
	$mstw_gl_zip = get_post_meta($post->ID, '_mstw_gl_zip', true );
	$mstw_gl_custom_url = get_post_meta($post->ID, '_mstw_gl_custom_url', true );  
	?>	
	
   <table class="form-table">
	<tr valign="top">
    	<th scope="row"><label for="mstw_gl_street" >Street Address:</label></th>
        <td><input maxlength="45" size="30" name="mstw_gl_street"
        	value="<?php echo esc_attr( $mstw_gl_street ); ?>"/></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_city">City:</label></th>
        <td><input maxlength="45" size="30" name="mstw_gl_city" 
        	value="<?php echo esc_attr( $mstw_gl_city ) ; ?>"/></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_state">State:</label></th>
        <td><input maxlength="45" size="30" name="mstw_gl_state" 
        	value="<?php echo esc_attr( $mstw_gl_state ) ; ?>"/></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_zip">Zip:</label></th>
        <td><input maxlength="45" size="30" name="mstw_gl_zip" 
        	value="<?php echo esc_attr( $mstw_gl_zip ); ?>"/></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_custom_url">Custom URL:</label></th>
        <td><input maxlength="256" size="30" name="mstw_gl_custom_url" 
        	value="<?php echo esc_url( $mstw_gl_custom_url ); ?>"/></td>
    </tr>
    </table>
    
<?php        	
}

// --------------------------------------------------------------------------------------
add_action( 'save_post', 'mstw_gl_save_meta' );
// --------------------------------------------------------------------------------------
// Save the Game Locations Meta Data
// --------------------------------------------------------------------------------------
function mstw_gl_save_meta( $post_id ) {
	//verify the metadata is set
	if ( isset( $_POST[ 'mstw_gl_city' ] ) ) {
	
		update_post_meta($post_id, '_mstw_gl_street',
			strip_tags( $_POST['mstw_gl_street'] ) );
			
		update_post_meta($post_id, '_mstw_gl_city',
			strip_tags( $_POST['mstw_gl_city'] ) );
			
		$trimmed = trim( $_POST['mstw_gl_state'] );
		if(empty( $trimmed ) ) 
			update_post_meta($post_id, '_mstw_gl_state', 'CA' );
		else 
			update_post_meta($post_id, '_mstw_gl_state', 
				strip_tags( $_POST['mstw_gl_state'] ) );
			
		update_post_meta($post_id, '_mstw_gl_zip',
			strip_tags( $_POST['mstw_gl_zip'] ) );
			
		update_post_meta($post_id, '_mstw_gl_custom_url',
			strip_tags( $_POST['mstw_gl_custom_url'] ) );
						
	}  
}

// --------------------------------------------------------------------------------------
add_filter( 'manage_edit-game_locations_columns', 'mstw_gl_edit_columns' ) ;
// --------------------------------------------------------------------------------------
// Set up the Game Locations 'view all' columns
// --------------------------------------------------------------------------------------
function mstw_gl_edit_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Location' ),
		'street' => __( 'Street' ),
		'city' => __( 'City' ),
		'state' => __( 'State' ),
		'zip' => __( 'Zip' ),
		'custom_url' => __( 'Custom URL' )
	);

	return $columns;
}

// --------------------------------------------------------------------------------------
add_action( 'manage_game_locations_posts_custom_column', 'mstw_gl_manage_columns', 10, 2 );
// --------------------------------------------------------------------------------------
// Display the Game Locations 'view all' columns
// --------------------------------------------------------------------------------------
function mstw_gl_manage_columns( $column, $post_id ) {
	global $post;
	
	/* echo 'column: ' . $column . " Post ID: " . $post_id; */

	switch( $column ) {
	
		/* If displaying the 'street' column. */
		case 'street' :

			/* Get the post meta. */
			$mstw_gl_street = get_post_meta( $post_id, '_mstw_gl_street', true );

			if ( empty( $mstw_gl_street ) )
				echo __( 'No Street Address' );
			else
				printf( __( '%s' ), $mstw_gl_street );

			break;

		/* If displaying the 'city' column. */
		case 'city' :

			/* Get the post meta. */
			$mstw_gl_city = get_post_meta( $post_id, '_mstw_gl_city', true );

			if ( empty( $mstw_gl_city ) )
				echo __( 'No City' );
			else
				printf( __( '%s' ), $mstw_gl_city );

			break;
			
		/* If displaying the 'state' column. */
		case 'state' :

			/* Get the post meta. */
			$mstw_gl_state = get_post_meta( $post_id, '_mstw_gl_state', true );


			if ( empty( $mstw_gl_state ) )
				echo __( 'No State' );
			else
				printf( __( '%s' ), $mstw_gl_state );

			break;	
			
		/* If displaying the 'zip' column. */
		case 'zip' :

			/* Get the post meta. */
			$mstw_gl_zip = get_post_meta( $post_id, '_mstw_gl_zip', true );

			if ( empty( $mstw_gl_zip ) )
				echo __( 'No Zip' );
			else
				printf( __( '%s' ), $mstw_gl_zip );

			break;	
			
		/* If displaying the 'custom url' column. */
		case 'custom_url' :

			/* Get the post meta. */
			$mstw_gl_custom_url = get_post_meta( $post_id, '_mstw_gl_custom_url', true );

			if ( empty( $mstw_gl_custom_url ) )
				echo __( 'No URL, use address.' );
			else
				printf( __( '%s' ), $mstw_gl_custom_url );

			break;			
			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

// --------------------------------------------------------------------------------------
if (is_admin()) {
	add_filter('post_row_actions','mstw_gs_remove_view',10,2);
}			
// --------------------------------------------------------------------------------------
//removes view from mstw_game_locatations list
function mstw_gs_remove_view( $actions ) {
	global $post;
    if( $post->post_type == 'game_locations' ) {
		unset( $actions['view'] );
	}
    return $actions;
}


// --------------------------------------------------------------------------------------
add_shortcode( 'mstw_gl_table', 'mstw_gl_shortcode_handler' );
// --------------------------------------------------------------------------------------
// Add the shortcode handler, which will create the Game Locations table on the user side.
// Handles the shortcode parameters, if there were any, 
// then calls mstw_gl_build_loc_tab() to create the output
// --------------------------------------------------------------------------------------
function mstw_gl_shortcode_handler(){	
	$mstw_gl_loc_tab = mstw_gl_build_loc_tab();
	return $mstw_gl_loc_tab;
}

// --------------------------------------------------------------------------------------
// Called by:	mstw_gl_shortcode_handler
// Builds the Game Locations table as a string (to replace the [shortcode] in a page or post.
// Loops through the Game Locations Custom posts and formats them into a pretty table.
// --------------------------------------------------------------------------------------
function mstw_gl_build_loc_tab() {
	// Get the game_location posts
	$posts = get_posts(array( 'numberposts' => -1,
							  'post_type' => 'game_locations',
							  'orderby' => 'title',
							  'order' => 'ASC' 
							));						
	
    if($posts) {
		// Make table of posts
		// Start with the table header
        $output = '<p>Click on location to view driving directions. </p>
        <table class="mstw-gl-table">
        <thead class="mstw-gl-table-head"><tr>
			<th>Location</th>
            <th>Address</th>
		</tr></thead>';
		
		// Loop through the posts and make the rows
		$even_and_odd = array('even', 'odd');
		$row_cnt = 1; // Keeps track of even and odd rows. Start with row 1 = odd.
		
		foreach($posts as $post){
			// set up some housekeeping to make styling in the loop easier
			$even_or_odd_row = $even_and_odd[$row_cnt]; 
			$row_class = 'mstw-gl-' . $even_or_odd_row;
			$row_tr = '<tr class="' . $row_class . '">';
			$row_td = '<td>'; 
			
			// create the row
			
			// column1: create the link for the location name to the map
			$custom_url = trim( get_post_meta( $post->ID, '_mstw_gl_custom_url', true) );
			
			if ( empty( $custom_url) ) { // build the url from the address fields
				$href = '<a href="http://maps.google.com?q=' . get_the_title( $post->ID ). "," .
				get_post_meta( $post->ID, '_mstw_gl_street', true ) . ', ' .
				get_post_meta( $post->ID, '_mstw_gl_city', true ) . ', ' .
				get_post_meta( $post->ID, '_mstw_gl_state', true ) . ', ' . 
				get_post_meta( $post->ID, '_mstw_gl_zip', true ) .
				'" target="_blank">';
			}
			else {
				$href = '<a href="' . $custom_url . '" target="_blank">';
			}
			
			$row_string = $row_tr . $row_td . $href . get_the_title( $post->ID ) . '</a></td>';
			
			// column2: create the address in a pretty format
			$row_string = $row_string . $row_td . get_post_meta( $post->ID, '_mstw_gl_street', true ) . '<br/>' . 
				get_post_meta( $post->ID, '_mstw_gl_city', true ) . ', ' .
				get_post_meta( $post->ID, '_mstw_gl_state', true ) . '  ' . 
				get_post_meta( $post->ID, '_mstw_gl_zip', true ) . '</td>';
			
			$output = $output . $row_string;
			
			$row_cnt = 1- $row_cnt;  // Get the styles right
			
		} // end of foreach post
		$output = $output . '</table>';
	}
	else { // No posts were found
		$output = "<h3> No Game Locations found. </h3>";
	}
	return $output;
}

/****************************************************************/


 
/****************************************************************/


function mstw_gl_settings_ui_should_work( ) { 
?>
	<div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div>
	<h2>Game Locations Settings</h2>
	<p>Coming Soon! Settings to modify the look and feel of the locations table. </p>
    
    <form method="post" action="options.php"> 
    	<?php settings_fields( 'mstw_gl_options_group' ); ?>
        
        <?php do_settings_sections( 'mstw-gl-options' ); ?>
        
    	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
    
    <p>End of the Game Locations Settings form. </p>
    
    </div> <!-- div class='wrap' -->
<?php
}

function mstw_gl_main_section_text() {
	echo '<p>Enter all colors in standard, six digit, hex format; e.g., #1A2BC3</p>';
}

function mstw_gl_hdr_bkgd_cb() {
	$mstw_gl_options = get_option('mstw_gl_options'); ?>
	<input type="text" size="8" name="mstw_gl_options[mstw_gl_hdr_bkgd]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_hdr_bkgd];?>" />
<?php
}

function mstw_gl_hdr_text_cb() {
	$mstw_gl_options = get_option('mstw_gl_options');
	echo "<input id='mstw_gl_hdr_text_color' name='mstw_gl_options[text_string]' size='40' type='text' 
				value='{$options['text_string']}' />";
}

function mstw_gl_render_settings_ui() {
?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Game Location Settings</h2>
		<p>Enter all colors in standard, six digit, hex format; e.g., #1A2BC3</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('mstw_gl_options_group'); ?>
			<?php $mstw_gl_options = get_option('mstw_gl_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">

				<tr>
					<th scope="row">Header Background Color</th>
					<td>
						<input type="text" size="8" name="mstw_gl_options[mstw_gl_hdr_bkgd]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_hdr_bkgd];?>" />
					</td>
				</tr>
                <tr>
					<th scope="row">Header Text Color</th>
					<td>
						<input type="text" size="8" name="mstw_gl_options[mstw_gl_hdr_text]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_hdr_text];?>" />
					</td>
				</tr>
                <tr>
					<th scope="row">Even Row Background Color</th>
					<td>
						<input type="text" size="8" name="mstw_gl_options[mstw_gl_even_bkgd]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_even_bkgd];?>" />
					</td>
				</tr>
                <tr>
					<th scope="row">Even Row Text Color</th>
					<td>
						<input type="text" size="8" name="mstw_gl_options[mstw_gl_even_text]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_even_text];?>" />
					</td>
				</tr>
                <tr>
					<th scope="row">Odd Row Background Color</th>
					<td>
						<input type="text" size="8" name="mstw_gl_options[mstw_gl_odd_bkgd]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_odd_bkgd];?>" />
					</td>
				</tr>
                <tr>
					<th scope="row">Odd Row Text Color</th>
					<td>
						<input type="text" size="8" name="mstw_gl_options[mstw_gl_odd_text]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_odd_text];?>" />
					</td>
				</tr>
                <tr>
					<th scope="row">Border Color</th>
					<td>
						<input type="text" size="8" name="mstw_gl_options[mstw_gl_brdr_color]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_brdr_color];?>" />
					</td>
				</tr>
                <tr>
					<th scope="row">Border Width</th>
					<td>
						<label><input type="text" size="3" name="mstw_gl_options[mstw_gl_brdr_width]" 
                        		value="<?php echo $mstw_gl_options[mstw_gl_brdr_width];?>" /> (measured in pixels)</label>
					</td>
				</tr>
                
                
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="mstw_gl_options[mstw_gl_default_opts]" type="checkbox" value="1" 
						<?php if (isset($mstw_gl_options['mstw_gl_default_opts'])) {
									checked('1', $mstw_gl_options['mstw_gl_default_opts']); 
								} ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br/><span style="color:#666666;margin-left:2px;">
                        Only check this if you want to reset plugin settings upon Plugin reactivation
                        </span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

	</div>
<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function mstw_gl_valid_options($input) {
	 // strip html from textboxes
	$input['mstw_gl_hdr_bkgd'] =  wp_filter_nohtml_kses($input['mstw_gl_hdr_bkgd']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['mstw_gl_hdr_text'] =  wp_filter_nohtml_kses($input['mstw_gl_hdr_text']); // Sanitize textbox input (strip html tags, and escape characters)
	return $input;
}