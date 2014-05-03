<?php
/*
Plugin Name: Game Locations
Plugin URI: http://wordpress.org/extend/plugins/game-locations/
Description: The Game Locations Plugin defines a custom type - Game Locations - for use in the MySportTeamWebite framework. Generations driving directions (from Google Maps) based on the address.
Version: 1.3
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
//
// CHANGE LOG: 
//	See the SVN repository at http://wordpress.org/plugins/game-locations/developers/
//
// -----------------------------------------------------------------------*/ 


// ----------------------------------------------------------------
	// Set up localization
	//
	add_action( 'init', 'mstw_gl_load_localization' );
		
	function mstw_gl_load_localization( ) {
		load_plugin_textdomain( 'mstw-loc-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	} 
	
// ----------------------------------------------------------------
// Add the custom MSTW icon to CPT pages
// SOMETHING CHANGED IN 3.8 WITH THE ICONS ON PAGES
/*	add_action('admin_head', 'mstw_gl_custom_css');
	
	function mstw_gl_custom_css() { ?>
		<style type="text/css">
			#icon-mstw-gs-main-menu.icon32 {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			#menu-posts-scheduled_game .wp-menu-image {
				background-image: url(<?php echo plugins_url( '/game-locations/images/mstw-admin-menu-icon.png', 'game-locations' );?>) no-repeat 6px -17px !important;
			}
			
			#icon-scheduled_game.icon32 {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			#icon-mstw_gs_teams.icon32 {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			#icon-mstw_gs_schedules.icon32 {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			
			
			
			#icon-edit.icon32-posts-scheduled_games {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			#icon-edit.icon32-posts-mstw_gs_teams {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			#icon-edit.icon32-posts-mstw_gs_schedules {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			
		</style>
	<?php
	}*/
	
// ----------------------------------------------------------------
// Deactivate, request upgrade, and exit if WP version is not right
// ----------------------------------------------------------------
	add_action( 'admin_init', 'mstw_gl_requires_wp_ver' );

	function mstw_gl_requires_wp_ver() {
		global $wp_version;
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare($wp_version, "3.3", "<" ) ) {
			if( is_plugin_active($plugin) ) {
				deactivate_plugins( $plugin );
				wp_die( "'" . $plugin_data['Name'] . "' " . __( 'requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.', 'mstw-loc-domain' ) . '<br /><br />' . __( 'Back to', 'mstw-loc-domain' ) . " <a href='" . admin_url() . "'>" . __( 'WordPress admin', 'mstw-loc-domain' ) . "</a>." );
			}
		}
	}

	// ----------------------------------------------------------------
	// Need the admin utils for convenience
	// ----------------------------------------------------------------
	if ( !function_exists( 'mstw_admin_utils_loaded' ) ) {
		// we're in wp-admin
		require_once ( dirname( __FILE__ ) . '/includes/mstw-admin-utils.php' );
    }
	
	// ----------------------------------------------------------------	
	// Add styles and scripts for the color picker. 
	/*
	add_action( 'admin_enqueue_scripts', 'mstw_gl_enqueue_color_picker' );
	
	function mstw_gl_enqueue_color_picker( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'mstw-gl-color-picker', plugins_url( 'game-locations/js/gl-color-settings.js' ), array( 'wp-color-picker' ), false, true ); 
	}
	*/

	//-----------------------------------------------------------------
	// Queue up the necessary CSS 
	//
	add_action( 'wp_enqueue_scripts', 'mstw_gl_enqueue_styles' );

	function mstw_gl_enqueue_styles ( ) {
		/* Find the full path to the css file & register the style */
		$mstw_gl_style_url = plugins_url( '/css/mstw-gl-styles.css', __FILE__ );
		
		wp_register_style( 'mstw_gl_style', $mstw_gl_style_url );
		
		/* If cssfile exists, register & enqueue the style */
		$mstw_gl_style_file = dirname( __FILE__ ) . '/css/mstw-gl-styles.css';
		
		if ( file_exists( $mstw_gl_style_file ) ) {
			wp_enqueue_style( 'mstw_gl_style' );	
		}
	}

	// ----------------------------------------------------------------
	// Add the custom MSTW icon to CPT pages
	//
	add_action('admin_head', 'mstw_gl_custom_css');
	
	function mstw_gl_custom_css() { ?>
		<style type="text/css">
			#icon-mstw-gl-main-menu.icon32 {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' ); ?>) transparent no-repeat;
			}
			#icon-game_locations.icon32 {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' ); ?>) transparent no-repeat;
			}
			#icon-edit.icon32-posts-game_locations {
				background: url(<?php echo plugins_url( '/game-locations/images/mstw-logo-32x32.png', 'game-locations' );?>) transparent no-repeat;
			}
			#menu-posts-game_locations .wp-menu-image {
				background-image: url(<?php echo plugins_url( '/game-locations/images/mstw-admin-menu-icon.png', 'game-locations' );?>) no-repeat 6px -17px !important;
			}
			
		</style>
	<?php }
	
	// ----------------------------------------------------------------
	// Remove Quick Edit Menu	
	//
	add_filter( 'post_row_actions', 'mstw_gl_remove_quick_edit', 10, 2 );

	function mstw_gl_remove_quick_edit( $actions, $post ) {
		if( $post->post_type == 'player' ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	// ----------------------------------------------------------------
	// Remove the Bulk Actions pull-down edit option
	//
	add_filter( 'bulk_actions-edit-player', 'mstw_gl_bulk_actions' );

    function mstw_gl_bulk_actions( $actions ){
        unset( $actions['edit'] );
        return $actions;
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
//		'post_row_actions'								mstw_gl_remove_view
//		
// --------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------
add_action( 'init', 'mstw_gl_register_post_type' );
// --------------------------------------------------------------------------------------
function mstw_gl_register_post_type() {
	/* Set up the arguments for the Game Locations post type */
	
	$menu_icon_url = plugins_url( ) . '/game-locations/images/mstw-admin-menu-icon.png';
	
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
            'name' => __( 'Game Locations', 'mstw-loc-domain' ),
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
        	),
		
		//'taxonomies' => array( 'teams' ),
		
		//'show_in_admin_bar'   => true,
		//'exclude_from_search' => false,
		//'show_ui'             => true,
		//'show_in_menu'        => 'mstw-tr-main-menu',
		//'menu_position'       => null,
		'menu_icon'           	=> $menu_icon_url,
		//'can_export'          => true,
		//'delete_with_user'    => false,
		//'hierarchical'        => false,
		//'has_archive'         => 'players',	
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
        	value="<?php echo esc_attr( $mstw_gl_state ) ; ?>"/>
		<br/>
		<span class='description'><?php _e( 'For US states use 2 letter abbreviation. Can include country, e.g, "WI, US", or use only country, e.g, "UK". Just check what works with Google Maps if you aren\'t using a custom map URL.', 'mstw-loc-domain' ); ?></span></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_zip"><?php _e( 'Zip:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="128" size="30" name="mstw_gl_zip" 
        	value="<?php echo esc_attr( $mstw_gl_zip ); ?>"/>
		<br/>
		<span class='description'><?php _e( 'Zip code or postal code.', 'mstw-loc-domain' ); ?></span></td>
    </tr>
    <tr valign="top">
    	<th scope="row"><label for="$mstw_gl_custom_url"><?php _e( 'Custom Map URL:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="256" size="30" name="mstw_gl_custom_url" 
        	value="<?php echo esc_url( $mstw_gl_custom_url ); ?>"/>
		<br/>
		<span class='description'><?php _e( 'Used to override the map generated from the address fields by Google Maps. Linked from the map thumbnail in the map column.', 'mstw-loc-domain' ); ?></span></td>
    </tr>
	<tr valign="top">
    	<th scope="row"><label for="$mstw_gl_venue_url"><?php _e( 'Venue URL:', 'mstw-loc-domain' ); ?></label></th>
        <td><input maxlength="256" size="30" name="mstw_gl_venue_url" 
        	value="<?php echo esc_url( $mstw_gl_venue_url ); ?>"/>
		<br/>
		<span class='description'><?php _e( 'Link to the venue\'s website. Normally linked from the location/venue name column.', 'mstw-loc-domain' ); ?></span></td>
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
		
		// We don't really want to default to CA, do we??		
		//$trimmed = trim( $_POST['mstw_gl_state'] );
		//if(empty( $trimmed ) ) 
			//update_post_meta($post_id, '_mstw_gl_state', 'CA' );
		//else 
		update_post_meta($post_id, '_mstw_gl_state', 
			strip_tags( $_POST['mstw_gl_state'] ) );
			
		update_post_meta($post_id, '_mstw_gl_zip',
			strip_tags( $_POST['mstw_gl_zip'] ) );
			
		update_post_meta($post_id, '_mstw_gl_custom_url',
			strip_tags( $_POST['mstw_gl_custom_url'] ) );
			
		update_post_meta( $post_id, '_mstw_gl_venue_url',
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
		'custom_url' => __( 'Custom Map URL', 'mstw-loc-domain' ),
		'venue_url' => __( 'Venue URL', 'mstw-loc-domain' ),
	);

	return $columns;
}

// --------------------------------------------------------------------------------------
// Display the Game Locations 'view all' columns
add_action( 'manage_game_locations_posts_custom_column', 'mstw_gl_manage_columns', 10, 2 );

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
				echo __( 'None (use address)', 'mstw-loc-domain' );
			else
				printf( '%s', $mstw_gl_custom_url );

			break;	

		/* If displaying the 'venue url' column. */
		case 'venue_url' :

			/* Get the post meta. */
			$mstw_gl_venue_url = get_post_meta( $post_id, '_mstw_gl_venue_url', true );

			if ( empty( $mstw_gl_venue_url ) )
				echo __( 'No Venue URL.', 'mstw-loc-domain' );
			else
				printf( '%s', $mstw_gl_venue_url );
				//echo $mstw_gl_venue_url;

			break;				
			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

	// --------------------------------------------------------------------------------------
	//removes view from mstw_game_locatations list
	if (is_admin()) {
		add_filter('post_row_actions','mstw_gl_remove_view',10,2);
	}			

	function mstw_gl_remove_view( $actions ) {
		global $post;
		if( $post->post_type == 'game_locations' ) {
			unset( $actions['view'] );
		}
		return $actions;
	}

//---------------------------------------------------------------------------
// Add the shortcode handler, which builds the Game Locations table on the user side.
// Handles the shortcode parameters, if there were any, 
// then calls mstw_gl_build_loc_tab() to create the output
//
	add_shortcode( 'mstw_gl_table', 'mstw_gl_shortcode_handler' );

	function mstw_gl_shortcode_handler( $atts ){
		// get the default display settings
		$defaults = mstw_gl_get_defaults( );
		
		//$output .= '<pre>DEFAULTS:' . print_r( $defaults, true ) . '</pre>';
		//return $output;
		
		// get the options set in the admin display settings screen
		$options = get_option( 'mstw_gl_options', $defaults );
		//$output .= '<pre>OPTIONS:' . print_r( $options, true ) . '</pre>';
		//return $output;
		foreach ( $options as $k=>$v ) {
			//if ( $k == 'show_date' )
				//$output .= $k . '=> ' . $v;
			if( $v == '' ) {
				//$output .= 'unset: ' . $k . '=> ' . $v;
				unset( $options[$k] );
			}
		}
		
		// merge the options with the defaults
		$args = wp_parse_args( $options, $defaults );
		//$output .= '<pre>DEFAULTS+OPTIONS:' . print_r( $args, true ) . '</pre>';
		//return $output;
		
		// then merge with the arguments passed to the shortcode
		$attribs = shortcode_atts( $args, $atts );
		
		$mstw_gl_loc_tab = mstw_gl_build_location_table( $attribs );
		
		return $mstw_gl_loc_tab;
	}

//------------------------------------------------------------------------
// Called by:	mstw_gl_shortcode_handler
// Builds the Game Locations table as a string 
//	to replace the [shortcode] in a page or post). Loops through the 
//	Game Locations and formats them into a pretty table.
//
	function mstw_gl_build_location_table( $args ) {
	
		//This is the return string
		$output = '';
		//$output .= '<pre>ARGS:' . print_r( $args, true ) . '</pre>';
		//return $output;		
		
		//Pull the $args array into individual variables
		extract( $args );
		
		// Get the game_location posts
		$posts = get_posts(array( 'numberposts' => -1,
								  'post_type' => 'game_locations',
								  'orderby' => 'title',
								  'order' => 'ASC' 
								));						
		
		if( $posts ) {
			// Make table of posts
			// Start with some instructions at the top
			if ( $show_instructions ) {
				if ( $gl_instructions == '' ) {
					$gl_instructions = __( 'Click on map to view driving directions.', 'mstw-loc-domain' );
				}
				$output = '<p>' . $gl_instructions . '</p>';
			}
			
			// Now build the table's header
			$output .= '<table class="mstw-gl-table">';
			$output .= '<thead class="mstw-gl-table-head"><tr>';
			$output .= '<th>' . $location_label . '</th>';
			
			if ( $show_address ) {
				$output .= '<th>' . $address_label . '</th>';
			}
			
			if ( $show_map ) {
				$output .= '<th>' . $map_label . '</th>';
			}
			
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
				
				// column1: location name to the map - 0 means don't build an image
				$link_str = mstw_gl_build_location_link( $location_link, $post, 0 );
						
				$row_string = $row_tr . $row_td . $link_str . '</td>';
				
				// column2: create the address in a pretty format
				if ( $show_address ) {
					$street = get_post_meta( $post->ID, '_mstw_gl_street', true );
					$street_string = ( $street != '' ? $street . '<br/>' : '' );
					$row_string .= $row_td . $street_string . 
						get_post_meta( $post->ID, '_mstw_gl_city', true ) . ', ' .
						get_post_meta( $post->ID, '_mstw_gl_state', true ) . '  ' . 
						get_post_meta( $post->ID, '_mstw_gl_zip', true ) . '</td>';
				}
				
				// column3: map image and link to map
				
				// look for a custom url, if none, build one
				if ( $show_map ) {
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
						if ( $gl_map_height == "" ) {
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
				}
				
				$output .= $row_string;
				
				$row_cnt = 1- $row_cnt;  // Get the styles right
				
			} // end of foreach post
			$output .= '</table>';
		}
		else { // No posts were found
			$output = "<h3> No Game Locations Found. </h3>";
		}
		return $output;
	}
//---------------------------------------------------------------
// Build the location link for the title & map
//
	function mstw_gl_build_location_link( $link_type, $post, $build_image ) { 
	
		$location = get_the_title( $post->ID ); 
		$map_url = get_post_meta( $post->ID, '_mstw_gl_custom_url', true );
		$venue_url = get_post_meta( $post->ID, '_mstw_gl_venue_url', true );
		
		switch ( $link_type ) {
			case 1: 	//link to map
				if ( !empty( $map_url ) ) {
					//use custom map url
					$ret_str = "<a href='" . $map_url . "' target='_blank'>" . $location . "</a>";
				} else { //build google maps href from address
				
					$center_string = $location. "," .
						get_post_meta( $post->ID, '_mstw_gl_street', true ) . ', ' .
						get_post_meta( $post->ID, '_mstw_gl_city', true ) . ', ' .
						get_post_meta( $post->ID, '_mstw_gl_state', true ) . ', ' . 
						get_post_meta( $post->ID, '_mstw_gl_zip', true );
						
					$href = '<a href="https://maps.google.com?q=' .$center_string . '" target="_blank" >'; 
					
					if ( $build_image ) {
						
						
						if ( $gl_map_width == "" ) {
							$gl_map_width = 250;
						}
						if ( $gl_map_height == "" ) {
							$gl_map_height = 75;
						}
						if ( $gl_marker_color == "" ) {
							$gl_marker_color = 'blue';
						}
						
						$ret_str = $href . '<img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $center_string . 
							'&markers=size:mid%7Ccolor:' . $gl_marker_color . '%7C' . $center_string . 
							'&zoom=15&size=' . $gl_map_width . 'x' . $gl_map_height . '&maptype=roadmap&sensor=false" />' . '</a>';
					} else {
						$ret_str = $href . $location . '</a>';
					}
				}
				break;
			
			case 2:		//link to venue
				if ( !empty( $venue_url ) ) {
					$ret_str = "<a href='" . $venue_url . "' target='_blank'>" . $location . "</a>";
				} else {
					$ret_str = $location;
				}
			break;
			
			default: 	//no link
				$ret_str = $location;
			break;
		}

		return $ret_str;
	}

//---------------------------------------------------------------
// ADMIN PAGE SETTINGS
//---------------------------------------------------------------

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
		<?php settings_errors( 'mstw_game_locations' ); ?>
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
		
		mstw_gl_setup_column_settings( );
	}

	function mstw_gl_setup_column_settings( ) {
	
		//$options = get_option( 'mstw_gl_options' );
	
		$display_on_page = 'mstw_gl_settings';
		$page_section = 'mstw_gl_column_settings';
		
		$options = get_option( 'mstw_gl_options', mstw_gl_get_defaults( ) );
	
		// Column Visibility and Label Section
		add_settings_section(
			$page_section,
			__( 'Column Visibility & Label Settings Settings', 'mstw-loc-domain' ),
			'mstw_gl_column_settings_text',
			$display_on_page
		);
		
		// Show/hide INSTRUCTIONS above table
		$args = array( 	'id' => 'show_instructions',
						'name'	=> 'mstw_gl_options[show_instructions]',
						'value'	=> $options['show_instructions'],
						'label'	=> __( 'Show or hide the instructions above the table. (Default: Show)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'show_instructions',
			__( 'Show Instructions:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,  //mstw_gl_settings
			$page_section,  //mstw_gl_column_settings
			$args
		);	
		
		// Instructions above locations table
		$args = array( 	'id' => 'gl_instructions',
						'name'	=> 'mstw_gl_options[gl_instructions]',
						'value'	=> $options['gl_instructions'],
						'label'	=> __( 'Defaults to &quot;Click on map to view driving directions.&quot;', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'gl_instructions',
			__( 'Locations Table Instructions:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// LOCATION column label
		$args = array( 	'id' => 'location_label',
						'name'	=> 'mstw_gl_options[location_label]',
						'value'	=> $options['location_label'],
						'label'	=> __( 'Set label for location column. (Default: &quot;Location&quot;). NOTE that this column cannot be hidden.)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'gl_location_label',
			__( 'Location Column Label:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Location link
		$args = array( 	'options' => array( 'No Link' => 0,
											'Add Link to Map' => 1,
											'Add Link to Venue' => 2,
											),
						'id' => 'location_link',
						'name'	=> 'mstw_gl_options[location_link]',
						'value'	=> $options['location_link'],
						'label'	=> __( 'Either an address or a custom map URL must be specified to add a link to a map. A venue URL must be specified to add a link to a venue. (Default: No Link)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'location_link',
			__( 'Add Link from Location:', 'mstw-loc-domain' ),
			'mstw_utl_select_option_ctrl', 
			$display_on_page,
			$page_section,
			$args
		);	
		
		// Show/hide ADDRESS column
		$args = array( 	'id' => 'show_address',
						'name'	=> 'mstw_gl_options[show_address]',
						'value'	=> $options['show_address'],
						'label'	=> __( 'Show or hide the Address column. (Default: Show)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'gl_show_address',
			__( 'Show Address Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,  //mstw_gl_settings
			$page_section,  //mstw_gl_column_settings
			$args
		);	
		
		// ADDRESS column label
		$args = array( 	'id' => 'address_label',
						'name'	=> 'mstw_gl_options[address_label]',
						'value'	=> $options['address_label'],
						'label'	=> __( 'Set label for address column. (Default: &quot;Address&quot;)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'gl_address_label',
			__( 'Address Column Label:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide MAP column
		$args = array( 	'id' => 'show_map',
						'name'	=> 'mstw_gl_options[show_map]',
						'value'	=> $options['show_map'],
						'label'	=> __( 'Show or hide the Map column. (Default: Show)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'show_map',
			__( 'Show Map Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,  //mstw_gl_settings
			$page_section,  //mstw_gl_column_settings
			$args
		);

		// MAP column label
		$args = array( 	'id' => 'map_label',
						'name'	=> 'mstw_gl_options[map_label]',
						'value'	=> $options['map_label'],
						'label'	=> __( 'Set label for map column. (Default: &quot;Map (Click for larger view)&quot;)', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);						
		add_settings_field(
			'gl_map_label',
			__( 'Map Column Label:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Color for location marker on map
		$args = array( 	'options' => array( 'black' => 'black',
											'blue' => 'blue',
											'brown' => 'brown',
											'gray' => 'gray',
											'green' =>  'green',
											'orange' => 'orange',
											'purple' =>  'purple',
											'red' => 'red',
											'white' => 'white',
											),
						'id' => 'gl_marker_color',
						'name'	=> 'mstw_gl_options[gl_marker_color]',
						'value'	=> $options['gl_marker_color'],
						'label'	=> __( 'Marker color on map in locations table. Standard Google Maps colors. (Default: Blue)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'gl_marker_color',
			__( 'Map Marker Color:', 'mstw-loc-domain' ),
			'mstw_utl_select_option_ctrl', 
			$display_on_page,
			$page_section,
			$args
		);	
		
		// MAP ICON WIDTH in location table
		$args = array( 	'id' => 'gl_map_width',
						'name'	=> 'mstw_gl_options[gl_map_width]',
						'value'	=> $options['gl_map_width'],
						'label'	=> __( 'Width in pixels (Default: 250)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'gl_map_width',
			__( 'Map icon width (in table):', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// MAP ICON HEIGHT in location table
		$args = array( 	'id' => 'gl_map_height',
						'name'	=> 'mstw_gl_options[gl_map_height]',
						'value'	=> $options['gl_map_height'],
						'label'	=> __( 'Height in pixels (Default: 75)', 'mstw-loc-domain' )
						);						
		add_settings_field(
			'gl_map_height',
			__( 'Map icon height (in table):', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
	}

// Main settings section instructions
function mstw_gl_column_settings_text( ) {
	echo '<p>' . __( 'Enter your game locations table settings. ', 'mstw-loc-domain' ) . '</p>';
	
	//. __( 'All color values are in hex, in the format 0x followed by six hex digits. For example, 0x123abd.', 'mstw-loc-domain' ) .  '</p>';
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
				case 'none_right_now':
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

//--------------------------------------------------------------
// Get the default display options
//
	function mstw_gl_get_defaults( ) {
		$defaults = array(	'gl_instructions'	=> 'Click on map to view driving directions.',
							'show_instructions'	=> 1,
							'location_label'	=> 'Location',
							'location_link'		=> 0,
							'show_address'		=> 1,
							'address_label'		=> 'Address',
							'show_map'			=> 1,
							'map_label'			=> 'Map (Click for larger view.)',
							'gl_marker_color'	=> 'blue',
							'gl_map_width'		=> 250,
							'gl_map_height'		=> 75,
							
							);		
		return $defaults;
	}
?>