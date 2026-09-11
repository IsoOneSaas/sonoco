<!-- resources/views/document/dashboard_user.blade.php -->
<x-icewall>

    <x-slot:title>
            Dashboard de Documentos
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item active" aria-current="page">Dashboard Documentos</li>
    </x-slot:breadcrumb>      

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 2xl:col-span-9">
                            <div class="grid grid-cols-12 gap-6 background-documents">                                                    

                                <!-- BEGIN: General Report -->
                                <div class="col-span-12 mt-8">
                                    <div class="intro-y flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            Reporte General
                                        </h2>
                                        <!-- <a href="" class="ml-auto flex items-center text-primary"> <i data-lucide="refresh-ccw" class="w-4 h-4 mr-3"></i> Recargar Datos </a> -->
                                    </div>

                                    <!-- Agenda -->
                                    <div class="">
                                        <div class="intro-y box mt-5">
                                            <select id="week-select" class="form-control w-40">
                                                @foreach( $range as $now )                                                
                                                <option value={{ $now['w'] }} label="Semana {{ $now['w'] }}" @if($now['w'] == $current) selected @endif>{{ $now['y'] }}</option>
                                                @endforeach
                                            </select>
                                            <div  class='flex bg-white shadow-md justify-start md:justify-center rounded-lg overflow-x-scroll mx-auto py-4 px-2  md:mx-12 w-full'>
        
                                                @foreach($week as $day)
                                                    @if( $day['today'] )
                                                    <div class='flex group bg-purple-600 shadow-lg dark-shadow rounded-lg mx-1 cursor-pointer justify-center relative  w-full'>
                                                        <span class="flex h-3 w-3 absolute -top-1 -right-1">
                                                            <span class="animate-ping absolute group-hover:opacity-75 opacity-0 inline-flex h-full w-full rounded-full bg-purple-400 "></span>
                                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-100"></span>
                                                        </span>
                                                        <div class='flex items-center px-4 py-4'>
                                                            <div class='text-center'>
                                                                <p class='text-gray-100 text-lg'> {{ $day['name'] }} </p>
                                                                <p class='text-gray-100 text-xl  mt-1 mb-3 font-bold'> {{ $day['number'] }} </p>                                                                
                                                                @foreach( $day['events'] as $event )
                                                                <div class="flex justify-center">
                                                                    <a href="{{ $event['link'] }}" class="btn btn-{{ $event['color'] }} w-50 mr-2 mb-2"> <i data-lucide="{{ $event['icon'] }}" class="w-8 h-8 mr-2"></i> <span class="text-sm">{{ $event['code'] }}</span> </a>
                                                                </div>                                                                     
                                                                @endforeach                                                               
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    @else
                                                    <div class='flex group hover:bg-purple-500 hover:shadow-lg hover-dark-shadow rounded-lg mx-1 transition-all	duration-300 justify-center w-full'>
                                                        <div class='flex items-center px-4 py-4'>
                                                            <div class='text-center'>
                                                                <p class='text-gray-900 group-hover:text-gray-100 text-lg transition-all duration-300'>{{ $day['name'] }} </p>
                                                                <p class='text-gray-900 group-hover:text-gray-100 text-xl mt-1 mb-3 group-hover:font-bold transition-all  duration-300'> {{ $day['number'] }} </p>
                                                                @foreach( $day['events'] as $event )
                                                                <div class="flex justify-center">
                                                                    <a href="{{ $event['link'] }}" class="btn btn-{{ $event['color'] }} w-50 mr-2 mb-2"> <i data-lucide="{{ $event['icon'] }}" class="w-8 h-8 mr-2"></i> <span class="text-sm">{{ $event['code'] }}</span> </a>
                                                                </div>                                                                     
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    @endif
                                                @endforeach
                                                                                    
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <!-- Estado de documentos -->
                                    <div class="grid grid-cols-12 gap-6 mt-5">
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <a href="{{ route('documents.master.index') }}" ><i data-lucide="list" class="report-box__icon text-primary"></i></a>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeMaster ?? 0 }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Documentos para ver</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <a href="{{ route('documents.control.manage.index', ['slug' => 'edit']) }}"><i data-lucide="file-code" class="report-box__icon text-warning"></i></a> 
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeEdit ?? 0 }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Documentos para editar</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <a href="{{ route('documents.control.manage.index', ['slug' => 'review']) }}"><i data-lucide="file-search" class="report-box__icon text-warning"></i> </a>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeReview ?? 0 }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Documentos para revisar</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <a href="{{ route('documents.control.manage.index', ['slug' => 'approve']) }}"><i data-lucide="file-check-2" class="report-box__icon text-warning"></i> </a>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeApprove ?? 0 }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Documentos para aprobar</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
@if($followup)                      
                                    <!-- Actividad del usuario -->               
                                    <div class="grid grid-cols-12 gap-6 mt-5">
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
                                            <div class="intro-y box mt-5">
                                                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                                    <h2 class="font-medium text-base mr-auto">
                                                        Registros creados
                                                    </h2>
                                                </div>
                                                <div class="p-5">
                                                    <ul class="bg-white rounded-lg shadow divide-y divide-gray-200 max-w-sm">
                                                        @foreach($recs as $rec)                                            
                                                        <li class="px-6 py-2  border-2">
                                                            <div class="text-right"><a href="{{ $rec['link'] }}" class="btn py-1 px-2  text-xs">@if( $rec['status'] == 0) Editar @else Ver @endif</a></div>                                                    
                                                            <span class="text-gray-700">{{ $rec['name'] }}</span><br>
                                                            <div class="flex justify-between">                                                                    
                                                                <span class="font-semibold text-lg">{{ $rec['nui'] }}</span>
                                                            </div>                                                    
                                                            <span class="text-gray-500 text-xs">{{ $rec['date'] }}</span>
                                                        </li>                                            
                                                        @endforeach
                                                    </ul>                                       
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
                                            <div class="intro-y box mt-5">
                                                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                                    <h2 class="font-medium text-base mr-auto">
                                                        Registros por aprobar
                                                    </h2>
                                                </div>
                                                <div class="p-5">
                                                    <ul class="bg-white rounded-lg shadow divide-y divide-gray-200 max-w-sm">
                                                        @foreach($auths as $auth)                                            
                                                        <li class="px-6 py-2  border-2">
                                                            <div class="text-right"><a href="{{ $auth['link'] }}" class="btn py-1 px-2  text-xs"> Editar</a></div>                                                    
                                                            <span class="text-gray-700">{{ $auth['name'] }}</span><br>
                                                            <div class="flex justify-between">                                                                    
                                                                <span class="font-semibold text-lg">{{ $auth['nui'] }}</span>
                                                            </div>                                                    
                                                            <span class="text-gray-500 text-xs">{{ $auth['date'] }}</span>
                                                        </li>                                            
                                                        @endforeach
                                                    </ul>   
                                                </div>
                                            </div>                                              
                                        </div>
                                    </div>
@endif                
                                </div>
                                <!-- END: General Report -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Content -->
        
</x-icewall>