=== Game Locations ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: maps,locations,driving directions,sports,venues,stadiums,sports venues,sports stadiums 
Requires at least: 3.3.1
Tested up to: 3.5
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a table of locations with automatic links to a Google map for each one. Custom links can also be created.

== Description ==

This is a simple plugin to create a list (table) of locations (screenshot-4) with links to maps to each one. It creates a custom post type, game_locations, installs an editor for this post type, and provides a shortcode, [mstw_gl_table], to display the custom posts.

Use the Edit Game Location screen (screenshot-2) to enter the Location (as the title), the street address, city, state, and zip. The plug-in builds the link to that location on Google Maps for display through the Game Locations table. The Custom URL field is normally not needed and therefore left blank. (See FAQ #2.)

**Notes:**

* The references are to "game locations", only because that was original purpose of the plugin. However, it works for any type of location - schools, churches, businesses, whatever.

* In version 1.1, the table was changed to provide a third column with a "thumbnail" icon of the map (for default Google Maps) and a link from it to the map URL. This is (hopefully) graphically more appealing, but the core functionality is really unchanged. A Settings page was added to the Admin Dashboard to support a few styling options (screenshot-4), particularly for the mail thumbnail in the table, but most styling must be done in the plugin's stylesheet.

* The Game Locations plugin is the first in a set of nine plugins supporting a framework for sports team websites. Others will include Game Schedules, Team Rosters, Coaching Staffs, Sponsors, plus more.

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

== Frequently Asked Questions ==

= How do I change the look of the locations table? =

In this version, you have to edit the plugin's stylesheet, mstw-gl-styles.css. It is located in game-locations/css. It is short, simple, and well documented. A Settings screen has been added to version 1.1 to control a few elements, especially the map thumbnails in the table. (See the Other Notes section.)

= I don't really like Google Maps. Can I use other mapping service? =

Yes. The custom URL field is provided for that purpose. An entry in that field will override the location's address information. The user will simply be directed to the link in the custom URL field. For example, this field can be used to provide MapQuest maps, or to spruce up Google maps to show driving directions from a particular point and the route outline on the map.

= I use names for locations that sometimes aren't recognized by Google. What should I do? =

Use the custom URL field, then the title will display but won't be used in the Google address.  

= Is this plugin ready for translation? =

Yes. As of version 1.1. Contact me if you would like help creating a translation in your language.

== Screenshots ==

1. All Game Locations
2. Edit Game Location
3. Game Location Table
4. The Settings Screen

== Other Notes ==

The Settings screen on the Admin Dashboard is new in Version 1.1. The settings below provide some control of the Game Locations table and the map icons displayed therein, including:

* Locations Table Instructions. The instructions that appear at the top of the Game Locations table. Defaults to "Click on map to view driving directions."

* Map marker color. You can select any color for the map marker indicating the location. You must enter a hex value in the format "0xABC123". (Don't ask me why Google Maps doesn't use "#ABC123". Guess I could have translated but hey, just do it!) Note that all six hex charaters are required. The default marker color is blue.

* Map icon width: The width of the map icon in the table in pixels. Google's API limits the value to 640, which would be far too big for the table anyway.

* Map icon height: The height of the map icon in the table in pixels. Google's API limits the value to 640, which would be far too big for the table anyway.


== Changelog ==

= 1.2 =
* Internationalized the admin pages. The default .po and .mo files have been updated and are in the /lang directory.
* A new "Venue URL" data field was added. It provides a link the the location/venue's web page, NOT MAP, from the venue name in the shortcode table.
* Added the (now standard) display settings to show/hide table columns and relable columns/data fields.
* Added more color settings and the javascript color picker for them.

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

== Upgrade Notice ==

The current version of Game Locations has been tested on WP 3.5 with the Twentyeleven theme. If you use older version of WordPress, good luck! If you are using a newer version, please let me know how the plugin works, especially if you encounter problems.

Upgrading to this version of Game Locations should not impact any existing locations. (But backup your DB before you upgrade, just in case. :)