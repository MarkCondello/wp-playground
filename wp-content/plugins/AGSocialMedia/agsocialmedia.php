<?php 
/*
Plugin Name: Antelope Genral Social Media Links
Author: Markamus
Version : 1.0
*/

//load text domain for localized languages: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
load_plugin_textdomain('agsml', false, basename(dirname(__FILE__)));

add_action('admin_menu', 'agsml_create_menu');
function agsml_create_menu(){
    //add top level menu and settings page
    add_options_page("Antelope General Social Media Links", "AG Social Media Links", 'manage_options', __FILE__, 'agsml_settings_page');
    add_filter("plugin_action_links", "agsml_settings_link", 10, 2);
    //call register settings function
    add_action("admin_init", 'agsml_register_settings');
}

//add settings link to plugins list
function agsml_settings_link($links, $file){
    static $this_plugin;
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin) { // This link does not work
        $settings_link = '<a href="options-general.php?page=AGSsocialMedia/antelope-social-media-link.php">' . __("Settings", "agsml_social_media") . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

/*The register_setting() function is useful for defining the data you want to save for your plugin,
and takes the following parameters:
register_setting( $option_group, $option_name, $sanitize_callback )*/
function agsml_register_settings(){ //saves settings to the wp_options table
    register_setting('antelope_social_group', 'agsml_facebook');
    register_setting('antelope_social_group', 'agsml_twitter');
    register_setting('antelope_social_group', 'agsml_twitter_logo');
    register_setting('antelope_social_group', 'agsml_youtube');
    register_setting('antelope_social_group', 'agsml_linkedin');
}

function agsml_settings_page() {
/* the inputs use form_option() which sanitizes data received */
?>
    <div class="wrap agsml_social_list">
        <h2>Antelope General Social Media Links</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'antelope_social_group' ); ?>
            <div class="setting">
                <p class="label_title"><?php _e('Facebook Profile:', 'agsml') ?></p>
                <p>
                    <label class="no_bold" for="agsml_facebook">
                        <span class="slim"><?php _e('Enter URL to your Facebook profile.', 'agsml') ?></span>
                        <input name="agsml_facebook" type="text" id="agsml_facebook" value="<?php form_option('agsml_facebook'); ?>" />
                    </label>
                </p>
                <hr/>
                <p class="label_title"><?php _e('Twitter Profile URL:', 'agsml') ?></p>
                <p>
                    <label class="no_bold" for="agsml_twitter">
                        <span class="slim"><?php _e('Enter the URL to your Twitter profile.') ?></span>
                        <input name="agsml_twitter" type="text" id="agsml_twitter" value="<?php form_option('agsml_twitter'); ?>" />
                    </label>
                </p>
                <!-- ToDo: Add the twitter logo options here -->
                <p>
                    <label class="no_bold" for="agsml_twitter">
                        <span class="slim"><?php _e('Enter the URL to a Twitter logo.') ?></span>
                        <input name="agsml_twitter_logo" type="text" id="agsml_twitter_logo" value="<?php form_option('agsml_twitter_logo'); ?>" />
                    </label>
                </p>
                <hr/>
                <p class="label_title"><?php _e('YouTube Profile URL:', 'agsml') ?></p>
                <p><label class="no_bold" for="agsml_youtube"><span class="slim">
                <?php _e('Enter the URL to your YouTube profile.');?></span>
                    <input name="agsml_youtube" type="text" id="agsml_youtube"
                value="<?php form_option('agsml_youtube'); ?>" /></label></p>
                 <hr/>
                <p class="label_title"><?php _e('LinkedIn Profile URL:', 'agsml') ?></p>
                <p><label class="no_bold" for="agsml_linkedin"><span class="slim">
                <?php _e('Enter the URL to your LinkedIn profile.', 'agsml')  ?></span>
                    <input name="agsml_linkedin" type="text" id="agsml_linkedin"
                value="<?php form_option('agsml_linkedin'); ?>" /></label></p>
                <hr/>
                <p class="setting">
                    <input type="submit" class="button-primary"
                value="<?php _e('Save Social Media Links', 'agsml') ?>" />
                </p>
            </div>
        </form>
    </div>
<?php 
}

function agsml_enqueue_styles(){
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style('agsml_stylesheet', $plugin_url . '/agsml-stylesheet.css' );
}
add_action('wp_enqueue_scripts', 'agsml_enqueue_styles' );

/* Register the widget */
function agsml_register_widget() {
    register_widget( 'Antelope_Widget' );
}

class Antelope_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'Antelope_Widget',
            __('Antelope Widget', 'agsml'), 
            [
                'classname'   => 'agsml_widget',
                'description' => __('Your social media links.', 'agsml'),
            ]
        );
    }
    public function widget($args, $instance){ //display the widget on website
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if($title){
            echo $before_title . $title . $after_title;
        }
        $social_list = '<ul>';
        if(get_option('agsml_facebook')){
            $social_list .= '<li class="facebook">
                <a href="' . get_option('agsml_facebook') . '"> '. __('Friend us on FB', 'agsml') .'</a>
                </li>';
        }
        if(get_option('agsml_twitter')){
            $social_list .= '<li class="twitter">
                <a href="' . get_option('agsml_twitter') . '"> 
                    <img src="' .  get_option('agsml_twitter_logo') .'" alt="Twitter logo," />'. __('Follow us on Twitter', 'agsml') .'</a>
            </li>';
        }
        if(get_option('agsml_linkedin')){
            $social_list .= '<li class="linkedin"><a href="' . get_option('agsml_linkedin') . '"> '. __('Connect on Linkedin', 'agsml') .'</a></li>';
        }
        if(get_option('agsml_youtube')){
            $social_list .= '<li class="youtube"><a href="' . get_option('agsml_youtube') . '"> '. __('Watch us on Youtube', 'agsml') .'</a></li>';
        }
        $social_list .= '</ul>';
        echo $social_list;
        echo $after_widget;
    }

    public function update($new_instance, $old_instance){ //save widget options once settings change
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    public function form($instance){ //form to display the widget settings
        $title = esc_attr($instance['title']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:');?></label>
            <input class="widefat" id="<?php $this->get_field_id('title');?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                type="text"
                value="<?php echo $title; ?>" />
        </p>
        <?php
    }

}

/* Load the widget */
add_action('widgets_init', 'agsml_register_widget');
?>