<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
 $height = $width * $prop;
?>
<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
    <img src="<?php echo $fb_data['source'] ?>" width="<?php echo $width ?>px" height="auto" style="max-width: <?php echo $width ?>px" />
</a>
