<?php

/*
  Plugin Name: Vote for post
  Description: A plugin for adding votes to posts.
  Version: 1.1
  Author: MarkCond
  Author URI: https://markcondello.com.au
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class VoteForPost {
  public function __construct()
  { 
    add_action("wp_ajax_user_vote", [$this, "userVote"]);
    add_action("wp_ajax_nopriv_user_vote", [$this, "goLogin"]);
  }
  public function userVote()
  {
    // die('reached userVote');
    if(!wp_verify_nonce($_REQUEST["nonce"], 'user_vote_nonce')) {
      exit("No vote for you...");
    }
    $post_id = $_REQUEST["post_id"];
    $vote_count = get_field("vote", $post_id) ?: 0;
    $new_vote_count = $vote_count + 1;
    $vote = update_field('vote', $new_vote_count, $post_id);
    if($vote === false) {
      $result['type'] = 'error';
      $result['votes_count'] = $vote_count;
    } else {
      $result['type'] = 'success';
      $result['votes_count'] = $new_vote_count;
    }

    //  die(var_dump($_SERVER));
    if ($_REQUEST["is_ajax"]){
    // if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    //   && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    } else {
      header("Location: ".$_SERVER['HTTP_REFERER']);
    }
    die();
  }
  public function goLogin()
  {
    echo "You mist login to vote.";
    die();
  }
}

$VoteForPost = new VoteForPost();
