<!-- resources/views/document/master.blade.php -->
<x-icewall>

    <x-slot:title>
        Listado Maestro de Documentos Publicados
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
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh" title="Refrescar la tabla"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-modal-suggestion" title="Sugerir documento"><i data-lucide="file-plus" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-view" title="Ver el documento"><i data-lucide="eye" class="w-5 h-5"></i></a>
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

                                    <div id="filter-accordion" class="accordion accordion-boxed">
                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-5" class="accordion-header">
                                                <button class="accordion-button" type="button" data-tw-toggle="collapse" data-tw-target="#faq-accordion-collapse-5" aria-expanded="true" aria-controls="faq-accordion-collapse-5"><i data-lucide="filter" class="w-5 h-5 inline-block"></i><span class="inline-block">&nbsp;Filtro</span></button>
                                            </div>
                                            <div id="faq-accordion-collapse-5" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-5" data-tw-parent="#faq-accordion-2">

                                                <div id="horizontal-form" class="pb-3">
                                                    <div class="preview ml-auto w-full">
                                                        <div class="form-inline">
                                                            <label for="date-selected" class="form-label sm:w-20 text-right pt-3">Rango:</label>
                                                            <input id="date-selected" type="text" class="form-control mt-2 border-slate-500" aria-label="Rango" style="padding: 0.15em 0.6em; font-size: 0.95em; border-radius: 5px">
                                                            <label for="system-selected" class="form-label sm:w-20 text-right pt-3">Requisito:</label>
                                                            <select id="system-selected" class="form-control mt-2 border-slate-500" aria-label="Requisito">
                                                                <option value="">Todos seleccionados</option>
                                                                @foreach($systems as $system)   
                                                                <option value={{ $system->system_id }} >{{ $system->name }}</option>
                                                                @endforeach                                                                                                        
                                                            </select>                                                            
                                                            <button id="btn-search" class="btn btn-primary shadow-md ml-3"><i data-lucide="search" class="w-4 h-4"></i></button>                                                                                     
                                                        </div>
                                                        <div class="form-inline">
                                                            <label for="process-selected" class="form-label sm:w-20 text-right pt-3">Procesos:</label>
                                                            <select multiple id="process-selected" class="form-control mt-2 border-slate-500" aria-label="Proceso">
                                                                @foreach($processes as $process)
                                                                <option value={{ $process->process_id }} @if($process->selected) selected @endif>{{ $process->name }}</option>
                                                                @endforeach
                                                            </select>                                                
                                                            <label for="location-selected" class="form-label sm:w-20 text-right pt-3 ml-3">Localización:</label>
                                                            <select multiple id="location-selected" class="form-control mt-2 border-slate-500" aria-label="Localización">
                                                                @foreach($locations as $location)
                                                                <option value={{ $location->location_id }} @if($location->selected) selected @endif>{{ $location->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            
                                                        </div>                                           
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <!-- BEGIN: DataTables -->
                                    <table id="documents-table" class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap">#</th>
                                                <th>Id</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Versión</th>
                                                <th>Proceso</th>
                                                <th>Tipo Documento</th>
                                                <th>Publicado</th>
                                                <th>Vigencia</th>
                                                <th>H</th>
                                                <th>S</th>
                                                <th>L</th>
                                                <th>A</th>
                                                <th>K</th>
                                                <th>T</th>
                                            </tr>
                                        </thead>
                                    </table>                                    
                                    <!-- END: DataTables -->

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->

                    <!-- BEGIN: Modal Suggestion -->
                    <div id="modal-suggestions" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-suggestions-title" class="font-medium text-base mr-auto">Editar sugerencia de nuevo documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <form id="uploadForm" method="post" action="{{ route('documents.control.solicitud.store') }}" enctype="multipart/form-data" class="dropzone">
                                        @csrf

                                        <div class="input-group mt-0">
                                            <div id="document" class="input-group-text flex"><i data-lucide="{{ trans('document/suggestion.form.document.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/suggestion.form.document.title') }}</div>
                                            <input type="text"  name="document" value="{{ old('document') }}" class="form-control  w-full" aria-describedby="document" placeholder="{{ trans('document/suggestion.form.document.placeholder') }}" minlength="2" maxlength="255" required>
                                            <div id="input-group-11" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/suggestion.form.document.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>                                            
                                        
                                            <div id="system" class="input-group-text flex ml-4"><i data-lucide="{{ trans('document/suggestion.form.system.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/suggestion.form.system.title') }}</div>
                                            <select  name="system_id" class="form-control w-full" required>                                                
                                                <option value="">{{ trans('document/suggestion.form.system.placeholder') }}</option>
                                                @foreach($systems as $system)   
                                                <option value={{ $system->system_id }} {{ old('system_id') == $system->system_id ? 'selected ' : '' }}>{{ $system->name }}</option>
                                                @endforeach
                                            </select> 
                                            <div id="input-group-12" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/suggestion.form.system.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>                                        
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="justification" class="input-group-text flex"><i data-lucide="{{ trans('document/suggestion.form.justification.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/suggestion.form.justification.title') }}</div>
                                            <textarea name="justification" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/suggestion.form.justification.placeholder') }}" rows="3" minlength="8" required>{{ old('justification') }}</textarea>
                                            <div id="input-group-21" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/suggestion.form.justification.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/suggestion.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/suggestion.form.name.title') }}</div>
                                            <input type="text"  name="name" value="{{ old('name') }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/suggestion.form.name.placeholder') }}" minlength="2" maxlength="255">
                                            <div id="input-group-31" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/suggestion.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>   
                                        </div>

                                        <div id="upload-zone" >
                                            <div class="dz-default dz-message"><h4>Mueva el archivo anexo aquí para ser cargado</h4></div>
                                        </div>
                                    </form>
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-suggestion-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cancelar</button>
                                    <button id="btn-suggestion-clear" type="button" class="btn btn-outline-secondary mr-1">Limpiar</button>
                                    <button id="btn-suggestion-ok" type="button" form="uploadForm" class="btn btn-primary">Salvar</button>
                                    <a id="modal-suggestions-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-suggestions" class="">.</a>

                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Suggestion -->


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
    let $systemColumn = 10;
    let $locationColumn = 11;
    let $dateInDefault;
    let $dateOutDefault;
    $(function () {
        let columnsDef = {!! $gridColDef !!};
        let col = {{ $gridColOrd }};
        let lang = {!! $gridLanguage !!};
        let columns = {!! $gridColExp !!};        
        let route = "{{ route('documents.master.index.render', ':slug') }}";
        
        // ACONDICIONAMIENTO
        //moment.defaultFormat = "YYYY-MM-DD"; // Solo interno
        setFooter('documents-table', columnsDef);
        var sCol = null;
        var sCol = isoGetStorage('iso_masterReturnCol');
        var sDir = isoGetStorage('iso_masterReturnDir');
        var initPage = ( isoGetStorage('iso_masterReturnPage') === null ) ? 1 : isoGetStorage('iso_masterReturnPage'); 
        var initOrder = ( sCol === null ) ? [[ col, 'desc']] : [[ 14, sDir]]; // sCol
        var initRecords = ( isoGetStorage('iso_masterReturnRows') === null ) ? 10 : isoGetStorage('iso_masterReturnRows');
        //var initFilter = ( isoGetStorage('iso_masterFilter') === null ) ? 'collapse' : isoGetStorage('iso_masterFilter');

        // FILTROS GENERALES
        setTypes();

        // PARAMETROS
        // $dateInDefault = moment().subtract(6, 'days');
        // $dateOutDefault = moment();
        
        // Sistema
        var sidStoraged = isoGetStorage('iso_masterSystem');
        if( (sidStoraged === null) || (sidStoraged == '') ) {
            var sidArray = $("#system-selected").val();
        } else {
            var sidArray = sidStoraged;
            $('#system-selected option[value='+sidStoraged+']').prop('selected', 'selected');
        }

        // Procesos
        var pidsStoraged = isoGetStorage('iso_masterProcesses');        
        var pidsArray = setStorageArray("process-selected", pidsStoraged);        
        console.log('PIDS ARRAY: '); 
        console.dir(pidsArray);
        
        // Localizaciones
        var lidsStoraged = isoGetStorage('iso_masterLocations');
        var lidsArray = setStorageArray("location-selected", lidsStoraged);  
        console.log('LIDS ARRAY: '); 
        console.dir(lidsArray);
        
        // Rango In
        var dateIn = isoGetStorage('iso_masterDatein');
        $dateInDefault = ( dateIn === null ) ? moment().subtract(6, 'days') : dateIn;
        console.log('DIN : '+ $dateInDefault);
        
        // Rango Out
        var dateOut = isoGetStorage('iso_masterDateout');
        $dateOutDefault = ( dateOut === null ) ? moment() : dateOut;
        console.log('DOUT : '+ $dateOutDefault);        

        // JSon
        param = {sid: sidArray, pids: pidsArray, lids: lidsArray, din: $dateInDefault, dout: $dateOutDefault};        
        console.dir(JSON.stringify(param)); 

        // DATATABLE
        var startTime = Date.now();
        console.log('Datatables init starts now: ', Date.now() - startTime);
        let myTable = $('#documents-table')
        .on('preXhr.dt', function () {
            console.log('Send ajax request ', Date.now() - startTime + ' milliseconds.');
        })
        .on('xhr.dt', function () {
            console.log('Received ajax response ', Date.now() - startTime + ' milliseconds.');
        })
        .DataTable({
            bProcessing: true,
            sAjaxSource: route.replace(':slug', JSON.stringify(param)),
            aoColumns: columnsDef,
            retrieve: true,
            //dom: 'Blfrtip',
            pageLength: initRecords,
            order: initOrder,
            orderClasses: false,
            responsive: true,
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
            columnDefs: [{
                targets: 8,
                createdCell: function(td, cellData, rowData, row, col) {
                    if( rowData[12] == 2 ) {
                        $(td).css('background-color', 'red');
                        console.log('row 2: '+ rowData[1]);
                    } else if( rowData[12] == 1 ) {
                        $(td).css('background-color', 'yellow');
                        console.log('row 1: '+ rowData[1]);
                    }
                }                
            },{
                "targets": 2, "className": "dt-nowrap"
            }],
            rowCallback: function( nRow, data, index, displayIndex ) {
                // Generar columna índice
                if(nRow){
                    var ordinal = displayIndex + 1;
                    $('td:eq(0)', nRow).html(ordinal);
                }
                return nRow;                
            },                
            initComplete: function() {
                console.log('DT init complete in ', Date.now() - startTime + ' milliseconds.');
                console.log('Total Rows: ' + this.api().data().count());
                this.api().columns().every( function (i) {
                    var column = this;
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( column.search() !== this.value ) {
                            column.search( this.value ).draw();
                        }
                    });   
                });                
            },
            language: lang
        }); // datatables

        // DATERANGE
        $('#date-selected').daterangepicker({
            locale: {
                format: "YYYY/MM/DD",   // FIXME:
                daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                customRangeLabel: "Personalizado",
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
                'Último semestre': [moment().subtract(5, 'months'), moment()]
            }            
        });

        $('#date-selected').on('apply.daterangepicker', function(ev, picker) {
            $dateInDefault = picker.startDate.format();
            console.log('DIN : '+$dateInDefault);
            $dateOutDefault = picker.endDate.format();
            console.log('DOUT : '+$dateOutDefault);
        }); 

        // MULTIPLESELECT
        $('#system-selected, #location-selected, #process-selected').multipleSelect();


        // BOTONES
        // Refrescar el listado
        $('#btn-refresh').on("click", function() {
            myTable.search('').columns().search('').draw();
            $(".col-filter").val('');
        }); // btn-refresh

        // Mostrar el documento en html
        $('#btn-view').on("click", function() {
            var rowdata = myTable.rows('.selected').data()[0];
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_show') }}");
            } else {
                var hash = rowdata.hash;
                var uri = "{{ route('documents.master.render', ':hash') }}"; 
                
                // Storage
                var info = myTable.page.info();
                var order = myTable.order();             
                isoSetStorage('iso_masterReturnUrl', isoGetCurrentURL());
                isoSetStorage('iso_masterReturnPage', info.page);
                isoSetStorage('iso_masterReturnCol', order[0][0]);
                isoSetStorage('iso_masterReturnDir', order[0][1]);
                isoSetStorage('iso_masterReturnRows', info.length);
                // Ref
                uri = uri.replace(':hash', hash);
                location.href = uri;                                 
            }
        }); // btn-view

        // UTILIDADES
        
        $("#btn-download").on("click", function() {
            myTable.button('.buttons-excel').trigger();
        });
        
        $("#btn-print").on("click", function() {
            myTable.button('.buttons-pdf').trigger();
        });

        $("#btn-colvis").on("click", function() {
            myTable.button('.buttons-colvis').trigger();
        });
        
        // Seleccionar fila
        $('#documents-table').on('click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            } else {
                myTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');                
            } // if selected
        }); // row selects


        // FILTROS
        //$('#system-selected, #process-selected, #location-selected').on('change', function() {
        $("#btn-search").on("click", function() {
            var sidValue = $("#system-selected").val();
            var pidsArray = $("#process-selected").val();
            var lidsArray = $("#location-selected").val();
            params = {sid: sidValue, pids: pidsArray, lids: lidsArray, din: $dateInDefault, dout: $dateOutDefault};
            console.dir(JSON.stringify(params));
            
            // Ajax
            var url =  route.replace(':slug', JSON.stringify(params));
            startTime = Date.now();
            myTable.ajax.url(url).load();
            myTable.state.clear();
            myTable.search('').columns().search('').draw(); 

            // Store
            isoSetStorage('iso_masterSystem', sidValue);
            isoSetStorage('iso_masterProcesses', pidsArray);
            isoSetStorage('iso_masterLocations', lidsArray);
            isoSetStorage('iso_masterDatein', $dateInDefault);
            isoSetStorage('iso_masterDateout', $dateOutDefault); //
            //isoSetStorage('iso_masterFilter', $dateOutDefault);
        }); // CHANGE selected

        // Filtro de palabras clave
        $('input[type="search"]').on( 'keyup click', function () {
            myTable.search('');     
            myTable.column(13).search(this.value).draw();
        });         

        $('#filter-typeName').on('change', function(){
            myTable.column(6).search(this.value).draw();   
        }); // filter-typeName        
        

        // GEMERA EL MODAL PARA OBSERVACIONES
        $('body').on('click', '#btn-modal-suggestion', function (e) {
            e.preventDefault();
            $('#uploadForm')[0].reset();
            $("#modal-suggestions-open")[0].click();
        });
                
    }); // document

    function setSystems() {
        var current = $("#system-selected").val();
        $.ajax({
            type: 'POST',
            data: {'sid':current},
            dataType: 'json',
            url: '/documentos/master/listado/sistemas',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                //console.dir(data);
                var output = '<option value="">Todos</option>';
                $.each(data, function(i, value) {
                    output += '<option value='+value.system_id;
                    output += (value.selected) ? ' selected' : '';
                    output += '>'+value.name+'</option>';
                });
                $("#system-selected").html(output);
            } // success
        });
    } // setSystems Fx

    function setLocations() {
        var current = $("#location-selected").val();
        $.ajax({
            type: 'POST',
            data: {'lid':current},
            dataType: 'json',
            url: '/documentos/master/listado/localizaciones',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                //console.dir(data);
                var output = '<option value="">Todos</option>';
                $.each(data, function(i, value) {
                    output += '<option value='+value.location_id;
                    output += (value.selected) ? ' selected' : '';
                    output += '>'+value.name+'</option>';
                });
                $("#location-selected").html(output);
            } // success
        });
    } // setLocations Fx 
    
    function setProcesses() {
        var current = $("#filter-processName").val();
        $.ajax({
            type: 'POST',
            data: {'name':current},
            dataType: 'json',
            url: '/documentos/master/listado/procesos',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                //console.dir(data);
                var output = '<option value="">Todos</option>';
                $.each(data, function(i, value) {
                    output += '<option value="'+value.name+'"';
                    output += (value.selected) ? ' selected' : '';
                    output += '>'+value.name+'</option>';
                });
                $("#filter-processName").html(output);
              

            } // success
        });
    } // setProcesses Fx
    
    function setTypes() {
        var current = $("#filter-typeName").val();
        $.ajax({
            type: 'POST',
            data: {'name':current}, //   FIXME: corregir
            dataType: 'json',
            url: '/documentos/master/listado/tipos',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                //console.dir(data);
                var output = '<option value="">Todos</option>';
                $.each(data, function(i, value) {
                    output += '<option value="'+value.name+'"';
                    output += (value.selected) ? ' selected' : '';
                    output += '>'+value.name+'</option>';
                });
                $("#filter-typeName").html(output);
            } // success
        });
    } // setTypes Fx
    
    function setStorageArray(tag, storaged) {
        var output = [];
        console.log('TAG: '+ tag + ' | INPUT: ' + storaged);
        if( storaged === null ) {
            output = $("#"+tag).val();
        } else { 
            $("#"+tag+" option").prop("selected", false);           
            if( storaged.indexOf(",") == -1 ) {
                // valor único
                output = [storaged];
                console.log('val: '+ storaged);
                $('#'+tag+' option[value='+storaged+']').prop('selected', 'selected');                
            } else {
                // arreglo                
                output = storaged.split(',');
                $.each(output, function(i, val) {
                    console.log('val: '+ val);
                    $('#'+tag+' option[value='+val+']').prop('selected', 'selected');
                });                 
            }           
        }
        return output;
    } // setStorage Fx

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
        $("#modal-suggestions-open")[0].click();
    </script> 
@endif 

@endpush

</x-icewall> 