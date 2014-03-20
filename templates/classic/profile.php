<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
?>
<div class="wpemfb-container" style="min-width:<?php echo $width ?>px">
	<div class="wpemfb-info">
		<div class="wpemfb-pic">
			<a href="http://facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
				<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
			</a>				
		</div>
		<div class="wpemfb-desc">
			<h4 class="wpemfb-title" >
				<a href="http://facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a><br>
				<?php WP_Embed_FB::follow_btn($fb_data['id']) ?>
			</h4>				
		</div>
	</div>
</div>

