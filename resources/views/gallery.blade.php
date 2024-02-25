@extends('main_template')

@section('title', 'Galería')

@section('site-content')
<div class="row">
    <div class="items-grid">
        <div class="column gallery-item threecol ">
            <div class="featured-image">
                <a href="{{ asset('img/catalonia-riviera-maya-resort-y-spa_147005578017.jpg') }}" class="colorbox  cboxElement" data-group="gallery-111" title="Catalonia Riviera Maya Resort &amp; Spa">
                    <img width="440" height="330" src="img/catalonia-riviera-maya-resort-y-spa_147005578017.jpg" class="attachment-preview wp-post-image" alt="Catalonia Riviera Maya Resort &amp; Spa">
                </a>
                <a class="featured-image-caption visible-caption" href="#"><h6>Catalonia Riviera Maya Resort &amp; Spa</h6></a>
            </div>
            <div class="block-background"></div>
        </div>
        <div class="column gallery-item threecol ">
            <div class="featured-image">
                <a href="img/catalonia-riviera-maya-resort-y-spa_147005578016.jpg" class="colorbox  cboxElement" data-group="gallery-111" title="Catalonia Riviera Maya Resort &amp; Spa">
                    <img width="440" height="330" src="img/catalonia-riviera-maya-resort-y-spa_147005578016.jpg" class="attachment-preview wp-post-image" alt="Catalonia Riviera Maya Resort &amp; Spa">
                </a>
                <a class="featured-image-caption visible-caption" href="#"><h6>Catalonia Riviera Maya Resort &amp; Spa</h6></a>
            </div>
            <div class="block-background"></div>
        </div>
        <div class="column gallery-item threecol ">
            <div class="featured-image">
                <a href="img/catalonia-riviera-maya-resort-y-spa_147005577915.jpg" class="colorbox  cboxElement" data-group="gallery-111" title="Catalonia Riviera Maya Resort &amp; Spa">
                    <img width="440" height="330" src="img/catalonia-riviera-maya-resort-y-spa_147005577915.jpg" class="attachment-preview wp-post-image" alt="Catalonia Riviera Maya Resort &amp; Spa">
                </a>
                <a class="featured-image-caption visible-caption" href="#"><h6>Catalonia Riviera Maya Resort &amp; Spa</h6></a>
            </div>
            <div class="block-background"></div>
        </div>
        <div class="column gallery-item threecol last">
            <div class="featured-image">
                <a href="img/catalonia-riviera-maya-resort-y-spa_147005577914.jpg" class="colorbox  cboxElement" data-group="gallery-111" title="Catalonia Riviera Maya Resort &amp; Spa">
                    <img width="440" height="330" src="img/catalonia-riviera-maya-resort-y-spa_147005577914.jpg" class="attachment-preview wp-post-image" alt="Catalonia Riviera Maya Resort &amp; Spa">
                </a>
                <a class="featured-image-caption visible-caption" href="#"><h6>Catalonia Riviera Maya Resort &amp; Spa</h6></a>
            </div>
            <div class="block-background"></div>
        </div>
        <div class="clear"></div>
                              
        <div class="clear"></div>
    </div>
    <nav class="pagination">
        <span class="page-numbers current">1</span><a class="page-numbers" href="galeria.php?pageNum_imagenes=1">2</a><a class="page-numbers" href="galeria.php?pageNum_imagenes=2">3</a><a class="page-numbers" href="galeria.php?pageNum_imagenes=3">4</a><a class="page-numbers" href="galeria.php?pageNum_imagenes=4">5</a><a class="page-numbers" href="galeria.php?pageNum_imagenes=5">6</a><a class="page-numbers" href="galeria.php?pageNum_imagenes=6">7</a><a class="page-numbers" href="galeria.php?pageNum_imagenes=7">8</a>
    </nav>
</div>
@endsection