<?php
//TODO add option 
class EmbFbAdmin{
	static function add_page(){
		add_options_page('EmbedFacebook', 'Embed Facebook', 'manage_options', 'embedfacebook', array('EmbFbAdmin', 'embedfb_page'));
	}
	static function savedata(){
		if(isset($_POST['wpemfb_app_secret'], $_POST['wpemfb_app_id' ] )) {
			if($_POST['wpemfb_app_id'] && $_POST['wpemfb_app_secret']){
				update_option('wpemfb_app_id',$_POST['wpemfb_app_id']);
				update_option('wpemfb_app_secret',$_POST['wpemfb_app_secret']);
			}
			if(isset($_POST['wpemfb_max_width'])){
				$prop = get_option('wpemfb_proportions') * $_POST['wpemfb_max_width'];
				update_option('wpemfb_max_width', $_POST['wpemfb_max_width']);
				update_option('wpemfb_height', $prop );
			}
			if(isset($_POST['wpemfb_theme'])){
				update_option('wpemfb_theme', $_POST['wpemfb_theme']);
			}			
			if(isset($_POST['wpemfb_show_like'])){
				update_option('wpemfb_show_like', 'true');
			}else{
				update_option('wpemfb_show_like', 'false');
			}			
			if(isset($_POST['wpemfb_enqueue_style'])){
				update_option('wpemfb_enqueue_style', 'true');
			}else{
				update_option('wpemfb_enqueue_style', 'false');
			}
			if(isset($_POST['wpemfb_fb_root'])){
				update_option('wpemfb_fb_root', 'true');
			}else{
				update_option('wpemfb_fb_root', 'false');
			}
			if(isset($_POST['wpemfb_show_follow'])){
				update_option('wpemfb_show_follow', 'true');
			}else{
				update_option('wpemfb_show_follow', 'false');
			}
			if(isset($_POST['wpemfb_raw_video'])){
				update_option('wpemfb_raw_video', 'true');
			}else{
				update_option('wpemfb_raw_video', 'false');
			}
			if(isset($_POST['wpemfb_raw_photo'])){
				update_option('wpemfb_raw_photo', 'true');
			}else{
				update_option('wpemfb_raw_photo', 'false');
			}
		}
	}
	static function embedfb_page() {
		if(isset($_POST['submit']) && check_admin_referer( 'wp-embed-facebook','save-data' )){
			self::savedata();
		}
		$checked = (get_option('wpemfb_enqueue_style') === 'true') ? 'checked' : '' ;
		$checked2 = (get_option('wpemfb_show_like') === 'true') ? 'checked' : '' ;
		$checked3 = (get_option('wpemfb_fb_root') === 'true') ? 'checked' : '' ;
		$checked4 = (get_option('wpemfb_show_follow') === 'true') ? 'checked' : '' ;
		$checked5 = (get_option('wpemfb_raw_video') === 'true') ? 'checked' : '' ;
		$checked6 = (get_option('wpemfb_raw_photo') === 'true') ? 'checked' : '' ;
		$sel1 = (get_option('wpemfb_theme') === 'default') ? 'selected' : '' ;
		$sel2 = (get_option('wpemfb_theme') === 'classic') ? 'selected' : '' ;
		?>
		<div class="wrap">
			<h2>WP Embed Facebook</h2>
			<div class="welcome-panel">
				<div class="welcome-panel-content">
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column">
							<form id="config-form" action="#" method="post">
								<table class="form-table">
									<?php wp_nonce_field( 'wp-embed-facebook','save-data' ); ?>
									<tbody>
										<tr>
											<h3><?php _e('Facebook application data', 'wp-embed-facebook') ?></h3>
										</tr>
										<tr valign="middle">
											<th>App ID</th>
											<td>
												<input type="text" name="wpemfb_app_id" required value="<?php echo get_option('wpemfb_app_id') ?>"  />
											</td>
										</tr>
										<tr valign="middle">
											<th>App Secret</th>
											<td>
												<input type="text" name="wpemfb_app_secret" required value="<?php echo get_option('wpemfb_app_secret') ?>"  />
											</td>
										</tr>
										<tr>
											<th><?php _e("Theme to use", 'wp-embed-facebook') ?></th>
											<td>
												<select name="wpemfb_theme">
												  <option value="default" <?php echo $sel1 ?> >Default</option>
												  <option value="classic" <?php echo $sel2 ?> >Classic</option>
												</select>											
											</td>											
										</tr>
										<tr valign="middle">
											<th><?php _e('Fb Cover Embed Width','wp-embed-facebook') ?></th>
											<td>
												<input type="text" name="wpemfb_max_width" value="<?php echo get_option('wpemfb_max_width') ?>" />
											</td>
										</tr>
										
										<tr valign="middle">
											<th><?php _e('Embed video as Post','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_raw_video"  <?php echo $checked5 ?> />
											</td>
										</tr>
										<tr valign="middle">
											<th><?php _e('Embed photo as Post','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_raw_photo"  <?php echo $checked6 ?> />
											</td>
										</tr>										
										<tr valign="middle">
											<th><?php _e('Add like button to embedded pages','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_show_like"  <?php echo $checked2 ?> />
											</td>
										</tr>	
										<tr valign="middle">
											<th><?php _e('Show follow button','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_show_follow"  <?php echo $checked4 ?> />
											</td>
										</tr>	
										
										<tr>
											<th>
												<h4><?php _e("Advanced Options", 'wp-embed-facebook') ?></h4>
											</th>
										</tr>																													
										<tr valign="middle">
											<th><?php _e('Enqueue Styles','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_enqueue_style"  <?php echo $checked ?> />
											</td>
										</tr>
										<tr valign="middle">
											<th><?php _e('Add fb-root and js on top of content','wp-embed-facebook') ?><br><small><?php _e('Some themes may not need this','wp-embed-facebook') ?></small></th>
											<td>
												<input type="checkbox" name="wpemfb_fb_root"  <?php echo $checked3 ?> />
											</td>
										</tr>
										<tr>
											<td>
												<?php //echo wp_nonce_field('wpebfb','nonce'); ?>
												<input type="submit" name="submit" class="button button-primary button-hero" value="<?php _e('Save','wp-embed-facebook') ?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</form
							<ul class="">
								<!--
								<li>
									<a href="http://www.saliuitl.org/wp-embed-fb/customize"><?php _e('Customize','wp-embed-facebook') ?></a>
								</li>
								<li>
									<a href="http://www.saliuitl.org/wp-embed-fb/support"><?php _e('Support','wp-embed-facebook') ?></a>
								</li>
								-->	
								<li>
									<a href="http://www.saliuitl.org/en/wp-embed-facebook"><?php _e('Plugin Web Site','wp-embed-facebook') ?></a>
								</li>															
							</ul>					
						</div>
						<div class="welcome-panel-column">
							<p></p>
						</div>
						<div class="welcome-panel-column welcome-panel-last">
								<h3 style="color:red;"><?php _e('Donate!', 'wp-embed-facebook') ?></h3>
								<p><?php _e('Help me keep this plugin up to date', 'wp-embed-facebook') ?></p>
								<p><strong><?php _e('Click the taco !', 'wp-embed-facebook') ?></strong></p>	
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="9TEJ8CGXMJEDG">
								<input type="image" src="http://saliuitl.org/img/taco.png" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
								<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
								</form>
								<img src="<?php echo plugins_url('/img/paypal.png', __FILE__) ?>" width="50px" />
								<p>
									<h3 style="color:red;"><?php _e('Searching for the Premium version ?', 'wp-embed-facebook') ?></h3>
									<ul>
										<li>
											<?php _e('Custom templates', 'wp-embed-facebook') ?>
										</li>
										<li>
											<?php _e('Compatibility with all themes imaginable', 'wp-embed-facebook') ?>
										</li>										
										<li>
											<?php _e("Multiple fb app id's per each multisite site", 'wp-embed-facebook') ?>
										</li>										
									</ul>
									<h4 style="color:#01007E;"><?php _e('Comming Soon', 'wp-embed-facebook') ?></h4>
								</p>
								<p style="text-align: center">
									<img src="<?php echo plugins_url('/img/hechoenmexico.png', __FILE__) ?>" width="80px" />
								</p>
								
						</div>
						<!-- <div class="welcome-panel-column welcome-panel-last"> -->
					</div>
				</div>
			</div>			
		</div><!-- .wrap -->		
		
		
		<?php	
	}
}
?>