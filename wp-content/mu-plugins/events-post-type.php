<?php

add_action('init', function () {
  register_post_type('event', [
    'supports' => ['title', 'editor', 'excerpt'],
    'public' => true,
    'show_in_rest' => true,
    'labels' => [
        'name' => 'Events',
        'add_new' => 'Add new Event',
        'edit_item' => 'Edit event',
        'all_items' => 'All Events',
        'singular_name' => 'Event',

    ],
    'menu_icon' => 'dashicons-calendar',

  ]);
});

?>