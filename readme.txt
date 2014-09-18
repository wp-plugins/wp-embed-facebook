=== WP Embed Facebook ===
Contributors: poxtron
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=J373TWCMJ5CEY&lc=MX&item_name=WP%20Embed%20Facebook&no_note=1&no_shipping=1&rm=1&return=http%3a%2f%2fwww%2ewpembedfb%2ecom%2fyou%2dare%2dawesome&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: Facebook, facebook, Social Plugins, embed facebook, facebook video, facebook posts, facebook publication, facebook publications, facebook event, facebook events, facebook pages, facebook page, facebook profiles, facebook album, facebook albums, facebook photos, facebook photo, social,
Requires at least: 3.8.1
Tested up to: 4.0
Stable tag: 1.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a Facebook video, page, event, album, photo, profile or post to any Wordpress post or page.

== Description ==

With this plugin you can embed any public facebook video, page, post, profile, photo or event directly into a wordpress post, without having to write a single line of code. Simply put the facebook url on a separate line on the content of any post, and this plugin will try to fetch data associated with that url and display it on publishing, if the data is not public, like “invite only” events or private profiles, it'll return a link.

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
Or you can use a shortcode
`[facebook=url width=200 raw=true]` 
width and raw are optional, raw only works for videos and photos
example
Album
`[facebook=https://www.facebook.com/zuck/media_set?set=a.941146602501.2418915.4&type=3]`
Video

= Options = 
In Settings > Embed Facebook.
* Change embed width
* Show like buttons on embedded Facebook pages and photos
* Show Follow Button
* Remove plugin styles
* Change Theme
* Add fb-root

**[Demo](http://www.saliuitl.org/en/wp-embed-facebook)**

* The information that shown on your post, is from facebook directly, no images or data are stored on your server. 

= On the next version =
* Embed Post Raw 
* Premium Version

== Installation ==

1. Download wp embed facebook plugin from [Wordpress](http://wordpress.org/plugins/wp-embed-facebook)
1. Extract to /wp-content/plugins/ folder, and activate the plugin in /wp-admin/.
1. Create a [facebook app](https://developers.facebook.com/apps).
1. Copy the app id and app secret to the “Embed Facebook” page under the Settings section.
1. Copy on a single line any facebook url.
1. Enjoy and tell someone !

== Customization ==
 
1. Copy the contents of `wp-embed-facebook/templates/default/` to `your-theme/plugins/wp-embed-facebook` 
1. Untick "Enqueue Styles" option to use your own css. 
1. Access all facebook data retrieved from the url using `print_r($fb_data)` on any template file.
1. Use WP_Embed_FB::like_btn($fb_id,$likes=null,$share=false,$faces=false) to personalize like button.
1. Click the taco ! ;)

== Changelog ==

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
