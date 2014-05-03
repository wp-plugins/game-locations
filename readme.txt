=== Game Locations ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: maps,locations,driving directions,sports,venues,stadiums,sports venues,sports stadiums 
Requires at least: 3.3.1
Tested up to: 3.9
Stable tag: 1.3
Text Domain: mstw-loc-domain
Domain Path: /lang
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a table of event venues with automatic links to Google maps. Custom map links can also be created.

== Description ==

The MSTW Game Locations plugin is more appropriately named "Event Venues". It creates a table of locations/venues with links to maps for each one. The table is displayed via a simple shortcode, [mstw_gl_table]. Use the Add/Edit Game Location screen to enter the Location (as the title), the street address, city, state, and zip. The plug-in generates the link to that location on Google Maps from the street address, city, state, and zip. A Custom URL can be used to replace the link to Google Maps.

= NEW IN VERSION 1.3 =

Version 1.3 is a maintenance release. Some namespace collisions with other MSTW plugins were eliminated. Please read the release notes for more information.

= Notes =

* The Game Locations plugin is part of the My Sports Team Website (MSTW) framework for sports team and league websites. Others include Game Schedules, Team Rosters, Coaching Staffs, and League Standings, which are all available on [WordPress.org](http://wordpress.org/extend/plugins). [Learn more about MSTW](http://shoalsummitsolutions.com/my-sports-team-website/).


= Helpful Links =
* [**See what the plugin and do for your website on the MSTW Dev Site -»**](http://dev.shoalsummitsolutions.com/)
* [**Read the user's manual at shoalsummitsolutions.com -»**](http://shoalsummitsolutions.com/category/gl-plugin)


**Notes:**

* The references are to "game locations", only because that was original purpose of the plugin. However, perhaps "Event Venue" is a better term, and in fact the plugin works for any type of location - schools, churches, businesses, whatever.

* The current version of Game Locations has been tested on WP 3.9 with the Twentyeleven theme. If you use older version of WordPress, good luck! If you are using a newer version, please let me know how the plugin works, especially if you encounter problems.

* The Game Locations plugin is the first in a set of plugins supporting the MSTW Framework for sports team websites. Others currently include Coaching Staffs, Game Schedules, Team Rosters, and League Standings.

== Installation ==

**IMPORTANT!**

*If you are upgrading, please read the upgrade notes. You shouldn't lose schedule data but you COULD easily lose and changes you've made to the plugin stylesheet.*


All the normal installation methods for WordPress plugins work:

1. Go to the Plugins->Installed plugins screen in Wordpress Admin. Click on Add New. Search for Game Locations. Install it.
2. Download the plugin (.zip file) from WordPress.org. Go to the Plugins->Installed plugins screen in Wordpress Admin. Click on Add New. Click on the Upload link. Find the downloaded .zip file on your computer. Install it.
3. Download the plugin (.zip file) from WordPress.org. Unzip the file. Upload the extracted plugin folder to your website's wp-content/plugins directory using an FTP client or your hosting provider's file manager app. Activate it on the Plugins->Installed plugins screen in WordPress Admin.

== Frequently Asked Questions ==

[The FAQs may be found here.](http://shoalsummitsolutions.com/gl-faq/)

== Screenshots ==

1. All Game Locations
2. Edit Game Location
3. Game Location Table (from shortcode)
4. The Settings Screen

== Upgrade Notice ==

The current version of Game Locations has been tested on WP 3.9 with the Twentyeleven theme. If you use older version of WordPress, good luck! If you are using a newer version, please let me know how the plugin works, especially if you encounter problems.

Upgrading to this version of Game Locations should not impact any existing data, however move any customizations made to the plugin stylesheet - css/mstw-gl-styles.css - because IT WILL BE OVERWRITTEN. (Always backup your WP database before you upgrade, just in case. :)


== Changelog ==

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