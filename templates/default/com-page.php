<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $width = $width - 20;
 $height = $width * $prop;  
?>

<div class="wpemfb-border" style="max-width: <?php echo $width ?>px">
	<div class="wpemfb-table">
			<div class="wpemfb-cell-left">
				<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
				</a>		
			</div>
			<div class="wpemfb-cell-right">
				<a class="wpemfb-title wpemfb-clean-link" href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
				<br>
				<?php WP_Embed_FB::like_btn($fb_data['id'],$fb_data['likes']) ?>
				<br>
				<?php if(isset($fb_data["website"])) : ?>
					<a class="wpemfb-clean-link wpemfb-color"  href="http://<?php echo WP_Embed_FB::getwebsite($fb_data["website"]) ?>" title="<?php _e('Web Site', 'wp-embed-facebook')  ?>" target="_blank">
						<?php _e('Web Site','wp-embed-facebook') ?>
					</a>						
				<?php endif; ?>
			</div>
	</div>	
</div>
