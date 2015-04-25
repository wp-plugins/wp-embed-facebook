<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
?>
<div style="width: <?php echo $width ?>px">
    <div class="wpemfb-video">
        <?php
        $url = $fb_data['source'];
        $file_array = explode('/',parse_url($url, PHP_URL_PATH));
        $file = end($file_array);
        $type_array = explode('.',$file);
        $type = end($type_array);
        $clean_type = strtolower($type);
        if( $clean_type == 'mp4' ) :?>

            <video controls>
                <source src="<?php echo $fb_data['source'] ?>" type="video/<?php echo $clean_type ?>">
            </video>

        <?php else : ?>

            <div class="fb-video" data-allowfullscreen="true"
                 data-href="https://www.facebook.com/video/embed?video_id=<?php echo $fb_data['id'] ?>">
            </div>

        <?php endif; ?>
    </div>
</div>