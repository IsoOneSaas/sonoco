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
                                    <div class="grid grid-cols-12 gap-6 mt-5">
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <a href="{{ route('documents.master.index') }}" >
                                                <div class="report-box zoom-in">
                                                    <div class="box p-5">
                                                        <div class="flex">
                                                            <i data-lucide="list" class="report-box__icon text-primary"></i>
                                                        </div>
                                                        <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeMaster ?? 0 }}</div>
                                                        <div class="text-base text-slate-500 mt-1">Documentos para ver</div>
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
                                </div>
                                <!-- END: General Report -->

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

                                <div class="col-span-12">
                                    <!-- BEGIN: View Documents -->
                                    <div class="intro-y box mt-5">
                                        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                            <h2 class="font-medium text-base mr-auto">
                                                Documentos Frecuentes
                                            </h2>
                                        </div>
                                        <div  class="p-5">
                                            <table id="frequency-table" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nombre</th>
                                                        <th>Versión</th>
                                                        <th>Publicado</th>
                                                        <th>Frecuencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($documents as $document)
                                                    <tr>
                                                        @foreach($document as $item)
                                                        <td>{!! $item !!}</td>
                                                        @endforeach
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- END: View Documents --> 
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Content -->

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <style>
        table#frequency-table td {
            font-size: 0.8em;
        }
        table#frequency-table th {
            font-size: 0.9em;
        }          
    </style>
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/chart-2.9.4/2.9.4/chart.js') }}"></script>
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
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

        new DataTable("#frequency-table", {
            columnDefs: [
                { targets: [0], className: 'dt-nowrap' },
                { targets: [2,4], className: 'dt-body-center' },
                { targets: [4], searchable: false },
            ],
            order: [[4, 'desc']],
            language: {
                lengthMenu: 'Mostrar _MENU_ documentos por página',
                zeroRecords: '<h4>No hay documentos encontrados</h4>',
                info: 'Mostrando página _PAGE_ de _PAGES_',
                infoEmpty: '*',
                infoFiltered: '(_TOTAL_ filtrados de _MAX_ documentos totales)',
                loadingRecords: 'Cargando...',
                search: 'Buscar: ',
                paginate: {
                    next: '>>',
                    previous: '<<'
                }
            }
        });

    }); // document
</script>
@endpush       
</x-icewall>