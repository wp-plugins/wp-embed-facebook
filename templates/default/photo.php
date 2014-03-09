<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $width = $width - 20;
 $height = $width * $prop;  
?>
<div class="wpemfb-container">
	<div class="wpemfb-info">
		<div class="wpemfb-pic">
			<img src="http://graph.facebook.com/<?php echo $fb_data['from']['id'] ?>/picture" />
		</div>
		<div class="wpemfb-desc">
			<h4 class="wpemfb-title" >
				<a href="https://facebook.com/<?php echo $fb_data['from']['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['from']['name'] ?>
				</a>
			</h4>
			<?php if(isset($fb_data['from']['category'])) : ?>
				<?php WP_Embed_FB::fb_categories($fb_data['from']['category']) ?><br>
			<?php endif; ?>
			<?php WP_Embed_FB::like_btn($fb_data['id']) ?><br>
		</div>
	</div>
	<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
		<img src="<?php echo $fb_data['source'] ?>" width="<?php echo $width ?>" height="auto" />
	</a>
</div>
