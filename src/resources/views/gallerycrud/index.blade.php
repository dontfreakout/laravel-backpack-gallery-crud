@extends('layouts.app')


@section('content')
        <div class="row topspace">
            <div class="col-sm-8 col-sm-offset-2">
                <article class="post">
                    <header class="entry-header">
                        <h1 class="entry-title">{{ $title }}</h1>
                    </header>
                </article>
            </div>
        </div>

@if(count($galleries) > 0)
<div class="row topspace">
    <div class="col-sm-8 col-sm-offset-2">

        @foreach ($galleries as $gallery)
        <article class="post">
            <header class="entry-header">
                <h1 class="entry-title"><a href="{{ route('gallery.show', ["slug" => $gallery->slug]) }}"
                                           rel="bookmark">{{ $gallery->title }}</a></h1>
            </header>
            <div class="entry-content">
                <p>{{ strip_tags(str_limit($gallery->body, 300)) }}</p>
            </div>
        </article>
        @endforeach
    </div>
</div>
@endif

@stop
