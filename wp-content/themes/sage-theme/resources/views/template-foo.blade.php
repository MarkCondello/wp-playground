{{--
  Template Name: Foo Template
--}}
@extends('layouts.app')
@section('content')

<main id="site-content">
<div class="container">
@php
$args = array(
  'post_type' => 'post',
  'cat' => 2,
  'order_by' => 'asc',
  'posts_per_page' => 6
);

$post_query = new WP_Query($args);
@endphp

@if ($post_query->have_posts())
  @while($post_query->have_posts())

      @php $post_query->the_post(); @endphp
      
      <div class="card">
        <h2>{{ the_title() }}</h2>
        <div>{{ the_excerpt() }}</div>
      </div>
  @endwhile
@endif

 </div>
</main><!-- #site-content -->

@endsection
