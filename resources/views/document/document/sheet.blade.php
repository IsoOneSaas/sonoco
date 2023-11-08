<!-- resources/views/document/document.sheet.blade.php -->
<x-icewall>

    <x-slot:title>
            Documentos  - Ficha Técnica
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ficha Técnica</li>
    </x-slot:breadcrumb>    

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Ficha Técnica del Documento
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">                            
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" id="btn-view"  title="Ver el documento"><i data-lucide="eye" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" id="btn-old"  title="Pasar a obsoleto"><i data-lucide="file-x" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" id="btn-copy" title="Versionar documento"><i data-lucide="files" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-delete" title="Eliminar documento"><i data-lucide="trash" class="w-5 h-5"></i></a>
                            <form method="POST" id="form-delete" action="{{ route('documents.control.documento.delete') }}">
                                @csrf
                                <input type="hidden" name="hash" value="{{ $data->hash }}" />
                                <input type="hidden" name="comment" />                                
                            </form>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" data-href="{{ route('documents.control.documento.index') }}" title="Regresar a la tabla" id="btn-exit"><i data-lucide="menu" class="w-5 h-5"></i></a>
                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                       
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <!-- BEGIN: Profile Menu -->
                        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                            <div class="intro-y box mt-5 lg:mt-0">
                                <div class="relative flex items-center p-5">
                                    <div class="w-12 h-12 image-fit">
                                        <i class="w-12 h-12 ml-1" data-lucide="{{ $data->statusIcon }}"></i>
                                        <span class="text-sm" >{{ $data->statusText }}</span>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="text-xl text-base">{{ $data->name }}</div>
                                        <div class="text-slate-500">Versión: {{ $data->version }}</div>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                        <div class="dropdown-menu w-56">
                                            <ul class="dropdown-content">
                                                <li>
                                                    <h6 class="dropdown-header">
                                                        Versiones
                                                    </h6>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                @if( $data->versions )
                                                @foreach($data->versions as $item)
                                                <li>
                                                    <a href="{{ route('documents.master.datasheet', $item->hash ) }}" class="dropdown-item">
                                                        <i data-lucide="box" class="w-4 h-4 mr-2"></i> {{ $item->text }} 
                                                        <div class="text-xs text-white px-1 rounded-full bg-success ml-auto">{{ $item->version }}</div>
                                                    </a>
                                                </li>
                                                @endforeach
                                                @endif
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <div class="flex p-1">
                                                        <button type="button" id="btn-version" class="btn btn-primary py-1 px-2">Nueva Versión</button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    <h2 class="text-lg text-center">{{ $data->code }}</h2>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    <div class="flex items-center mt-0" href=""> <i data-lucide="flag" class="w-4 h-4 mr-2"></i> {{ $data->setNameSystem }} </div>
                                    <div class="flex items-center mt-5" href=""> <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> {{ $data->setNameLocation }} </div>                                    
                                    <div class="flex items-center mt-5" href=""> <i data-lucide="compass" class="w-4 h-4 mr-2"></i> {{ $data->setNameProcess }} </div>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    <div class="flex items-center mt-0"> <i data-lucide="type" class="w-4 h-4 mr-2"></i> {{ $data->setNameType }} </div>
                                    <div class="flex items-center mt-5"> <i data-lucide="shuffle" class="w-4 h-4 mr-2"></i> {{ $data->flow }} </div>
                                    <div class="flex items-center mt-5"> <i data-lucide="codesandbox" class="w-4 h-4 mr-2"></i> {{ $data->pattern }} </div>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    @if( $data->TAGS  )                                    
                                        @foreach( $data->TAGS as $item )
                                        @if( $loop->index == 0 )
                                        <ul aria-label="{{ $item->class }}">
                                        @endif                                        
                                        <li class="flex items-center"><i data-lucide="tag" class="w-4 h-4 mr-2"></i> {{ $item->tag }}</li>
                                        @endforeach                                       
                                    </ul>
                                    @endif
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400 flex">
                                    <button type="button" id="btn-open" class="btn btn-primary py-1 px-2">Ver</button>
                                    <!-- <button type="button" class="btn btn-outline-secondary py-1 px-2 ml-auto">New Quick Link</button> -->
                                </div>
                            </div>

                            <!-- BEGIN: Latest Uploads -->
                            <div class="intro-y box col-span-12 lg:col-span-6 mt-6">
                                <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                    <h2 class="font-medium text-base mr-auto">
                                        Archivos adjuntos
                                    </h2>
<!--                                     <div class="dropdown ml-auto sm:hidden">
                                        <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                        <div class="dropdown-menu w-40">
                                            <ul class="dropdown-content">
                                                <li> <a href="javascript:;" class="dropdown-item">All Files</a> </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-secondary hidden sm:flex">All Files</button> -->
                                </div>
                                <div class="p-5">

                                    @if( $data->LINKS )
                                    @foreach( $data->LINKS as $item )
                                    <div id="link-{{ $item->link_id }}" class="flex items-center mt-3">
                                        <div class="file"><img src="{{ url('assets/images/mimes/'. $item->image) }}" class="w-12" /></div>
                                        <div class="ml-4">
                                            <p class="font-medium">{{ $item->name }}</p> 
                                            <div class="text-slate-500 text-xs mt-0.5">{{ round($item->size/1000, 0) }} kB</div>
                                        </div>
                                        <div class="dropdown ml-auto">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content">
                                                    <li>
                                                        <a href="javascript:;" class="dropdown-item link-download" data-file="{{ $item->file }}" > <i data-lucide="download" class="w-4 h-4 mr-2"></i> Descargar </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" class="dropdown-item link-delete" data-id="{{ $item->link_id }}" > <i data-lucide="trash" class="w-4 h-4 mr-2"></i> Eliminar </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif

                                </div>
                            </div>
                            <!-- END: Latest Uploads -->                            

                            <!--
                            <div class="intro-y box p-5 bg-primary text-white mt-5">
                                <div class="flex items-center">
                                    <div class="font-medium text-lg">Important Update</div>
                                    <div class="text-xs bg-white dark:bg-primary dark:text-white text-slate-700 px-1 rounded-md ml-auto">New</div>
                                </div>
                                <div class="mt-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</div>
                                <div class="font-medium flex mt-5">
                                    <button type="button" class="btn py-1 px-2 border-white text-white dark:text-slate-300 dark:bg-darkmode-400 dark:border-darkmode-400">Take Action</button>
                                    <button type="button" class="btn py-1 px-2 border-transparent text-white dark:border-transparent ml-auto">Dismiss</button>
                                </div>
                            </div>
                            -->

                        </div>
                        <!-- END: Profile Menu -->
                        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                            <div class="grid grid-cols-12 gap-6">
                                <!-- BEGIN: Daily Sales -->
                                <div class="intro-y box col-span-12 2xl:col-span-6">
                                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Flujo del documento
                                        </h2>
<!--                                         <div class="dropdown ml-auto sm:hidden">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content">
                                                    <li>
                                                        <a href="javascript:;" class="dropdown-item"> <i data-lucide="file" class="w-4 h-4 mr-2"></i> Download Excel </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-secondary hidden sm:flex"> <i data-lucide="file" class="w-4 h-4 mr-2"></i> Download Excel </button> -->
                                    </div>
                                    <div class="p-5">                                   

                                        @foreach( $data->FLOW as $key => $items )
                                        <div class="relative flex w-full bg-slate-100 py-1 px-2 mb-1"><h2>{{ $key }}</h2></div>
                                        @foreach( $items as $item )                                        
                                        <div class="relative flex items-center mb-2">
                                            <!-- Edición -->                                            
                                            <div class="w-10 h-10 ml-2 flex-none image-fit">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url($item['avatar']) }}">
                                            </div>
                                            <div class="ml-4 mr-auto">
                                                <div class="font-medium">{{ $item['name'] }}</div> 
                                                <div class="text-slate-500 mr-5 sm:mr-5">{{ $item['job'] }}</div>
                                            </div>
                                            <div class="font-medium text-slate-600 dark:text-slate-500 mr-2">{{  $item['date'] }}&nbsp;<input type="checkbox" {{ $item['checked'] }} disabled /></div>
                                        </div>
                                        <div>
                                            @if( isset($item['backs']) )
                                            @foreach( $item['backs'] as $back )
                                            <div class="bg-iso-red-100 py-1 px-2 mb-2 mx-8 flex text-xs items-center"><span class="ml-4">RETROCESO: {{ $back['user'] }}</span><span class="ml-auto">{{ $back['date'] }}</span></div>
                                            @endforeach
                                            @endif
                                        </div>
                                        @endforeach
                                        @endforeach

                                    </div>
                                </div>
                                <!-- END: Daily Sales -->
                                <!-- BEGIN: Observaciones -->
                                <div class="intro-y box col-span-12 2xl:col-span-6">
                                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Observaciones
                                        </h2>
                                        <button data-carousel="announcement" data-target="prev" class="tiny-slider-navigator btn btn-outline-secondary px-2 mr-2"> <i data-lucide="chevron-left" class="w-4 h-4"></i> </button>
                                        <button data-carousel="announcement" data-target="next" class="tiny-slider-navigator btn btn-outline-secondary px-2"> <i data-lucide="chevron-right" class="w-4 h-4"></i> </button>
                                    </div>
                                    <div class="tiny-slider py-5" id="announcement">
                                        @foreach( $data->SIGHTS as $item  )
                                        <div id="sight-{{ $item->sighting_id }}" class="px-5">
                                            <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-normal text-xl">{{ $item->type }}</div>
                                            <div class="font-medium text-lg mt-1">{{ $item->author }}</div>
                                            <div class="text-slate-600 dark:text-slate-500 mt-2">
                                                {!! $item->content !!}
                                            </div>
                                            <div class="flex items-center mt-5">
                                                <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium"><input type="checkbox" class="w-4 h-4" onClick="checkSighting({{ $item->sighting_id }})" {{ $item->checked }}  /></div>
                                                <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium ml-2">{{ $item->txtDate }}</div>
                                                <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium ml-2">{{ $item->page }}</div>
                                                <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium ml-2">{{ $item->section }}</div>
                                                <div class="ml-auto">
                                                    <button id="btn-sighting-delete" data-id="{{ $item->sighting_id }}" type="button" class="btn btn-primary py-1 px-1 ml-2"><i data-lucide="trash" class="w-6 h-6"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- END: Observaciones -->

                                <!-- BEGIN: History -->
                                <div class="intro-y box col-span-12 2xl:col-span-6">
                                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Historial de Cambios
                                        </h2>
                                        <button data-carousel="history" data-target="prev" class="tiny-slider-navigator btn btn-outline-secondary px-2 mr-2"> <i data-lucide="chevron-left" class="w-4 h-4"></i> </button>
                                        <button data-carousel="history" data-target="next" class="tiny-slider-navigator btn btn-outline-secondary px-2"> <i data-lucide="chevron-right" class="w-4 h-4"></i> </button>
                                    </div>
                                    <div class="tiny-slider py-5" id="history">
                                        @foreach( $data->CHANGES as $item  )
                                        <div id="sight-{{ $item->sighting_id }}" class="px-5">
                                            <div class="font-medium text-lg mt-1">{{ $item->author }}</div>
                                            <div class="text-slate-600 dark:text-slate-500 mt-2">
                                                {!! $item->text !!}
                                            </div>
                                            <div class="flex items-center mt-5">
                                                <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium ml-2">{{ $item->date }}</div>
                                                <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium ml-2">{{ $item->content_id }}</div>
                                                <div class="ml-auto">
                                                    <button  data-id="{{ $item->change_id }}" type="button" class="btn btn-primary py-1 px-1 ml-2 btn-change-delete"><i data-lucide="trash" class="w-6 h-6"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- END: History -->                                

                                <!-- BEGIN: Top Products - ->
                                <div class="intro-y box col-span-12 2xl:col-span-6">
                                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Top Products
                                        </h2>
                                        <div class="dropdown ml-auto">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content">
                                                    <li>
                                                        <a href="" class="dropdown-item"> <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i> New Chat </a>
                                                    </li>
                                                    <li>
                                                        <a href="" class="dropdown-item"> <i data-lucide="trash" class="w-4 h-4 mr-2"></i> Delete </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <ul class="nav nav-boxed-tabs justify-center flex-col sm:flex-row" role="tablist">
                                            <li id="top-products-laravel-tab" class="nav-item" role="presentation">
                                                <a href="javascript:;" class="nav-link text-center w-full sm:w-20 mb-2 sm:mb-0 sm:mx-2 py-2 px-0 active" data-tw-target="#top-products-laravel" aria-controls="top-products-laravel" aria-selected="true" role="tab" > <i data-lucide="box" class="block w-6 h-6 mb-2 mx-auto"></i> Laravel </a>
                                            </li>
                                            <li id="top-products-symfony-tab" class="nav-item" role="presentation">
                                                <a href="javascript:;" class="nav-link text-center w-full sm:w-20 mb-2 sm:mb-0 sm:mx-2 py-2 px-0" data-tw-target="#top-products-symfony" aria-selected="false" role="tab" > <i data-lucide="inbox" class="block w-6 h-6 mb-2 mx-auto"></i> Symfony </a>
                                            </li>
                                            <li id="top-products-bootstrap-tab" class="nav-item" role="presentation">
                                                <a href="javascript:;" class="nav-link text-center w-full sm:w-20 mb-2 sm:mb-0 sm:mx-2 py-2 px-0" data-tw-target="#top-products-bootstrap" aria-selected="false" role="tab" > <i data-lucide="activity" class="block w-6 h-6 mb-2 mx-auto"></i> Bootstrap </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content mt-8">
                                            <div id="top-products-laravel" class="tab-pane active" role="tabpanel" aria-labelledby="top-products-laravel-tab">
                                                <div class="flex flex-col sm:flex-row items-center">
                                                    <div class="mr-auto">
                                                        <a href="" class="font-medium">Wordpress Template</a> 
                                                        <div class="text-slate-500 mt-1">HTML, PHP, Mysql</div>
                                                    </div>
                                                    <div class="w-full sm:w-auto flex items-center mt-3 sm:mt-0">
                                                        <div class="bg-success/20 text-success rounded px-2 mr-5">+20%</div>
                                                        <div class="progress h-1 mt-2 sm:w-40">
                                                            <div class="progress-bar w-1/2 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col sm:flex-row items-center mt-5">
                                                    <div class="mr-auto">
                                                        <a href="" class="font-medium">Laravel Template</a> 
                                                        <div class="text-slate-500 mt-1">PHP, Mysql</div>
                                                    </div>
                                                    <div class="w-full sm:w-auto flex items-center mt-3 sm:mt-0">
                                                        <div class="bg-success/20 text-success rounded px-2 mr-5">+55%</div>
                                                        <div class="progress h-1 mt-2 sm:w-40">
                                                            <div class="progress-bar w-2/3 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col sm:flex-row items-center mt-5">
                                                    <div class="mr-auto">
                                                        <a href="" class="font-medium">Tailwind HTML Template</a> 
                                                        <div class="text-slate-500 mt-1">HTML, CSS, JS</div>
                                                    </div>
                                                    <div class="w-full sm:w-auto flex items-center mt-3 sm:mt-0">
                                                        <div class="bg-success/20 text-success rounded px-2 mr-5">+40%</div>
                                                        <div class="progress h-1 mt-2 sm:w-40">
                                                            <div class="progress-bar w-3/4 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <! -- END: Top Products -- >
                                <! -- BEGIN: Work In Progress -- >
                                <div class="intro-y box col-span-12 2xl:col-span-6">
                                    <div class="flex items-center px-5 py-5 sm:py-0 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Work In Progress
                                        </h2>
                                        <div class="dropdown ml-auto sm:hidden">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                            <div class="nav nav-tabs dropdown-menu w-40" role="tablist">
                                                <ul class="dropdown-content">
                                                    <li> <a id="work-in-progress-mobile-new-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#work-in-progress-new" class="dropdown-item" role="tab" aria-controls="work-in-progress-new" aria-selected="true">New</a> </li>
                                                    <li> <a id="work-in-progress-mobile-last-week-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#work-in-progress-last-week" class="dropdown-item" role="tab" aria-selected="false">Last Week</a> </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="nav nav-link-tabs w-auto ml-auto hidden sm:flex" role="tablist" >
                                            <li id="work-in-progress-new-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5 active" data-tw-target="#work-in-progress-new" aria-controls="work-in-progress-new" aria-selected="true" role="tab" > New </a> </li>
                                            <li id="work-in-progress-last-week-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5" data-tw-target="#work-in-progress-last-week" aria-selected="false" role="tab" > Last Week </a> </li>
                                        </ul>
                                    </div>
                                    <div class="p-5">
                                        <div class="tab-content">
                                            <div id="work-in-progress-new" class="tab-pane active" role="tabpanel" aria-labelledby="work-in-progress-new-tab">
                                                <div>
                                                    <div class="flex">
                                                        <div class="mr-auto">Pending Tasks</div>
                                                        <div>20%</div>
                                                    </div>
                                                    <div class="progress h-1 mt-2">
                                                        <div class="progress-bar w-1/2 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="flex">
                                                        <div class="mr-auto">Completed Tasks</div>
                                                        <div>2 / 20</div>
                                                    </div>
                                                    <div class="progress h-1 mt-2">
                                                        <div class="progress-bar w-1/4 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="flex">
                                                        <div class="mr-auto">Tasks In Progress</div>
                                                        <div>42</div>
                                                    </div>
                                                    <div class="progress h-1 mt-2">
                                                        <div class="progress-bar w-3/4 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="flex">
                                                        <div class="mr-auto">Tasks In Review</div>
                                                        <div>70%</div>
                                                    </div>
                                                    <div class="progress h-1 mt-2">
                                                        <div class="progress-bar w-4/5 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <a href="" class="btn btn-secondary block w-40 mx-auto mt-5">View More Details</a> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <! -- END: Work In Progress -- >
                                <! -- BEGIN: Latest Tasks -- >
                                <div class="intro-y box col-span-12 2xl:col-span-6">
                                    <div class="flex items-center px-5 py-5 sm:py-0 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Latest Tasks
                                        </h2>
                                        <div class="dropdown ml-auto sm:hidden">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                            <div class="nav nav-tabs dropdown-menu w-40" role="tablist">
                                                <ul class="dropdown-content">
                                                    <li> <a id="latest-tasks-mobile-new-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#latest-tasks-new" class="dropdown-item" role="tab" aria-controls="latest-tasks-new" aria-selected="true">New</a> </li>
                                                    <li> <a id="latest-tasks-mobile-last-week-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#latest-tasks-last-week" class="dropdown-item" role="tab" aria-selected="false">Last Week</a> </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="nav nav-link-tabs w-auto ml-auto hidden sm:flex" role="tablist" >
                                            <li id="latest-tasks-new-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5 active" data-tw-target="#latest-tasks-new" aria-controls="latest-tasks-new" aria-selected="true" role="tab" > New </a> </li>
                                            <li id="latest-tasks-last-week-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5" data-tw-target="#latest-tasks-last-week" aria-selected="false" role="tab" > Last Week </a> </li>
                                        </ul>
                                    </div>
                                    <div class="p-5">
                                        <div class="tab-content">
                                            <div id="latest-tasks-new" class="tab-pane active" role="tabpanel" aria-labelledby="latest-tasks-new-tab">
                                                <div class="flex items-center">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Create New Campaign</a> 
                                                        <div class="text-slate-500">10:00 AM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                                <div class="flex items-center mt-5">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Meeting With Client</a> 
                                                        <div class="text-slate-500">02:00 PM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                                <div class="flex items-center mt-5">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Create New Repository</a> 
                                                        <div class="text-slate-500">04:00 PM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                                <div class="flex items-center mt-5">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Meeting With Client</a> 
                                                        <div class="text-slate-500">10:00 AM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                                <div class="flex items-center mt-5">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Create New Repository</a> 
                                                        <div class="text-slate-500">11:00 PM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <! -- END: Latest Tasks -- >
                                <! -- BEGIN: General Statistics -- >
                                <div class="intro-y box col-span-12 2xl:col-span-6">
                                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            General Statistics
                                        </h2>
                                        <div class="dropdown ml-auto">
                                            <a class="dropdown-toggle w-5 h-5 block sm:hidden" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                            <button class="dropdown-toggle btn btn-outline-secondary font-normal hidden sm:flex" aria-expanded="false" data-tw-toggle="dropdown"> Export <i data-lucide="chevron-down" class="w-4 h-4 ml-2"></i> </button>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content">
                                                    <li>
                                                        <div class="dropdown-header">Export Tools</div>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a href="" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print </a>
                                                    </li>
                                                    <li>
                                                        <a href="" class="dropdown-item"> <i data-lucide="external-link" class="w-4 h-4 mr-2"></i> Excel </a>
                                                    </li>
                                                    <li>
                                                        <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> CSV </a>
                                                    </li>
                                                    <li>
                                                        <a href="" class="dropdown-item"> <i data-lucide="archive" class="w-4 h-4 mr-2"></i> PDF </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex flex-col sm:flex-row items-center">
                                            <div class="flex flex-wrap sm:flex-nowrap mr-auto">
                                                <div class="flex items-center mr-5 mb-1 sm:mb-0">
                                                    <div class="w-2 h-2 bg-pending rounded-full mr-3"></div>
                                                    <span>Author Sales</span> 
                                                </div>
                                                <div class="flex items-center mr-5 mb-1 sm:mb-0">
                                                    <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                                                    <span>Product Profit</span> 
                                                </div>
                                            </div>
                                            <div class="dropdown mt-3 sm:mt-0 mr-auto sm:mr-0">
                                                <button class="dropdown-toggle btn btn-outline-secondary font-normal" aria-expanded="false" data-tw-toggle="dropdown"> Filter by Month <i data-lucide="chevron-down" class="w-4 h-4 ml-2"></i> </button>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content overflow-y-auto h-32">
                                                        <li> <a href="" class="dropdown-item">January</a> </li>
                                                        <li> <a href="" class="dropdown-item">February</a> </li>
                                                        <li> <a href="" class="dropdown-item">March</a> </li>
                                                        <li> <a href="" class="dropdown-item">June</a> </li>
                                                        <li> <a href="" class="dropdown-item">July</a> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="report-chart mt-8">
                                            <div class="h-[212px]">
                                                <canvas id="report-line-chart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <! -- END: General Statistics -->



                            </div>
                        </div>
                        
                    </div>
                    <!-- BEGIN: Modal Version -->
                    <div id="modal-versions" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-versions-title" class="font-medium text-base mr-auto">Editar sugerencia de nuevo documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <form id="version-form" method="post" action="{{ route('documents.control.documento.version') }}" enctype="multipart/form-data" class="dropzone">
                                        @csrf
                                        <input type="hidden" name="hash" value="{{ $data->hash }}" />
                                        <div class="input-group mt-0">
                                            <div id="version" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.version.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.version.title') }}</div>
                                            <input type="number"  name="version" value="{{ $data->newVersion }}" class="form-control  w-full" aria-describedby="document" placeholder="{{ trans('document/document.form.version.placeholder') }}" required>
                                            <div id="input-group-11" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.version.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>                      
                                        </div>
                                    </form>
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-version-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cancelar</button>
                                    <button id="btn-version-ok" type="button" form="version-form" class="btn btn-primary">Crear</button>
                                    <a id="modal-versions-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-versions" class="">.</a>

                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Version -->

                </div>
                <!-- END: Content -->

@push('meta')                
    <meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush
                
@push('styles')
<link rel="stylesheet" href="{{ url('assets/js/sweetalert/11.7.12/minimal.min.css') }}" />
<style>
    
    ul:before{
        content:attr(aria-label);
        font-size:110%;
        font-weight:bold;
        margin-left:-10px;
    }
    
    .iso-swal-title {
        line-height: 100%;
    }
</style>
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/sweetalert/11.7.12/sweetalert2.min.js') }}"></script>
<script src="{{ url('assets/js/iso_scripts.js') }}"></script>

<script document="text/javascript">
    var $urlContent = "{{ $data->urlContent }}";
    var $target1 = "{{ config('settings.document_status.publish') }}";
    var $target2 = "{{ config('settings.document_status.obsolete') }}";
    var $status = "{{ $data->status }}";    
    $(function () {

        // BTN CREACION DE NUEVA VERSION
        $('#btn-copy, #btn-version').on("click", function(e)  {
            e.preventDefault();
            if( $status == $target1 ) {
                $("#modal-versions-open")[0].click();
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.publish.no-publish') }}");
            }            
        }); // btn-copy

        $('#btn-version-ok').on("click", function(e) {
            e.preventDefault();
            var form = $("#version-form");
            $.ajax({                
                type: 'POST',
                dataType: 'json',
                url: form.attr('action'),
                data: form.serialize(),
                async: false,                
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },                                             
                success: function(json) {  
                    console.dir(json);
                    $("#btn-version-ko").click(); 
                    if( json.status == 'success' ) {
                        var route = "{{ route('documents.control.documento.edit', ':hash') }}";
                        route = route.replace(':hash', json.hash);
                        setSuccessNotification('success', '', json.message);
                        setTimeout(goLocation, 3000, route);
                    } else {
                        setSuccessNotification('error', 'Oops!', json.message);
                    }
                } // success
            }); // ajax            
        });  // btn-version-ok

        // BTN CAMBIO A ESTADO DELETED
        $('#btn-delete').on("click", function(e)  {
            e.preventDefault();
            swal.fire({
                title: "{{ trans('document/document.delete.title') }}",
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: "{{ trans('document/document.delete.placeholder') }}",
                inputAttributes: {
                    'aria-label': "{{ trans('document/document.delete.placeholder') }}"
                },
                showCancelButton: true,
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'No, cancelar!',                
                customClass: {
                    title: 'iso-swal-title',
                    confirmButton: 'btn btn-danger waves-effect waves-effect waves-light',
                    cancelButton: 'btn btn-default ml-2 waves-effect'
                },
                buttonsStyling: false                                
            })
            .then( (result) => {
                //alert(result.value);
                if(result.value === '') {
                    setSuccessNotification('error', 'Oops!', "{{ trans('document/document.delete.no-comment') }}");
                    return false;                             
                } else if (result.value) {
                    $("input[name='comment']").val(result.value);
                    $("#form-delete").submit();
                }
            });                
        }); // btn-delete
        
        // BTN SALIR
        $('#btn-exit').on("click", function() {
            var url = $(this).data('href');
            //setTimeout(goLocation, 10, url);
            //location.href=url;
            history.back();
        }); // btn-exit
        
        // BTN ELIMINAR OBSERVACION
        $('body').on('click', '#btn-sighting-delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var route = "{{ route('documents.master.sighting.delete', ':id') }}";
            route = route.replace(':id', id);
            swal.fire({
                title: "{{ trans('document/sighting.delete.title') }}?",
                text: "{{ trans('document/sighting.delete.text') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'No, cancelar!',  
                customClass: {
                    title: 'iso-swal-title',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-default ml-2'
                },
                buttonsStyling: false  
            })
            .then((result) => {
                if (result.value) {
                    $.ajax({
                        url: route,
                        type: 'GET',
                        dataType: 'json',                
                        success: function(json) {
                            console.dir(json);
                            if( json.status == 'success' ) {
                                $("#sight-"+id).remove();
                                setSuccessNotification('success', '', json.message);                                
                            } else {
                                setSuccessNotification('error', 'Oops!', json.message);
                            }
                        } // success
                    }); // ajax
                } // if
            });  // swal            
            
        }); // btn-sighting-delete

        // BTN DELETE LINK
        $('body').on('click', '.link-delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            //alert('Eliminate id='+id);
            deleteAttachment(id);
        }); // link-delete

        // BTN DOWNLOAD LINK
        $('body').on('click', '.link-download', function (e) {
            e.preventDefault();
            var file = $(this).data('file');
            //alert('Download file='+file);
            openAttachment(file);
        }); // link-download

        // BTN CAMBIO A ESTADO OBSOLETE
        $('#btn-old').on("click", function(e)  {
            e.preventDefault();           
            if( ($status == $target1) || ($status == $target2) ) {            
                swal.fire({
                    title: "{{ trans('document/document.obsolete.title') }}",
                    icon: 'warning',
                    input: 'textarea',
                    inputPlaceholder: "{{ trans('document/document.obsolete.placeholder') }}",
                    inputAttributes: {
                        'aria-label': "{{ trans('document/document.obsolete.placeholder') }}"
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Si, cambiar!',
                    cancelButtonText: 'No, cancelar!',                
                    customClass: {
                        title: 'iso-swal-title',
                        confirmButton: 'btn btn-danger waves-effect waves-effect waves-light',
                        cancelButton: 'btn btn-default ml-2 waves-effect'
                    },
                    buttonsStyling: false                                
                })
                .then( (result) => {
                    //alert(result.value);
                    if(result.value === '') {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/document.obsolete.no-comment') }}");
                        return false;                             
                    } else if (result.value) {
                        var route = "{{ route('documents.control.documento.obsolete') }}";
                        var hash = "{{ $data->hash }}";
                        $.ajax({
                            url: route,
                            data: {hash: hash, note: result.value },
                            type: 'POST',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },                        
                            success: function(json) {
                                console.dir(json);
                                if( json.status == 'success' ) {
                                    setSuccessNotification('success', '', json.message);
                                    location.reload(true);                               
                                } else {
                                    setSuccessNotification('error', 'Oops!', json.message);
                                }
                            } // success
                        }); // ajax
                    }
                }); // then
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.publish.no-publish') }}");
            }           
        }); // btn-old
        
        $('#btn-view, #btn-open').on("click", function() {
            var hash = "{{ $data->hash }}";
            var uri = "{{ route('documents.master.render', ':hash') }}";
            
            if( ($status == $target1) || ($status == $target2) ) { 
                // Storage            
                isoSetStorage('iso_returnUrl', isoGetCurrentURL());
                // Ref
                uri = uri.replace(':hash', hash);
                location.href = uri;
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.publish.no-publish') }}");
            }                                                   
        }); // btn-view
        
        // BTN ELIMINAR CAMBIO
        //$('body').on('click', '#btn-change-delete', function (e) {
        $('.btn-change-delete').on("click", function(e) {   
            e.preventDefault();
            var id = $(this).data('id');
            //alert('Eliminate id='+id);
            deleteChange(id);            
        }); // btn-change-delete
                
    }); // document

    function goLocation(url) {
        location.href = url;
    }   // go to new Document
    
    function openAttachment(file) {
        var win;
        //var uri = $urlContent + file;
        alert(file);
        win = window.open(file, '_blank');
    }
    
    function deleteAttachment(id) {
        var route = "{{ route('documents.control.anexos.destroy', ':id') }}";

        swal.fire({
            title: "Está seguro de eliminar el anexo seleccionado?",
            text: "{{ trans('document/link.delete.text') }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar!',                
            customClass: {
                title: 'iso-swal-title',
                confirmButton: 'btn btn-danger waves-effect waves-effect waves-light',
                cancelButton: 'btn btn-default ml-2 waves-effect'
            },
            buttonsStyling: false
        })
        .then( (result) => {
            if (result.value) {
                route = route.replace(':id', id); 
                //alert(route);
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },                              
                    success: function(json) {
                        console.dir(json);
                        if( json.status == 'success' ) {
                            $("#link-"+id).remove();
                            setSuccessNotification('success', '', json.message);                                
                        } else {
                            setSuccessNotification('error', 'Oops!', json.message);
                        }
                    } // success
                }); // ajax
            } // if
        });                 
    } // deleteAttachment

    function checkSighting(id) {
        var route = "{{ route('documents.master.check', ':id') }}";
        route = route.replace(':id', id);        
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',                
            success: function(json) {
                console.dir(json);
            } // success
        }); // ajax
    } // checkSighting Fx

    function deleteChange(id) {
        var route = "{{ route('documents.control.change.delete', ':hash') }}";
        var hash = "{{ $data->hash }}";
        swal.fire({
            title: "{{ trans('document/change.delete.title') }}",
            text: "{{ trans('document/change.delete.text') }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar!',                
            customClass: {
                title: 'iso-swal-title',
                confirmButton: 'btn btn-danger waves-effect waves-effect waves-light',
                cancelButton: 'btn btn-default ml-2 waves-effect'
            },
            buttonsStyling: false
        })
        .then( (result) => {
            if (result.value) {
                route = route.replace(':hash', hash); 
                //alert(route);
                $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },                              
                    success: function(json) {
                        console.dir(json);
                        if( json.status == 'success' ) {
                            $("#link-"+id).remove();
                            setSuccessNotification('success', '', json.message);                                
                        } else {
                            setSuccessNotification('error', 'Oops!', json.message);
                        }
                    } // success
                }); // ajax
            } // if
        });                 
    } // deleteAttachment    

</script>

@include('components.notification_index')

@endpush

</x-icewall> 