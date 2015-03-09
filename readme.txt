=== WP Embed Facebook ===
Contributors: poxtron
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8Q85GT3Q8Q26
Tags: Facebook, facebook, Social Plugins, embed facebook, facebook video, facebook posts, facebook publication, facebook publications, facebook event, facebook events, facebook pages, facebook page, facebook profiles, facebook album, facebook albums, facebook photos, facebook photo, social,
Requires at least: 3.8.1
Tested up to: 4.1.1
Stable tag: 1.8.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a Facebook video, page, event, album, photo, profile or post to any Wordpress post or page.

== Description ==

Embed any public facebook video, page, post, profile, photo or event directly into a wordpress post, without having to write a single line of code. Simply put the facebook url on a separate line on the content of any post, and this plugin will fetch data associated with that url and display it, if the data is not public, like “invite only” events or private profiles, will return a link.

= Supported Embeds =
* Facebook Videos
* Facebook Albums
* Facebook Events
* Facebook Fotos
* Facebook Fan pages
* Facebook Community pages
* Facebook Profiles
* Facebook Publications

= Requirements =
* Facebook App id and Secret

= How to use it =
Put on a single and separate line the Facebook URL.
Or you can use a shortcode `[facebook=url width=200 raw=true]` 
width and raw are optional, raw only works for videos and photos

= Options =
* Settings -> Embed Facebook.
* Show latest Posts of Fan Page
* Number of photos shown on album embed
* Embed width customization
* Show like buttons on Fan Pages
* Show Follow Button on profile embed
* Remove plugin styles
* Change Theme
* Add fb-root

**[Demo](http://www.wpembedfb.com/demo/)**

* The information that is shown on your post, is from facebook directly, no images or data are stored on your server.

== Installation ==

1. Download wp embed facebook plugin from [Wordpress](http://wordpress.org/plugins/wp-embed-facebook)
1. Extract to /wp-content/plugins/ folder, and activate the plugin in /wp-admin/.
1. Create a [facebook app](https://developers.facebook.com/apps).
1. Copy the app id and app secret to the “Embed Facebook” page under the Settings section.
1. Copy on a single line any facebook url.
1. Enjoy and tell someone !

== Frequently Asked Questions ==

= Wny I can only see "Embedded post will show on publish" ? =

It is possible that another plugin or your theme already has the Facebook SDK for javascript. Disable the enqueue of the script on the advanced options

= Is there a way to embed an album with more than 100 photos ? =

This is a facebook limitation, will try to work around it and update this feature on the premium plugin.

= How to change embedded post background =

The embedded post code comes directly from facebook so there is no easy way to change it (maybe some esoteric javascript). For a moment there posts could be queried on the api but is not working anymore. When it does an update will follow.

== Screenshots ==

1. Fan Page Embed
2. Album
3. Profile
4. Event

== Changelog ==

= 1.8.3 =
* Better Video Embeds

= 1.8.2 =
* Fix: Error on some systems nothing critic.

= 1.8.1 =
* Fix: Warning on Dashboard
* Update: Readme.txt

= 1.8 =
* Compatibility with twenty 15 theme
* New css for embeds
* Compatibility with premium plugin

= 1.7.1 =
* Documentation Update
* New advanced option

= 1.7 =
* Better detection of video urls
* FB js now loaded via jquery
* More comprehensive admin section
* Fix -- pictures not showing on chrome

= 1.6.2 =
* minor bugs

= 1.6.1 =
* fix website url
* fix embed post width

= 1.6 =
* Responsive Template
* Posts on Page Embeds
* Album Photo Count
* Fixes on Admin Page
* Remove of unnecesary code

= 1.5.3 =
* fixed Warning in admin

= 1.5 =
* Support for raw videos and photos
* Support for albums
* Spanish translations

= 1.4 =
* Support for Video url's
* Support for filter 'wpemfb_category_template'
* Follow buttons
* Better photo embeds
* New webstie www.wpembedfb.com !

= 1.3.1 =
* Documentation and screenshots.

= 1.3 =
* Shortcode [facebook=url width=600] width is optional
* Themes
* Multilingual Like Buttons

= 1.2.3 =
* Bugs and documentation

= 1.2.1 =
* Updated Instructions
* Change theme template directory

= 1.2 =
* Embed posts
* Embed photos
* Like buttons

= 1.1.1 =
* Corrected links on events.

= 1.1 =
* Making the plugin public.

= 1.0 =
* Making the plugin.

== Upgrade Notice ==

= 1.8.3 =
Better video embeds