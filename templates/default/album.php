<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $width = $width - 20;
?>
<div class="wpemfb-border" style="max-width: <?php echo $width ?>px">
	<div class="wpemfb-table">
		<div class="wpemfb-cell-left">
			<a class="wpemfb-clean-link" href="https://facebook.com/<?php echo $fb_data['from']['id'] ?>" target="_blank" rel="nofollow">
				<img src="http://graph.facebook.com/<?php echo $fb_data['from']['id'] ?>/picture" />
			</a>			
			
		</div>
		<div class="wpemfb-cell-right">
			<a class="wpemfb-title wpemfb-clean-link" href="https://facebook.com/<?php echo $fb_data['from']['id'] ?>" target="_blank" rel="nofollow">
				<?php echo $fb_data['from']['name'] ?>
			</a>	
			<br>
			<?php if(isset($fb_data['from']['category'])) : ?>
				<?php echo $fb_data['from']['category'].'<br>'  ?>
			<?php endif; ?>
			<a class="wpemfb-color wpemfb-clean-link" href="https://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow"><?php echo $fb_data['name'] ?></a>
		</div>
	</div>	
	<div class="wpemfb-table">
		<div class="wpemfb-cell" style="text-align: center">
			<?php
			foreach ($fb_data['photos']['data'] as $pic) {
				?>
					<a class="wpemfb-clean-link" href="<?php echo $pic['source'] ?>"  data-lightbox="roadtrip" data-title="<?php echo $pic['name'] ?>" >
						<img class="wpemfb-thmb" src="<?php echo $pic['picture'] ?>" />
					</a>			
				<?php
			}
			?>
		</div>
	</div>
</div>