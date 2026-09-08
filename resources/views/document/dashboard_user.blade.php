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

                                        <h1>Agenda va aquí</h1>

                                        </div>
                                    </div>

                                    <!-- Estado de documentos -->
                                    <div class="grid grid-cols-12 gap-6 mt-5">
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <a href="{{ route('documents.master.index') }}" ><i data-lucide="list" class="report-box__icon text-primary"></i></a>
<!--                                                         <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                        </div> -->
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
<!--                                                         <div class="ml-auto">
                                                            <div class="report-box__indicator bg-danger tooltip cursor-pointer" title="2% Lower than last month"> 2% <i data-lucide="chevron-down" class="w-4 h-4 ml-0.5"></i> </div>
                                                        </div> -->
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
<!--                                                         <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer" title="12% Higher than last month"> 12% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                        </div> -->
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
<!--                                                         <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer" title="22% Higher than last month"> 22% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                        </div> -->
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ $badgeApprove ?? 0 }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Documentos para aprobar</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Documentos frecuentes -->
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
                                <!-- END: General Report -->

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
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
        
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