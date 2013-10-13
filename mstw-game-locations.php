<?php
/*
Plugin Name: Game Locations
Plugin URI: http://wordpress.org/extend/plugins/game-locations/
Description: The Game Locations Plugin defines a custom type - Game Locations - for use in the MySportTeamWebite framework. Generations driving directions (from Google Maps) based on the address.
Version: 1.1
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
//
// 20130121 - MAO:
//	- Added a new table column with small map graphics from google maps. Looks nicer but
//		no real change to the basic functionality.
//	- Added an admin settings section to support the new map graphics.
//	- Updated the code to support Internationalization. (Missing and incorrect wrappers.)
//
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
            'name' => __( 'MSTW Game Locations', 'mstw-loc-domain' ),
            'singular_name' => __( 'Game Location', 'mstw-loc-domain' ),
			'all_items' => __( 'All Locations', 'mstw-loc-domain' ),
            'add_new' => __( 'Add New Location', 'mstw-loc-domain' ),
            'add_new_item' => __( 'Add Location', 'mstw-loc-domain' ),
            'edit_item' => __( 'Edit Location', 'mstw-loc-domain' ),
            'new_item' => __( 'New Location', 'mstw-loc-domain' ),
			//'View Game Location' needs a custom page template that is of no value.
			'view_item' => null, 
            'search_items' => __( 'Search Game Locations', 'mstw-loc-domain' ),
            'not_found' => __( 'No Locations Found', 'mstw-loc-domain' ),
            'not_found_in_trash' => __( 'No Locations Found In Trash', 'mstw-loc-domain' ),
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
	$mstw_gl_venue_url = get_post_meta($post->ID, '_mstw_gl_venue_url', true ); 
	?>	
	
   <table class="form-table">
	<tr valign="top">
    	<th scope="row"><label for="mstw_gl_street" ><?php _e( 'Street Address:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="128" size="30" name="mstw_gl_street"
        	value="<?php echo esc_attr( $mstw_gl_street ); ?>"/></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_city"><?php _e( 'City:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="128" size="30" name="mstw_gl_city" 
        	value="<?php echo esc_attr( $mstw_gl_city ) ; ?>"/></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_state"><?php _e( 'State:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="128" size="30" name="mstw_gl_state" 
        	value="<?php echo esc_attr( $mstw_gl_state ) ; ?>"/></td>
		<td><?php _e( 'For US states use 2 letter abbreviation.', 'mstw-loc-domain' ); ?></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_zip"><?php _e( 'Zip:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="128" size="30" name="mstw_gl_zip" 
        	value="<?php echo esc_attr( $mstw_gl_zip ); ?>"/></td>
		<td><?php _e( 'Zip code or postal code.', 'mstw-loc-domain' ); ?></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_custom_url"><?php _e( 'Custom Map URL:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="256" size="30" name="mstw_gl_custom_url" 
        	value="<?php echo esc_url( $mstw_gl_custom_url ); ?>"/></td>
		<td><?php _e( 'Used to override the map generated from the address fields by Google Maps. Linked from the map thumbnail in the map column.', 'mstw-loc-domain' ); ?></td>
    </tr>
	<tr valign="top">
    	<th scope="row"><label for="$mstw_gl_venue_url"><?php _e( 'Venue URL:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="256" size="30" name="mstw_gl_venue_url" 
        	value="<?php echo esc_url( $mstw_gl_venue_url ); ?>"/></td>
		<td><?php _e( 'Link to the venue\'s website. Normally linked from the location/venue name column.', 'mstw-loc-domain' ); ?></td>
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
			
		update_post_meta($post_id, '_mstw_gl_venue_url',
			strip_tags( $_POST['mstw_gl_venue_url'] ) );
						
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
		'title' => __( 'Location', 'mstw-loc-domain' ),
		'street' => __( 'Street', 'mstw-loc-domain' ),
		'city' => __( 'City', 'mstw-loc-domain' ),
		'state' => __( 'State', 'mstw-loc-domain' ),
		'zip' => __( 'Zip', 'mstw-loc-domain' ),
		'custom_url' => __( 'Custom URL', 'mstw-loc-domain' )
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
				echo __( 'No Street Address', 'mstw-loc-domain' );
			else
				printf( '%s', $mstw_gl_street );

			break;

		/* If displaying the 'city' column. */
		case 'city' :

			/* Get the post meta. */
			$mstw_gl_city = get_post_meta( $post_id, '_mstw_gl_city', true );

			if ( empty( $mstw_gl_city ) )
				echo __( 'No City', 'mstw-loc-domain' );
			else
				printf( '%s', $mstw_gl_city );

			break;
			
		/* If displaying the 'state' column. */
		case 'state' :

			/* Get the post meta. */
			$mstw_gl_state = get_post_meta( $post_id, '_mstw_gl_state', true );


			if ( empty( $mstw_gl_state ) )
				echo __( 'No State', 'mstw-loc-domain' );
			else
				printf( '%s', $mstw_gl_state );

			break;	
			
		/* If displaying the 'zip' column. */
		case 'zip' :

			/* Get the post meta. */
			$mstw_gl_zip = get_post_meta( $post_id, '_mstw_gl_zip', true );

			if ( empty( $mstw_gl_zip ) )
				echo __( 'No Zip', 'mstw-loc-domain' );
			else
				printf( '%s', $mstw_gl_zip );

			break;	
			
		/* If displaying the 'custom url' column. */
		case 'custom_url' :

			/* Get the post meta. */
			$mstw_gl_custom_url = get_post_meta( $post_id, '_mstw_gl_custom_url', true );

			if ( empty( $mstw_gl_custom_url ) )
				echo __( 'No URL, use address.', 'mstw-loc-domain' );
			else
				printf( '%s', $mstw_gl_custom_url );

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
	// Get the settings/options
	$options = get_option( 'mstw_gl_options' );
	$gl_instructions = $options['gl_instructions'];
	$gl_map_width = $options['gl_map_width'];
	$gl_map_height = $options['gl_map_heigth'];
	$gl_marker_color = $options['gl_marker_color'];;

	// Get the game_location posts
	$posts = get_posts(array( 'numberposts' => -1,
							  'post_type' => 'game_locations',
							  'orderby' => 'title',
							  'order' => 'ASC' 
							));						
	
    if($posts) {
		// Make table of posts
		// Start with some instructions at the top
		if ( $gl_instructions == '' ) {
			$gl_instructions = __( 'Click on map to view driving directions.', 'mstw-loc-domain' );
		}
        $output = '<p>' . $gl_instructions . '</p>';
		
		// Now build the table's header
        $output .= '<table class="mstw-gl-table">';
        $output .= '<thead class="mstw-gl-table-head"><tr>';
		$output .= '<th>' . __( 'Location', 'mstw-loc-domain' ) . '</th>';
        $output .= '<th>' . __( 'Address', 'mstw-loc-domain' ) . '</th>';
		$output .= '<th>' . __( 'Map', 'mstw-loc-domain' ) . ' (' . __( 'Click for larger view', 'mstw-loc-domain' ) . ')</th>';
		$output .= '</tr></thead>';
		
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
			
			// column1: location name to the map
			$row_string = $row_tr . $row_td . get_the_title( $post->ID ) . '</td>';
			
			// column2: create the address in a pretty format
			$row_string = $row_string . $row_td . get_post_meta( $post->ID, '_mstw_gl_street', true ) . '<br/>' . 
				get_post_meta( $post->ID, '_mstw_gl_city', true ) . ', ' .
				get_post_meta( $post->ID, '_mstw_gl_state', true ) . '  ' . 
				get_post_meta( $post->ID, '_mstw_gl_zip', true ) . '</td>';
				
			// column3: map image and link to map
			
			// look for a custom url, if none, build one
			$custom_url = trim( get_post_meta( $post->ID, '_mstw_gl_custom_url', true) );
			
			if ( empty( $custom_url ) ) {  // build the url from the address fields
				$center_string = get_the_title( $post->ID ). "," .
					get_post_meta( $post->ID, '_mstw_gl_street', true ) . ', ' .
					get_post_meta( $post->ID, '_mstw_gl_city', true ) . ', ' .
					get_post_meta( $post->ID, '_mstw_gl_state', true ) . ', ' . 
					get_post_meta( $post->ID, '_mstw_gl_zip', true );
					
				$href = '<a href="https://maps.google.com?q=' .$center_string . '" target="_blank" >';
				
				if ( $gl_map_width == "" ) {
					$gl_map_width = 250;
				}
				if ( $gl_map_heigth == "" ) {
					$gl_map_height = 75;
				}
				if ( $gl_marker_color == "" ) {
					$gl_marker_color = 'blue';
				}
				
				$row_string .= $row_td . $href . '<img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $center_string . 
					'&markers=size:mid%7Ccolor:' . $gl_marker_color . '%7C' . $center_string . 
					'&zoom=15&size=' . $gl_map_width . 'x' . $gl_map_height . '&maptype=roadmap&sensor=false" />' . '</a></td>';
			
			}
			else {  // use the custom url
				$href = '<a href="' . $custom_url . '" target="_blank">';
				
				$row_string .= $row_td . $href . __( 'Custom Map', 'mstw-loc-domain' ) . '</a></td>';
			}
			
			//$row_string = $row_tr . $row_td . $href . get_the_title( $post->ID ) . '</a></td>';
			
			
			
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
// ADMIN PAGE SETTINGS
/****************************************************************/

// --------------------------------------------------------------------------------------
// Add a menu for our option page
add_action('admin_menu', 'mstw_gl_add_page');

function mstw_gl_add_page() {
	//The next line adds the settings page to the Settings menu
	//add_options_page( 'Game Locations Settings', 'Game Locations', 'manage_options', 'mstw_gl_settings', 'mstw_gl_option_page' );
	
	// But I decided to add the settings page to the Locations menu
	$page = add_submenu_page( 	'edit.php?post_type=game_locations', 
						'Game Locations Settings', 
						'Settings', 
						'manage_options', 
						'mstw_gl_settings', 
						'mstw_gl_option_page' );
}

// --------------------------------------------------------------------------------------
// Render the option page
function mstw_gl_option_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Game Locations Plugin Settings</h2>
		<?php //settings_errors(); ?>
		<form action="options.php" method="post">
			<?php settings_fields('mstw_gl_options'); ?>
			<?php do_settings_sections('mstw_gl_settings'); ?>
			<input name="Submit" type="submit" class="button-primary" value="Save Changes" />
		</form>
	</div>
	<?php
}

// --------------------------------------------------------------------------------------
// Register and define the settings
add_action('admin_init', 'mstw_gl_admin_init');
function mstw_gl_admin_init(){
	register_setting(
		'mstw_gl_options',
		'mstw_gl_options',
		'mstw_gl_validate_options'
	);
	
	// First (& only?) Section
	add_settings_section(
		'mstw_gl_main_settings',
		'Game Locations Settings',
		'mstw_gl_main_settings_text',
		'mstw_gl_settings'
	);
	
	// Instructions above locations table
	add_settings_field(
		'mstw_gl_instructions',
		'Locations Table Instructions (defaults to "Click on map to view driving directions."):',
		'mstw_gl_instructions_input',
		'mstw_gl_settings',
		'mstw_gl_main_settings'
	);
	
	// Color for location marker on map
	add_settings_field(
		'mstw_gl_marker_color',
		'Map marker color (hex):',
		'mstw_gl_marker_color_input',
		'mstw_gl_settings',
		'mstw_gl_main_settings'
	);
	
	// Width of map icon in location table
	add_settings_field(
		'mstw_gl_map_width',
		'Map icon (in table) width (pixels):',
		'mstw_gl_map_width_input',
		'mstw_gl_settings',
		'mstw_gl_main_settings'
	);
	
	// Height of map icon in location table
	add_settings_field(
		'mstw_gl_map_height',
		'Map icon (in table) height (pixels):',
		'mstw_gl_map_height_input',
		'mstw_gl_settings',
		'mstw_gl_main_settings'
	);
}

// Main settings section instructions
function mstw_gl_main_settings_text( ) {
	echo '<p>' . __( 'Enter your game locations table settings. ', 'mstw-loc-domain' ) . '<br/>' . __( 'All color values are in hex, in the format 0x followed by six hex digits. For example, 0x123abd.', 'mstw-loc-domain' ) .  '</p>';
}

/*--------------------------------------------------------------
 *	Input fields for the main section
 */
 
 function mstw_gl_instructions_input( ) {
	// get option 'gl_instructions' value from the database
	$options = get_option( 'mstw_gl_options' );
	$gl_instructions = $options['gl_instructions'];
	// echo the field
	echo "<input id='gl_instructions' name='mstw_gl_options[gl_instructions]' type='text' size='50' value='$gl_instructions' />";
}
 
function mstw_gl_marker_color_input( ) {
	// get option 'gl_marker_color' value from the database
	$options = get_option( 'mstw_gl_options' );
	$gl_marker_color = $options['gl_marker_color'];
	// echo the field
	echo "<input id='gl_marker_color' name='mstw_gl_options[gl_marker_color]' type='text' value='$gl_marker_color' />";
}

function mstw_gl_map_width_input() {
	// get option 'gl_map_width' value from the database
	$options = get_option( 'mstw_gl_options' );
	$gl_map_width = $options['gl_map_width'];
	// echo the field
	echo "<input id='gl_map_width' name='mstw_gl_options[gl_map_width]' type='text' value='$gl_map_width' />";
}

function mstw_gl_map_height_input() {
	// get option 'gl_map_height' value from the database
	$options = get_option( 'mstw_gl_options' );
	$gl_map_height = $options['gl_map_height'];
	// echo the field
	echo "<input id='gl_map_height' name='mstw_gl_options[gl_map_height]' type='text' value='$gl_map_height' />";
}

/*--------------------------------------------------------------
 *	Validate user input (we want text only)
 */
 
function mstw_gl_validate_options( $input ) {
	// Create our array for storing the validated options
	$output = array();
	// Pull the previous (good) options
	$options = get_option( 'mstw_gl_options' );
	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {
		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {
			switch ( $key ) {
				// add the hex colors
				case 'gl_marker_color':
					// validate the color for proper hex format
					$sanitized_color = mstw_gl_sanitize_hex_color( $input[$key] );
					
					// decide what to do - save new setting 
					// or display error & revert to last setting
					if ( isset( $sanitized_color ) ) {
						// blank input is valid
						$output[$key] = $sanitized_color;
					}
					else  {
						// there's an error. Reset to the last stored value
						$output[$key] = $options[$key];
						// add error message
						add_settings_error( 'mstw_' . $key,
											'mstw_gl_hex_color_error',
											'Invalid hex color entered!',
											'error');
					}
					break;
				case 'gl_width':
				case 'gl_height':
					$output[$key] = intval( $input[$key] );
					break;	
				// Check all other settings
				default:
					//case 'gl_instructions':
					//case 'gl_width':
					//case 'gl_height':
					$output[$key] = sanitize_text_field( $input[$key] );
					// There should not be user/accidental errors in these fields
					break;
				
			} // end switch
		} // end if
	} // end foreach
	
	// Return the array processing any additional functions filtered by this action
	
	return apply_filters( 'mstw_gl_validate_filters', $output, $input );
}

function mstw_gl_sanitize_hex_color( $color ) {
	// Check $color for proper hex color format (3 or 6 digits) or the empty string.
	// Returns corrected string if valid hex color, returns null otherwise
	
	if ( '' === $color )
		return '';

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^0x([A-Fa-f0-9]{6})$|', $color ) /*preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color )*/ )
		return $color;

	return null;
}

/*--------------------------------------------------------------
 *	Display the admin notices
 */
function mstw_gl_admin_notices( ) {
    settings_errors( );
}
add_action( 'admin_notices', 'mstw_gl_admin_notices' );