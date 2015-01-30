<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $height = $width * $prop;
 $start_time_format = !empty($fb_data['is_date_only']) ? '%e %b %Y' : '%e %b %Y %l:%M %P';
 $start_time = strtotime($fb_data['start_time']) + get_option('gmt_offset')*3600; //shows event date on local time
?>
<?php //Events have now covers but are not pulled from default request, maybe this will change in time.  ?>

<div class="wpemfb-container" style="max-width: <?php echo $width ?>px" >	
	<?php do_action('wpemfb_event',$height,$fb_data); ?>	
	<div class="wpemfb-row wpemfb-pad-top">
			<div class="wpemfb-col-3 wpemfb-text-center">
				<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture/large" />
				</a>		
			</div>
			<div class="wpemfb-col-9 wpemfb-pl-none">
				<a class="wpemfb-title" href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
				<br>
				<?php echo strftime($start_time_format, $start_time ) ?>
				<br>
				<?php 
					if(isset($fb_data['venue']['id'])){
						_e('@ ', 'wp-embed-facebook');
						echo '<a href="http://www.facebook.com/'.$fb_data['venue']['id'].'" target="_blank">'.$fb_data['location'].'</a>';
					} else {
						echo isset($fb_data['location']) ? __('@ ', 'wp-embed-facebook') . $fb_data['location'] : '';  
					} 
				?>
				<br>
				<?php echo __('Creator: ', 'wp-embed-facebook').'<a href="http://www.facebook.com/'.$fb_data['owner']['id'].'" target="_blank">'.$fb_data['owner']['name'].'</a>' ?>
			</div>
	</div>	
</div>
