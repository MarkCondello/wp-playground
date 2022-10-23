@extends('layouts.app')

@section('content')
<h1>Single.php</h1>
<iframe
  src="https://markcondello.com.au/styleguide"
  style="width: 500px;margin: auto;height: 550px;border: none;display: block;"
  ></iframe>
  @while(have_posts())
    @php(the_post())
    @php($votes = get_field("vote") ?: 0)
    <p>This post has <span id="vote_counter">{{ $votes }}</span> votes.</p>
    @php($nonce = wp_create_nonce("user_vote_nonce"))
    <!-- <form method="POST" action="{{ admin_url('admin-ajax.php') }}">
      <input type="hidden" name="action" value="user_vote">
      <input type="hidden" name="nonce" value="{{ $nonce }}"> 
      <input type="hidden" name="post_id" value="{{ the_ID() }}"> 
      <button type="submit"
        data-nonce="{{ $nonce }}"
        data-post_id="{{ the_ID() }}"
        class="user_vote"
      >Vote for this post</button>
    </form>  -->

    <button data-nonce="{{ $nonce }}" data-post-id="{{ the_ID() }}" id="user_vote">Vote for this post.</button>

    @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
  @endwhile
@endsection
