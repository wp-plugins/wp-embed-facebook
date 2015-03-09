<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $show_posts = get_option("wpemfb_show_posts") == "true" ? true : false;
?>
<div class="wpemfb-container" 
						style="	width:<?php echo $width ?>px;
							    margin-left: auto;
							    margin-right: auto;						
						">
	<div class="wpemfb-cover" 
					style="	height:<?php echo $height ?>px; 
							width:<?php echo $width?>px; 
							background-image: url(<?php echo $fb_data['cover']['source'] ?>); 
							background-position: 0% <?php echo $fb_data['cover']['offset_y'] ?>%;
					 		">
		
	</div>
	<div class="wpemfb-info">
		<div class="wpemfb-pic">
			<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
				<img class="wpemfb-thmb-profile" src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
			</a>				
		</div>
		<div class="wpemfb-desc">
			<h4 class="wpemfb-title" >
				<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
			</h4>
			<?php
				if($fb_data['category'] == 'Musician/band'){
					echo isset($fb_data['genre']) ? $fb_data['genre'].'<br>' : '';
				} else {
					_e($fb_data['category'],'wp-embed-facebook');
				}
			?><br>
			<?php WP_Embed_FB::like_btn($fb_data['id'],$fb_data['likes']) ?><br>
			<?php echo isset($fb_data["website"]) ? WP_Embed_FB::getwebsite($fb_data["website"]) : ""; ?>					
		</div>
	</div>
	<?php if($show_posts) :  ?>
			<br>
			<?php foreach($fb_data['posts']['data'] as $fbpost) : ?>
				<?php $link = explode("_", $fbpost['id']) ?>
				<div class="wpemfb-posts-container" onclick="window.open('<?php echo "https://www.facebook.com/".$link[0]."/posts/".$link[1] ?>', '_blank')">
					<?php if(isset($fbpost['picture'])) : ?>
						<div class="wpemfb-post-image">
							<img src="<?php echo $fbpost['picture'] ?>" />
						</div>
					<?php endif; ?>
					<div class="wpemfb-post-message" style=" width:<?php echo isset($fbpost['picture']) ? "69%" : "100%" ?>;">
						<p><?php echo $fbpost['message'] ?></p>
						<p class="wpemfb-likes">
							<?php echo isset($fbpost['likes']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y6/r/l9Fe9Ugss0S.gif" />'.$fbpost['likes']['summary']['total_count'].' ' : ""  ?>
							<?php echo isset($fbpost['comments']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yg/r/V8Yrm0eKZpi.gif" />'.$fbpost['comments']['summary']['total_count'].' ' : ""  ?>
							<?php echo isset($fbpost['shares']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y2/r/o19N6EzzbUm.png" />'.$fbpost['shares']['count'].' ' : ""  ?>
						</p>
					</div>
				</div>	
				<?php //print_r($fbpost) ?>
			<?php endforeach; ?>
	<?php endif; ?>	
</div>
