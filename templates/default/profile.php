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
				<div>
				<a class="wpemfb-title wpemfb-clean-link" href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
				</div>
				<div>
					<?php WP_Embed_FB::follow_btn($fb_data['id']) ?>
				</div>
			</div>
	</div>	
</div>

