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
		<div class="wpemfb-cell">
			<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
				<img src="<?php echo $fb_data['source'] ?>" width="100%" height="auto" style="max-width: <?php echo $width ?>px" />
			</a>			
		</div>
	</div>
</div>
