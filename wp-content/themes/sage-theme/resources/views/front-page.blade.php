@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  {!! $homeBanner !!}

  <x-example-component title="Front page file" class="foo-barr"></x-example-component>
  <!-- <h1>FRONT PAGE file</h1> -->
  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif
  @php
    $latestPosts = new WP_Query([
      'post_type' => 'page',
      'posts_per_page' => 2,
      'orderby' => 'date',
      'order' => 'DESC',
    ]);
    $latestEvents = new WP_Query([
      'post_type' => 'event',
      'posts_per_page' => 2,
      'orderby' => 'date',
      'order' => 'DESC',
    ]);
  @endphp
  <div id="two-cols">
  <section>
      <h2>Latest Events:</h2>
    @while($latestEvents->have_posts())
        @php($latestEvents->the_post())
        <div style="border: 1px solid grey; margin-bottom: 1rem;">
        <!-- {{get_post_type()}} -->
        <p>{{ the_time('D M Y') }}</p>
        @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
        </div>
        @php( wp_reset_query() )
    @endwhile
  </section> 
    <section>
      <h2>Latest Posts:</h2>
    @while($latestPosts->have_posts())
        @php($latestPosts->the_post())
        <div style="border: 1px solid grey; margin-bottom: 1rem;">
        <!-- {{get_post_type()}} -->
        <p>{{ the_time('D M Y') }}</p>
        @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
        </div>
        @php( wp_reset_query() )
    @endwhile
    </section>

  </div>
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection
