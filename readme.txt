=== Game Locations ===
Contributors: MarkODonnell
Donate link: 
Tags: maps, locations, driving directions 
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: 0.3

Provides a table of locations with links to maps to each one.

== Description ==

This is a simple plugin to create a list (table) of locations with links to maps to each one. It creates a custom post type, game_locations, installs an editor for this post type, and provides a shortcode, [mstw_gl_table], to display the custom posts.

Use the Edit Game Location screen (screenshot-2) to enter the Location (as the title), the street address, city, state, and zip. The plug-in builds the link to that location on Google Maps for display through the Game Locations table. The Custom URL field is normally not needed and therefore left blank. (See FAQ #2.)

**Notes:**

* The references are to "game locations", only because that was original purpose of the plugin. However, it works for any type of location - schools, churches, businesses, whatever.

* The Game Locations plugin is the first in a set of nine plugins supporting a framework for sports team websites. Others will include Game Schedules, Team Rosters, Coaching Staffs, Sponsors, plus more.

== Installation ==

1. Copy the entire /mstw-game-locations/ directory into your /wp-content/plugins/ directory.

2. Activate the plugin.

3. Enter your locations.

4. Place the shortcode [mstw_gl_table] on the page(s) where you want the locations table to appear.

== Frequently Asked Questions ==

= How do I change the look of the locations table? =

In this version, you have to edit the plugin's stylesheet, mstw-gl-styles.css. It is located in game-locations/css. It is short, simple, and well documented. In the near future, I plan to provide options on the admin page to control the table's colors, link syles, etc.

= I don't really like Google Maps. Can I use other mapping service? =

Yes. The custom URL field is provided for that purpose. An entry in that field will override the location's address information. The user will simply be directed to the link in the custom URL field. For example, this field can be used to provide MapQuest maps, or to spruce up Google maps to show driving directions from a particular point and the route outline on the map.

= I use names for locations that sometimes aren't recognized by Google. What should I do? =

Use the custom URL field, then the title will display but won't be used in the Google address.  

== Screenshots ==

1. All Game Locations.
2. Edit Game Location.
3. Game Location Table.

== Changelog ==

= 0.3 =
* Increased the length of the Custom URL field to 256.

= 0.2 =
* Changed links to Google Maps in shortcode to include location street address.

= 0.1 =
* Initial release.