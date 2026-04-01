<!-- resources/views/document/control.sighting.blade.php -->
<x-icewall>

    <x-slot:title>
            Documentos con Observaciones
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Documentos con Observaciones</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Listado de Documentos con Observaciones
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
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
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box p-5 mt-5">

                        <div id="horizontal-form" class="pb-3">
                            <div class="preview ml-auto w-60">
                                <div class="form-inline">
                                    <label for="scope-selected" class="form-label sm:w-20 text-right pt-3">Estado:</label>
                                    <select id="scope-selected" class="form-control form-select-sm mt-2 border-slate-500" aria-label="">
                                        <option value="0">Sin revisar</option>
                                        <option value="1">Revisados</option>
                                        <option value="all">Todos</option>
                                    </select>                                    
                                </div>
                            </div>
                        </div>                        

                        <table id="sightings-table" class="display responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="all"></th>
                                    <th class="all">No</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Código</th>
                                    <th class="all">Tipo</th>                                              
                                    <th class="all">Nombre</th>
                                    <th class="all">Usuario</th>
                                    <th class="all">Página</th>
                                    <th class="all">Sección</th>
                                    <th class="all"></th>
                                    <th class="none"></th>
                                    <th class="none"></th>
                                </tr>
                                <tr>
                                    <th class="th-filter"></th>
                                    <th class="th-filter">No</th>
                                    <th class="th-filter">Fecha</th>
                                    <th class="th-filter">Código</th>
                                    <th class="th-filter">Tipo</th>                                              
                                    <th class="th-filter">Nombre</th>
                                    <th class="th-filter">Usuario</th>
                                    <th class="th-filter">Página</th>
                                    <th class="th-filter">Sección</th>
                                    <th class="th-filter"></th>
                                    <th class="none" style="width:0"></th>
                                    <th class="none" style="width:0"></th>
                                </tr>                                
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                    <!-- END: HTML Table Data -->
                </div>
                <!-- END: Content -->

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Buttons-2.3.6/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/responsive.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />

@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Buttons-2.3.6/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Buttons-2.3.6/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/pdfmake-0.2.7/pdfmake.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/pdfmake-0.2.7/vfs_fonts.js') }}"></script>
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>

<script document="text/javascript">
    let $lang = {!! $gridLanguage !!};
    let $myTable;
    $(function () {
        
        setTable();

        $('body').on('click', '.btn-file-show', function (e) {
            e.preventDefault();
            var win;
            var file = $(this).data('file');                 
            var uri = "{{ route('documents.control.observacion.open', ':name') }}"; 

            if( file != '' ) {
                uri = uri.replace(':name', file);
                //alert(uri);
                win = window.open(uri, '_blank');
                win.focus();
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/sighting.open.no-found') }}");
            }
        });

        $('body').on('change', '#scope-selected', function (e) {
            $myTable.clear().destroy();
            setTable();
        });
                
        $("#btn-download").on("click", function() {
            $myTable.button('.buttons-excel').trigger();
        });
        
        $("#btn-print").on("click", function() {
            $myTable.button('.buttons-pdf').trigger();
        });        

 
    }); // document

    function setTable() {
        var columnsDef = {!! $gridColDef !!};
        var col = {{ $gridColOrd }};        
        var columnsExp = {!! $gridColExp !!};          
        var scope = $("#scope-selected").val();
        var route = "{{ route('documents.control.observacion.show', ':slug') }}";

 

        $myTable = $('#sightings-table').DataTable({
            ajax: route.replace(':slug', scope),
            columns: columnsDef,
            order: [[col, 'desc']],
            responsive: true,
            orderCellsTop: true,
            fixedHeader: true,            
            language: $lang,
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
                        columns: columnsExp 
                    }
                }
            ],            
            rowCallback: function( nRow, data, index, displayIndex ) {
                // Generar columna índice
                if(nRow){
                    var ordinal = displayIndex + 1;
                    $('td:eq(1)', nRow).html(ordinal);
                }
                return nRow;                
            },
            drawCallback: function(settings) {
                var api = this.api();
                api.columns().every( function (i) {
                    var column = this;
                    if( columnsDef[i].filterable == true ) {                     
                        //console.log('column: '+i);
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

                this.api().columns().every( function (i) {
                    var column = this;
                    var id =  columnsDef[i].data;
                    
                    if( columnsDef[i].filterable == true ) {
                        //console.log('column: '+id);
                        $("#filter-"+id).on( 'change', function () {
                            //console.log($(this).val());
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

            }            
        }); // datatable

        // Filtros : generación
        $('#sightings-table thead tr:eq(1) th').each( function (i) {
            var tag;
            var item = columnsDef[i];
            //console.dir(item);
            if( typeof item.visible !== 'undefined' && item.visible === false ) {
                $(this).html('');
            } else {
                if( typeof item.filterable !== 'undefined' && item.filterable === true ) {
                    tag = '<select id="filter-' + item.data + '" class="col-filter select-filter"></select>';
                    $(this).html(tag);
                } else {
                    if( typeof item.searchable !== 'undefined' && item.searchable === true ) {
                        $(this).html('<input id="filter-' + item.data + '" type="text" class="col-filter input-filter deletable" placeholder="Buscar ' + item.title + '" />');
                    } else {
                        $(this).html('');
                    }
                }
            }          
        });          

        // Array to track the ids of the details displayed rows
        const detailRows = [];
               
        $('body').on('click', '.btn-sheet', function (e) {
            e.preventDefault();
            var hash = $(this).data('hash');
            var route = "{{ route('documents.master.datasheet', ':hash') }}";

            if (hash === undefined || hash === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_sheet') }}");
            } else { 
                route = route.replace(':hash', hash);
                location.href = route;
            }            
        }); // #btn-sheet       

    } // setTable

    function gridFormatSighting(d) {     
        return '<table class=""display compact responsive" width="100%"><tr><th>Fecha</th><th>Usuario</th><th>Tipo</th><th>Página</th><th>Sección</th><th>Contenido</th></tr>'+d.sights+'</table>';
    } // gridFormatSighting
    
    function checkSighting(id) {
        var route = "{{ route('documents.control.observacion.edit', ':id') }}";
        route = route.replace(':id', id);        
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',                
            success: function(json) {
                console.dir(json);
                if (json.success) {
                    $myTable.clear().destroy();
                    setTable();
                }
            } // success
        }); // ajax
    } // checkSighting




        

</script>


@include('components.notification_index')

@endpush

</x-icewall> 