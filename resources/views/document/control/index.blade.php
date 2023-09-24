<!-- resources/views/document/control.index.blade.php -->
<x-icewall>

    <x-slot:title>
            {{ $grid_title }}
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Listado de Documentos</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            {{ $grid_head }}
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>                                                       
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-edit"><i data-lucide="edit" class="w-5 h-5"></i></a> 
                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-print" href="javascript:;" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Imprimir tabla </a>
                                        </li>
                                        <li>
                                            <a id="btn-download" href="javascript:;" class="dropdown-item"> <i data-lucide="download" class="w-4 h-4 mr-2"></i> Exportar tabla </a>
                                        </li>
                                        <li>
                                            <a id="btn-colvis" href="javascript:;" class="dropdown-item"> <i data-lucide="sliders" class="w-4 h-4 mr-2"></i> Visibilidad </a>
                                        </li>                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box p-5 mt-5">
                        <div class="p-5" id="striped-rows-table">
                            <div class="preview">

                                <div id="grid-table">                                   
                                    <table id="documents-table" class="table table-bordered nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Versión</th>
                                                <th>Proceso</th>
                                                <th>Tipo Documento</th>
                                                <th>Viene de</th>
                                                <th>H</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>                                       
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->
                </div>
                <!-- END: Content -->

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Buttons-2.3.6/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Buttons-2.3.6/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Buttons-2.3.6/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Buttons-2.3.6/js/buttons.colVis.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/pdfmake-0.2.7/pdfmake.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/pdfmake-0.2.7/vfs_fonts.js') }}"></script>
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>

<script document="text/javascript">
    $(function () {
        let columnsConf = {!! $gridColDef !!};
        let order = [[{{ $gridColOrd }}, 'asc']];
        let lang = {!! $gridLanguage !!};
        let columns = {!! $gridColExp !!};        
        let route = "{{ route('documents.control.manage.show', ':slug') }}";
        let action = "{!! $action !!}";
        
        // Acondicionamiento
        route = route.replace(':slug', action);
        setFooter('documents-table', columnsConf);
        // Datatable
        let myTable = new DataTable("#documents-table", {
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: route,
            order: order,
            columns: columnsConf,           
            language: lang,          
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: columns 
                    }
                },
                {
                    extend: 'colvis',
                    columns: columns
                }
            ],
            initComplete: function () {
                var $this = this.api();

                // Generar filtros de columnas
                setFilters($this, columnsConf);

            }
        }); // datatable



        // Buttons        
        $('#btn-edit').on("click", function()  {
            var rowdata = myTable.rows('.selected').data()[0];
            //var url = "{ { route('documents.control.manage.edit', ':id') } }";
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_send') }}");
            } else {
                var uri = "{{ $editUrl }}"+rowdata.hash;
                //alert(uri);
                location.href = uri;
            }
        }); // btn-edit
      
        $('#btn-refresh').on("click", function() {
            myTable.search('').columns().draw();
        }); // btn-refresh
        
        $("#btn-download").on("click", function() {
            myTable.button('.buttons-excel').trigger();
        });
        
        $("#btn-print").on("click", function() {
            myTable.button('.buttons-pdf').trigger();
        });

        $("#btn-colvis").on("click", function() {
            myTable.button('.buttons-colvis').trigger();
        });
        
        // Tools
        $('#documents-table').on('click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            } else {
                myTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');                
            } // if selected
        }); // row selects
                
    }); // document

    

</script>


@include('components.notification_index')

@endpush

</x-icewall> 