<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $width = $width;
 $height = $width * $prop;  
?>
<div class="wpemfb-container" style="max-width: <?php echo $width ?>px">
	<div class="wpemfb-row">
			<div class="wpemfb-col-3 wpemfb-text-center">
				<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
				</a>		
			</div>
			<div class="wpemfb-col-9 wpemfb-pl-none">
				<a class="wpemfb-title" href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
				<br>
				<div><?php WP_Embed_FB::like_btn($fb_data['id'],$fb_data['likes']) ?></div>
				<?php if(isset($fb_data["website"])) : ?>
					<br>
						<a href="http://<?php echo WP_Embed_FB::getwebsite($fb_data["website"]) ?>" title="<?php _e('Web Site', 'wp-embed-facebook')  ?>" target="_blank">
							<?php _e('Web Site','wp-embed-facebook') ?>
						</a>
				<?php endif; ?>
			</div>
	</div>	
</div>