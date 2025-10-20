<!DOCTYPE html>
<html class='no-js' lang='es'>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>    
        <title>{{ $DATA['name'] }}</title>
        <link rel="stylesheet" href="{{ url('assets/css/head.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/css/render.css') }}" />
    </head>
    <body class="iso-body">
        <div class="iso-page">
            <div class="content">            
                <div class="intro-y box p-5 mt-5 w-full">

                            <div class="iso-body iso-{{ $size ?? 'emtpy' }}">                                                                   
                                <div class="iso-page">                      
                                    <div class="w-full p-2">
                                        @include('document/document/head_default')
                                        <div class="overflow-x-auto my-4">
     
                                            <div id="html-pattern">    
                                            @if( $DATA['txt'] != '' )
                                                {!! $DATA['txt'] !!}
                                            @else
                                                &nbsp;
                                            @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                </div>
            </div>
        </div>
    </body>
</html> 