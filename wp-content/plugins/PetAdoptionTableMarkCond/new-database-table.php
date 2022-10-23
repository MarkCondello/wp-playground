<?php

/*
  Plugin Name: Pet Adoption (DB Table)
  Version: 1.0
  Author: Brad
  Author URI: https://www.udemy.com/user/bradschiff/
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once plugin_dir_path(__FILE__) . 'inc/generatePet.php';

class PetAdoptionTablePlugin {
  public $message = null;
  function __construct() {
    global $wpdb; // global allows use to access the global context within a class or function body, we can also use the $_GLOBALS['variable'] syntax
    $this->charset = $wpdb->get_charset_collate();
    $this->tableName = $wpdb->prefix . "pets";
    add_action('activate_PetAdoptionTableMarkCond/new-database-table.php', [$this, 'onActivate']); // only runs when plugin is activated
    // add_action('activate_plugin', [$this, 'onActivate']); // only runs when plugin is activated
    // uses 'ativate_' plus the name of the plugin folder / forward slash the name of the main php file
    // add_action('admin_head', [$this, 'populateFast']);
    add_action('wp_enqueue_scripts', [$this, 'loadAssets']);
    add_filter('template_include', [$this, 'loadTemplate'], 99); // add plugin template for specific page url

    add_action('admin_post_add_pet', [$this, 'handlePost']);
    add_action('admin_post_nopriv_add_pet', [$this, 'handlePost']); // no privledges

    add_action('admin_post_delete_pet', [$this, 'handleDelete']);
    add_action('admin_post_nopriv_delete_pet', [$this, 'handleDelete']); // no privledges

    // Edit route updates
    add_action('init', function() {
      add_rewrite_rule('edit-pet/([0-9]+)[/]?$', 'index.php?pet=$matches[1]', 'top');
    });
    // tell WP that the pet is an acceptable query var
    add_filter('query_vars', function($query_vars){
      $query_vars[] = 'pet';
      return $query_vars;
    });

    add_action('template_include', function($template){
      $this->petId = null;
      if (get_query_var('pet') == false || get_query_var('pet') == '' ) {
        return $template;
      }
      $this->petId = get_query_var('pet');
      var_dump($this->petId); // DOES NOT PROVIDE THE PET ID PASSED IN
      return plugin_dir_path(__FILE__) .'/inc/template-edit-pet.php';
    });

    add_action('admin_post_edit_pet', [$this, 'handleEdit']);
    add_action('admin_post_nopriv_edit_pet', [$this, 'handleEdit']); // no privledges
  }

  function onActivate() {
    // this dbDelta() function checks for changes in the table generated and updates it if it has been modified
    // in order to access this function, we need to include the update.php file first
    //details about creating tables can be found in the docs here: https://codex.wordpress.org/Creating_Tables_with_Plugins
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta("CREATE TABLE $this->tableName (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      birthyear smallint(5) NOT NULL DEFAULT 0,
      petweight smallint(5) NOT NULL DEFAULT 0,
      favfood varchar(60) NOT NULL DEFAULT '',
      favhobby varchar(60) NOT NULL DEFAULT '',
      favcolor varchar(60) NOT NULL DEFAULT '',
      petname varchar(60) NOT NULL DEFAULT '',
      species varchar(60) NOT NULL DEFAULT '',
      PRIMARY KEY  (id)
    ) $this->charset;");
  }

  function onAdminRefresh() {
    global $wpdb;
    $wpdb->insert(
      $this->tableName,
      [
        'birthyear' => rand(2006, 2021),
        'petweight' => rand(1, 100),
        'favcolor' => 'Poo',
        'favfood' => 'Poo',
        'favhobby' => 'Poo',
        'petname' => 'Haggis',
        'species' => 'retard shitsu'
      ]
    );
  }
  public function handlePost()
  {
    if (current_user_can('administrator')) {
      $pet = generatePet();
      $pet['petname']= sanitize_text_field($_POST['pet_name']);
      global $wpdb;
      $wpdb->insert($this->tableName, $pet);
      wp_redirect(site_url('/custom-sql-table-example'));
    } else {
      wp_redirect(site_url());
    }
  }
  public function handleDelete()
  {
    if (current_user_can('administrator')) {
      global $wpdb;
      $wpdb->delete($this->tableName, [ 'id' => sanitize_text_field($_POST['pet_id']) ]);
      $this->message = "The pet {$_POST['pet_name']} has been deleted.";
      wp_redirect(site_url("/custom-sql-table-example?message=$this->message"));
    } else {
      wp_redirect(site_url());
    }
  }
  public function handleEdit()
  {
    if (current_user_can('administrator') && isset($_POST['petid'])) {
      $pet['petname']= sanitize_text_field($_POST['petname']);
      global $wpdb;
      $petUpdate = $wpdb->prepare("UPDATE $this->tableName
        SET petname = %s
        WHERE id = %d;", sanitize_text_field($_POST['petname']), $_POST['petid']
      );
      $wpdb->query($petUpdate);
      wp_redirect(site_url('/custom-sql-table-example?message=The pet '. $_POST["petname"]. ' has been updated...'));
    } else {
      wp_redirect(site_url());
    }
  }

  function loadAssets() {
    if (is_page('custom-sql-table-example')) {
      wp_enqueue_style('petadoptioncss', plugin_dir_url(__FILE__) . 'pet-adoption.css');
    }
  }

  function loadTemplate($template) {
    if (is_page('custom-sql-table-example')) {
      return plugin_dir_path(__FILE__) . 'inc/template-pets.php';
    }
    return $template;
  }

  function populateFast() {
    $query = "INSERT INTO $this->tableName (`species`, `birthyear`, `petweight`, `favfood`, `favhobby`, `favcolor`, `petname`) VALUES ";
    $numberofpets = 10000;
    for ($i = 0; $i < $numberofpets; $i++) {
      $pet = generatePet();
      $query .= "('{$pet['species']}', {$pet['birthyear']}, {$pet['petweight']}, '{$pet['favfood']}', '{$pet['favhobby']}', '{$pet['favcolor']}', '{$pet['petname']}')";
      if ($i != $numberofpets - 1) {
        $query .= ", ";
      }
    }
    /*
    Never use query directly like this without using $wpdb->prepare in the
    real world. I'm only using it this way here because the values I'm 
    inserting are coming fromy my innocent pet generator function so I
    know they are not malicious, and I simply want this example script
    to execute as quickly as possible and not use too much memory.
    */
    // var_dump($query);
    global $wpdb;
    $wpdb->query($query);
  }

}

$petAdoptionTablePlugin = new PetAdoptionTablePlugin();