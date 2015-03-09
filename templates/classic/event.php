<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
?>
<?php
 $start_time_format = !empty($fb_data['is_date_only']) ? '%e %b %Y' : '%e %b %Y %l:%M %P';
 $start_time = strtotime($fb_data['start_time']) + get_option('gmt_offset')*3600; //shows event date on local time
?>
<div class="wpemfb-container" style="min-width:<?php echo $width ?>px">

	<div class="wpemfb-info">
		<div class="wpemfb-pic">
			<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
				<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
			</a>				
		</div>
		<div class="wpemfb-desc">
			<h4 class="wpemfb-title" >
				<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<?php echo $fb_data['name'] ?>
				</a>
			</h4>
			<?php echo strftime($start_time_format, $start_time ) ?><br>
				<?php 
				if(isset($fb_data['venue']['id'])){
					echo '<a href="http://www.facebook.com/'.$fb_data['venue']['id'].'" target="_blank">'.$fb_data['location'].'</a>';
				} else {
					echo $fb_data['location'];  
				} ?>
				<br>
				<?php echo __('Creator: ', 'wp-embed-facebook').'<a href="http://www.facebook.com/'.$fb_data['owner']['id'].'" target="_blank">'.$fb_data['owner']['name'].'</a>' ?>
		</div>
	</div>
</div>
