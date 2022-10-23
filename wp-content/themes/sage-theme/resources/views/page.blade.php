@extends('layouts.app')

@section('content')
<h1>PAGE template</h1>
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    @includeFirst(['partials.content-page', 'partials.content'])
  @endwhile
@endsection
