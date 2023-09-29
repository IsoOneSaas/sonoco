<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <style>{!! $css ?? '' !!}</style>
    </head>
    <body>
        <p>[{{ $data['uid'] }}] {{ $data['name'] }} ({{ $data['role'] }}) @ {{ $data['date'] }}</p>
        {!! $content ?? '' !!}
    </body>
</html>