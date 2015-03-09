<?php
//TODO add option 
class EmbFbAdmin{
	static function admin_enqueue_scripts($hook_suffix){
		if($hook_suffix == 'settings_page_embedfacebook'){
			global $wp_scripts;
			wp_enqueue_script('wpemfb-admin', WPEMFBURL.'lib/js/admin.js',array('jquery-ui-accordion'));
		    $queryui = $wp_scripts->query('jquery-ui-core');
		    $url = "http://ajax.googleapis.com/ajax/libs/jqueryui/".$queryui->ver."/themes/smoothness/jquery-ui.css";
		    wp_enqueue_style('jquery-ui-start', $url, false, null);		
		}
		$translation_array = array( 'local' => get_locale(), 'fb_id'=>get_option('wpemfb_app_id'), 'fb_root'=>get_option('wpemfb_fb_root') );
		wp_localize_script( 'wpemfb', 'WEF', $translation_array );				
	}
	static function admin_init(){
		$theme = get_option('wpemfb_theme');
		add_editor_style( WPEMFBURL.'templates/'.$theme.'/wpemfb.css' );	
	}
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
			if(isset($_POST['wpemfb_raw_post'])){
				update_option('wpemfb_raw_post', 'true');
			}else{
				update_option('wpemfb_raw_post', 'false');
			}
			if(isset($_POST['wpemfb_enq_lightbox'])){
				update_option('wpemfb_enq_lightbox', 'true');
			}else{
				update_option('wpemfb_enq_lightbox', 'false');
			}
			if(isset($_POST['wpemfb_enq_wpemfb'])){
				update_option('wpemfb_enq_wpemfb', 'true');
			}else{
				update_option('wpemfb_enq_wpemfb', 'false');
			}
			if(isset($_POST['wpemfb_enq_fbjs'])){
				update_option('wpemfb_enq_fbjs', 'true');
			}else{
				update_option('wpemfb_enq_fbjs', 'false');
			}			
		}
		do_action('wpemfb_admin_savedata');
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
		
		$checked8 = (get_option('wpemfb_raw_post') === 'true') ? 'checked' : '' ;
		$checked9 = (get_option('wpemfb_enq_lightbox') === 'true') ? 'checked' : '' ;
		$checked10 = (get_option('wpemfb_enq_wpemfb') === 'true') ? 'checked' : '' ;
		$checked11 = (get_option('wpemfb_enq_fbjs') === 'true') ? 'checked' : '' ;
		$sel1 = (get_option('wpemfb_theme') === 'default') ? 'selected' : '' ;
		$sel2 = (get_option('wpemfb_theme') === 'classic') ? 'selected' : '' ;
		?>
		<style>
			.ui-widget-content th{
				font-weight: normal;
				padding-right: 10px;
			}
			.settings-col{
				width: 50% !important;
				padding-right: 2% !important; 
				text-align: left !important; 
			}
			.welcome-panel-last{
				width: 47% !important;
				text-align: center;
			}
			@media (max-width:870px){
				.settings-col{
					width: 100% !important; 
				}			
				.welcome-panel-last{
					width: 100% !important;
				}	
			}
		</style>
		<div class="wrap">
			<h2>WP Embed Facebook</h2>
			<div class="welcome-panel">
				<div class="welcome-panel-content">
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column settings-col" >
							<form id="config-form" action="#" method="post">
								<?php wp_nonce_field( 'wp-embed-facebook','save-data' ); ?>
								<div id="accordion">
									<h5><?php _e('Facebook application data', 'wp-embed-facebook') ?></h5>
									<div>
										<table>
											<tbody>
												<tr valign="middle">
													<th>App ID</th>
													<td>
														<input type="text" name="wpemfb_app_id" required value="<?php echo get_option('wpemfb_app_id') ?>" size="38"  />
													</td>
												</tr>
												<tr valign="middle">
													<th>App Secret</th>
													<td>
														<input type="text" name="wpemfb_app_secret" required value="<?php echo get_option('wpemfb_app_secret') ?>" size="38"  />
													</td>
												</tr>
										</table>										
									</div>
									<h5><?php _e('General Options','wp-embed-facebook') ?></h5>
									<div>
										<table>
											<tbody>
												<tr>
													<th><?php _e("Template to use", 'wp-embed-facebook') ?></th>
													<td>
														<select name="wpemfb_theme">
														  <option value="default" <?php echo $sel1 ?> >Default</option>
														  <option value="classic" <?php echo $sel2 ?> >Classic</option>
														  <?php do_action('wpemfb_admin_theme'); ?>
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
													<th><?php _e('Number of Photos <br> on Embedded Albums','wp-embed-facebook') ?></th>
													<td>
														<input type="number" name="wpemfb_max_photos" value="<?php echo get_option('wpemfb_max_photos') ?>"  />
													</td>
												</tr>	
												<tr valign="middle">
													<th><?php _e('Show follow button <br>on Embedded Profiles','wp-embed-facebook') ?></th>
													<td>
														<input type="checkbox" name="wpemfb_show_follow"  <?php echo $checked4 ?> />
													</td>
												</tr>																								
											</tbody>
										</table>
									</div>
									<h5><?php _e('Embedded Fan Pages', 'wp-embed-facebook') ?></h5>
									<div>
										<table>
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
														<input type="number" name="wpemfb_max_posts" value="<?php echo get_option('wpemfb_max_posts') ?>"  style="width: 60px;"/>
													</td>
												</tr>		
											</tbody>
										</table>										
									</div>
									<h5><?php _e('Raw Embedded Options', 'wp-embed-facebook') ?></h5>
									<div>
										<table>	
											<tbody>																
												<tr valign="middle">
													<th><?php _e('Embed Videos Raw','wp-embed-facebook') ?>
													</th>
													
													<td>
														<input type="checkbox" name="wpemfb_raw_video"  <?php echo $checked5 ?> />
													</td>
												</tr>
												<tr valign="middle">
													<th><?php _e('Embed Photos Raw','wp-embed-facebook') ?></th>
													<td>
														<input type="checkbox" name="wpemfb_raw_photo"  <?php echo $checked6 ?> />
													</td>
												</tr>
												<?php 
												 /*
												<tr valign="middle">
													<th><?php _e('Embed Posts Raw','wp-embed-facebook') ?></th>
													<td>
														<input type="checkbox" name="wpemfb_raw_post"  <?php echo $checked8 ?> />
													</td>
												</tr>
												*/
												 ?>								
											</tbody>
										</table>										
									</div>
									<?php do_action('wpemfb_options'); ?>
									<h5><?php _e('Advanced Options', 'wp-embed-facebook') ?></h5>	
									<div>
										<table>
											<tbody>
												<tr valign="middle">
													<th><?php _e('Enqueue Styles','wp-embed-facebook') ?></th>
													<td>
														<input type="checkbox" name="wpemfb_enqueue_style"  <?php echo $checked ?> />
													</td>
												</tr>
												<tr valign="middle">
													<th><?php _e('Add fb-root on top of content','wp-embed-facebook') ?><br><small><?php _e('Some themes may not need this','wp-embed-facebook') ?></small></th>
													<td>
														<input type="checkbox" name="wpemfb_fb_root"  <?php echo $checked3 ?> />
													</td>
												</tr>
												<tr valign="middle">
													<th><?php _e('Enqueue Lightbox script','wp-embed-facebook') ?></th>
													<td>
														<input type="checkbox" name="wpemfb_enq_lightbox"  <?php echo $checked9 ?> />
													</td>
												</tr>
												<tr valign="middle">
													<th><?php _e('Enqueue WPEmbedFB script','wp-embed-facebook') ?><br></th>
													<td>
														<input type="checkbox" name="wpemfb_enq_wpemfb"  <?php echo $checked10 ?> />
													</td>
												</tr>
												<tr valign="middle">
													<th><?php _e('Enqueue Facebook SDK','wp-embed-facebook') ?><br></th>
													<td>
														<input type="checkbox" name="wpemfb_enq_fbjs"  <?php echo $checked11 ?> />
													</td>
												</tr>												
											</tbody>
										</table>										
									</div>							
								</div>
								<input type="submit" name="submit" class="button button-primary button-hero" value="<?php _e('Save','wp-embed-facebook') ?>" />
							</form>
						</div>
						<div class="welcome-panel-column welcome-panel-last" >
							<?php ob_start(); ?>
							<h3><?php _e( 'Premium Version Available', 'wp-embed-facebook') ?></h3>
							<h2><?php _e( 'Only $6.99 USD', 'wp-embed-facebook') ?></h2>
							<br>
							<a class="button button-primary" href="http://www.wpembedfb.com/premium"><?php _e('Check it out', 'wp-embed-facebook') ?></a>
							<br>
									<ul>
										<li>
											<?php _e('Events with cover', 'wp-embed-facebook') ?>
										</li>
										<li>
											<?php _e('Fan Page Full Embed', 'wp-embed-facebook') ?>
										</li>
										<!--
										<li>
											<?php _e('More Responsive Templates', 'wp-embed-facebook') ?>
										</li>
										-->
										<li>
											<?php _e('One Year Premium Support', 'wp-embed-facebook') ?>
										</li>																				
									</ul>							
							<hr>
								<p>
								<small><?php _e( 'More information', 'wp-embed-facebook') ?></small><br>
								<a href="http://www.wpembedfb.com" style="color:#23487F;"><?php _e('Plugin Web Site','wp-embed-facebook') ?></a>
								</p>
								<hr>
								<p>
									<img src="<?php echo plugins_url('/img/hechoenmexico.png', __FILE__) ?>" width="60px" />
								</p>
							<?php echo apply_filters('wpemfb_admin',ob_get_clean()); ?>
						</div>
					</div>
				</div>
			</div>			
		</div>
		<?php	
	}
}
?>