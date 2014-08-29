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
			if(isset($_POST['wpemfb_max_photos'])){
				update_option('wpemfb_max_photos', $_POST['wpemfb_max_photos']);
			}
			if(isset($_POST['wpemfb_max_posts'])){
				update_option('wpemfb_max_posts', $_POST['wpemfb_max_posts']);
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
			if(isset($_POST['wpemfb_show_posts'])){
				update_option('wpemfb_show_posts', 'true');
			}else{
				update_option('wpemfb_show_posts', 'false');
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
		$checked7 = (get_option('wpemfb_show_posts') === 'true') ? 'checked' : '' ;
		$sel1 = (get_option('wpemfb_theme') === 'default') ? 'selected' : '' ;
		$sel2 = (get_option('wpemfb_theme') === 'classic') ? 'selected' : '' ;
		?>
		<div class="wrap">
			<h2>WP Embed Facebook</h2>
			<div class="welcome-panel">
				<div class="welcome-panel-content">
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column " style="width: 55%">
							<form id="config-form" action="#" method="post">
								<?php wp_nonce_field( 'wp-embed-facebook','save-data' ); ?>
								<h3 style="color: #23487F;"><?php _e('Facebook application data', 'wp-embed-facebook') ?></h3>
								<table class="form-table">
									<tbody>
										<tr valign="middle">
											<th>App ID</th>
											<td>
												<input type="text" name="wpemfb_app_id" required value="<?php echo get_option('wpemfb_app_id') ?>" size="29"  />
											</td>
										</tr>
										<tr valign="middle">
											<th>App Secret</th>
											<td>
												<input type="text" name="wpemfb_app_secret" required value="<?php echo get_option('wpemfb_app_secret') ?>" size="29"  />
											</td>
										</tr>
								</table>
								<h3 style="color: #23487F;"><?php _e('Embedded Pages Options', 'wp-embed-facebook') ?></h3>
								<table class="form-table">
									<tbody>
										<tr valign="middle">
											<th><?php _e('Show like button','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_show_like"  <?php echo $checked2 ?> />
											</td>
										</tr>										
										<tr valign="middle">
											<th><?php _e('Show latest posts','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_show_posts"  <?php echo $checked7 ?> />
											</td>
										</tr>				
										<tr valign="middle">
											<th><?php _e('Number of posts','wp-embed-facebook') ?></th>
											<td>
												<input type="number" name="wpemfb_max_posts" value="<?php echo get_option('wpemfb_max_posts') ?>" />
											</td>
										</tr>		
									</tbody>
								</table>
								<h3 style="color: #23487F;"><?php _e('Other Options', 'wp-embed-facebook') ?></h3>
								<table class="form-table">	
									<tbody>																
										<tr>
											<th><?php _e("Template to use", 'wp-embed-facebook') ?></th>
											<td>
												<select name="wpemfb_theme">
												  <option value="default" <?php echo $sel1 ?> >Default</option>
												  <option value="classic" <?php echo $sel2 ?> >Classic</option>
												</select>											
											</td>											
										</tr>
										<tr valign="middle">
											<th><?php _e('Embed Max-Width','wp-embed-facebook') ?></th>
											<td>
												<input type="number" name="wpemfb_max_width" value="<?php echo get_option('wpemfb_max_width') ?>"  />
											</td>
										</tr>
										<tr valign="middle">
											<th><?php _e('Photos in Album','wp-embed-facebook') ?></th>
											<td>
												<input type="number" name="wpemfb_max_photos" value="<?php echo get_option('wpemfb_max_photos') ?>"  />
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
											<th><?php _e('Show follow button','wp-embed-facebook') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_show_follow"  <?php echo $checked4 ?> />
											</td>
										</tr>
									</tbody>
								</table>			
								<h3 style="color: #23487F;"><?php _e('Advanced Options', 'wp-embed-facebook') ?></h3>								
								<table class="form-table">
									<tbody>
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
							</form>
						</div>
						<div class="welcome-panel-column welcome-panel-last " style="width: 40%">
								<h3 style="color:#CF1912;"><?php _e('Donate!', 'wp-embed-facebook') ?></h3>
								<p><?php _e('Help me keep this plugin up to date', 'wp-embed-facebook') ?></p>
								<p><strong><?php _e('Click the taco !', 'wp-embed-facebook') ?></strong></p>
								<p>
								<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=J373TWCMJ5CEY&lc=MX&item_name=WP%20Embed%20Facebook&no_note=1&no_shipping=1&rm=1&return=http%3a%2f%2fwww%2ewpembedfb%2ecom%2fyou%2dare%2dawesome&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">
									<img src="<?php echo plugins_url('/img/taco.png', __FILE__) ?>" >
								</a>
								</p>	
								<img src="<?php echo plugins_url('/img/paypal.png', __FILE__) ?>" width="50px" />
								<hr>
								<p>
									<h3 style="color:#CF1912;"><?php _e('Searching for the Premium version ?', 'wp-embed-facebook') ?></h3>
									<ul>
										<li>
											<?php _e('Embed Full Pages', 'wp-embed-facebook') ?>
										</li>
										<li>
											<?php _e('Embed Events with cover', 'wp-embed-facebook') ?>
										</li>										
										<li>
											<?php _e('More Resposive Teplates', 'wp-embed-facebook') ?>
										</li>										
									</ul>
									<h3 style="color:#23487F; text-align:center;"><?php _e('Comming Soon', 'wp-embed-facebook') ?></h3>
								</p>
								<hr>
								<h4 style="color:#23487F;"><?php _e('Shortcode Examples:', 'wp-embed-facebook') ?></h4>
								<p><?php _e('Raw Photo', 'wp-embed-facebook') ?></p>
								<p>[facebook=https://www.facebook.com/photo.php?fbid=10150777131437722&set=a.10150777131307722.434719.6798562721 raw=true]</p>
								<p><?php _e('Raw is optional, and only works on photos and videos.', 'wp-embed-facebook') ?></p>
								<p><?php _e('Event', 'wp-embed-facebook') ?></p>
								<p>[facebook=https://www.facebook.com/events/611232852279921/ ]</p>
								<p><strong><?php _e('You can avoid the shortcode by placing the url on a single line', 'wp-embed-facebook') ?></strong></p>
								<hr>
								<p style="text-align: center">
									<img src="<?php echo plugins_url('/img/hechoenmexico.png', __FILE__) ?>" width="60px" />
								</p>
								<p><a href="http://www.wpembedfb.com"><?php _e('Plugin Web Site','wp-embed-facebook') ?></a></p>																	
						</div>
						<!-- <div class="welcome-panel-column welcome-panel-last"> -->
					</div>
				</div>
			</div>			
		</div><!-- .wrap -- 766>		
		
		<?php	
	}
}
?>