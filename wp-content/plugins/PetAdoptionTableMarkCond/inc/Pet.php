<?php
class Pet {
  private $tableName = '';
  private $query = '';

  public function __construct()
  {
    global $wpdb;
    $this->tableName = $wpdb->prefix . 'pets';
    $this->query = "SELECT * FROM $this->tableName ";
  }

  public function get($id)
  {
    global $wpdb;
    $this->query .= "WHERE id = %d LIMIT 1;";
    return $wpdb->get_results($wpdb->prepare($this->query, $id));
  }
}