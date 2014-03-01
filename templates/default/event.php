<?php
/*
 * You can alter this placing a copy on yourtheme/plugins/wp-embed-fb/
 * Event recives the following default info:
 * name
 * id
 * start_time
 * end_time (empty if none)
 * is_date_only (empty if not)
 * description
 * location
 * venue
 * 	->id (empty if none)
 *  ->name (empty if none)
 * updated_time
 */
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
				<?php echo __('Creator: ', 'wp-embed-fb').'<a href="http://www.facebook.com/'.$fb_data['owner']['id'].'" target="_blank">'.$fb_data['owner']['name'].'</a>' ?>
		</div>
	</div>
</div>
