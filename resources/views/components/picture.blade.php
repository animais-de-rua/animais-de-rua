<picture>
    <source srcset="/img/{{ $image }}.webp" type="image/webp"/>
    <source srcset="/img/{{ $image }}.jpg" type="image/jpeg"/>
    <img src="/img/{{ $image }}.jpg" alt="{{ __(ucfirst(preg_replace('/\d+/', '', $image))) }}" loading="{{ isset($lazy) ? 'lazy' : 'auto' }}"/>
</picture>
