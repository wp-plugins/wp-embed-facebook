<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $height = $width * $prop;
 $show_posts = get_option("wpemfb_show_posts") == "true" ? true : false;
 //$wp_emb_fbsdk = WP_Embed_FB::$fbsdk;
?>
<div class="wpemfb-container" style="max-width: <?php echo $width ?>px" >
	<div class="wpemfb-cover"
		style= "
				height:<?php echo $height ?>px;
				background-image: url(<?php echo $fb_data['cover']['source'] ?>); 
				background-position: 0% <?php echo $fb_data['cover']['offset_y'] ?>%;
		 		" onclick="window.open('<?php echo $fb_data['link'] ?>', '_blank')" >
	</div>				
	<div class="wpemfb-row wpemfb-pad-top">
			<div class="wpemfb-col-2 wpemfb-text-center">
				<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
					<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" width="50px" height="50px" />
				</a>		
			</div>
			<div class="wpemfb-col-10">
				<a class="wpemfb-title" href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
				<br>
				<?php
					if($fb_data['category'] == 'Musician/band'){
						echo isset($fb_data['genre']) ? $fb_data['genre'] : '';
					} else {
						_e($fb_data['category'],'wp-embed-facebook');
					}
				?><br>
				<?php if(isset($fb_data["website"])) : ?>
					<a  href="<?php echo WP_Embed_FB::getwebsite($fb_data["website"]) ?>" title="<?php _e('Web Site', 'wp-embed-facebook')  ?>" target="_blank">
						<?php _e('Web Site','wp-embed-facebook') ?>
					</a>						
				<?php endif; ?>
				<div style="float: right;"><?php WP_Embed_FB::like_btn($fb_data['id'],$fb_data['likes']) ?></div>	
			</div>
	</div>	
	<?php if($show_posts) :  ?>
		<?php foreach($fb_data['posts']['data'] as $fbpost) : ?>

			<?php if(isset($fbpost['picture']) || isset($fbpost['message'])) : ?>
				<?php $link = explode("_", $fbpost['id']); ?>
				<hr>
				<div class="wpemfb-row">
					<?php if(isset($fbpost['picture']) && isset($fbpost['message'])) : ?>
						<div class="wpemfb-col-3 wpemfb-text-center">
							<a class="wpemfb-clean-link" href="<?php echo "https://www.facebook.com/".$link[0]."/posts/".$link[1] ?>" target="_blank" rel="nofollow">
								<img src="<?php echo $fbpost['picture'] ?>" width="70px" height"70px" />
							</a>
						</div>
						<div class="wpemfb-col-9 wpemfb-pl-none">
							<span class="wpemfb-page-post"><?php echo make_clickable($fbpost['message']) ?></span><br>
							<a class="wpemfb-post-link" href="<?php echo "https://www.facebook.com/".$link[0]."/posts/".$link[1] ?> " target="_blank" rel="nofollow">
								<?php echo isset($fbpost['likes']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y6/r/l9Fe9Ugss0S.gif" />'.$fbpost['likes']['summary']['total_count'].' ' : ""  ?>
								<?php echo isset($fbpost['comments']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yg/r/V8Yrm0eKZpi.gif" />'.$fbpost['comments']['summary']['total_count'].' ' : ""  ?>
								<?php echo isset($fbpost['shares']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y2/r/o19N6EzzbUm.png" />'.$fbpost['shares']['count'].' ' : ""  ?>
							</a>
						</div>
					<?php elseif(isset($fbpost['picture'])): ?>
						<div class="wpemfb-col-12 wpemfb-text-center">
							<a class="wpemfb-clean-link" href="<?php echo "https://www.facebook.com/".$link[0]."/posts/".$link[1] ?>" target="_blank" rel="nofollow">
								<img src="<?php echo $fbpost['picture'] ?>" />
							</a>
						</div>						
					<?php elseif(isset($fbpost['message'])): ?>	
						<div class="wpemfb-col-12">
							<span class="wpemfb-page-post"><?php echo make_clickable($fbpost['message']) ?></span><br>
							<a class="wpemfb-post-link" href="<?php echo "https://www.facebook.com/".$link[0]."/posts/".$link[1] ?> " target="_blank" rel="nofollow">
								<?php echo isset($fbpost['likes']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y6/r/l9Fe9Ugss0S.gif" />'.$fbpost['likes']['summary']['total_count'].' ' : ""  ?>
								<?php echo isset($fbpost['comments']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yg/r/V8Yrm0eKZpi.gif" />'.$fbpost['comments']['summary']['total_count'].' ' : ""  ?>
								<?php echo isset($fbpost['shares']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y2/r/o19N6EzzbUm.png" />'.$fbpost['shares']['count'].' ' : ""  ?>
							</a>
						</div>						
					<?php endif; ?>
				</div>	
			<?php endif; ?>

		<?php endforeach; ?>
	<?php endif; ?>
</div>