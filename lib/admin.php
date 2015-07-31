<?php

//TODO add option
class EmbFbAdmin
{
    static function admin_enqueue_scripts($hook_suffix)
    {
        if ($hook_suffix == 'settings_page_embedfacebook') {
            global $wp_scripts;
            wp_enqueue_script('wpemfb-admin', WPEMFBURL . 'lib/js/admin.js', array('jquery-ui-accordion'));
            $queryui = $wp_scripts->query('jquery-ui-core');
            $url = "https://ajax.googleapis.com/ajax/libs/jqueryui/" . $queryui->ver . "/themes/smoothness/jquery-ui.css";
            wp_enqueue_style('jquery-ui-start', $url, false, null);
        }
        $translation_array = array('local' => get_locale(), 'fb_id' => get_site_option('wpemfb_app_id'), 'fb_root' => get_site_option('wpemfb_fb_root'));
        wp_localize_script('wpemfb', 'WEF', $translation_array);
        wp_enqueue_script('wpemfb');
    }

    static function admin_init()
    {
        $theme = get_site_option('wpemfb_theme');
        add_editor_style(WPEMFBURL . 'templates/' . $theme . '/wpemfb.css');
    }

    static function add_page()
    {
        add_options_page('EmbedFacebook', 'Embed Facebook', 'manage_options', 'embedfacebook', array('EmbFbAdmin', 'embedfb_page'));
    }

    /**
     * @param $option string option name
     * @param $type string  direct or true/false
     */
    static function save_option($option, $type)
    {
        if ($type == 'direct') {
            if (isset($_POST[$option])) {
                update_site_option($option, $_POST[$option]);
            }
        } else {
            if (isset($_POST[$option])) {
                update_site_option($option, 'true');
            } else {
                update_site_option($option, 'false');
            }
        }
    }

    static function savedata()
    {
        if (isset($_POST['wpemfb_app_secret'], $_POST['wpemfb_app_id'])) {

            $options = array(
                'wpemfb_app_id'         => 'direct',
                'wpemfb_app_secret'     => 'direct',
                'wpemfb_max_width'      => 'direct',
                'wpemfb_max_photos'     => 'direct',
                'wpemfb_max_posts'      => 'direct',
                'wpemfb_theme'          => 'direct',
                'wpemfb_show_like'      => 'true/false',
                'wpemfb_enqueue_style'  => 'true/false',
                'wpemfb_fb_root'        => 'true/false',
                'wpemfb_show_follow'    => 'true/false',
                'wpemfb_raw_video'      => 'true/false',
                'wpemfb_raw_photo'      => 'true/false',
                'wpemfb_show_posts'     => 'true/false',
                'wpemfb_raw_post'       => 'true/false',
                'wpemfb_enq_lightbox'   => 'true/false',
                'wpemfb_enq_wpemfb'     => 'true/false',
                'wpemfb_enq_fbjs'       => 'true/false',
                'wpemfb_ev_local_tz'    => 'true/false',
                'wpemfb_raw_video_fb'   => 'true/false',
            );
            foreach ($options as $option => $type) {
                self::save_option($option, $type);
            }
            if (isset($_POST['wpemfb_max_width']) && is_int($_POST['wpemfb_max_width'])) {
                $prop = get_site_option('wpemfb_proportions') * $_POST['wpemfb_max_width'];
                update_site_option('wpemfb_height', $prop);
            }
        }
        /**
         * Save extra options, requires coordination with 'wpemfb_options' action
         *
         * @since 1.8
         *
         */
        do_action('wpemfb_admin_save_data');
    }

    static function field($type, $name, $label, $args = array())
    {
        switch ($type) {
            case 'checkbox':
                ob_start();
                ?>
                <tr valign="middle">
                    <th><?php echo $label ?></th>
                    <td>
                        <input type="checkbox"
                               name="<?php echo $name ?>" <?php echo (get_site_option($name) === 'true') ? 'checked' : '' ?> />
                    </td>
                </tr>
                <?php
                ob_end_flush();
                break;
            default:
                ob_start();
                ?>
                <tr valign="middle">
                    <th><?php echo $label ?></th>
                    <td>
                        <input type="<?php echo $type ?>"
                               name="<?php echo $name ?>" <?php echo isset($args['required']) ? 'required' : '' ?>
                               value="<?php echo get_site_option($name) ?>" size="38"/>
                    </td>
                </tr>
                <?php
                ob_end_flush();
        }
    }
    static function section($title=''){
        if(!empty($title)) :
            ?>
            <h5><?php echo $title ?></h5>
            <div>
                <table>
                    <tbody>
            <?php
        else :
            ?>
                    </tbody>
                </table>
            </div>
            <?php
        endif;
    }
    static function embedfb_page()
    {
        if (isset($_POST['save-data']) && wp_verify_nonce($_POST['save-data'], 'wp-embed-facebook')) {
            self::savedata();

        }
        $sel1 = (get_site_option('wpemfb_theme') === 'default') ? 'selected' : '';
        $sel2 = (get_site_option('wpemfb_theme') === 'classic') ? 'selected' : '';

        ?>
        <style>
            .ui-widget-content th {
                font-weight: normal;
                padding-right: 10px;
            }

            .settings-col {
                width: 50% !important;
                padding-right: 2% !important;
                text-align: left !important;
            }

            .welcome-panel-last {
                width: 47% !important;
                text-align: center;
            }

            @media (max-width: 870px) {
                .settings-col {
                    width: 100% !important;
                }

                .welcome-panel-last {
                    width: 100% !important;
                }
            }
        </style>
        <div class="wrap">
            <h2>WP Embed Facebook</h2>
            <div class="welcome-panel">
                <div class="welcome-panel-content">
                    <div class="welcome-panel-column-container">
                        <div class="welcome-panel-column settings-col">
                            <form id="config-form" action="#" method="post">
                                <?php wp_nonce_field('wp-embed-facebook', 'save-data'); ?>
                                <div id="accordion">
                                    <?php
                                        self::section(__('Facebook application data', 'wp-embed-facebook'));
                                        self::field('text', 'wpemfb_app_id', __('App ID', 'wp-embed-facebook'), array('required' => 'true'));
                                        self::field('text', 'wpemfb_app_secret', __('App Secret', 'wp-embed-facebook'), array('required' => 'true'));
                                        self::section();
                                    ?>
                                    <?php self::section(__("General Options", 'wp-embed-facebook')); ?>
                                    <tr valign="middle">
                                        <th><?php _e("Template to use", 'wp-embed-facebook') ?></th>
                                        <td>
                                            <select name="wpemfb_theme">
                                                <option value="default" <?php echo $sel1 ?> >Default</option>
                                                <option value="classic" <?php echo $sel2 ?> >Classic</option>
                                                <?php
                                                /**
                                                 * Add a new theme option
                                                 * @since 1.8
                                                 */
                                                do_action('wpemfb_admin_theme');
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php
                                        self::field('number', 'wpemfb_max_width', __('Embed Max-Width', 'wp-embed-facebook'));
                                        self::field('number', 'wpemfb_max_photos', __('Number of Photos <br>on Embedded Albums', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_show_follow', __('Show follow button <br>on Embedded Profiles', 'wp-embed-facebook'));
                                        self::section();
                                        self::section(__('Embedded Fan Pages', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_show_like', __('Show like button', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_show_posts', __('Show latest posts', 'wp-embed-facebook'));
                                        self::field('number', 'wpemfb_max_posts', __('Number of posts', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_ev_local_tz', __('Show events on local time', 'wp-embed-facebook'));
                                        self::section();
                                        self::section(__('Raw Embedded Options', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_raw_video', __('Embed Videos Raw', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_raw_photo', __('Embed Photos Raw', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_raw_video_fb', __('Use Facebook embed code on raw videos', 'wp-embed-facebook'));
                                        self::section();
                                        /**
                                         * Add more options to the plugin page save them with 'wpemfb_admin_save_data' action
                                         * @since 1.8
                                         */
                                        do_action('wpemfb_options');
                                        self::section(__('Advanced Options', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_enqueue_style', __('Enqueue Styles', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_fb_root', __('Add fb-root on top of content', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_enq_lightbox', __('Enqueue Lightbox script', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_enq_wpemfb', __('Enqueue WPEmbedFB script', 'wp-embed-facebook'));
                                        self::field('checkbox', 'wpemfb_enq_fbjs', __('Enqueue Facebook SDK', 'wp-embed-facebook'));
                                        self::section();
                                    ?>
                                </div>
                                <input type="submit" name="submit" class="button button-primary button-hero"
                                       value="<?php _e('Save', 'wp-embed-facebook') ?>"/>
                            </form>
                        </div>
                        <div class="welcome-panel-column welcome-panel-last">
                            <?php ob_start(); ?>
                            <h3><?php _e('Premium Version Available', 'wp-embed-facebook') ?></h3>

                            <h2><?php _e('Only $6.99 USD', 'wp-embed-facebook') ?></h2>
                            <br>
                            <a class="button button-primary"
                               href="http://www.wpembedfb.com/premium"><?php _e('Check it out', 'wp-embed-facebook') ?></a>
                            <br>
                            <ul>
                                <li>
                                    <?php _e('Events with cover', 'wp-embed-facebook') ?>
                                </li>
                                <li>
                                    <?php _e('Fan Page Full Embed', 'wp-embed-facebook') ?>
                                </li>
                                <li>
                                    <?php _e('One Year Premium Support', 'wp-embed-facebook') ?>
                                </li>
                            </ul>
                            <hr>
                            <p>
                                <small><?php _e('More information', 'wp-embed-facebook') ?></small>
                                <br>
                                <a href="http://www.wpembedfb.com"
                                   style="color:#23487F;"><?php _e('Plugin Web Site', 'wp-embed-facebook') ?></a>
                            </p>
                            <hr>
                            <p>
                                <img src="<?php echo plugins_url('/img/hechoenmexico.png', __FILE__) ?>" width="60px"/>
                            </p>
                            <?php echo apply_filters('wpemfb_admin', ob_get_clean()); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}