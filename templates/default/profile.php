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
				<div>
				<a class="wpemfb-title" href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
				</div>
				<div>
					<?php WP_Embed_FB::follow_btn($fb_data['id']) ?>
				</div>
			</div>
	</div>	
</div>

