@php
    $author = $blog->createdUser;
    $socialLinks = $blog->authorSocialLinks();
@endphp
<div class="mb-4">
    <img src="{{ $author?->adminlte_image() ?? asset('images/teleoperadora.jpg') }}"
        width="10%" class="img-thumbnail rounded-circle"
        alt="{{ $author?->name ?? 'Autor' }}">
    <span class="fs-6">
        <strong>{{ $author?->name ?? '—' }}</strong>
        @foreach ($socialLinks as $link)
            <a href="{{ $link['url'] }}" title="{{ $link['label'] }}"
                target="_blank" rel="noopener noreferrer"
                class="text-decoration-none ms-1">
                <i class="{{ $link['icon'] }}" style="{{ $link['style'] }}"></i>
            </a>
        @endforeach
    </span>
</div>
