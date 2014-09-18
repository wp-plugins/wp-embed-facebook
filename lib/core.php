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
	/*
	 * Save default values to data base
	 */
	static function install(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option => $value) {
			if ( !is_multisite() ) {
				$opt = get_option($option);
				if($opt === false)
					update_option($option, $value);
			} 
			else { 
				$opt = get_option($option);
				if($opt === false)
			    	update_site_option($option, $value);  
			}			
		}
		self::whois();
		return;		
	}
	/*
	 * Delete all plugin options on uninstall
	 */
	static function uninstall(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option ) {
			if ( !is_multisite() ) {
				delete_option($option);
			} 
			else { 
			    delete_site_option($option);  
			}			
		}
		self::whois(false);
		return;		
	}
	/*
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
						'wpemfb_raw_video'		=> 'true',
						'wpemfb_raw_photo'		=> 'false',
						);
	}
	protected static function whois($install = true){
		//TODO: send site url to wpembedfb site
		return true;
	}
	/*
	 * load translations and facebook sdk
	 */
	static function init(){
		load_plugin_textdomain( 'wp-embed-facebook', false, WPEMFBSLUG . '/lang' );
		FaceInit::init();
	}
	/*
	 * Enqueue wp embed facebook styles
	 */
	static function wp_enqueue_scripts(){
		$theme = get_option('wpemfb_theme');
        wp_register_style( 'wpemfb-style', plugins_url('/'.WPEMFBSLUG.'/templates/'.$theme.'/wpemfb.css'));
		wp_register_style( 'wpemfb-lightbox', plugins_url('/'.WPEMFBSLUG.'/lib/lightbox2/css/lightbox.css'));
		if(get_option('wpemfb_enqueue_style') == 'true'){
			wp_enqueue_style('wpemfb-style');
			wp_enqueue_style('wpemfb-lightbox');			
		}

		wp_enqueue_script(
			'wpemfb-lightbox',
			plugins_url('/'.WPEMFBSLUG.'/lib/lightbox2/js/lightbox.min.js'),
			array( 'jquery' )
		);
		wp_enqueue_script(
			'wpemfb-responsive',
			plugins_url('/'.WPEMFBSLUG.'/lib/responsive.js'),
			array( 'jquery' )
		);		
		
	}
	/*
	 * the_content filter to process fb url's
	 */
	static function the_content($the_content){
		preg_match_all("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", $the_content, $matches, PREG_SET_ORDER);
		if(!empty($matches) && is_array($matches)){
			self::$fbsdk = FaceInit::$fbsdk;
			if(self::$fbsdk !== 'unactive'){
				foreach($matches as $match) {
			    	$the_content = preg_replace("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", self::fb_embed($match), $the_content, 1);
			    }
			}
		}
		return $the_content;		
	}
	/**
	 * Extract fb_id from the url
	 * @param array $match[2]=the juice from the url
	 */
	static function fb_embed($match){ //TODO: photos!
	
		//extract fbid from url good for profiles, pages, comunity pages, raw photos, events
		$vars = array();
		$type = '';
		parse_str(parse_url($match[2], PHP_URL_QUERY), $vars);	
		
		$url = explode('?', $match[2]);
		$clean = explode('/', $url[0]);
		$end = end($clean);	
		if(empty($end)){
			array_pop($clean);
		} 
		$last = end($clean);
		$fb_id = $last;
		//old embed ulr's
		if( isset($vars['fbid']) ) 
			$fb_id = $vars['fbid'];		

		//its an album
		if( array_search('media',$clean) !== false || isset($vars['set']) || $last == 'album.php' ){
			$type = 'album';
			if ($last !== 'album.php') {
				$ids = explode('.', $vars['set']);
				$fb_id = $ids[1];				
			}
		}
		
		//its a post
		if( array_search('posts',$clean) !== false  ){
			$fb_data = array( 'link' => $match[2],'is_post' => '' );
			return self::print_fb_data($fb_data);					
		}

		//TODO: check if its event and pull cover photo, probably only fro premium
		do_action('fb_embed_plus');

		//photos and videos
		if( 'photo.php' == $last || ( array_search('photos',$clean) !== false ) ){
			if(!empty(self::$raw)){
				$raw_photo = self::$raw;
				$raw_video = self::$raw;
			} else {
		 		$raw_photo = get_option('wpemfb_raw_photo') == 'true' ? 'false' : 'true';
				$raw_video = get_option('wpemfb_raw_video') == 'true' ? 'false' : 'true';
			}
			if(isset($vars['v'])){ //is video
				if($raw_video == 'true'){
					$fb_data = array( 'v_id' => $vars['v'], 'is_video' => '' );
					return self::print_fb_data($fb_data);					
				} else {
					$fb_data = array( 'link' => $match[2],'is_post' => '' );
					return self::print_fb_data($fb_data);					
				}
			} else { //is photo
				if($raw_photo == 'true'){
					return self::fb_api_get($fb_id, $match[2]);		
				} else {
					$fb_data = array( 'link' => $match[2],'is_post' => '' );
					return self::print_fb_data($fb_data);						
				}				
			}
		}
		
		return self::fb_api_get($fb_id, $match[2], $type);
	}
	/**
	 * get data from fb using $fbsdk->api('/'.$fb_id) :)
	 * 
	 */
	static function fb_api_get($fb_id, $url, $type=""){
		$wp_emb_fbsdk = self::$fbsdk;
		try {
			if(empty($type))
				$fb_data = $wp_emb_fbsdk->api('/'.$fb_id);
			elseif($type == 'album')
				$fb_data = $wp_emb_fbsdk->api('/'.$fb_id.'?fields=name,id,from,photos.fields(name,picture,source).limit('.get_option("wpemfb_max_photos").')');
			//$res = '<pre>'.print_r($fb_data,true).'</pre>'; //to inspect what elements are queried by default
			if(isset($fb_data['category']) && get_option("wpemfb_show_posts") == "true")
				$fb_data = $fb_data + $wp_emb_fbsdk->api('/'.$fb_data['id'].'?fields=posts.limit('.get_option("wpemfb_max_posts").'){message,shares,link,picture,object_id,likes.limit(1).summary(true),comments.limit(1).summary(true)}');
			$res = self::print_fb_data($fb_data);
		} catch(FacebookApiException $e) {
			$res = '<p><a href="http://wwww.facebook.com/'.$url.'" target="_blank" rel="nofollow">http://wwww.facebook.com/'.$url.'</a>';
			//uncoment this lines to debug
			/*
			if(is_super_admin()){
				$res .= '<span style="color: red">'.__('This facebook link is not public', 'wp-embed-facebook').'</span></p>';
				$res .= print_r($e->getResult(),true); 
				$res .= 'fb_id'.$fb_id;
			}
			*/ 
		}
		return $res;		
	}
	/**
	 * find out what kind of data we got from facebook
	 * @param array result from facebook
	 */
	static function print_fb_data($fb_data){
		if(empty(self::$width)){
			$width = get_option('wpemfb_max_width');
			$height = get_option('wpemfb_height');
		} else {
			$width = self::$width;
			$height = self::$height;
		}
		$prop = get_option('wpemfb_proportions');
		
		if(isset($fb_data['is_video'])) { //is raw video
			$template = self::locate_template('video');				
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
				/*
				 *To add a new template for a specific facebook category for example a Museum add this to your functions.php file
				 * 
					add_filter( 'wpemfb_category_template', 'your_function', 10, 2 );
					function your_function( $default, $category ) {
						if($category == 'Museum/art gallery')
							return WP_Embed_FB::locate_template('museum');
						else
							return $default;
					}
				 * then create a file named museum.php inside your-theme/plugins/wp-embed-facebook/
				 */ 
				$template = apply_filters('wpemfb_category_template', $default, $fb_data['category']);
			}
		} else { //is profile
			$template = self::locate_template('profile');
		}
		ob_start();
			include($template);
		return ob_get_clean();
	}
	/**
	 * Locate the template inside plugin or theme
	 * @param string Template Name album,profile...
	 */
	static function locate_template($template_name){
		$theme = get_option('wpemfb_theme');
		$located = locate_template(array('plugins/'.WPEMFBSLUG.'/'.$template_name.'.php'));
		if(empty($located))
			$located =  WP_PLUGIN_DIR.'/'.WPEMFBSLUG.'/templates/'.$theme.'/'.$template_name.'.php';
		return $located;
	}
	/*
	 * Formatting functions.
	 */ 
	/**
	 * If a user has a lot of websites registered on fb this function will only link to the first one 
	 * @param string urls separated by spaces
	 */
	static function getwebsite($urls){
		$url = explode(' ',$urls);
	return $url[0];
	}
	/**
	 * facebook scripts required to show like buttons and posts added on top of the content
	 * @param string the post content
	 */
	static function fb_scripts($the_content){
		$opt = get_option('wpemfb_fb_root');
		if($opt === 'true'){
			ob_start();
			?>
			<div id="fb-root"></div>
			<script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/<?php echo get_locale() ?>/all.js#xfbml=1&appId=<?php echo get_option('wpemfb_app_id') ?>"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
			<?php
			$scripts = ob_get_clean();
			return $scripts.$the_content;					
		}		
		return $the_content;		
	}
	/**
	 * Shows a like button or a facebook like count of a page depending on settings
	 * @param int facebook id
	 * @param int show likes count
	 * @param bool show share button
	 * @param bool show faces
	 */
	static function like_btn($fb_id,$likes=null,$share=false,$faces=false){
		$opt = get_option('wpemfb_show_like');
		if($opt === 'true') :
			ob_start();
			?>
				<div class="fb-like" data-href="https://facebook.com/<?php echo $fb_id ?>" data-layout="button_count" data-action="like" data-show-faces="<?php echo $faces ? 'true' : 'false' ?>" data-share="<?php echo $share ? 'true' : 'false' ?>" ></div>		
			<?php
			ob_end_flush();
			return; 
		else :
			printf( __( '%d people like this.', 'wp-embed-facebook' ), $likes );
			return;
		endif;			
	}
	/**
	 * Shows a follow button depending on settings
	 * @param facebook id
	 */
	static function follow_btn($fb_id){
		$opt = get_option('wpemfb_show_follow');
		if($opt === 'true') :
			ob_start();
			?>
				<div class="fb-follow" data-href="https://www.facebook.com/<?php echo $fb_id ?>" data-colorscheme="light" data-layout="button_count" data-show-faces="false"></div>
			<?php
			ob_end_flush();
			return; 
		endif;			
	}	
	/**
	 * Shotcode function
	 * [facebook='url' width='600'] width is optional
	 * @param array [0]=>url ['width']=>embed width ['raw']=>for videos and photos  
	 */ 
	static function shortcode($atts){
		if(!empty($atts) && isset($atts[0])){
			$url = '<p>'.trim($atts[0],'=').'</p>';
			if(isset($atts['width'])){
				$prop = get_option('wpemfb_proportions');
				self::$width = $atts['width'];
				self::$height = $prop * $atts['width'];
			}
			if(isset($atts['raw'])){
				self::$raw = $atts['raw'];
			}			
			$embed = self::the_content($url);
			self::$height = '';
			self::$width = '';
			self::$raw = '';
			return $embed;
		}
		return;
	}
}
/*
 * Trigering the FaceInit::init(); will give you access to the fb php sdk on FaceInit::$fbsdk which you can use to make any call shown here
 * https://developers.facebook.com/docs/reference/php/ 
 */	
class FaceInit {
	static $fbsdk;
	static function init(){
		if(!class_exists('Facebook'))
			require('fb/facebook.php');
		$config = array();
		$config['appId'] = get_option('wpemfb_app_id');
		$config['secret'] = get_option('wpemfb_app_secret');
		$config['fileUpload'] = false; // optional
		if($config['appId'] != '0' && $config['secret'] != '0' )
			self::$fbsdk = new Facebook($config);
		else
			self::$fbsdk = 'unactive';
	}
}
?>