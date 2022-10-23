<?php

/*
  Plugin Name: Word / Character Count & Read-time
  Description: Word and character count with read time for content on blog posts
  Version: 1.0
  Author: MarkCond
  Author URI: https://markcondello.com.au
*/

class WordCountAndReadTime {
  public function __construct() 
  {
    add_action('admin_menu', [$this, 'adminPage']);
    add_action('admin_init', [$this, 'settings']);
    add_filter('the_content', [$this, 'ifWrap']);
  }
  public function ifWrap($content)
  {
    if(is_single() && is_main_query() &&
        (
          get_option('wcp_word_count', '1') ||
          get_option('wcp_character_count', 'on') ||
          get_option('wcp_read_time', 'on')
        )
      ) {
      return $this->frontEndHTML($content);
    }
    return $content;
  }
  public function frontEndHTML($content)
  {
    $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Meta')) .'</h3>';
    if (get_option('wcp_word_count', 'on') == 'on' || get_option('wcp_read_time', 'on') == 'on') {
      $wordCount = str_word_count(strip_tags($content));
    }
    if (get_option('wcp_word_count', 'on') == 'on') {
      $html .= '<p>This post has ' . $wordCount . ' words.</p>';
    }
    if (get_option('wcp_character_count', 'on') == 'on') {
      $html .= '<p>This post has ' . strlen(strip_tags($content)) . ' characters.</p>';
    }
    if (get_option('wcp_read_time', 'on') == 'on') {
      $timeToRead = round(($wordCount/225), 2);
      $html .= '<p>This post takes approx '.$timeToRead .' minute(s) to read.</p>';
    }
    if (get_option('wcp_location', '0') === '0') {
      return $html . $content;
    }
    return $content . $html;
  }

  public function settings() 
  {
    add_settings_section('wcp_first_section', 'A place where the meta information will be displayed.', null, 'word-count-settings');

    add_settings_field('wcp_location', 'Display Location', [$this, 'LocationHTML'], 'word-count-settings', 'wcp_first_section');
    register_setting('word_count_plugin', 'wcp_location', 
    [
      'sanitize_callback' => [$this, 'sanitizeLocation'], // custom santize method
      'default' => '0'
    ]);

    add_settings_field('wcp_headline', 'Headline Text', [$this, 'headlineHTML'], 'word-count-settings', 'wcp_first_section');
    register_setting('word_count_plugin', 'wcp_headline', 
    [
      'sanitize_callback' => 'sanitize_text_field', // WP's santize function
      'default' => 'Post Statistics'
    ]);

    add_settings_field('wcp_word_count', 'Word Count', [$this, 'wordCountHTML'], 'word-count-settings', 'wcp_first_section');
    register_setting('word_count_plugin', 'wcp_word_count', 
    [
      'sanitize_callback' => 'sanitize_text_field', // WP's santize function
      'default' => 'on'
    ]);

    add_settings_field('wcp_character_count', 'Character count', [$this, 'characterCountHTML'], 'word-count-settings', 'wcp_first_section');
    register_setting('word_count_plugin', 'wcp_character_count', 
    [
      'sanitize_callback' => 'sanitize_text_field', // WP's santize function
      'default' => 'on'
    ]);

    add_settings_field('wcp_read_time', 'Read time', [$this, 'readTimeHTML'], 'word-count-settings', 'wcp_first_section');
    register_setting('word_count_plugin', 'wcp_read_time', 
    [
      'sanitize_callback' => 'sanitize_text_field', // WP's santize function
      'default' => 'on'
    ]);
  }
  public function locationHTML() 
  { ?>
  <select name="wcp_location">
    <option value="0" <?php selected(get_option('wcp_location'), '0') ?>>Top</option>
    <option value="1" <?php selected(get_option('wcp_location'), '1') ?>>Bottom</option>
    <option value="test" <?php selected(get_option('wcp_location'), 'test') ?>>Some value</option>
  </select>
<?php
  }
  public function headlineHTML() 
  { ?>
  <input type="text" name="wcp_headline" value="<?= esc_attr(get_option('wcp_headline')) ?>" placeholder="The headline for the meta info.">
  <?php
  }
  public function wordCountHTML() 
  { ?>
    <input type="checkbox" name="wcp_word_count" <?php checked(get_option('wcp_word_count'), 'on') ?>>
    <?php
  }
  public function characterCountHTML() 
  { ?>
    <input type="checkbox" name="wcp_character_count" <?php checked(get_option('wcp_character_count'), 'on') ?>>
    <?php
  }
  public function readTimeHTML() 
  { ?>
    <input type="checkbox" name="wcp_read_time" <?php checked(get_option('wcp_read_time'), 'on') ?>>
    <?php
  }
  public function adminPage() 
  {
    add_options_page('Word count settings', 'Word Count', 'manage_options', 'word-count-settings', [$this, 'settingsMarkup']);
  }
  public function sanitizeLocation($input) 
  {
    if($input !== '0' && $input !== '1') {
      add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be Top or Bottom.');
      return get_option('wcp_location');
    }
    return $input;
  }
  public function settingsMarkup() 
  {
    ?>
    <div class="wrap">
      <h1>WordCount settings.</h1>
      <form action="options.php" method="POST">
        <?php
          settings_fields('word_count_plugin'); // this handles the nonce settings and the fields validation
          do_settings_sections('word-count-settings');
          submit_button();
        ?>
      </form>
     </div>
  <?php
  }
}

$wordCountAndReadTime = new WordCountAndReadTime();


