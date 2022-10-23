<?php

/*
  Plugin Name: Replace Words Filter
  Description: A plugin for replacing keywords with prefined words.
  Version: 1.1
  Author: MarkCond
  Author URI: https://markcondello.com.au
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class ReplaceWordsFilter {
  public function __construct()
  {
    add_action('admin_menu', [$this, 'menuPage']);
    add_filter('the_content', [$this, 'filterLogic']);
  }
  public function menuPage()
  {
    $mainMenuHook = add_menu_page('Words to Filter', 'Replace Words Filter', 'manage_options', 'replace-word-filter', [$this, 'replaceWordFilterPage'], plugin_dir_url(__FILE__) . '/assets/imgs/icons/filter.svg', 12);
    // add_menu_page('Words to Filter', 'Replace Words Filter', 'manage_options', 'replace-word-filter', [$this, 'replaceWordFilterPage'], 'dashicons-format-quote', 12);
    add_submenu_page('replace-word-filter', 'Words To Filter', 'Words list', 'manage_options', 'replace-word-filter', [$this, 'replaceWordFilterPage']); // Customize the label for the first level default menu created by WP
    $optionsMenuHook = add_submenu_page('replace-word-filter', 'Replace Words Filter Options', 'Replace Options', 'manage_options', 'replace-word-filter-options', [$this, 'replaceWordFilterOptionsPage']);
    add_action("load-{$mainMenuHook}", [$this, 'mainPageAssets']);
  }
  public function mainPageAssets()
  {
    wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) .'/assets/css/main.css');
  }
  public function replaceWordFilterPage()
  { ?>
    <div id="replace-words-main-page" class="wrap">
      <h1>Replace Words Filter.</h1>
      <?php if($_POST['just_submitted']) $this->handleForm() ?>
      <form method="POST" method="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="hidden" name="just_submitted" value="true" />
        <?php wp_nonce_field('saveFilterWords', 'rwfNonce') ?>
        <label for="words_to_replace">
          <p>Enter a comma-seperated list of the words to replace.</p>
        </label>
        <div class="container">
          <textarea name="words_to_replace" id="words_to_replace"><?= esc_html( get_option('words_to_replace', null)); ?></textarea>
        </div>
        <input type="submit" id="submit" class="button button-primary" value="Save Filter Changes">
      </form>
    </div>
    <?php
  }
  public function handleForm()
  {
    if (wp_verify_nonce($_POST['rwfNonce'], 'saveFilterWords') && current_user_can('manage_options')){
      update_option('words_to_replace', sanitize_text_field($_POST['words_to_replace']));
      ?>
      <div class="updated"><p>Your filtered words were saved...</p></div>
      <?php
    } else {
      ?>
      <div class="error">
        <p>Sorry, you do not have permission to perform this action.</p>
      </div>
      <?php
    }
  }
  public function replaceWordFilterOptionsPage()
  { ?>
    <div class="wrap">
      <h1>Replace Word Filter options.</h1>
      <?php if($_POST['replacement_form_submitted']) $this->handleReplacementForm() ?>
      <form method="POST" action="<?= $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ?>">
        <input type="hidden" name="replacement_form_submitted" value="true" >
      <?php wp_nonce_field('saveReplacementWord', 'rwfSubNonce');
      // echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
      // echo '<pre>';
      //   var_dump($_SERVER);
      //   echo '</pre>';
      ?>
        <label for="replacement_word">
          <p>Enter a word to replace the targeted words.</p>
        </label>
        <div class="container">
          <input name="replacement_word" id="replacement_word" value="<?= esc_html( get_option('replacement_word', null)); ?>">
        </div>
        <p>Leave blank to simply remove the replacement words.</p>
        <input type="submit" id="submit" class="button button-primary" value="Save Replacement Word">
      </form>
    </div>
    <?php
  }
  public function handleReplacementForm()
  {
    if (wp_verify_nonce($_POST['rwfSubNonce'], 'saveReplacementWord') && current_user_can('manage_options')){
      update_option('replacement_word', sanitize_text_field($_POST['replacement_word']));
      ?>
      <div class="updated"><p>Your replacement word was saved...</p></div>
      <?php
    } else {
      ?>
      <div class="error">
        <p>Sorry, you do not have permission to perform this action.</p>
      </div>
      <?php
    }
  }
  public function filterLogic($content)
  {
    if (get_option('words_to_replace')) {
      $targetWords = explode(',', get_option('words_to_replace'));
      $trimmedTargetWords = array_map('trim', $targetWords); //'trim '
      $replacementWord = esc_html( get_option('replacement_word', null));
      return str_ireplace($trimmedTargetWords, $replacementWord, $content);
    }
    return $content;
  }
}

$replaceWordsFilter = new ReplaceWordsFilter();