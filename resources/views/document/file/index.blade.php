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
                                                        <div class="grid grid-cols-3 gap-2">
                                                            <div class="form-inline">
                                                                <label for="date-selected" class="form-label sm:w-20 text-right">Rango:</label>
                                                                <input id="date-selected" type="text" class="form-control w-36 border-slate-500 iso-input" aria-label="Rango">
                                                            </div>
                                                        </div>
                                                        <div class="form-inline">

                                                            <label for="system-selected" class="form-label sm:w-20 text-right pt-3">Requisitos:</label>
                                                            <select multiple id="system-selected" class="form-control mt-2 border-slate-500" aria-label="Requisito">
                                                                @foreach($systems as $system)   
                                                                <option value={{ $system->system_id }} selected>{{ $system->name }}</option>
                                                                @endforeach                                                                                                        
                                                            </select>
                                                            <label for="process-selected" class="form-label sm:w-20 text-right">Procesos:</label>
                                                            <select multiple id="process-selected" class="form-control mt-2 border-slate-500" aria-label="Proceso">
                                                                @foreach($processes as $process)   
                                                                <option value={{ $process->process_id }} selected>{{ $process->name }}</option>
                                                                @endforeach  
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
                                                <th>H</th>
                                                <th>N</th>
<!--                                                 <th>Medio Soporte</th>
                                                <th>C1</th>
                                                <th>C2</th> -->
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
                                                <th class="th-filter">H</th>
                                                <th class="th-filter">N</th>
<!--                                                 <th class="th-filter">Medio Soporte</th>
                                                <th class="th-filter">C1</th>
                                                <th class="th-filter">C2</th> -->
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
                                                <th>H</th>
                                                <th>N</th>                                                
<!--                                                 <th>Medio Soporte</th>
                                                <th>C1</th>
                                                <th>C2</th> -->
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
        $initCols = ( isoGetStorage('iso_fileColumns') === null ) ? columns : isoGetStorage('iso_fileColumns');           
        //$initCols = setColumns(columns);
        console.dir($initCols);

        // Filas
        var initFiles = ( isoGetStorage('iso_fileReturnRows') === null ) ? 10 : isoGetStorage('iso_fileReturnRows');

        // Sistema
        var sidsStoraged = isoGetStorage('iso_fileSystems');
        var sidsArray = setStorageInteger("system-selected", sidsStoraged);  
        
        // Proceso
        var pidsStoraged = isoGetStorage('iso_fileProcesses');
        var pidsArray = setStorageInteger("process-selected", pidsStoraged);          

        // Rango In
        var dateIn = isoGetStorage('iso_fileDatein');
        $dateInDefault = ( dateIn === null ) ? moment().subtract(6, 'days') : dateIn;
        
        // Rango Out
        var dateOut = isoGetStorage('iso_fileDateout');
        $dateOutDefault = ( dateOut === null ) ? moment() : dateOut;         

        // DATATABLES
        param = {sids: sidsArray, pids: pidsArray, din: $dateInDefault, dout: $dateOutDefault};
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

        // Filtros : generación
        $('#files-table thead tr:eq(1) th').each( function (i) {
            var tag;
            var item = columnsDef[i+8];
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

        $('#system-selected, #process-selected').on('change', function() {
            $("#btn-filter").removeClass('btn-success').addClass('btn-primary');
        });        
        
        // BOTONES

        // Filtras lista
        $("#btn-filter").on("click", function() {
            var sidsValue = $("#system-selected").val();
            var pidsArray = $("#process-selected").val();

            var info = $myTable.page.info();            
            var params = {sids: sidsValue, pids: pidsArray, din: $dateInDefault, dout: $dateOutDefault};
            var visibleColumns = $myTable.columns().visible().toArray();
            var url =  $route.replace(':slug', JSON.stringify(params));

            console.log('Searching...');
            console.dir(JSON.stringify(params));
            
            if( (sidsValue.length > 0) && (pidsArray.length > 0) ) {
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
                isoSetStorage('iso_fileProcesses', pidsArray);
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
        //alert(visibleColumns);

        isoSetStorage('iso_fileColumns', visibleColumns);  
    } // setStorage

    function setColumns(columns) {
        var cut = 6;
        var width = 1122;
        var viewportWidth = $(window).width(); 

        console.log('Width: '+ viewportWidth ); 
        var newCols = ( isoGetStorage('iso_fileColumns') === null ) ? columns : isoGetStorage('iso_fileColumns'); 
               
        if( newCols.length > cut ) {
            if( viewportWidth < width ) {
                passCols = newCols.splice(0, cut);
                newCols = passCols;
            } // if            
        } // if

        return newCols;
    } // setColumns
 
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