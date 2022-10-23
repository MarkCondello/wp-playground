@extends('layouts.app')

@section('content')
<!-- <h1>ARCHIVE.php</h1> -->
<h1>
  {{ the_archive_title() }}
  <!-- @if (is_author())
  Posts authored by: {{ the_author() }}
  @endif
  @if (is_category())
  Posts Categorised by: {{ single_cat_title() }}
  @endif -->
</h1>
<p>{{ the_author_meta('description') }}</p>
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    @includeFirst(['partials.content-page', 'partials.content'])
  @endwhile
@endsection