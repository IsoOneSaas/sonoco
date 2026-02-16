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
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('files.admin.create') }}" id="btn-new" title="Nuevo archivo"><i data-lucide="plus" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2 iso-disabled" href="javascript:;" id="btn-edit" title="Editar el archivo"><i data-lucide="edit" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2 iso-disabled" href="javascript:;" id="btn-view" title="Ver el archivo"><i data-lucide="eye" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-delete"><i data-lucide="trash" class="w-5 h-5"></i></a>
                            <form method="POST" id="form-delete" action="">
                                @method('DELETE')
                                @csrf                                
                            </form>                            
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
                                    <!-- BEGIN: Filters -->
                                    <div id="faq-accordion-2" class="accordion accordion-boxed">
                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-6" class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-tw-toggle="collapse" data-tw-target="#faq-accordion-collapse-6" aria-expanded="false" aria-controls="faq-accordion-collapse-6"><img id="loading-image" alt="Cargando..." class="h-8 inline-flex mr-20" src="{{ url('/assets/images/loading_small.gif') }}"><i data-lucide="search" class="w-5 h-5 inline-block"></i><span class="inline-block">&nbsp;Buscar en archivos</span></button>
                                            </div>
                                            <div id="faq-accordion-collapse-6" class="accordion-collapse collapse show" aria-labelledby="faq-accordion-content-6" data-tw-parent="#faq-accordion-2">
                                                <div id="horizontal-form" class="pb-3">
                                                    <div class="preview ml-auto w-full">

                                                        <div class="form-inline">
                                                                <label for="date-selected" class="form-label sm:w-20 text-right">Rango:</label>
                                                                <input id="date-selected" type="text" class="form-control w-36 border-slate-500 iso-input" aria-label="Rango">
                                                                <label for="system-selected" class="form-label sm:w-20 text-right">Requisitos:</label>
                                                                <select multiple id="system-selected" class="form-control mt-2 border-slate-500" size="1" aria-label="Requisito">
                                                                    @foreach($systems as $system)   
                                                                    <option value={{ $system->system_id }} selected>{{ $system->name }}</option>
                                                                    @endforeach                                                                                                        
                                                                </select>                                                                
                                                        </div>
                                                        <div class="form-inline">
                                                            <label for="location-selected" class="form-label sm:w-20 text-right pt-3">Localizaciones:</label>
                                                            <select multiple id="location-selected" class="form-control mt-2 border-slate-500" size="1" aria-label="Localizacion">
                                                                @foreach($locations as $location)   
                                                                <option value={{ $location->location_id }} selected>{{ $location->name }}</option>
                                                                @endforeach                                                                                                      
                                                            </select>
                                                            <label for="department-selected" class="form-label sm:w-20 text-right ml-2">Departamentos:</label>
                                                            <select multiple id="department-selected" class="form-control mt-2 border-slate-500 ml-2" size="1" aria-label="Departamento">
 
                                                            </select>                                                                                                                                                                                                                                                                         
                                                        </div>
                                                        <div class="form-inline">
                                                            <label for="topic-selected" class="form-label sm:w-20 text-right pt-3">Temas:</label>
                                                            <select multiple id="topic-selected" class="form-control mt-2 border-slate-500" size="1" aria-label="Tema">                                                                                                      
                                                            </select>
                                                            <label for="subtopic-selected" class="form-label sm:w-20 text-right">Subtemas:</label>
                                                            <select multiple id="subtopic-selected" class="form-control mt-2 border-slate-500" size="1" aria-label="Subtema"> 
                                                            </select>                                                                                                                                                                                                                                                                         
                                                        </div>                                                        

                                                        <div class="flex mt-3 justify-center">
                                                            <button id="btn-filter" class="btn btn-primary shadow-md"><i data-lucide="filter" class="w-4 h-4"></i>&nbsp;Buscar&nbsp;&nbsp;</button> 
                                                        </div>                                           
                                                    </div>
                                                </div>                                            

                                            </div>
                                        </div>
                                    </div>
                                    <!-- END: Filters -->                                
                                    <br />
                                    <!-- BEGIN: DataTables -->
                                    <table id="files-table" class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap">#</th>
                                                <th>ID</th>
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
                                                <th>H</th>
                                                <th>N</th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th class="th-filter">ID</th>                                                
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
                                                <th class="th-filter">H</th>
                                                <th class="th-filter">N</th>
                                            </tr>                                            
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
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
                                                <th>H</th>
                                                <th>N</th>                                                
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
    <style>
        .iso-input {
            padding: 0.15em 0.6em; 
            font-size: 0.95em; 
            border-radius: 5px;                
        }
        .input-filter {
            width: 100%
        }     
    </style>
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
    let $initCols;
    let $myTable;
    let $route = "{{ route('files.render', ':slug') }}";

    $(function () {
        let columnsDef = {!! $gridColDef !!};
        let col = {{ $gridColOrd }};        
        let columns = {!! $gridColExp !!};
        let lang = {!! $gridLanguage !!};
        let startTime = Date.now();
                            

        // Página
        var initPage = ( isoGetStorage('iso_fileReturnPage') === null ) ? 1 : isoGetStorage('iso_fileReturnPage'); 
        
        // Orden
        var sCol = isoGetStorage('iso_fileReturnCol');
        var sDir = isoGetStorage('iso_fileReturnDir');        
        // console.log('sCol:'+sCol+' col:'+col+' sDir:'+sDir); 
        var initOrder = ( sCol !== 'undefined' && sCol ) ? [[ sCol, sDir]] : [[ col, 'asc']];

        // Columnas       
        //console.table(columnsDef);
        columnsDef = setColumns(columnsDef);
        console.table(columnsDef);

        // Filas
        var initFiles = ( isoGetStorage('iso_fileReturnRows') === null ) ? 10 : isoGetStorage('iso_fileReturnRows');

        // Sistema
        var sidsStoraged = isoGetStorage('iso_fileSystems');
        var sidsArray = setStorageInteger("system-selected", sidsStoraged);  

        // Localizaciones
        var lidsStoraged = isoGetStorage('iso_fileLocations');
        var lidsArray = setStorageInteger("location-selected", lidsStoraged);          
        
        // Departamento
        // var didsStoraged = isoGetStorage('iso_fileDepartments');
        // var didsArray = setStorageInteger("department-selected", didsStoraged);      
        
        // Temas
        //var tidsStoraged = isoGetStorage('iso_fileTopics');  
        

        // Rango In
        var dateIn = isoGetStorage('iso_fileDatein');
        $dateInDefault = ( dateIn === null ) ? moment().subtract(6, 'days') : dateIn;
        
        // Rango Out
        var dateOut = isoGetStorage('iso_fileDateout');
        $dateOutDefault = ( dateOut === null ) ? moment() : dateOut;         

        // DATATABLES
        param = {sids: sidsArray, lids: lidsArray, dids: [], tids: [], xids: [], din: $dateInDefault, dout: $dateOutDefault};
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
            pageLength: parseInt(initFiles),
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
                    //postfixButtons: ['colvisRestore'],
                    columns: columns,
                    hide: [18]
                }
            ],
            //stateSave: true,            
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

        // FILTROS

        // Filtros de Columna
        $('#files-table thead tr:eq(1) th').each( function (i) {
            var tag;
            var item = columnsDef[i+1];

            if( typeof item !== 'undefined' ) {
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
                        } // if else
                    } // if else
                } // if else
            } // if        
        }); // #files-table

        // Filtro de departamentos
        $("#location-selected").on("change", function(e) {
            e.preventDefault();
            var lidsArray = $("#location-selected").val();
            console.log(':: Selected    LIDS ::');
            console.dir(lidsArray);
            if( lidsArray.length > 0 ) {
                setDepartmentAjax(lidsArray);
            } else {
                console.log('Clear #department-selected');
                $("#department-selected").val('').multipleSelect('destroy').html('');
            } // if
        });        

        // Filtro de temas
        $("#department-selected").on("change", function(e) {
            e.preventDefault();
            var didsArray = $("#department-selected").val();
            console.log(':: Selected DIDS ::');
            console.dir(didsArray);
            if( didsArray.length > 0 ) {
                setTopicAjax(didsArray);
            } else {
                console.log('Clear #topic-selected');
                $("#topic-selected").val('').multipleSelect('destroy').html('');
            } // if
        });

        // Filtro de subtemas
        $("#topic-selected").on("change", function(e) {
            e.preventDefault();
            var tidsArray = $("#topic-selected").val();
            console.log(':: Selected TIDS ::');
            console.dir(tidsArray);
            if( tidsArray.length > 0 ) {
                setSubtopicAjax(tidsArray);
            } else {
                console.log('Clear #subtopic-selected');
                $("#subtopic-selected").val('').multipleSelect('destroy').html('');
            } // if
        });        
        
        
        // UTILIDADES        
        $("#btn-download").on("click", function() {
            $myTable.button('.buttons-excel').trigger();
        });
        
        $("#btn-print").on("click", function() {
            $myTable.button('.buttons-pdf').trigger();
        });

        $("#btn-colvis").on("click", function() {
            $myTable.button('.buttons-colvis').trigger();
        });
        
        $('input.deletable').wrap('<span class="deleteicon"></span>').after($('<span>x</span>').click(function() {
            $(this).prev('input').val('').trigger('change').focus();
        }));        
        
        // Seleccionar fila
        $('#files-table').on('click', 'tr', function () {
            data = $myTable.row(this).data();
            if ( $(this).hasClass('selected') ) {
                // Deseleccionado
                $(this).removeClass('selected');
                $('#btn-edit').addClass('iso-disabled');
                $('#btn-view').addClass('iso-disabled');
            } else {
                // Seleccionado
                $myTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');                
                if( data.status == 1 ) {
                    // Bloqueado
                    $('#btn-edit').addClass('iso-disabled');  
                    $('#btn-view').removeClass('iso-disabled');
                } else {
                    $('#btn-edit').removeClass('iso-disabled');
                    $('#btn-view').addClass('iso-disabled'); 
                }               
            } // if selected
        }); // row selects 
        
        // Efectos botón
        $('#date-selected').on('blur', function() {
            $("#btn-filter").removeClass('btn-success').addClass('btn-primary');
        });

        $('#system-selected, #department-selected').on('change', function() {
            $("#btn-filter").removeClass('btn-success').addClass('btn-primary');
        });        
        
        // BOTONES

        // Filtras lista
        $("#btn-filter").on("click", function() {
            var sidsValue = $("#system-selected").val();
            var lidsValue = $("#location-selected").val();
            var didsArray = $("#department-selected").val();
            var tidsArray = $("#topic-selected").val();
            var xidsArray = $("#subtopic-selected").val();

            var visibleColumns = $myTable.columns().visible().toArray();
            var info = $myTable.page.info();            
            var params = {sids: sidsValue, lids: lidsArray, dids: didsArray, tids: tidsArray, xids: xidsArray, din: $dateInDefault, dout: $dateOutDefault};            
            var url =  $route.replace(':slug', JSON.stringify(params));

            console.log('Searching...');
            console.dir(JSON.stringify(params));
            
            if( (sidsValue.length > 0) && (lidsValue.length > 0) && (didsArray.length > 0) && (tidsArray.length > 0) && (xidsArray.length > 0) ) {
                // Ajustes a cambio
                $("#filter-typeName").html('');
                $("#filter-date").html('');
                $("#loading-image").show();
                $("#btn-filter").removeClass('btn-success').addClass('btn-primary'); 
                $('#btn-edit').addClass('iso-disabled');
                $('#btn-view').addClass('iso-disabled');                           
                                
                // Ajax            
                $myTable.ajax.url(url).load();
                $myTable.state.clear();

                // Store            
                isoSetStorage('iso_fileSystems', sidsValue);
                isoSetStorage('iso_fileLocations', lidsValue);
                isoSetStorage('iso_fileDepartments', didsArray);
                isoSetStorage('iso_fileTopics', tidsArray);         // TODO: Definir si va junto a subtopics
                isoSetStorage('iso_fileSubtopics', xidsArray);
                isoSetStorage('iso_fileDatein', $dateInDefault);
                isoSetStorage('iso_fileDateout', $dateOutDefault); //
                isoSetStorage('iso_fileReturnRows', info.length);
                isoSetStorage('iso_fileColumns', visibleColumns); 
            } else {
                swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Debe seleccionar al menos una opción de todos los selectores"
                });
            } // if
        }); // btn-filter       

        // Editar el archivo existente
        $('#btn-edit').on("click", function()  {
            var rowdata = $myTable.rows('.selected').data()[0];
            var url = "{{ route('files.admin.edit', ':id') }}";
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/file.error.grid.row_edit') }}");
            } else { 
                setStorage();                               
                url = url.replace(':id', rowdata.hash);
                location.href = url;                
            }
        }); // btn-edit 

        // Eliminar Archivo // TODO: Decidir si se quita
        $('#btn-delete').on("click", function()  {
            var txt = '';
            var rowdata = $myTable.rows('.selected').data()[0];
            var form = $("#form-delete"); 
            var url = "{{ route('files.admin.destroy', ':hash') }}";
            var msg = "{{ trans('document/file.file.delete.text2', ['N' => ':no']) }} \n \n";
            
           if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/file.error.grid.row_delete') }}");
            } else {            
               if ( rowdata.count > 0 ) {
                    txt = msg.replace(':no', rowdata.count);
                    console.log(rowdata.count);
                }
                txt += "{{ trans('document/file.file.delete.text1') }}";
                action = url.replace(':hash', rowdata.hash);
                form.attr('action', action);
                swal({
                    title: "{{ trans('document/file.file.delete.title') }}"+rowdata.code+"?",
                    text: txt,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        setStorage();
                        form.submit();
                    }
                });                 
            } // if/else
        }); // btn-delete          

        $('#btn-refresh').on("click", function() {
            $myTable.search('').columns().search('').draw();
            $(".col-filter").val('');
            $('#btn-edit').addClass('iso-disabled');
            $('#btn-view').addClass('iso-disabled'); 
            // Quitar seleccion de fila
        }); // btn-refresh               

        // DATERANGE
        console.log('DIN : '+$dateInDefault+' | DOUT : '+$dateOutDefault);
        $('#date-selected').daterangepicker({
            locale: {
                format: 'YYYY/MM/DD',
                daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                customRangeLabel: "-"
            },
            showDropdowns: true,
            maxDate: moment(),
            startDate: $dateInDefault,
            endDate: $dateOutDefault,         
            ranges: {
                'Hoy': [moment(), moment()],
                'Última semana': [moment().subtract(6, 'days'), moment()],
                'Último mes': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Pasado mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Último semestre': [moment().subtract(5, 'months'), moment()],
                'Todos': [moment("1970-01-01"), moment()]
            }                     
        });

        $('#date-selected').on('apply.daterangepicker', function(ev, picker) {
            $dateInDefault = picker.startDate.format();
            console.log('DIN : '+$dateInDefault);
            $dateOutDefault = picker.endDate.format();
            console.log('DOUT : '+$dateOutDefault);
        });
        
        // setTimeout(function() {
        //     $('#files-table').DataTable().buttons().container().appendTo('#colvis-container');
        // }, 100)
                        
    }); // document

    $(document).ready(function() {
        $("#location-selected").trigger('change');        
    });    

    function setStorageInteger(tag, storaged) {
        var output = '';
        //console.log('TAG: '+ tag + ' | INPUT: ' + storaged);

        if( (storaged === null) || (storaged == '') ) {
            var array = $("#"+tag).val();
        } else {
            var array = ( storaged.indexOf(",") == -1 ) ? [storaged] : storaged.split(',');
        }

        //console.dir(array);
        $('#'+tag+' option').each(function(i) {
            
            if( $.inArray( this.value , array ) !== -1 ) {
                output += '<option value='+ parseInt(this.value) +' selected>'+ this.text +'</option>';
                //console.log(i, this.value , this.text, 'Selected');
            } else {
                output += '<option value='+ parseInt(this.value) +'>'+ this.text +'</option>';
                //console.log(i, this.value , this.text, '');
            }
        });        

        $("#"+tag).html(output);
        $("#"+tag).multipleSelect(); 
        return array;
    } // setStorageInteger Fx     

    function setStorage() {
        // Storage
        var info = $myTable.page.info();
        var order = $myTable.order();             
        isoSetStorage('iso_fileReturnUrl', isoGetCurrentURL());
        isoSetStorage('iso_fileReturnPage', info.page);
        isoSetStorage('iso_fileReturnCol', order[0][0]);
        isoSetStorage('iso_fileReturnDir', order[0][1]);
        isoSetStorage('iso_fileReturnRows', info.length);

        var visibleColumns = $myTable.columns().visible().toArray();
        isoSetStorage('iso_fileColumns', visibleColumns);  
    } // setStorage

    function setColumns(columns) {
        var cut = 5;
        var n = 0;
        var width = 1122;
        var viewportWidth = $(window).width();        
        var isnarrow = ( viewportWidth < width ) ? true : false;
        $initCols = isoGetStorage('iso_fileColumns');

        if( $initCols === null ) {
            // Valido ancho
            if( isnarrow ) {
                $.each(columns, function(i, column) {
                    if( column.searchable === true || column.filterable === true  ) {
                        //console.log('COLUMN: '+column.title);  // typeof item.visible !== 'undefined' && item.visible === false
                        if( (column.visible === true) && (n > cut) ) {
                            column.visible = false;                            
                        } // if
                        n = n + 1;
                    } // if
                }); // each
            } // if                            
        } else {
            var visible = $initCols.split(',');            
            $.each(columns, function(i, column) {
                //console.log('Definition: '+column.visible+' cookie: '+visible[i]);
                column.visible = (visible[i] == 'false') ? false : true;
                if( isnarrow ) {
                    if( column.searchable || column.filterable  ) {
                        //console.log('COLUMN: '+column.title);
                        if( (column.visible != 'false') && (n > cut) ) {
                            column.visible = false;                            
                        } // if
                        n = n + 1;
                    } // if
                } // if
            }); // each
        }
        return columns;
    } //setColumns

    function setDepartmentAjax(lids) {        
        var route = "{{ route('files.select.departments') }}";        
        console.log('Running setDepartmentAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {lids: lids},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                //console.dir(data);
                if( data.success ) {
                    // Generar nuevo select de temas                
                    generateDepartmentsSelect(data.departments);
                    // 
                    $("#department-selected").trigger('change');
                } else {
                    setSuccessNotification('error', 'Oops!', data.message);
                    // Blanquear select 
                    $("#department-selected").multipleSelect('destroy').html('');
                }
            } // success
        }); // ajax         
    } // setDepartmentAjax Fx

    function generateDepartmentsSelect(departments) {
        var previous = '';
        var output = '';
        $("#department-selected").multipleSelect('destroy').html('');
        if( departments.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.department.no-exist") }}'); 
        } else {
            $.each(departments, function(i, department) {
                if( department.location != previous ) {
                    if(previous != '') {
                        output += '</optgroup>';
                    } // if
                    output += '<optgroup id="dpd'+department.location_id+'" label="'+department.location+'">';
                    previous = department.location;
                } // if
                output += '<option value="'+department.department_id+'"';
                output += ' selected';
                output += '>'+department.name+'</option>';            
            });
            output += '</optgroup>';
            $("#department-selected").html(output).multipleSelect();
        }        
    } // generateDepartmentsSelect  


    function setTopicAjax(dids) {        
        var route = "{{ route('files.select.topics') }}";        
        console.log('Running setTopicAjax with route: '+route);
        //console.log('Voy a crear Tema con nombre '+txt+' para el departamento '+no);
        $.ajax({
            url: route,
            type: 'POST',
            data: {dids: dids},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                //console.dir(data);
                if( data.success ) {
                    // Generar nuevo select de temas                
                    generateTopicsSelect(data.topics);
                    // 
                    $("#topic-selected").trigger('change');
                } else {
                    setSuccessNotification('error', 'Oops!', data.message);
                    // Blanquear select 
                    $("#topic-selected").multipleSelect('destroy').html('');
                }
            } // success
        }); // ajax         
    } // setTopicAjax Fx

    function generateTopicsSelect(topics) {
        var previous = '';
        var output = '';
        $("#topic-selected").multipleSelect('destroy').html('');
        if( topics.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.topic.no-exist") }}'); 
        } else {
            $.each(topics, function(i, topic) {
                if( topic.department != previous ) {
                    if(previous != '') {
                        output += '</optgroup>';
                    } // if
                    output += '<optgroup id="dpt'+topic.department_id+'" label="'+topic.department+'">';
                    previous = topic.department;
                } // if
                output += '<option value="'+topic.topic_id+'"';
                output += ' selected';
                output += '>'+topic.name+'</option>';            
            });
            output += '</optgroup>';
            $("#topic-selected").html(output).multipleSelect();
            //$.fn.multipleSelect.defaults.filter = true;
        }        
    } // generateTopicsSelect   
    
    function setSubtopicAjax(tids) {        
        var route = "{{ route('files.select.subtopics') }}";        
        console.log('Running setSubtopicAjax with route: '+route);
        //console.log('Voy a crear Tema con nombre '+txt+' para el departamento '+no);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'tids': tids},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                //console.dir(data);
                if( data.success ) {
                    // Generar nuevo select de temas                
                    generateSubtopicsSelect(data.subtopics);
                    // 
                    
                } else {
                    setSuccessNotification('error', 'Oops!', data.message);
                    // Blanquear select 
                    $("#subtopic-selected").multipleSelect('destroy').html('');
                }
            } // success
        }); // ajax         
    } // setSubtopicAjax Fx

    function generateSubtopicsSelect(subtopics) {
        var previous = '';
        var output = '';
        $("#subtopic-selected").multipleSelect('destroy').html('');
        if( subtopics.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.subtopic.no-exist") }}'); 
        } else {
            $.each(subtopics, function(i, subtopic) {
                if( subtopic.topic != previous ) {
                    if(previous != '') {
                        output += '</optgroup>';
                    } // if
                    output += '<optgroup id="top'+subtopic.topic_id+'" label="'+subtopic.topic+'">';
                    previous = subtopic.topic;
                } // if
                output += '<option value="'+subtopic.subtopic_id+'"';
                output += ' selected';
                output += '>'+subtopic.name+'</option>';            
            });
            output += '</optgroup>';
            $("#subtopic-selected").html(output).multipleSelect();
        }        
    } // generateSubtopicsSelect       
 
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