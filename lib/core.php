<?php
class WP_Embed_FB {
	static $fbsdk;
	protected static $_values = array();
	static function install(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option => $value) {
			if ( !is_multisite() ) {
				$opt = get_option($option);
				if($opt === false)
					update_option($option, $value);
			} 
			else { // TODO: multiple fb apps for each site
				$opt = get_option($option);
				if($opt === false)
			    	update_site_option($option, $value);  
			}			
		}
		return;		
	}
	static function uninstall(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option ) {
			if ( !is_multisite() ) {
				delete_option($option);
			} 
			else { // TODO: multiple fb apps for each site
			    delete_site_option($option);  
			}			
		}
		return;		
	}	
	static function getdefaults(){
		return array(
						'wpemfb_max_width' 		=> '600',
						'wpemfb_enqueue_style' 	=> 'true',
						//TODO: Quitar esto ponerlo en 0
						'wpemfb_app_id' 		=> '0',
						'wpemfb_app_secret'		=> '0',
						'wpemfb_proportions' 	=> 0.36867,
						'wpemfb_height'			=> '221.202',
						'wpemfb_show_like'		=> 'true',
						);
	}
	static function wp_enqueue_scripts(){
		
        wp_register_style( 'wpemfb-style', plugins_url('../templates/default/wpemfb.css', __FILE__) );
        wp_enqueue_style( 'wpemfb-style' );		
	}
	static function the_content($the_content){
		preg_match_all("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", $the_content, $matches, PREG_SET_ORDER);
		if(!empty($matches) && is_array($matches)){
			self::$fbsdk = FaceInit::$fbsdk;
			if(self::$fbsdk !== 'unactive')
			    foreach($matches as $match) {
			    	$the_content = preg_replace("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", self::fb_embed($match), $the_content, 1);
			    }			
		}

		return self::fb_scripts().$the_content;		
	}
	static function fb_scripts(){
		ob_start();
		?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
		<?php
		return ob_get_clean();		
	}
	static function like_btn($fb_id,$likes=null){
		$opt = get_option('wpemfb_show_like');
		if($opt === 'true') :
			ob_start();
			?>
				<div class="fb-like" data-href="https://facebook.com/<?php echo $fb_id ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>		
			<?php
			ob_end_flush();
			return; 
		else :
			printf( __( '%d people like this.', 'wp-embed-fb' ), $likes );
			return;
		endif;			
	}
	static function fb_embed($match){ //TODO: photos!
		$vars = array();
		parse_str(parse_url($match[2], PHP_URL_QUERY), $vars);
		if(isset($vars['fbid'])){ //for photos deprecated by fb 
			$fb_id = $vars['fbid'];
		} else {
			$url = explode('?', $match[2]);
			$clean = explode('/', $url[0]);
			$end = end($clean);	
			if(empty($end)){
				array_pop($clean);
			} 
			$fb_id = end($clean);
			if( $key = array_search('posts',$clean) !== false ){
				$user = $clean[$key -1];
				$post = $clean[$key +1];
				$fb_data = array('user' => $user ,'is_post' => $post);
				return self::print_fb_data($fb_data);				
			} else {
			$wp_emb_fbsdk = self::$fbsdk;
			try {
				$fb_data = $wp_emb_fbsdk->api('/'.$fb_id);
				//$res = '<pre>'.print_r($fb_data,true).'</pre>'; //to inspect what elements are queried by default
				$res = self::print_fb_data($fb_data);
			} catch(FacebookApiException $e) {
				$res = '<p><a href="http://wwww.facebook.com/'.$match[2].'" target="_blank" rel="nofollow">http://wwww.facebook.com/'.$match[2].'</a><br>';
				if(is_super_admin()){
					$res .= '<span style="color: red">'.__('This facebook link is not public', 'wp-embed-fb').'</span></p>';
					$res .= print_r($e->getResult(),true); //see the problem here
				}
					 
			}
			return $res;	
			}			
		}
		return self::fb_api_get($fb_id, $match[2]);
	}
	static function fb_api_get($fb_id, $url){
		$wp_emb_fbsdk = self::$fbsdk;
		try {
			$fb_data = $wp_emb_fbsdk->api('/'.$fb_id);
			//$res = '<pre>'.print_r($fb_data,true).'</pre>'; //to inspect what elements are queried by default
			$res = self::print_fb_data($fb_data);
		} catch(FacebookApiException $e) {
			$res = '<p><a href="http://wwww.facebook.com/'.$url.'" target="_blank" rel="nofollow">http://wwww.facebook.com/'.$url.'</a>';
			if(is_super_admin()){
				$res .= '<span style="color: red">'.__('This facebook link is not public', 'wp-embed-fb').'</span></p>';
				$res .= print_r($e->getResult(),true); //see the problem here
				$res .= 'fb_id'.$fb_id;
			}
				 
		}
		return $res;		
	}
	static function print_fb_data($fb_data){
		$width = get_option('wpemfb_max_width');
		$height = get_option('wpemfb_height'); 
		if(isset($fb_data['start_time'])) { //is event
			$template = self::locate_template('event');
		//} elseif(isset($fb_data['source'])) { //is photo Deprecated by facebook now embed post
			//$template = self::locate_template('photo');
		} elseif(isset($fb_data['category'])) { //is a page
			if(isset($fb_data['is_community_page']) && $fb_data['is_community_page'] == "1" ){
				$template = self::locate_template('com-page'); //is community page
			}else {
				switch ($fb_data['category']) {
					case 'Musician/band': //is a band page
						$template = self::locate_template('band');
					break; //TODO: add action to add more categories
					default:
						$template = self::locate_template('other');
					break;
				}
			}
		} elseif(isset($fb_data['is_post'])) {
			$template = self::locate_template('posts');
		} elseif(isset($fb_data['width'])) {
			$template = self::locate_template('photo');
		}else { //is profile
			$template = self::locate_template('profile');
		}
		ob_start();
		include($template);
		return ob_get_clean();
	}
	static function locate_template($template_name){
		$located = locate_template(array('plugins/wp-embed-facebook/'.$template_name.'.php'));
		if( empty($located) ){
			$located =  plugin_dir_path( __FILE__ ).'../templates/default/'.$template_name.'.php';
		}
		return $located;
	}
	static function getwebsite($urls){
		$url = explode(' ',$urls);
		$clean = explode('?', $url[0]);
		$cleaner = str_replace(array('http://', 'https://'), array('',''), $clean[0]);
		$ret = '<a href="http://'.$cleaner.'" title="'.__('Web Site', 'wp-embed-fb').'" target="_blank">'.__('Web Site','wp-embed-fb').'</a>';
	return $ret;
	}
	static function fb_categories($category){
		$fbcats = array(	
							__('Museum/art gallery') ,
							__('Local business'),
							__('Concert venue'),
							__('Public places'),
						);
		//$catsflip = array_flip($fbcats); TODO: Translate categories
		if($id = array_search($category, $fbcats) !== false)
			echo $fbcats[$id];
		else
			echo (string)$category;
		return;
		//$replace = array('Museo - Galería de Arte','Negocio Local','Sala de Conciertos','Espacio público');
	}
}
	
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