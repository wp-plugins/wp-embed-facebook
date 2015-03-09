<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $width = $width;
?>
<div class="wpemfb-container" style="max-width: <?php echo $width ?>px">
	<div class="wpemfb-row">
		<div class="wpemfb-col-3 wpemfb-text-center">
			<a href="https://facebook.com/<?php echo $fb_data['from']['id'] ?>" target="_blank" rel="nofollow">
				<img src="http://graph.facebook.com/<?php echo $fb_data['from']['id'] ?>/picture" />
			</a>			
			
		</div>
		<div class="wpemfb-col-9 wpemfb-pl-none">
			<a class="wpemfb-title" href="https://facebook.com/<?php echo $fb_data['from']['id'] ?>" target="_blank" rel="nofollow">
				<?php echo $fb_data['from']['name'] ?>
			</a>	
			<br>
			<?php if(isset($fb_data['from']['category'])) : ?>
				<?php echo $fb_data['from']['category'].'<br>'  ?>
			<?php endif; ?>
			<a href="https://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow"><?php echo $fb_data['name'] ?></a>
		</div>
	</div>	
	<hr>
	<div class="wpemfb-row">
		<div class="wpemfb-col-12 wpemfb-text-center">
			<?php
			foreach ($fb_data['photos']['data'] as $pic) {
				?>
					<a href="<?php echo $pic['source'] ?>"  data-lightbox="roadtrip" data-title="<?php echo $pic['name'] ?>" >
						<img class="wpemfb-thmb" src="<?php echo $pic['picture'] ?>" />
					</a>			
				<?php
			}
			?>
		</div>
	</div>
</div>