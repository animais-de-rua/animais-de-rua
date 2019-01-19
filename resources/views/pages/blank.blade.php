@extends('layouts.app')

@section('script')
<script>
caches.open('adr').then(function (cache) {
    cache.match(document.location.pathname, {ignoreSearch: true}).then(response => {
        response && response.text().then(html => {
            document.getElementById('content').innerHTML = html;
        });
    });
});
</script>
@endsection

@section('content')
@endsection
