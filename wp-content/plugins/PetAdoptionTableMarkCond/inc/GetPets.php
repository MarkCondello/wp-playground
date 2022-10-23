<?php

class GetPets {
  private $filteredParams = [];
  private $queryVals = [];
  private $tableName = '';
  private $query = '';
  private $countQuery = '';
  private $acceptedFields = ['petname', 'species', 'petweight',  'birthyear', 'favhobby', 'favcolor', 'favfood', 'limit'];
  private $acceptedMinRangeFields = ['minpetweight', 'minbirthyear'];
  private $acceptedMaxRangeFields = ['maxpetweight', 'maxbirthyear'];
  public $limit = 100;
  public $count = 0;
  public function __construct()
  {
    global $wpdb;
    $this->tableName = $wpdb->prefix . 'pets';
    $this->query = "SELECT * FROM $this->tableName ";
    $this->countQuery = "SELECT COUNT(*) AS count FROM $this->tableName ";
    if (count($_GET)) {
      $this->getParams();
      $paramsCount = count($this->filteredParams);
      if ($paramsCount) {
        $this->buildQuery($paramsCount);
      }
    }
    $this->query .= "LIMIT $this->limit;";
    $this->countQuery .= ";";
    // var_dump($petsQuery);
    $this->pets = $wpdb->get_results($wpdb->prepare($this->query, $this->queryVals));
    $this->count = $wpdb->get_results($wpdb->prepare($this->countQuery, $this->queryVals));
  }

  public function getParams()
  {
    foreach($_GET as $index=>$param) {
      if (in_array($index, array_merge($this->acceptedFields, $this->acceptedMinRangeFields, $this->acceptedMaxRangeFields) )) {
        if ($index === 'limit') {
          $this->limit = $param;
        } else {
          $this->filteredParams[$index] = sanitize_text_field($param);
        }
      }
    }
  }
  public function buildQuery($paramsCount)
  {
    foreach($this->filteredParams as $key=>$param) {
      if (is_string($param)){
        $paramType = 'string';
      }
      if(is_numeric($param)) {
        $paramType = 'integer';
      } 
      if (isset($paramType)) {
        $placeholder = $paramType === 'integer' ? '%d' : '%s';
        if ($key === array_key_first($this->filteredParams)) {
          $this->query .= 'WHERE ';
          $this->countQuery .= 'WHERE ';
        }
        if ($paramsCount > 1 && $key !== array_key_first($this->filteredParams)) {
          $this->query .= 'AND ';
          $this->countQuery .= 'AND ';
        }
        //allow support for min / max weight and min max birthyear
        $rangeField = null;
        if (in_array($key,  array_merge($this->acceptedMinRangeFields, $this->acceptedMaxRangeFields))){
          if (str_contains($key, 'petweight')) {
            $rangeField = 'petweight';
          }
          if (str_contains($key, 'birthyear')) {
            $rangeField = 'birthyear';
          }
        }
        if (in_array($key,  $this->acceptedMinRangeFields)){
          $this->query .= "$rangeField > $placeholder ";
          $this->countQuery .= "$rangeField > $placeholder ";
        } else if (in_array($key,  $this->acceptedMaxRangeFields)){
          $this->query .= "$rangeField < $placeholder ";
          $this->countQuery .= "$rangeField < $placeholder ";
        } else {
          $this->query .= "$key = $placeholder ";
          $this->countQuery .= "$key = $placeholder ";
        }

        // var_dump($this->query);
        $this->queryVals[] = $param;
      }
    }
  }
}
?>