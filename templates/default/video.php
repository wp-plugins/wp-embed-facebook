<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
?>
<?php
$url = $fb_data['source'];
$file_array = explode('/',parse_url($url, PHP_URL_PATH));
$file = end($file_array);
$type_array = explode('.',$file);
$type = end($type_array);
$clean_type = strtolower($type);
if( $clean_type == 'mp4' ||  $clean_type == 'webm' || $clean_type == 'ogg') :?>

    <video width="<?php echo $width ?>" height="280px" controls>
        <source src="<?php echo $fb_data['source'] ?>" type="video/<?php echo $clean_type ?>">
    </video>

<?php else : ?>

    <iframe src="https://www.facebook.com/video/embed?video_id=<?php echo $fb_data['id'] ?>" width="<?php echo $width ?>" height="280px"></iframe>

<?php endif; ?>