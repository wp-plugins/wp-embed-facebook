<?php
class EmbFbAdmin{
	static function add_page(){
		add_options_page('EmbedFacebook', 'Embed Facebook', 'manage_options', 'embedfacebook', array('EmbFbAdmin', 'embedfb_page'));
	}
	static function savedata(){
		if(isset($_POST['wpemfb_app_secret'], $_POST['wpemfb_app_id' ] )) {
			if($_POST['wpemfb_app_id'] && $_POST['wpemfb_app_secret']){
				if ( !is_multisite() ) {
					update_option('wpemfb_app_id',$_POST['wpemfb_app_id']);
					update_option('wpemfb_app_secret',$_POST['wpemfb_app_secret']);
				} 
				else { 
					update_site_option('wpemfb_app_id',$_POST['wpemfb_app_id']);
					update_site_option('wpemfb_app_secret',$_POST['wpemfb_app_secret']);
				}					
			}
			if(isset($_POST['wpemfb_max_width'])){
				$prop = get_option('wpemfb_proportions') * $_POST['wpemfb_max_width'];
				if ( !is_multisite() ) {
					update_option('wpemfb_max_width', $_POST['wpemfb_max_width']);
					update_option('wpemfb_height', $prop );
				} 
				else {
					update_site_option('wpemfb_max_width', $_POST['wpemfb_max_width']);
				update_site_option('wpemfb_height', $prop);
				}				
			}
			if(isset($_POST['wpemfb_theme'])){
				if ( !is_multisite() ) 
					update_option('wpemfb_theme', $_POST['wpemfb_theme']);
				else 
					update_site_option('wpemfb_theme', $_POST['wpemfb_theme']);
			}			
			if(isset($_POST['wpemfb_show_like'])){
				if ( !is_multisite() ) 
					update_option('wpemfb_show_like', 'true');
				else  
					update_site_option('wpemfb_show_like', 'true');
			}else{
				if ( !is_multisite() ) {
					update_option('wpemfb_show_like', 'false');
				} 
				else { 
					update_site_option('wpemfb_show_like', 'false');
				}				
			}			
			if(isset($_POST['wpemfb_enqueue_style'])){
				if ( !is_multisite() ) 
					update_option('wpemfb_enqueue_style', 'true');
				else  
					update_site_option('wpemfb_enqueue_style', 'true');
			}else{
				if ( !is_multisite() ) {
					update_option('wpemfb_enqueue_style', 'false');
				} 
				else { 
					update_site_option('wpemfb_enqueue_style', 'false');
				}				
			}
			if(isset($_POST['wpemfb_fb_root'])){
				if ( !is_multisite() ) 
					update_option('wpemfb_fb_root', 'true');
				else  // TODO: multiple fb apps for each site
					update_site_option('wpemfb_fb_root', 'true');
			}else{
				if ( !is_multisite() ) {
					update_option('wpemfb_fb_root', 'false');
				} 
				else { // TODO: multiple fb apps for each site
					update_site_option('wpemfb_fb_root', 'false');
				}				
			}
		}
	}
	static function embedfb_page() {
		if(isset($_POST['submit']) && check_admin_referer( 'wp-embed-fb','save-data' )){
			self::savedata();
		}
		$checked = (get_option('wpemfb_enqueue_style') === 'true') ? 'checked' : '' ;
		$checked2 = (get_option('wpemfb_show_like') === 'true') ? 'checked' : '' ;
		$checked3 = (get_option('wpemfb_fb_root') === 'true') ? 'checked' : '' ;
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
									<?php wp_nonce_field( 'wp-embed-fb','save-data' ); ?>
									<tbody>
										<tr>
											<h3><?php _e('Facebook application data', 'wp-embed-fb') ?></h3>
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
											<th><?php _e("Theme to use", 'wp-embed-fb') ?></th>
											<td>
												<select name="wpemfb_theme">
												  <option value="default" <?php echo $sel1 ?> >Default</option>
												  <option value="classic" <?php echo $sel2 ?> >Classic</option>
												</select>											
											</td>											
										</tr>
										<tr>
											<th>
													<h4><?php _e("Other Options", 'wp-embed-fb') ?></h4>
											</th>
										</tr>								
										<tr valign="middle">
											<th><?php _e('Fb Cover Embed Width','wp-embed-fb') ?></th>
											<td>
												<input type="text" name="wpemfb_max_width" value="<?php echo get_option('wpemfb_max_width') ?>" />
											</td>
										</tr>
										<tr valign="middle">
											<th><?php _e('Add like button to pages','wp-embed-fb') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_show_like"  <?php echo $checked2 ?> />
											</td>
										</tr>										
										<tr valign="middle">
											<th><?php _e('Enqueue Styles','wp-embed-fb') ?></th>
											<td>
												<input type="checkbox" name="wpemfb_enqueue_style"  <?php echo $checked ?> />
											</td>
										</tr>
										<tr valign="middle">
											<th><?php _e('Add fb-root and javascript on top of content','wp-embed-fb') ?><br><small><?php _e('Some themes may not need this','wp-embed-fb') ?></small></th>
											<td>
												<input type="checkbox" name="wpemfb_fb_root"  <?php echo $checked3 ?> />
											</td>
										</tr>																				
										<tr>
											<td>
												<?php //echo wp_nonce_field('wpebfb','nonce'); ?>
												<input type="submit" name="submit" class="button button-primary button-hero" value="<?php _e('Save','wp-embed-fb') ?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</form
							<ul class="">
								<!--
								<li>
									<a href="http://www.saliuitl.org/wp-embed-fb/customize"><?php _e('Customize','wp-embed-fb') ?></a>
								</li>
								<li>
									<a href="http://www.saliuitl.org/wp-embed-fb/support"><?php _e('Support','wp-embed-fb') ?></a>
								</li>
								-->	
								<li>
									<a href="http://www.saliuitl.org/en/wp-embed-facebook"><?php _e('Plugin Web Site','wp-embed-fb') ?></a>
								</li>															
							</ul>					
						</div>
						<div class="welcome-panel-column">
							<p></p>
						</div>
						<div class="welcome-panel-column welcome-panel-last">
								<h3 style="color:red;"><?php _e('Donate!', 'wp-embed-fb') ?></h3>
								<p><?php _e('Help me keep this plugin up to date', 'wp-embed-fb') ?></p>
								<p><strong><?php _e('Click the taco !', 'wp-embed-fb') ?></strong></p>	
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="9TEJ8CGXMJEDG">
								<input type="image" src="http://saliuitl.org/img/taco.png" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
								<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
								</form>
								<img src="<?php echo plugins_url('/img/paypal.png', __FILE__) ?>" width="50px" />
								<p>
									<h3 style="color:red;"><?php _e('Searching for the Premium version ?', 'wp-embed-fb') ?></h3>
									<ul>
										<li>
											<?php _e('Custom templates', 'wp-embed-fb') ?>
										</li>
										<li>
											<?php _e('Compatibility with all themes imaginable', 'wp-embed-fb') ?>
										</li>										
										<li>
											<?php _e("Multiple fb app id's per each multisite site", 'wp-embed-fb') ?>
										</li>										
									</ul>
									<h4 style="color:#01007E;"><?php _e('Comming Soon', 'wp-embed-fb') ?></h4>
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