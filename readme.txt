=== Game Locations ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: maps,locations,driving directions,sports,venues,stadiums,sports venues,sports stadiums 
Requires at least: 3.3.1
Tested up to: 3.8
Stable tag: 1.2
Text Domain: mstw-loc-domain
Domain Path: /lang
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a table of locations with automatic links to a Google map for each one. Custom links can also be created.

== Description ==

The MSTW Game Locations plugin creates a table of locations (screenshot-3) with links to maps for each one. It creates a custom post type, game_locations, installs an editor for this post type, and provides a shortcode, [mstw_gl_table], to display the custom posts.

Use the Add/Edit Game Location screen (screenshot-2) to enter the Location (as the title), the street address, city, state, and zip. The plug-in builds the link to that location on Google Maps for display through the Game Locations table. The Custom URL field can be used to replace the Google Map generated from the address fields, which is linked from the map thumbnail in the map column.(See FAQ #2.)

**Notes:**

* The references are to "game locations", only because that was original purpose of the plugin. However, perhaps "Game Venue" is a better term, and in fact the plugin works for any type of location - schools, churches, businesses, whatever.

* The current version of Game Locations has been tested on WP 3.8 with the Twentyeleven theme. If you use older version of WordPress, good luck! If you are using a newer version, please let me know how the plugin works, especially if you encounter problems.

* In version 1.1, the table was changed to provide a third column with a "thumbnail" icon of the map (for default Google Maps) and a link from it to the map URL. This is (hopefully) graphically more appealing, but the core functionality is really unchanged. A Settings page was added to the Admin Dashboard to support a few styling options (screenshot-4), particularly for the mail thumbnail in the table, but most styling must be done in the plugin's stylesheet.

* The Game Locations plugin is the first in a set of plugins supporting the MSTW Framework for sports team websites. Others currently include Coaching Staffs, Game Schedules, Team Rosters, and League Standings.

== Installation ==

The *AUTOMATED* way:

1. Go to the Plugins->Installed plugins page in WordPress Admin.
2. Click on Add New.
3. Search for Game Locations.
4. Click Install Now.
5. Activate the plugin.
6. Use the new Games menu to create and manage your schedules.

The *MANUAL* way:

1. Copy the entire /mstw-game-locations/ directory into your /wp-content/plugins/ directory.
2. Activate the plugin.
3. Enter your locations.
4. Place the shortcode [mstw_gl_table] on the page(s) where you want the locations table to appear. (Be sure to use the editor's HTML tab, not the Visual tab.)

**UPGRADING**
Upgrading to this version of Game Locations should not impact any existing locations, however move any customizations made to the plugin stylesheet, because IT WILL BE OVERWRITTEN. (Always backup your WP database before you upgrade, just in case. :)

== Frequently Asked Questions ==

= How do I change the look of the locations table? =

Edit the plugin's stylesheet, mstw-gl-styles.css, located in game-locations/css. It is short, simple, and well documented. A Display Settings admin screen provides control of a few elements, especially the map thumbnails in the table. (See the Other Notes section.)

= I don't really like Google Maps. Can I use other mapping service? =

Yes. The custom URL field is provided for that purpose. An entry in that field will override the location's address information. The user will be directed to the link in the custom URL field. For example, this field can be used in the rare event that an address is too new for Google Maps, or to provide MapQuest maps in place of Google maps, or to spruce up the Google maps to show driving directions from a particular point and the route outline on the map.

= I use names for locations that sometimes aren't recognized by Google. What should I do? =

Use the custom URL field, then the title will display but won't be used in the Google address.  

= Is this plugin ready for translation? =

Yes. As of version 1.1. Contact me if you would like help creating a translation in your language. (A bug preventing translation was fixed in version 1.2.)

== Screenshots ==

1. All Game Locations
2. Edit Game Location
3. Game Location Table (from shortcode)
4. The Settings Screen

== Other Notes ==

A demo of the plugin is available on [the MSTW development and test site](http://shoalsummitsolutions.com/dev/).

More complete documentation is available on [the Shoal Summit Solutions site](http://shoalsummitsolutions.com/).

== Upgrade Notice ==

The current version of Game Locations has been tested on WP 3.8 with the Twentyeleven theme. If you use older version of WordPress, good luck! If you are using a newer version, please let me know how the plugin works, especially if you encounter problems.

Upgrading to this version of Game Locations should not impact any existing locations, however move any customizations made to the plugin stylesheet, because IT WILL BE OVERWRITTEN. (Always backup your WP database before you upgrade, just in case. :)


== Changelog ==

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