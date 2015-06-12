<?php
/*
 * You can create your own template by placing a copy of this file on yourtheme/plugins/wp-embed-fb/
 * to access all fb data print_r($fb_data)
 */
?>
<?php
$start_time_format = !empty($fb_data['is_date_only']) ? 'l, j F Y' : 'l, j F Y g:s a';
$start_time = strtotime($fb_data['start_time']);
if (get_site_option('wpemfb_ev_local_tz', 'true') == 'true') {
    $start_time = $start_time + get_option('gmt_offset') * 3600; //shows event date on local time
}
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
            <?php echo date_i18n($start_time_format, $start_time) ?><br>
            <?php
            if(isset($fb_data['place']['id'])){
                echo '<a href="http://www.facebook.com/'.$fb_data['place']['id'].'" target="_blank">'.$fb_data['location'].'</a>';
            } else {
                echo isset($fb_data['place']['name']) ? __('@ ', 'wp-embed-facebook') . $fb_data['place']['name'] : '';
            }
            ?>
            <br>
            <?php echo __('Creator: ', 'wp-embed-facebook').'<a href="http://www.facebook.com/'.$fb_data['owner']['id'].'" target="_blank">'.$fb_data['owner']['name'].'</a>' ?>
        </div>
    </div>
</div>
