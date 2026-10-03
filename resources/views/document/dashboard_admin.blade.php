<!-- resources/views/document/dashboard_admin.blade.php -->
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
                                            <a href="{{ route('documents.master.index') }}" >
                                                <div class="report-box zoom-in">
                                                    <div class="box p-5">
                                                        <div class="flex">
                                                            <i data-lucide="list" class="report-box__icon text-primary"></i>
                                                        </div>
                                                        <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeMaster ?? 0 }}</div>
                                                        <div class="text-base text-slate-500 mt-1">Documentos vistos</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <a href="{{ route('documents.control.manage.index', ['slug' => 'edit']) }}">
                                                <div class="report-box zoom-in">
                                                    <div class="box p-5">
                                                        <div class="flex">
                                                            <i data-lucide="file-code" class="report-box__icon text-warning"></i> 
                                                        </div>
                                                        <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeEdit ?? 0 }}</div>
                                                        <div class="text-base text-slate-500 mt-1">Documentos para editar</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <a href="{{ route('documents.control.manage.index', ['slug' => 'review']) }}">
                                                <div class="report-box zoom-in">
                                                    <div class="box p-5">
                                                        <div class="flex">
                                                            <i data-lucide="file-search" class="report-box__icon text-warning"></i> 
                                                        </div>
                                                        <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeReview ?? 0 }}</div>
                                                        <div class="text-base text-slate-500 mt-1">Documentos para revisar</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <a href="{{ route('documents.control.manage.index', ['slug' => 'approve']) }}">
                                                <div class="report-box zoom-in">
                                                    <div class="box p-5">
                                                        <div class="flex">
                                                            <i data-lucide="file-check-2" class="report-box__icon text-warning"></i> 
                                                        </div>
                                                        <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeApprove ?? 0 }}</div>
                                                        <div class="text-base text-slate-500 mt-1">Documentos para aprobar</div>
                                                    </div>
                                                </div>
                                            </a> 
                                        </div>
                                    </div>

                                    <!-- Estado de actividad general -->
                                    <div class="grid grid-cols-12 gap-6 mt-5">
                                        <div class="col-span-6">
                                            <!-- BEGIN: Pie Chart -->
                                            <div class="intro-y box mt-5">
                                                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                                    <h2 class="font-medium text-base mr-auto">
                                                        Estado de Documentos
                                                    </h2>
                                                </div>
                                                <div id="pie-chart" class="p-5">
                                                    <div class="preview">
                                                        <div class="h-[600px]">
                                                            <canvas id="myChart"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END: Pie Chart -->                                
                                        </div>
                                        <div class="col-span-6">
                                            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y mt-5">
                                                <a class="m-0 p-0" href="{{ route('documents.control.solicitud.index') }}">
                                                    <div class="report-box zoom-in">
                                                        <div class="box p-5">
                                                            <div class="flex">
                                                                <i data-lucide="list-checks" class="report-box__icon text-warning"></i>                                                
                                                            </div>
                                                            <div class="text-3xl font-medium leading-8 mt-6">{{ $status['SPR'] ?? 0 }}</div>
                                                            <div class="text-base text-slate-500 mt-1">Solicitudes por revisar</div>
                                                        </div>
                                                    </div>
                                                </a> 
                                            </div>
                                            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y mt-10">
                                                <a class="m-0 p-0" href="{{ route('documents.control.observacion.index') }}">
                                                    <div class="report-box zoom-in">
                                                        <div class="box p-5">
                                                            <div class="flex">
                                                                <i data-lucide="view" class="report-box__icon text-warning"></i>                                                                                                         
                                                            </div>
                                                            <div class="text-3xl font-medium leading-8 mt-6">{{ $status['OPR'] ?? 0 }}</div>
                                                            <div class="text-base text-slate-500 mt-1">Observaciones por revisar</div>
                                                        </div>
                                                    </div>
                                                </a>
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


@push('scripts-bottom')
<script src="{{ url('assets/js/chart-2.9.4/2.9.4/chart.js') }}"></script>

<script type="text/javascript">
    $(function () {
        
        const myChart = new Chart("myChart", {
            type: "pie",
            data: {
                labels: ['Publicado','Publicación','Aprobación','Revisión','Edición'],
                datasets: [{
                    label: 'My First Dataset',
                    data: {{ $status['PIE'] }},
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        "rgb(0, 255, 0)",
                        "rgb(0, 0, 255)",
                    ],
                    hoverOffset: 4                    
                }]
            },
            options: {}
        });

        $('body').on('change', "#week-select", function (e) {        
            var week = $(this).val();
            var year = $('#week-select option:selected').text();
            //console.log('YEAR: '+year+' WEEK: '+week);
            location.href = '/documentos/dashboard/'+year+'/'+week; 
        });

    }); // document
</script>
@endpush       
</x-icewall>