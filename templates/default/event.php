<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $width_out = $width;
 $width = $width_out - 20;
 $height = $width * $prop; 
 $start_time_format = !empty($fb_data['is_date_only']) ? '%e %b %Y' : '%e %b %Y %l:%M %P';
 $start_time = strtotime($fb_data['start_time']) + get_option('gmt_offset')*3600; //shows event date on local time
 
?>
<?php //Events have now covers but are not pulled from default request, maybe this will change in time.  ?>

<div class="wpemfb-border" style="max-width: <?php echo $width ?>px">
	<div class="wpemfb-table">
			<div class="wpemfb-cell-left">
				<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
				</a>		
			</div>
			<div class="wpemfb-cell-right">
				<a class="wpemfb-title wpemfb-clean-link" href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
				<br>
				<?php echo strftime($start_time_format, $start_time ) ?>
				<br>
				<?php 
					echo __('@ ', 'wp-embed-facebook');
					if(isset($fb_data['venue']['id'])){
						echo '<a class="wpemfb-color wpemfb-clean-link"  href="http://www.facebook.com/'.$fb_data['venue']['id'].'" target="_blank">'.$fb_data['location'].'</a>';
					} else {
						echo $fb_data['location'];  
					} 
				?>
				<br>
				<?php echo __('Creator: ', 'wp-embed-facebook').'<a class="wpemfb-color wpemfb-clean-link" href="http://www.facebook.com/'.$fb_data['owner']['id'].'" target="_blank">'.$fb_data['owner']['name'].'</a>' ?>
			</div>
	</div>	
</div>
