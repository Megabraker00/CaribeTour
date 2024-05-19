@extends('main_template')

@section('title', 'Unos destinos de puta madre')

@section('site-content')

<div class="row">

    @if(empty($subCategories))
    <h2>La categoría no exite</h2>
    @endif

    @foreach ($subCategories as $index => $category)

    <div class="fourcol column @if(($index + 1) % 3 == 0) last @endif">
        <div class="featured-blog">
            <article class="post-112 post type-post status-publish format-standard hentry category-guides tag-amet tag-dolor tag-lorem post">
                <div class="featured-image">
                    <a hreflang="es" type="text/html" href="{{ route('destinos.provincia', ['country' => $category->parentCategory->slug, 'province' => $category->slug]) }}">
                        <img width="440" height="299" src="{{ asset( 'images/' . $category->mainImage()->path )}}" class="attachment-normal wp-post-image" alt="{{ $category->name }}" title="{{ $category->name }}">
                    </a>
                </div>
                <div class="post-content">
                    <h2 class="post-title">
                        <a hreflang="es" type="text/html" href="{{ route('destinos.provincia', ['country' => $category->parentCategory->slug, 'province' => $category->slug]) }}" title="{{ $category->name }}">{{ $category->name }} desde 631,40€</a>
                    </h2>
                </div>
            </article>
        </div>
    </div>

    @if(($index + 1) % 3 == 0) <div class="clear"></div> @endif

    @endforeach

</div>

@endsection