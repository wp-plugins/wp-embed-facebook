=== WP Embed Facebook ===
Contributors: poxtron
Donate link: http://www.wpembedfb.com/donate
Tags: facebook, embed, opengraph, fbsdk, facebook events, facebook pages, facebook profiles, facebook videos, facebook posts,
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a Facebook page, post, event, photo or profile to any Wordpress post or page.

== Description ==

With this plugin you can embed any public facebook page, post, profile, photo or event directly into a wordpress post, without having to write a single line of code. Simply put the facebook url on a separate line on the content of any post, and this plugin will try to fetch data associated with that url and display it on publishing, if the data is not public, like “invite only” events or private profiles, it'll return a link.

= Supported Embeds =
* Profiles
* Community pages
* Fan pages
* Videos
* Events
* Posts
* Photos

= Requirements =
* Facebook App id and Secret

= How to use it =
Put on a single and separate line the Facebook URL.
Or you can use a shortcode
`[facebook=url width=200]` 
width is optional

= Options = 
In Settings > Embed Facebook.
* Change embed width
* Show like buttons on embedded Facebook pages and photos
* Remove plugin styles
* Change Theme
* Add fb-root

**[Demo](http://www.wpembedfb.com)**

* The information that shown on your post, is from facebook directly, no images or data are stored on your server. 

= On the next version : =
* Page Albums
* Responsive theme

== Installation ==

1. Download wp embed facebook plugin from [Wordpress](http://wordpress.org/plugins/wp-embed-facebook)
1. Extract to /wp-content/plugins/ folder, and activate the plugin in /wp-admin/.
1. Create a [facebook app](https://developers.facebook.com/apps).
1. Copy the app id and app secret to the “Embed Facebook” page under the Settings section.
1. Copy on a single line any facebook url.
1. Enjoy and tell someone !

== Customisation ==
 
1. Copy the contents of `wp-embed-facebook/templates/default/` to `your-theme/plugins/wp-embed-facebook` 
1. Untick "Enqueue Styles" option to use your own css. 
1. Access all facebook data retrieved from the url using `print_r($fb_data)` on any template file.
1. Use WP_Embed_FB::like_btn($fb_id,$likes=null,$share=false,$faces=false) to personalize like button.
1. Use WP_Embed_FB::follow_btn($fb_id); to display de follow button
1. Click the taco ! ;)

== Changelog ==

= 1.4 =
* Support for Video url's
* Support for filter 'wpemfb_category_template'
* Follow buttons
* Better photo embeds
* New webstie www.wpembedfb.com !

= 1.3.1 =
* Documentation and demo.

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
