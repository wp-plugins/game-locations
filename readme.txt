=== Game Locations ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: maps,locations,driving directions,sports,venues,stadiums,sports venues,sports stadiums 
Requires at least: 3.3.1
Tested up to: 3.9
Stable tag: 1.4
Text Domain: mstw-loc-domain
Domain Path: /lang
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

THIS PLUGIN HAS BEEN REPLACED BY MSTW SCHEDULES & SCOREBOARDS.

== Description ==

The MSTW Game Locations plugin has been replaced by the [MSTW Schedules & Scoreboards plugin](http://wordpress.org/plugins/mstw-schedules-scoreboards), and is therefore no longer supported. Please install, or upgrade to MSTW Schedules & Scoreboards, which includes all the features of this plugin plus much more. 

== Installation ==

**DO NOT INSTALL THIS PLUGIN. IT IS NO LONGER SUPPORTED. INSTALL [MSTW SCHEDULES & SCOREBOARDS](http://wordpress.org/plugins/mstw-schedules-scoreboards).**

== Frequently Asked Questions ==

[The FAQs may be found here.](http://shoalsummitsolutions.com/gl-faq/)

== Screenshots ==

1. All Game Locations
2. Edit Game Location
3. Game Location Table (from shortcode)
4. The Settings Screen

== Upgrade Notice ==

Do not upgrade this plugin. Install MSTW Game Schedules & Scoreboards, which has replaced it.

== Changelog ==

= 1.4 =
* Notification of plugin replacement.

= 1.3.1 =
* Changed action from wp_enque_scripts(??) to wp_enqueue_style to prevent PHP warning.

= 1.3 =
* Cleaned up name space collisions in the admin utils for the MSTW Framework. PREVIOUS VERSIONS WILL NOT WORK WITH THE MSTW FRAMEWORK.
* Added a tag (mstw_game_locations) to the settings_errors() call to eliminate multiple 'Settings Saved.' messages.
* Removed (blank) street address line in table if no street address is provided.

= 1.2 =
* Completed internationalized the admin pages. The default .po and .mo files have been updated and are in the /lang directory.
* A new "Venue URL" data field was added. It provides a link the the location/venue's web page, NOT MAP, from the venue name, NOT MAP ICON, in the shortcode table.
* Added the (now standard) display settings to show/hide table columns and related columns/data fields.
* Map marker color was changed to support only the basic 8 Google Map marker colors. (Don't ask, just live with it.)
* Many tweaks were made to suppress PHP Notices & Warnings. 

= 1.1 =
* The plugin was "internationalized". It is now ready for translation and the default .po and .mo files are in the /lang directory.
* The Game Locations table was enhanced to add map icons for each location.
* A settings screen was added to support the changes to the table. (See the Other Notes section.)

= 0.4 =
* Changed the way styles are enqueued to fix a compatibility issue with WP 3.4

= 0.3 =
* Increased the length of the Custom URL field to 256.

= 0.2 =
* Changed links to Google Maps in shortcode to include location street address.

= 0.1 =
* Initial release.