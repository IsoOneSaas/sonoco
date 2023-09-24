<!-- resources/views/dashboard/master.blade.php -->
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
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="list" class="report-box__icon text-primary"></i> 
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
                                                        <i data-lucide="file-code" class="report-box__icon text-warning"></i> 
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
                                                        <i data-lucide="file-search" class="report-box__icon text-warning"></i> 
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
                                                        <i data-lucide="file-check-2" class="report-box__icon text-warning"></i> 
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
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="list-checks" class="report-box__icon text-warning"></i> 
                                                    <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="Ir hacer la revisión"> <a class="m-0 p-0" href="{{ route('documents.control.solicitud.index') }}"> Revisar <i data-lucide="arrow-up-right" class="w-4 h-4 ml-0.5"></i></a> </div>
                                                    </div>                                                 
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{ $SPR ?? 0 }}</div>
                                                <div class="text-base text-slate-500 mt-1">Solicitudes por revisar</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y mt-10">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="view" class="report-box__icon text-warning"></i>                                                
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{ $OPR ?? 0 }}</div>
                                                <div class="text-base text-slate-500 mt-1">Observaciones por revisar</div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>

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
                    data: {{ $PIE }},
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

    }); // document
</script>
@endpush       
</x-icewall>