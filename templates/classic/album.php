<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
?>
<div class="wpemfb-container" >
	<div style="max-width: <?php echo $width ?>px;">
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
					<?php echo $fb_data['from']['category'].'<br>'  ?>
				<?php endif; ?>
				<a href="https://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow"><?php echo $fb_data['name'] ?></a>
			</div>
		</div>
		<p style="text-align: center">
		<?php
		foreach ($fb_data['photos']['data'] as $pic) {
			?>
				<a class="wpemfb-link" href="<?php echo $pic['source'] ?>"  data-lightbox="roadtrip" data-title="<?php echo $pic['name'] ?>" >
					<img class="wpemfb-thmb" src="<?php echo $pic['picture'] ?>" />
				</a>			
			<?php
		}
		?>
		</p>
	</div>
</div>
