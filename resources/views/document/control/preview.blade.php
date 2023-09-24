<!DOCTYPE html>
<html class='no-js' lang='es'>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>    
        <title>{{ $document->name }}</title>
        <link rel="stylesheet" href="{{ url('assets/css/head.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/css/preview.css') }}" />
    </head>
    <body class="iso-body iso-{{ $size ?? 'emtpy' }}">
        <div class="iso-page iso-{{ $size ?? 'emtpy' }}">
            <div class="content">            
                <div class="intro-y box p-5 mt-5 w-full">
                    @include('document/document/head_default')
                    <div class="overflow-x-auto iso-content">
                        {!! $document->content !!}
                    </div>
                    <div class="iso-content iso-link">
                        @if( $attachment )
                            <h2>Anexos: </h2>
                            <ul>
                                @foreach($attachment as $item)
                                <li><a href="{{ url($item->url) }}" target="_blank">{{$item->name }} [{{ round($item->size/1000,0) }}kB]</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @include('document/document/footer_default')
                </div>
            </div>
        </div>
    </body>
</html>    