<!-- resources/views/document/blank.blade.php -->
<x-icewall>

    <x-slot:title>
        Blank page
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Listado Maestro</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Listado Maestro de Documentos Publicados
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-view"><i data-lucide="eye" class="w-5 h-5"></i></a>
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
                                                <th class="whitespace-nowrap">#</th>
                                                <th>Id</th>
                                                <th class="whitespace-nowrap">Código</th>
                                                <th>Nombre</th>
                                                <th>Versión</th>
                                                <th>Proceso</th>
                                                <th>Tipo Documento</th>
                                                <th>Publicado</th>
                                                <th>Vigencia</th>
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
        let columnsDef = {!! $gridColDef !!};
        let order = [[{{ $gridColOrd }}, 'asc']];
        let lang = {!! $gridLanguage !!};
        let columns = {!! $gridColExp !!};        
        let route = "{{ route('documents.control.master.show', ':slug') }}";
        let action = "empty";
        
        // Acondicionamiento
        route = route.replace(':slug', action);
        setFooter('documents-table', columnsDef);
        // Datatable
        let myTable = new DataTable("#documents-table", {
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: route,
            order: order,
            columns: columnsDef,
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
                setFilters($this, columnsDef);

            }
        });
    }); // document

</script>
@endpush
</x-icewall> 