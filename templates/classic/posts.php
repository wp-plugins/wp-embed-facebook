<?php if(!isset($fb_data['link'])) : ?>
<div class="fb-post" data-href="https://www.facebook.com/<?php echo $fb_data['user'] ?>/posts/<?php echo $fb_data['is_post'] ?>" data-width="<?php echo $width ?>">
</div>
<?php else : ?>
<div class="fb-post" data-href="<?php echo $fb_data['link'] ?>" data-width="<?php echo $width ?>">
</div>	
<?php endif; ?>
