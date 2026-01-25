<!-- resources/views/document/index.blade.php -->
<x-icewall>

    <x-slot:title>
        Listado Maestro de Registros
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Listado Maestro</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Listado Maestro de Registros
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh" title="Refrescar la tabla"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2 iso-disabled" href="javascript:;" id="btn-edit" title="Editar el documento"><i data-lucide="edit" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2 iso-disabled" href="javascript:;" id="btn-view" title="Ver el documento"><i data-lucide="eye" class="w-5 h-5"></i></a>
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

                                    <div id="faq-accordion-2" class="accordion accordion-boxed">

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-6" class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-tw-toggle="collapse" data-tw-target="#faq-accordion-collapse-6" aria-expanded="false" aria-controls="faq-accordion-collapse-6"><img id="loading-image" alt="Cargando..." class="h-8 inline-flex mr-20" src="{{ url('/assets/images/loading_small.gif') }}"><i data-lucide="search" class="w-5 h-5 inline-block"></i><span class="inline-block">&nbsp;Buscar en registros</span></button>
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

                                                            <label for="system-selected" class="form-label sm:w-20 text-right pt-3">Requisito:</label>
                                                            <select multiple id="system-selected" class="form-control mt-2 border-slate-500" aria-label="Requisito">
                                                                @foreach($systems as $system)   
                                                                <option value={{ $system->system_id }} selected>{{ $system->name }}</option>
                                                                @endforeach                                                                                                        
                                                            </select>
                                                            <label for="process-selected" class="form-label sm:w-20 text-right">Procesos:</label>
                                                            <select multiple id="process-selected" class="form-control mt-2 border-slate-500" aria-label="Proceso">
                                                                @foreach($processes as $process)
                                                                <option value={{ $process->process_id }} @if($process->selected) selected @endif>{{ $process->name }}</option>
                                                                @endforeach
                                                            </select>                                                                                                                                                                                                                                                                         
                                                        </div>
                                                        <div class="form-inline">                                              
                                                            <label for="group-selected-f" class="form-label sm:w-20 text-right pt-3">Grupo:</label>
                                                            <select id="group-selected-f" class="form-control mt-2 border-slate-500 iso-input">
                                                                <option value="">Todos seleccionados</option>
                                                                @foreach($groups as $item)
                                                                <option value="{{ $item->group }}">{{ $item->group }}</option>
                                                                @endforeach
                                                            </select>
                                                            <label for="tag-selected-f" class="form-label sm:w-20 text-right">Etiqueta:</label>
                                                            <select id="tag-selected-f" class="form-control mt-2 border-slate-500 iso-input">
                                                                <option value="">Todos seleccionados</option>
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

                                    <br />
                                    <!-- BEGIN: DataTables -->
                                    <table id="records-table" class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap">#</th>
                                                <th>Id</th>
                                                <th>NUI</th>
                                                <th>Nombre</th>
                                                <th>Elaborado por</th>
                                                <th>Tema</th>
                                                <th>Subtema</th>
                                                <th>Publicado</th>
                                                <th>Origen</th>
                                                <th>STS</th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th class="th-filter">Id</th>
                                                <th class="th-filter">NUI</th>
                                                <th class="th-filter">Nombre</th>
                                                <th class="th-filter">Elaborado por</th>
                                                <th class="th-filter">Tema</th>
                                                <th class="th-filter">Subtema</th>
                                                <th class="th-filter">Publicado</th>
                                                <th class="th-filter">Origen</th>
                                                <th class="th-filter">STS</th>
                                            </tr>                                            
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>NUI</th>
                                                <th>Nombre</th>
                                                <th>Elaborado por</th>
                                                <th>Tema</th>
                                                <th>Subtema</th>
                                                <th>Publicado</th>
                                                <th>Origen</th>
                                                <th>STS</th>
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
        span.deleteicon {
            position: relative;
            display: inline-flex;
            align-items: center;                
        }
        span.deleteicon span {
            position: absolute;
            display: block;
            right: 3px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            color: #fff;
            background-color: #ccc;
            font: 13px monospace;
            text-align: center;
            line-height: 1em;
            cursor: pointer;
            
        }
        span.deleteicon input {
            padding-right: 18px;
            box-sizing: border-box;
        }
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
    let $myTable;
    let $route = "{{ route('records.index.render', ':slug') }}";

    $(function () {
        let columnsDef = {!! $gridColDef !!};
        let col = {{ $gridColOrd }};        
        let columns = {!! $gridColExp !!};
        let lang = {!! $gridLanguage !!};

        var startTime = Date.now();                       
        var sCol = isoGetStorage('iso_recordReturnCol');
        var sDir = isoGetStorage('iso_recordReturnDir');
        var initPage = ( isoGetStorage('iso_recordReturnPage') === null ) ? 1 : isoGetStorage('iso_recordReturnPage');
        // console.log('sCol:'+sCol+' col:'+col+' sDir:'+sDir); 
        var initOrder = ( sCol !== 'undefined' && sCol ) ? [[ sCol, sDir]] : [[ col, 'asc']];
        // console.log('iNITORDER:');
        // console.dir(initOrder);        
        var initRecords = ( isoGetStorage('iso_recordReturnRows') === null ) ? 10 : isoGetStorage('iso_recordReturnRows');

        // Sistema
        var sidsStoraged = isoGetStorage('iso_recordSystems');
        var sidsArray = setStorageInteger("system-selected", sidsStoraged);   

        // Procesos       
        var pidsStoraged = isoGetStorage('iso_recordProcesses');        
        var pidsArray = setStorageInteger("process-selected", pidsStoraged);                 
        
        // Grupos
        // var gidsStoraged = isoGetStorage('iso_recordGroups');
        // var gidInteger = setStorageString("group-selected-f", gidsStoraged);  

        // Etiquetas
        // var tidsStoraged = isoGetStorage('iso_recordTags');
        // var tidInteger = setStorageInteger("tag-selected-f", tidsStoraged);                  
        
        // Rango In
        var dateIn = isoGetStorage('iso_recordDatein');
        $dateInDefault = ( dateIn === null ) ? moment().subtract(6, 'days') : dateIn;
        
        // Rango Out
        var dateOut = isoGetStorage('iso_recordDateout');
        $dateOutDefault = ( dateOut === null ) ? moment() : dateOut;           

        // DATATABLES
        param = {sids: [], pids: [], gid: '', tid: '', din: $dateInDefault, dout: $dateOutDefault};

        console.dir(param);
        //console.dir(columnsDef);
        console.log('Datatables init starts now: ', Date.now() - startTime);
         
        $myTable = $('#records-table')
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
        

        // Filtros : generación
        $('#records-table thead tr:eq(1) th').each( function (i) {
            var tag;
            var item = columnsDef[i+1];
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
     
        // DATERANGE
        //console.log('DIN : '+$dateInDefault+' | DOUT : '+$dateOutDefault);
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


        // BOTONES
        // Refrescar el listado
        $('#btn-refresh').on("click", function() {
            $myTable.search('').columns().search('').draw();
            $(".col-filter").val('');
            $('#btn-edit').addClass('iso-disabled');
            $('#btn-view').addClass('iso-disabled'); 
        }); // btn-refresh

        // Mostrar el registro en html
        $('#btn-view').on("click", function() {
            var rowdata = $myTable.rows('.selected').data()[0];
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/record.grid.row_show') }}");
            } else {
                var id = rowdata.record_id;
                setView(id);                              
            }
        }); // btn-view

        // Editar el registro en html
        $('#btn-edit').on("click", function() {
            var rowdata = $myTable.rows('.selected').data()[0];
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/record.grid.row_show') }}");
            } else {
                var id = rowdata.record_id;
                setEdit(id);
            }
        }); // btn-view        

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
        $('#records-table').on('click', 'tr', function () {
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

        // FILTROS ***
        $("#btn-filter").on("click", function() {
            var sidsValue = $("#system-selected").val();
            var pidsArray = $("#process-selected").val();
            var gidInteger = $("#group-selected-f").val();
            var tidInteger = $("#tag-selected-f").val();

            var info = $myTable.page.info();            
            var params = {sids: sidsValue, pids: pidsArray, gid: gidInteger, tid: tidInteger, din: $dateInDefault, dout: $dateOutDefault};
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
                isoSetStorage('iso_recordSystems', sidsValue);
                isoSetStorage('iso_recordProcesses', pidsArray);
                isoSetStorage('iso_recordGroups', gidInteger);
                isoSetStorage('iso_recordTags', tidInteger);
                isoSetStorage('iso_recordDatein', $dateInDefault);
                isoSetStorage('iso_recordDateout', $dateOutDefault); //
                isoSetStorage('iso_recordReturnRows', info.length);
            } else {
                swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Debe seleccionar al menos una opción de todos los selectores"
                });
            }                

        }); // CHANGE selected

        $('#group-selected-f').on('change', function() {
            var group = this.value;
            if( group != '' ) {
                tagAjax(group);
            } else {
                $("#tag-selected-f").html('<option value="" selected>Todos seleccionados</option>');
            }
        });
               
        // Efectos Botón
        $('#date-selected, #text-input, #tag-input').on('blur', function() {
            $("#btn-filter").removeClass('btn-success').addClass('btn-primary');
        });

        $('#system-selected, #process-selected, #group-selected-f, #tag-selected-f').on('change', function() {
            $("#btn-filter").removeClass('btn-success').addClass('btn-primary');
        });
        
                        
    }); // document

    function tagAjax(group) {
        var route = "{{ route('records.edit.tag') }}";
        console.log('Running tagAjax with route: '+route+' and Group: '+group);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt': group},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                console.dir(data);                                                                
                var output  = '<option value="">Todos</option>';                                
                $.each(data, function(i, value) {
                    output += '<option value="'+value.tag+'">'+value.tag+'</option>';
                });
                $("#tag-selected-f").html(output);
            } // success
        }); // ajax 
    } //tagAjax Fx     

    function setFilters() {
        // Tipo de documento
        var output = '<option value="">Seleccione Tipo Documento</option>';
        $("#tag-selected > option:selected").each( function() {
            output += '<option value="' + $(this).text() + '">' + $(this).text() + '</option>';
        });
        $("#filter-typeName").html(output);
    }

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

    function setStorageString(tag, storaged) {
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
                output += '<option value="'+ parseInt(this.value) +'" selected>'+ this.text +'</option>';
                //console.log(i, this.value , this.text, 'Selected');
            } else {
                output += '<option value="'+ parseInt(this.value) +'">'+ this.text +'</option>';
                //console.log(i, this.value , this.text, '');
            }
        });        

        $("#"+tag).html(output);
        return array;
    } // setStorageString Fx

    function setStorage() {
        // Storage
        var info = $myTable.page.info();
        var order = $myTable.order();             
        isoSetStorage('iso_recordReturnUrl', isoGetCurrentURL());
        isoSetStorage('iso_recordReturnPage', info.page);
        isoSetStorage('iso_recordReturnCol', order[0][0]);
        isoSetStorage('iso_recordReturnDir', order[0][1]);
        isoSetStorage('iso_recordReturnRows', info.length);
    } // setStorage
    
    function setView(id) {
        var uri = "{{ route('records.render', ':hash') }}";
        $.ajax({
            url: '/documentos/registro/hash/'+id,
            type: 'GET',
            dataType: 'json',                
            success: function(json) {
                setStorage();
                uri = uri.replace(':hash', json.hash);
                console.log('URI: '+uri);
                location.href = uri;   
            } // success
        }); // ajax 
    } // setView Fx

    function setEdit(id) {
        var uri = "{{ route('records.edit', ':hash') }}";
        $.ajax({
            url: '/documentos/registro/hash/'+id,
            type: 'GET',
            dataType: 'json',                
            success: function(json) {
                setStorage();
                uri = uri.replace(':hash', json.hash);
                console.log('URI: '+uri);
                location.href = uri;   
            } // success
        }); // ajax 
    } // setView Fx    

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