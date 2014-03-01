<div class="wpemfb-container" style="min-width:<?php echo $height ?>px">
	<div class="wpemfb-pagebk" 
					style="	height:<?php echo $height ?>px; 
							width:<?php echo $width?>px; 
							background-image: url(<?php echo $fb_data['cover']['source'] ?>); 
							background-position: 0% <?php echo $fb_data['cover']['offset_y'] ?>%;
					 		">
		
	</div>
	<div class="wpemfb-info">
		<div class="wpemfb-pic">
			<a href="<?php echo $fb_data['link'] ?>" target="_blank">
				<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
			</a>				
		</div>
		<div class="wpemfb-desc">
			<h4 class="wpemfb-title" >
				<a href="<?php echo $fb_data['link'] ?>" target="_blank">
					<?php echo $fb_data['name'] ?>
				</a>
			</h4>
			<?php echo isset($fb_data['genre']) ? $fb_data['genre'].'<br>' : '' ?>
			<?php printf( __( '%d people like this.', 'wp-embed-fb' ), $fb_data['likes'] ); ?><br>
			<?php echo isset($fb_data["website"]) ? WP_Embed_FB::getwebsite($fb_data["website"]) : ""; ?>					
		</div>
	</div>
</div>



