<picture>
    <source {{ isset($lazy) ? 'data-' : '' }}srcset="/img/{{ $image }}.webp" type="image/webp"/>
    <source {{ isset($lazy) ? 'data-' : '' }}srcset="/img/{{ $image }}.jpg" type="image/jpeg"/>
    <img {{ isset($lazy) ? 'data-' : '' }}src="/img/{{ $image }}.jpg" alt="{{ __(ucfirst(preg_replace('/\d+/', '', $image))) }}"/>
</picture>
