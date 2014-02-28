<div class="wpemfb-container" style="min-width:<?php echo $width ?>px">
	<div class="wpemfb-info" style="width: <?php echo $width ?>">
		<div class="wpemfb-pic">
			<a href="http://facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
				<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
			</a>				
		</div>
		<div class="wpemfb-desc">
			<h4 class="wpemfb-title" >
				<a href="http://facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
			</h4>	
			<?php printf( __( '%d people like this.', 'wp-embed-fb' ), $fb_data['likes'] ); ?><br>
			<?php echo isset($fb_data["website"]) ? WP_Embed_FB::getwebsite($fb_data["website"]) : ""; ?>					
		</div>
	</div>
</div>

