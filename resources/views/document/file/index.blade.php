<!-- resources/views/document/index.blade.php -->
<x-icewall>

    <x-slot:title>
        Archivo de Registros
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Archivo de Registros</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Listado de Archivos de Registros
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh" title="Refrescar la tabla"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2 iso-disabled" href="javascript:;" id="btn-new" title="Nuevo archivo"><i data-lucide="pencil" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2 iso-disabled" href="javascript:;" id="btn-edit" title="Editar el archivo"><i data-lucide="edit" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2 iso-disabled" href="javascript:;" id="btn-view" title="Ver el archivo"><i data-lucide="eye" class="w-5 h-5"></i></a>
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

                                    <!-- BEGIN: DataTables -->
                                    <table id="files-table" class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap">#</th>
                                                <th>ID</th>
                                                <th>XID</th>
                                                <th>PID</th>
                                                <th>LID</th>
                                                <th>DID</th>
                                                <th>JID</th>
                                                <th>TID</th>
                                                <th>SID</th>
                                                <th>Proceso</th>
                                                <th>Código</th>
                                                <th>Tema</th>
                                                <th>Subtema</th>
                                                <th>Nombre</th>
                                                <th>Responsable</th>
                                                <th>Frecuencia de Retención</th>
                                                <th>Tiempo Mínimo Retención</th>
                                                <th>Tiempo Archivo Muerto</th>
                                                <th>Almacenamiento</th>
                                                <th>Clasificación</th>
                                                <th>Indexación</th>
                                                <th>Disposición Final</th>
                                                <th>Medio Soporte</th>
                                                <th>C1</th>
                                                <th>C2</th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th class="th-filter">ID</th>
                                                <th class="th-filter">XID</th>
                                                <th class="th-filter">PID</th>
                                                <th class="th-filter">LID</th>
                                                <th class="th-filter">DID</th>
                                                <th class="th-filter">JID</th>
                                                <th class="th-filter">TID</th>
                                                <th class="th-filter">SID</th>
                                                <th class="th-filter">Proceso</th>
                                                <th class="th-filter">Código</th>
                                                <th class="th-filter">Tema</th>
                                                <th class="th-filter">Subtema</th>
                                                <th class="th-filter">Nombre</th>
                                                <th class="th-filter">Responsable</th>
                                                <th class="th-filter">Frecuencia de Retención</th>
                                                <th class="th-filter">Tiempo Mínimo Retención</th>
                                                <th class="th-filter">Tiempo Archivo Muerto</th>
                                                <th class="th-filter">Almacenamiento</th>
                                                <th class="th-filter">Clasificación</th>
                                                <th class="th-filter">Indexación</th>
                                                <th class="th-filter">Disposición Final</th>
                                                <th class="th-filter">Medio Soporte</th>
                                                <th class="th-filter">C1</th>
                                                <th class="th-filter">C2</th>
                                            </tr>                                            
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>XID</th>
                                                <th>PID</th>
                                                <th>LID</th>
                                                <th>DID</th>
                                                <th>JID</th>
                                                <th>TID</th>
                                                <th>SID</th>
                                                <th>Proceso</th>
                                                <th>Código</th>
                                                <th>Tema</th>
                                                <th>Subtema</th>
                                                <th>Nombre</th>
                                                <th>Responsable</th>
                                                <th>Frecuencia de Retención</th>
                                                <th>Tiempo Mínimo Retención</th>
                                                <th>Tiempo Archivo Muerto</th>
                                                <th>Almacenamiento</th>
                                                <th>Clasificación</th>
                                                <th>Indexación</th>
                                                <th>Disposición Final</th>
                                                <th>Medio Soporte</th>
                                                <th>C1</th>
                                                <th>C2</th>
                                            </tr>                                            
                                        </tfoot>
                                    </table>                                    
                                    <!-- END: DataTables -->

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->

                </div>
                <!-- END: Content -->
@push('meta')                
    <meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush


@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Buttons-2.3.6/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/daterangepicker-master/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/multiple-select-1.6.0/dist/multiple-select.min.css') }}" />
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
<script src="{{ url('assets/js/dropzone-5.9.3/dropzone.min.js') }}"></script>
<script src="{{ url('assets/js/dropzone-5.9.3/config_document_suggestion.js') }}"></script> 
<script src="{{ url('assets/js/daterangepicker-master/moment.min.js') }}"></script>
<script src="{{ url('assets/js/daterangepicker-master/daterangepicker.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/multiple-select.min.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/locale/multiple-select-es-ES.min.js') }}"></script>
<script src="{{ url('assets/js/iso_scripts.js') }}"></script>

<script document="text/javascript">
    let $storageData = [];
    let $dateInDefault;
    let $dateOutDefault;
    let $myTable;
    let $route = "{{ route('files.index.render', ':slug') }}";

    $(function () {
        let columnsDef = {!! $gridColDef !!};
        let col = {{ $gridColOrd }};        
        let columns = {!! $gridColExp !!};
        let lang = {!! $gridLanguage !!};

        var startTime = Date.now();                       
        //var sCol = isoGetStorage('iso_recordReturnCol');
        //var sDir = isoGetStorage('iso_recordReturnDir');
        //var initPage = ( isoGetStorage('iso_recordReturnPage') === null ) ? 1 : isoGetStorage('iso_recordReturnPage');        
        // console.log('sCol:'+sCol+' col:'+col+' sDir:'+sDir); 
        //var initOrder = ( sCol !== 'undefined' && sCol ) ? [[ sCol, sDir]] : [[ col, 'asc']];      
        var initOrder = [[ col, 'asc']];


        //var initRecords = ( isoGetStorage('iso_recordReturnRows') === null ) ? 10 : isoGetStorage('iso_recordReturnRows');
        var initRecords = 10;

        // Rango In
        //var dateIn = isoGetStorage('iso_recordDatein');
        var dateIn = null;
        $dateInDefault = ( dateIn === null ) ? moment().subtract(6, 'days') : dateIn;
        
        // Rango Out
        //var dateOut = isoGetStorage('iso_recordDateout');
        var dateOut = null;
        $dateOutDefault = ( dateOut === null ) ? moment() : dateOut;         

        // DATATABLES
        param = {sids: [], pids: [], gid: '', tid: '', din: $dateInDefault, dout: $dateOutDefault};
        console.table(param);        

        $myTable = $('#files-table')
        .on('preXhr.dt', function () {
            console.log('Send ajax request ', Date.now() - startTime + ' milliseconds.');
        })
        .on('xhr.dt', function () {
            console.log('Received ajax response ', Date.now() - startTime + ' milliseconds.');
            $("#loading-image").hide();
            $("#btn-filter").removeClass('btn-primary').addClass('btn-success'); 
        })
        .DataTable({
            dom: 'lrtip', // 'Blfrtip'
            bProcessing: true,
            sAjaxSource: $route.replace(':slug', JSON.stringify(param)),
            aoColumns: columnsDef,
            retrieve: true,
            pageLength: parseInt(initRecords),
            order: initOrder[0],
            orderClasses: false,
            responsive: true,
            orderCellsTop: true,
            fixedHeader: true,            
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
                }
                ,
                {
                    extend: 'colvis',
                    columns: columns
                }
            ],            
            rowCallback: function( nRow, data, index, displayIndex ) {
                // Generar columna índice
                if(nRow){
                    var ordinal = displayIndex + 1;
                    if( data.status == 1 ) {
                        ordinal = '<span class="inline-flex items-baseline">'+ordinal+' <img alt="(o)" class="h-2" src="/assets/images/redled.png"></span>'
                    }
                    $('td:eq(0)', nRow).html(ordinal);
                }
                return nRow;                
            },
            drawCallback: function(settings) {
                var api = this.api();
                api.columns().every( function (i) {
                    var column = this;
                    if( columnsDef[i].filterable == true ) {                     
                        var id =  columnsDef[i].data;
                        var output = '<option value="">Todos</option>';
                        var val = $("#filter-"+id).val();
                        column.data().unique().sort().each( function ( d, j ) { 
                            if( (d !== null) && (d != '') ) {
                                output +=  '<option value="' + d + '">' + d + '</option>';
                            }
                        });
                        $("#filter-"+id).html(output).val(val);                                             
                    }
                });
            },
            initComplete: function() {
                console.log('DT init complete in ', Date.now() - startTime + ' milliseconds.');
                console.log('Total Rows: ' + this.api().data().count());
                $("#btn-filter").removeClass('btn-success').addClass('btn-primary');
                this.api().columns().every( function (i) {
                    var column = this;
                    var id =  columnsDef[i].data;                    
                    if( columnsDef[i].filterable == true ) {
                        $("#filter-"+id).on( 'change', function () {
                            var val = $(this).val();
                            column.search( val ? '^' + val + '$' : '', true, false).draw();
                        });                                                
                    } else if( columnsDef[i].searchable == true ) {                                                
                        $("#filter-"+id).on( 'keyup change clear', function() {
                            if ( column.search() !== this.value ) {
                                column.search( this.value ).draw();
                            }
                        });
                    }                        
                });
            },
            language: lang                                   
        }); // datatables

                        
    }); // document

 
</script>


@if ($message = Session::get('success'))
    <script>
        setSuccessNotification('success', 'Felicitaciones!', '{{ $message }}');
    </script>    
@endif

@if ($message = Session::get('error'))
    <script>
        setSuccessNotification('error', 'Oops!', '{{ $message }}');
    </script> 
@endif

@if ($message = Session::get('alert'))
    <script>
        setSuccessNotification('error', 'Oops!', '{!! $message !!}');
    </script> 
@endif 

@endpush

</x-icewall> 