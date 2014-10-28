<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data 
 * <iframe src="https://www.facebook.com/video/embed?video_id=<?php echo $fb_data['v_id'] ?>" width="<?php echo $width ?>" height="280px"></iframe>
 */
?>
<iframe src="https://www.facebook.com/video/embed?video_id=<?php echo $fb_data['id'] ?>" width="<?php echo $width ?>" height="280px"></iframe>