<?php
/*
 * Main Class of the plugin.
 */

class WP_Embed_FB {
	static $fbsdk;
	static $width = '';
	static $height = '';
	static $theme = '';
	static $raw = '';
	static $num_posts = '';
	/**
	 * Save default values to data base
	 */
	static function install(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option => $value) {
			$opt = get_option($option);
			if($opt === false)
				update_option($option, $value);
			if(!isset($type))
				$type = $opt==false?"activated":"reactivated";
		}
		self::whois($type);
		return;
	}
	/**
	 * Delete all plugin options on uninstall
	 */
	static function uninstall(){
		$defaults = self::getdefaults();
		if ( is_multisite() ) {
			$sites = wp_get_sites();
			foreach ($sites as $site) {
				switch_to_blog($site['blog_id']);
				foreach ($defaults as $option => $value ) {
					delete_option($option);
				}
			}
			restore_current_blog();
		} else {
			foreach ($defaults as $option => $value ) {
				delete_option($option);
			}
		}
		self::whois('uninstalled');
		return;
	}
	static function deactivate(){
		self::whois('deactivated');
		return;
	}
	/**
	 * Default options
	 */
	static function getdefaults(){
		return array(
			'wpemfb_max_width' 		=> '450',
			'wpemfb_max_photos' 	=> '25',
			'wpemfb_max_posts' 		=> '2',
			'wpemfb_show_posts' 	=> 'false',
			'wpemfb_enqueue_style' 	=> 'true',
			'wpemfb_app_id' 		=> '0',
			'wpemfb_app_secret'		=> '0',
			'wpemfb_proportions' 	=> 0.36867,
			'wpemfb_height'			=> '221.202',
			'wpemfb_show_like'		=> 'true',
			'wpemfb_fb_root'		=> 'true',
			'wpemfb_theme'			=> 'default',
			'wpemfb_show_follow'	=> 'true',
			'wpemfb_raw_video'		=> 'false',
			'wpemfb_raw_photo'		=> 'false',
			'wpemfb_raw_post'		=> 'false',
			'wpemfb_enq_lightbox'	=> 'true',
			'wpemfb_enq_wpemfb'		=> 'true',
			'wpemfb_enq_fbjs'		=> 'true',
			'wpemfb_ev_local_tz'    => 'true',
			'wpemfb_raw_video_fb'   => 'false',
		);
	}
	//("uninstalled","deactivated","activated","reactivated")
	protected static function whois($install){
		$home = home_url();
		$home = esc_url($home);
		@file_get_contents("http://www.wpembedfb.com/api/?whois=$install&site_url=$home");
		return true;
	}
	/**
	 * load translations and facebook sdk
	 */
	static function init(){
		load_plugin_textdomain( 'wp-embed-facebook', false, WPEMFBSLUG . '/lang' );
		FaceInit::init();
	}
	/**
	 * Enqueue wp embed facebook styles
	 */
	static function wp_enqueue_scripts(){
		if(get_option('wpemfb_enqueue_style') == 'true'){
			$theme = get_option('wpemfb_theme');
			wp_register_style( 'wpemfb-style', WPEMFBURL.'templates/'.$theme.'/wpemfb.css');
			wp_register_style( 'wpemfb-lightbox', WPEMFBURL.'lib/lightbox2/css/lightbox.css');
			wp_enqueue_style('wpemfb-style');
			wp_enqueue_style('wpemfb-lightbox');
		}
		if(get_option('wpemfb_enq_lightbox') == 'true'){
			wp_enqueue_script(
				'wpemfb-lightbox',
				WPEMFBURL.'lib/lightbox2/js/lightbox.min.js',
				array( 'jquery' )
			);
		}
		if(get_option('wpemfb_enq_wpemfb') == 'true'){
			wp_enqueue_script(
				'wpemfb',
				WPEMFBURL.'lib/js/wpembedfb.js',
				array( 'jquery' )
			);
		}
		if(get_option('wpemfb_enq_fbjs') == 'true'){
			wp_enqueue_script(
				'wpemfbjs',
				WPEMFBURL.'lib/js/fb.js',
				array( 'jquery' )
			);
			$translation_array = array( 'local' => get_locale(), 'fb_id'=>get_option('wpemfb_app_id') );
			wp_localize_script( 'wpemfbjs', 'WEF', $translation_array );
		}
	}
	/**
	 * Find facebook urls on a string and return content
	 * @param string post content
	 * @return string content with embeds if present.
	 */
	static function the_content($the_content){
		preg_match_all("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", $the_content, $matches, PREG_SET_ORDER);
		if(!empty($matches) && is_array($matches)){
			foreach($matches as $match) {
				$the_content = preg_replace("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", self::fb_embed($match), $the_content, 1);
			}
		}
		return $the_content;
	}
	/**
	 * Extract fb_id from the url
	 * @param array $match[2]=url without ' https://www.facebook.com/ '
	 * @return string Embedded content
	 */
	static function fb_embed($match){
		self::$fbsdk = FaceInit::get_fbsdk();
		if(self::$fbsdk !== 'unactive') {
			//extract fbid from url good for profiles, pages, comunity pages, raw photos, events
			$vars = array();
			$type = '';
			parse_str(parse_url($match[2], PHP_URL_QUERY), $vars);

			$url = explode('?', $match[2]);
			$clean = explode('/', $url[0]);
			$end = end($clean);
			if (empty($end)) {
				array_pop($clean);
			}
			$last = end($clean);
			$fb_id = $last;
			//old embed ulr's
			if (isset($vars['fbid']))
				$fb_id = $vars['fbid'];

			//its an album
			if (array_search('media', $clean) !== false || isset($vars['set']) || $last == 'album.php') {
				$type = 'album';
				if ($last !== 'album.php') {
					$ids = explode('.', $vars['set']);
					$fb_id = $ids[1];
				}
			}
			/**
			 * Filter the embed type.
			 *
			 * @since 1.8
			 *
			 * @param string $type the embed type.
			 * @param array $clean url parts of the request.
			 */
			$type = apply_filters('wpemfb_embed_type', $type, $clean);
			//its a post
			if (array_search('posts', $clean) !== false) {
				$fb_data = array('link' => $match[2], 'is_post' => '');
				return self::print_fb_data($fb_data);
			}
			if (isset($vars['v'])) { //is video
				$fb_id = $vars['v'];
				return self::fb_api_get($fb_id, $match[2], 'video');
			}
			if (!empty(self::$raw)) {
				$raw_photo = self::$raw;
			} else {
				$raw_photo = get_option('wpemfb_raw_photo') == 'true' ? 'true' : 'false';
			}
			//photos
			if ('photo.php' == $last || (array_search('photos', $clean) !== false)) {
				if ($raw_photo == 'true') {
					return self::fb_api_get($fb_id, $match[2]);
				} else {
					$fb_data = array('link' => $match[2], 'is_post' => '');
					return self::print_fb_data($fb_data);
				}
			}
			return self::fb_api_get($fb_id, $match[2], $type);
		} else {
			$return = '';
			if(is_super_admin()){
				$return .= '<p>'.__('Add Facebook App ID and Secret on admin to make this plugin work.','wp-embed-facebook').'</p>';
				$return .= '<p><a href="'.admin_url("options-general.php?page=embedfacebook").'" target="_blank">'.__("WP Embed Facebook Settings","wp-embed-facebook").'</a></p>';
				$return .= '<p><a href="https://developers.facebook.com/apps" target="_blank">'.__("Your Facebook Apps","wp-embed-facebook").'</a></p>';
			}
			$return .= '<p><a href="https://www.facebook.com/'.$match[2].'" target="_blank" rel="nofollow">https://www.facebook.com/'.$match[2].'</a></p>';
			return $return;
		}

	}
	/**
	 * get data from fb using WP_Embed_FB::$fbsdk->api('/'.$fb_id) :)
	 *
	 * @param int facebook id
	 * @param string facebook url
	 * @type string type of embed
	 * @return facebook api resulto or error
	 */
	static function fb_api_get($fb_id, $url, $type=""){
		$wp_emb_fbsdk = self::$fbsdk;
		try {
			if($type == 'album')
				$api_string = $fb_id.'?fields=name,id,from,description,photos.fields(name,picture,source).limit('.get_option("wpemfb_max_photos").')';
			else
				$api_string = $fb_id;

			/**
			 * Filter the fist fbsdk query
			 *
			 * @since 1.9
			 *
			 * @param string $api_string The fb api request string according to type
			 * @param string $fb_id The id of the object being requested.
			 * @param string $type The detected type of embed
			 *
			 */
			$fb_data = $wp_emb_fbsdk->api('/v2.3/'.apply_filters('wpemfb_api_string',$api_string,$fb_id,$type));
			$num_posts = self::$num_posts !== '' && is_numeric(self::$num_posts) ? self::$num_posts : get_option("wpemfb_max_posts");
			$api_string2 = '';
			if(isset($fb_data['embed_html']))
				$fb_data = array_merge($fb_data,array('is_video' => '1','link'=>$url));
			elseif( isset($fb_data['category']) && get_option("wpemfb_show_posts") == "true")
				$api_string2 = '/'.$fb_data['id'].'?fields=posts.limit('.$num_posts.'){message,shares,link,picture,object_id,likes.limit(1).summary(true),comments.limit(1).summary(true)}';

			/**
			 * Filter the second fbsdk query if necessary
			 *
			 * @since 1.9
			 *
			 * @param string $api_string2 The second request string empty if not necessary
			 * @param array $fb_data The result from the first query
			 * @param string $type The detected type of embed
			 *
			 */
			$api_string2 = apply_filters('wpemfb_2nd_api_string',$api_string2,$fb_data,$type);

			if(!empty($api_string2)){
				$extra_data = $wp_emb_fbsdk->api('/v2.3/'.$api_string2);
				$fb_data = array_merge($fb_data,$extra_data);
			}
			/**
			 * Filter all data received from facebook.
			 *
			 * @since 1.9
			 *
			 * @param array $fb_data the final result
			 * @param string $type The detected type of embed
			 */
			$fb_data = apply_filters('wpemfb_fb_data',$fb_data,$type);

			$res = self::print_fb_data($fb_data);
		} catch(FacebookApiException $e) {
			$res = '<p><a href="https://www.facebook.com/'.$url.'" target="_blank" rel="nofollow">https://www.facebook.com/'.$url.'</a>';
			if(is_super_admin()){
				$error = $e->getResult();
				$res .= '<br><span style="color: #4a0e13">'.__('Error').':&nbsp;'.$error['error']['message'].'</span>';
			}
			$res .= '</p>';
		}
		return $res;
	}
	/**
	 * find out what kind of data we got from facebook
	 *
	 * @param array $fb_data result from facebook
	 * @return string embedded content
	 */
	static function print_fb_data($fb_data){
		if(isset($fb_data['is_video'])) { //is raw video
			if (!empty(self::$raw)) {
				$raw_video = self::$raw;
			} else {
				$raw_video = get_option('wpemfb_raw_video') == 'true' ? 'true' : 'false';
			}
			if ($raw_video == 'true') {
				$template = self::locate_template('video');
			} else {
				$template = self::locate_template('posts');
			}
		} elseif(isset($fb_data['is_post'])) { //is post
			$template = self::locate_template('posts');
		} elseif(isset($fb_data['start_time'])) { //is event
			$template = self::locate_template('event');
		} elseif(isset($fb_data['photos'])) { //is album
			$template = self::locate_template('album');
		} elseif(isset($fb_data['width'])) { //is raw photo
			$template = self::locate_template('photo');
		} elseif(isset($fb_data['category'])) { //is page
			if(isset($fb_data['is_community_page']) && $fb_data['is_community_page'] == "1" ){
				$template = self::locate_template('com-page'); //is community page
			}else {
				$default = self::locate_template('page');
				/**
				 * Add a new template for a specific facebook category
				 *
				 * for example a Museum create the new template at your-theme/plugins/wp-embed-facebook/museum.php
				 * then on functions.php of your theme
				 *
				 * add_filter( 'wpemfb_category_template', 'your_function', 10, 2 );
				 *
				 * function your_function( $default, $category ) {
				 *      if($category == 'Museum/art gallery')
				 *          return WP_Embed_FB::locate_template('museum');
				 *      else
				 *      return $default;
				 * }
				 *
				 * @since 1.0
				 *
				 * @param string $default file full path
				 * @param array $fb_data['category']  data from facebook
				 */
				$template = apply_filters('wpemfb_category_template', $default, $fb_data['category']);
			}
		} else { //is profile
			$template = self::locate_template('profile');
		}
		//get default variables to use on templates
		if(empty(self::$width)){
			$width = get_option('wpemfb_max_width');
			$height = get_option('wpemfb_height');
		} else {
			$width = self::$width;
			$height = self::$height;
		}
		$prop = get_option('wpemfb_proportions');
		ob_start();
		//show embed post on admin
		if(is_admin() && isset($fb_data['is_post'])) : ?>
			<script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
		<?php endif;
		/**
		 * Change the file to include on a certain embed.
		 *
		 * @since 1.8
		 *
		 * @param string $template file full path
		 * @param array $fb_data data from facebook
		 */
		$template = apply_filters('wpemfb_template',$template,$fb_data);
		include( $template );
		return preg_replace('/^\s+|\n|\r|\s+$/m', '', ob_get_clean());
	}
	/**
	 * Locate the template inside plugin or theme
	 *
	 * @param string $template_name Template file name
	 * @return string Template location
	 */
	static function locate_template($template_name){
		$theme = get_option('wpemfb_theme');
		$located = locate_template(array('plugins/'.WPEMFBSLUG.'/'.$template_name.'.php'));
		$file = 'templates/'.$theme.'/'.$template_name.'.php';
		if(empty($located)){
			$located =  WPEMFBDIR.$file;
		}
		return $located;
	}
	/*
	 * Formatting functions.
	 */
	/**
	 * If a user has a lot of websites registered on fb this function will only link to the first one
	 * @param string $urls separated by spaces
	 * @return string first url
	 */
	static function getwebsite($urls){
		$url = explode(' ',$urls);
		return $url[0];
	}
	/**
	 * Shows a like button or a facebook like count of a page depending on settings
	 * @param int $fb_id facebook id
	 * @param int $likes show likes count
	 * @param bool $share show share button
	 * @param bool $faces show faces
	 */
	static function like_btn($fb_id,$likes=null,$share=false,$faces=false){
		$opt = get_option('wpemfb_show_like');
		if($opt === 'true') :
			ob_start();
			?>
			<div class="fb-like" data-href="https://facebook.com/<?php echo $fb_id ?>" data-layout="button_count" data-action="like" data-show-faces="<?php echo $faces ? 'true' : 'false' ?>" data-share="<?php echo $share ? 'true' : 'false' ?>" >
			</div>
			<?php
			echo ob_get_clean();
			return;
		else :
			printf( __( '%d people like this.', 'wp-embed-facebook' ), $likes );
			return;
		endif;
	}
	/**
	 * Shows a follow button depending on settings
	 * @param int $fb_id facebook id or username
	 */
	static function follow_btn($fb_id){
		$opt = get_option('wpemfb_show_follow');
		if($opt === 'true') :
			ob_start();
			?>
			<div class="fb-follow" data-href="https://www.facebook.com/<?php echo $fb_id ?>" data-colorscheme="light" data-layout="button_count" data-show-faces="false">
			</div>
			<?php
			ob_end_flush();
			return;
		endif;
	}
	/**
	 * Shortcode function
	 * [facebook='url' width='600'] width is optional
	 * @param array $atts [0]=>url ['width']=>embed width ['raw']=>for videos and photos
	 * @return string
	 */
	static function shortcode($atts){
		if(!empty($atts) && isset($atts[0])){
			$clean = trim($atts[0],'=');
			if( is_numeric($clean) )
				$url = '<p>https://www.facebook.com/'.$clean.'/</p>';
			else
				$url = '<p>'.$clean.'</p>';

			if(isset($atts['width'])){
				$prop = get_option('wpemfb_proportions');
				self::$width = $atts['width'];
				self::$height = $prop * $atts['width'];
			}
			if(isset($atts['raw'])){
				self::$raw = $atts['raw'];
			}
			if(isset($atts['posts'])){
				self::$num_posts = $atts['posts'];
			}
			$embed = self::the_content($url);
			self::$height = self::$width = self::$raw = self::$num_posts = '';
			return $embed;
		}
		return '';
	}
	static function embed_register_handler($match){
		return self::fb_embed($match);
	}
	static function fb_root($ct){
		$root = '<div id="fb-root"></div>';
		$root .= "\n";
		return $root.$ct;
	}
}

/**
 * Triggering the FaceInit::init(); will give you access to the fb php sdk on FaceInit::$fbsdk which you can use to make any call shown here
 * https://developers.facebook.com/docs/reference/php/
 */
class FaceInit {
	static $fbsdk = null;
	static function init(){
		require('fb/facebook.php');
		$config = array();
		$config['appId'] = get_option('wpemfb_app_id');
		$config['secret'] = get_option('wpemfb_app_secret');
		$config['fileUpload'] = false; // optional
		if($config['appId'] != '0' && $config['secret'] != '0' )
			self::$fbsdk = new Sigami_Facebook($config);
		else
			self::$fbsdk = 'unactive';
	}
	static function get_fbsdk(){
		if(self::$fbsdk == null)
			self::init();
		return self::$fbsdk;
	}
}