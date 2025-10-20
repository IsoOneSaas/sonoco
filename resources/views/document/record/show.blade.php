<!-- resources/views/document/record/show.blade.php -->
<x-icewall>

    <x-slot:title>
        Registro - Visualizar
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('records.index') }}">Listado Maestro Registros</a></li>
        <li class="breadcrumb-item active" aria-current="page">Visualizar Registro</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Visualizar Registro
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" title="Listado" id="btn-back"><i data-lucide="menu" class="w-5 h-5"></i></a>
                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>                               
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-modal-history" href="javascript:;" class="dropdown-item"> <i data-lucide="cast" class="w-4 h-4 mr-2"></i> Historial </a>
                                        </li>
                                        
                                        <li>
                                            <a id="btn-print" href="javascript:;" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Imprimir </a>
                                        </li> 
                                                                                                                                                           
                                    </ul>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- BEGIN: Editor -->
                    <div class="intro-y box p-5 mt-5 bg-slate-200 flex justify-center">
                   
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
                    <!-- END: Editor -->                                                     
                </div>
                <!-- END: Content -->



@push('styles')
    <link rel="stylesheet" href="{{ url('assets/css/head.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/preview.css') }}" />    
    <style>
        .iso-body {
            margin: 0;
        }
        td.stamp { 
            height: 40px;         
            text-align: center;
            opacity:0.5;
            z-index:99;
            color:#AAA; 
        }        
       h2 {
        font-weight: 700;
        font-size: 1.2em;
        margin-bottom: 0.3em;
       }
       p, li {
        margin-bottom: 0.2em
       }            
    </style>
@endpush

@push('scripts-bottom')
    <script src="{{ url('assets/js/iso_scripts.js') }}"></script>
    <script>
        $(function () {
            $("#btn-back").on("click", function() {
                var uri = "{{ route('records.index') }}";
                location.href = uri; 
            });
        }); // document
    </script>
@endpush
</x-icewall> 