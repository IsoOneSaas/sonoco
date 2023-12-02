<!-- resources/views/document/document.index.blade.php -->
<x-icewall>

    <x-slot:title>
            Documentos en Proceso - Administrar
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item active" aria-current="page">Documentos</li>
    </x-slot:breadcrumb>    

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Listado de Documentos en Proceso
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh" title="Refrescar la tabla"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>                            
                            <a class="btn btn-primary shadow-md mr-1" href="{{ route('documents.control.documento.create') }}" id="btn-add" title="Crear documento"><i data-lucide="plus" class="w-5 h-5"></i></a>                            
                            <a class="btn btn-primary shadow-md mr-1 iso-disabled" href="javascript:;" id="btn-edit" title="Editar documento"><i data-lucide="edit" class="w-5 h-5"></i></a>                                                        

                            <a class="btn btn-primary shadow-md mr-1 iso-disabled" href="javascript:;" id="btn-sight" title="Ver comentarios"><i data-lucide="message-circle" class="w-5 h-5"></i></a>                            
                            <a class="btn btn-primary shadow-md mr-1 iso-disabled" href="javascript:;" id="btn-sheet" title="Ficha documento"><i data-lucide="file-text" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-3 iso-disabled" href="javascript:;" id="btn-view" title="Ver el documento"><i data-lucide="eye" class="w-5 h-5"></i></a>
                            <a class="btn btn-secondary shadow-md mr-3 iso-disabled" href="javascript:;" id="btn-send" title="Gestionar documento"><i data-lucide="external-link" class="w-5 h-5"></i></a>
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
                                                <button class="accordion-button collapsed" type="button" data-tw-toggle="collapse" data-tw-target="#faq-accordion-collapse-6" aria-expanded="false" aria-controls="faq-accordion-collapse-6"><img id="loading-image" alt="Cargando..." class="h-8 inline-flex mr-20" src="{{ url('/assets/images/loading_small.gif') }}"><i data-lucide="search" class="w-5 h-5 inline-block"></i><span class="inline-block">&nbsp;Buscar</span></button>
                                            </div>
                                            <div id="faq-accordion-collapse-6" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-6" data-tw-parent="#faq-accordion-2">

                                                <div id="horizontal-form" class="pb-3">
                                                    <div class="preview ml-auto w-full">
                                                        <div class="grid grid-cols-3 gap-2">
                                                            <div class="form-inline">
                                                                <label for="date-selected" class="form-label sm:w-20 text-right">Rango:</label>
                                                                <input id="date-selected" type="text" class="form-control w-32 border-slate-500 iso-input" aria-label="Rango">
                                                            </div>
                                                            <div class="form-inline">
                                                                <label for="text-input" class="form-label sm:w-20 text-right">Texto:</label>
                                                                <input id="text-input" type="text" class="form-control w-52 border-slate-500 iso-input deletable" aria-label="Texto">
                                                            </div>
                                                            <div class="form-inline">
                                                                <label for="status-selected" class="form-label sm:w-20 text-right pt-3">Estado:</label>
                                                                <select id="status-selected" class="form-control form-select-sm mt-2 border-slate-500" aria-label="Estado">
                                                                    <option value=0>En proceso</option>
                                                                    <option value=1>Publicados</option>
                                                                    <option value=9>Obsoletos</option>
                                                                    <option value=2>Cancelados</option>
                                                                    <option value=3>Eliminados</option>
                                                                    <option value=4>Desestimado</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-inline">

                                                            <label for="system-selected" class="form-label sm:w-20 text-right pt-3">Requisito:</label>
                                                            <select multiple id="system-selected" class="form-control mt-2 border-slate-500" aria-label="Requisito">
                                                                @foreach($systems as $system)   
                                                                <option value={{ $system->system_id }} selected>{{ $system->name }}</option>
                                                                @endforeach                                                                                                        
                                                            </select>
                                                            <label for="location-selected" class="form-label sm:w-20 text-right pt-3 ml-5">Localización:</label>
                                                            <select multiple id="location-selected" class="form-control mt-2 border-slate-500" aria-label="Localización">
                                                                @foreach($locations as $location)   
                                                                <option value={{ $location->location_id }} selected>{{ $location->name }}</option>
                                                                @endforeach                                                                                                        
                                                            </select>
                                                            <label for="type-selected" class="form-label sm:w-20 text-right pt-3">Tipos:</label>
                                                            <select multiple id="type-selected" class="form-control mt-2 border-slate-500" aria-label="Tipo">
                                                                @foreach($types as $type)
                                                                <option value={{ $type->type_id }} selected >{{ $type->name }}</option>
                                                                @endforeach
                                                            </select>                                                                                                                         

                                                        </div>

                                                        <div class="flex mt-3 justify-center">
                                                            <button id="btn-search" class="btn btn-primary shadow-md"><i data-lucide="filter" class="w-4 h-4"></i>&nbsp;Buscar&nbsp;&nbsp;</button> 
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
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Versión</th>
                                                <th>Proceso</th>
                                                <th>Tipo Documento</th>
                                                <th>Responsable</th>
                                                <th>Viene de</th>
                                                <th>Estado</th>
                                                <th>H</th>
                                                <th>C</th>
                                                <th>X</th>
                                                <th>F</th>
                                                <th>L</th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Versión</th>
                                                <th>Proceso</th>
                                                <th>Tipo Documento</th>
                                                <th>Responsable</th>
                                                <th>Viene de</th>
                                                <th>Estado</th>
                                                <th>H</th>
                                                <th>C</th>
                                                <th>X</th>
                                                <th>F</th>
                                                <th>L</th>
                                            </tr>                                            
                                        </thead>
                                        <tfood>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Versión</th>
                                                <th>Proceso</th>
                                                <th>Tipo Documento</th>
                                                <th>Responsable</th>
                                                <th>Viene de</th>
                                                <th>Estado</th>
                                                <th>H</th>
                                                <th>C</th>
                                                <th>X</th>
                                                <th>F</th>
                                                <th>L</th>                                            
                                        </tfood>                                     
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->

                    <!-- BEGIN: Modal Sighting -->
                    <div id="modal-sightings" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-sightings-title" class="font-medium text-base mr-auto">Administrar observaciones del documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                    <table id="example" class="display dataTable" style="width:100%" aria-describedby="example_info">
                                        <thead>
                                            <tr>
                                                <th class="dt-control sorting_disabled" rowspan="1" colspan="1" style="width: 22.9688px;"></th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Fecha</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Nombre</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Tipo</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Página</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Sección</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-sightings-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cerrar</button>
                                    <a id="modal-sightings-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-sightings" class="">.</a>

                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Sighting -->                      

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
<script src="{{ url('assets/js/daterangepicker-master/moment.min.js') }}"></script>
<script src="{{ url('assets/js/daterangepicker-master/daterangepicker.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/multiple-select.min.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/locale/multiple-select-es-ES.min.js') }}"></script>
<script src="{{ url('assets/js/iso_scripts.js') }}"></script>

<script document="text/javascript">
    let $urlContent = '{{ $urlContent }}';
    let $myTable;
    let $beforeRow = 0;
    let $selectedRow = false;
    let $columnsConf = {!! $gridColDef !!};

    $(function () {
    
        
        let col = {{ $gridColOrd }};
        let lang1 = {!! $gridLanguage !!};
        let lang2 = {!! $modalLanguage !!};
        let columns = {!! $gridColExp !!};        
        let route = "{{ route('documents.control.documento.show', ':slug') }}";
        let param = [];
        let filterColumn = 13;
        let $dateInDefault;
        let $dateOutDefault;        
        //let initPage = 1;
        //let initOrder =  [[ col, 'desc']]; 
        //console.dir(lang1);
        
        // ACONDICIONAMIENTO        
        var sCol = isoGetStorage('control_returnCol');
        var sDir = isoGetStorage('control_returnDir');        
        var initPage = ( isoGetStorage('control_returnPage') === null ) ? 1 : isoGetStorage('control_returnPage'); 
        var initOrder = ( sCol === null ) ? [[ col, 'desc']] : [[ sCol, sDir]];
        var initRecords = ( isoGetStorage('iso_controlReturnRows') === null ) ? 10 : isoGetStorage('iso_controlReturnRows');
         
        // PARAMETROS
        //console.log('timeSelected: '+timeSelected);        
        //setFooter('documents-table', $columnsConf);
        

        // FILTROS GENERALES
        //setProcesses();
        //setTypes();        
        //setUsers();
    

        //*** VALIDADO */

        // Estado
        var statusSelected = isoGetStorage('iso_controlStatus');
        var currentStatusSelected = ( (statusSelected === null) || (statusSelected === '')  ) ? $("#status-selected").val() : statusSelected;
        if( (statusSelected === null) || (statusSelected === '')  ) {
            var currentStatusSelected = $("#status-selected").val();
        } else {
            var currentStatusSelected = statusSelected;
            $("#status-selected").val(currentStatusSelected);
        }

        // Sistema
        var sidsStoraged = isoGetStorage('iso_controlSystems');
        console.log('SIDSTRORAGE: ');
        console.dir(sidsStoraged);
        var sidsArray = setStorageArray("system-selected", sidsStoraged);
        
        // Localizaciones
        var lidsStoraged = isoGetStorage('iso_controlLocations');
        console.log('LIDSTRORAGE: ');
        console.dir(lidsStoraged);
        var lidsArray = setStorageArray("location-selected", lidsStoraged);    
        
        // Tipos
        var tidsStoraged = isoGetStorage('iso_controlTypes');
        console.log('TIDSTRORAGE: ');
        console.dir(tidsStoraged);
        var tidsArray = setStorageArray("type-selected", tidsStoraged);          

        // Rango In
        var dateIn = isoGetStorage('iso_controlDatein');
        $dateInDefault = ( dateIn === null ) ? moment().subtract(6, 'days') : dateIn;
        
        // Rango Out
        var dateOut = isoGetStorage('iso_controlDateout');
        $dateOutDefault = ( dateOut === null ) ? moment() : dateOut;  
        
        // JSon
        
        //param = {time: currentTimeSelected, status: currentStatusSelected, din: $dateInDefault, dout: $dateOutDefault};       
        param = {status: currentStatusSelected, sids: sidsArray, lids: lidsArray, tids: tidsArray,  din: $dateInDefault, dout: $dateOutDefault, txt: ''};
        console.dir(JSON.stringify(param));         
                
        // DATATABLE
        var startTime = Date.now();
        console.log('Datatables init starts now: ', Date.now() - startTime);

        $myTable = $('#documents-table')
        .on('preXhr.dt', function () {

            console.log('Send ajax request ', Date.now() - startTime + ' milliseconds.');
        })
        .on('xhr.dt', function () {
            console.log('Received ajax response ', Date.now() - startTime + ' milliseconds.');
            $("#loading-image").hide();
            $("#btn-search").removeClass('btn-primary').addClass('btn-success'); 
        })
        .DataTable({
            dom: 'lrtip',
            bProcessing: true,
            sAjaxSource: route.replace(':slug', JSON.stringify(param)),
            aoColumns: $columnsConf,
            retrieve: true,
            pageLength: initRecords,
            order: initOrder,
            page: initPage,
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
            columnDefs: [{
                "targets": 9,  // status
                "createdCell": function (td, cellData, rowData, row, col) {
                    if( rowData.color == 0 ) {
                        $(td).addClass('redColorClass');
                    } else if( rowData.color == 1 ) {
                        $(td).addClass('greenColorClass');
                    } else {
                        $(td).addClass('defaultColorClass');
                    }                   
                } // fx
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
            drawCallback: function(settings) {
                var api = this.api();
                api.columns().every( function (i) {
                    var column = this;
                    if( $columnsConf[i].filterable == true ) {                     
                        //console.log('column: '+i);
                        var id =  $columnsConf[i].data;
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
                
                this.api().columns().every( function (i) {
                    var column = this;
                    var id =  $columnsConf[i].data;
                    
                    if( $columnsConf[i].filterable == true ) {
                        //console.log('column: '+id);
                        $("#filter-"+id).on( 'change', function () {
                            //console.log($(this).val());
                            var val = $(this).val();
                            column.search( val ? '^' + val + '$' : '', true, false).draw();
                        });                                                
                    } else if( $columnsConf[i].searchable == true ) {                                                
                        $("#filter-"+id).on( 'keyup change clear', function() {
                            if ( column.search() !== this.value ) {
                                column.search( this.value ).draw();
                            }
                        });
                    }                        
                });
            },                                    
            language: lang1               
        }); // datatables

        // Filtros : generación
        $('#documents-table thead tr:eq(1) th').each( function (i) {
            var tag;
            var item = $columnsConf[i+1];
            //console.dir(item);
            if( typeof item.visible !== 'undefined' && item.visible === false ) {
                $(this).html('');
            } else {
                if( typeof item.filterable !== 'undefined' && item.filterable === true ) {
                    tag = '<select id="filter-' + item.data + '" class="col-filter select-filter"><option value="">Todos</option></select>';
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
        
        // BUTTONS       
        $('#btn-edit').on("click", function()  {
            var rowdata = $myTable.rows('.selected').data()[0];
            var url = "{{ route('documents.control.documento.edit', ':hash') }}";
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_edit') }}");
            } else { 
                isoSetStorage('iso_statusFilter', $("#filter-status").val());              
                //console.log('ID: '+rowdata.user_id);                
                url = url.replace(':hash', rowdata.hash);
                location.href = url;                
            }
        }); // btn-edit
        
        $('#btn-send').on("click", function()  {
            var rowdata = $myTable.rows('.selected').data()[0];
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_send') }}");
            } else {
                // Storage
                var info = $myTable.page.info();
                var order = $myTable.order();             
                isoSetStorage('iso_returnUrl', isoGetCurrentURL());
                isoSetStorage('iso_returnPage', info.page);
                isoSetStorage('iso_returnCol', order[0][0]);
                isoSetStorage('iso_returnDir', order[0][1]);

                isoSetStorage('iso_statusFilter', $("#filter-status").val());
                
                if( rowdata.control == 'edit' ) {
                    location.href = "/documentos/control/gestion/editar/admin/"+rowdata.hash;
                } else if( rowdata.control == 'show' ) {
                    isoSetStorage('iso_returnUrl', isoGetCurrentURL());
                    location.href = "/documentos/master/publicado/"+rowdata.hash;
                }                                
            }
        }); // btn-send        

        $('#btn-refresh').on("click", function() {
            $myTable.state.clear();
            $myTable.search('').columns().search('').draw();
            $(".col-filter").val('');
        }); // btn-refresh

        // Mostrar el documento en html
        $('#btn-view').on("click", function() {
            var rowdata = $myTable.rows('.selected').data()[0];
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_show') }}");
            } else {
                var uri = "{{ route('documents.master.render', ':hash') }}";                 
                // Ref
                uri = uri.replace(':hash', rowdata.hash);
                location.href = uri;                                 
            }
        }); // btn-view 
        
        $('input.deletable').wrap('<span class="deleteicon"></span>').after($('<span>x</span>').click(function() {
            $(this).prev('input').val('').trigger('change').focus();
        }));          

        // SUBMENU        
        $("#btn-download").on("click", function() {
            $myTable.button('.buttons-excel').trigger();
        });
        
        $("#btn-print").on("click", function() {
            $myTable.button('.buttons-pdf').trigger();
        });

        $("#btn-colvis").on("click", function() {
            $myTable.button('.buttons-colvis').trigger();
        });

        // SELECT
        $myTable.on('click', 'tbody tr', function () {
            data = $myTable.row(this).data();
            $selectedRow = ($selectedRow) ? false : true;
            if( data.document_id != $beforeRow ) {
                $selectedRow = true;
            } // if
            $beforeRow = data.document_id;

            if( $selectedRow ) {
                //console.log('Seleccionado '+$beforeRow);
                if( data.filter == 0 ) {
                    // En proceso
                    $('#btn-edit').removeClass('iso-disabled');
                    $('#btn-send').removeClass('iso-disabled'); 
                    $('#btn-sight').addClass('iso-disabled');
                    $('#btn-sheet').removeClass('iso-disabled');                    
                } else {
                    // Publicado
                    $('#btn-sight').removeClass('iso-disabled');
                    $('#btn-view').removeClass('iso-disabled');
                    $('#btn-sheet').removeClass('iso-disabled');
                    $('#btn-edit').addClass('iso-disabled'); 
                } // if/else                
            } else {
                //console.log('NO Seleccionado');
                if( data.filter == 0 ) {
                    $('#btn-edit').addClass('iso-disabled');    
                    $('#btn-send').addClass('iso-disabled');
                    $('#btn-sheet').addClass('iso-disabled');
                } else {
                    $('#btn-sight').addClass('iso-disabled'); 
                    $('#btn-view').addClass('iso-disabled');
                    $('#btn-sheet').addClass('iso-disabled');
                } // if/else
            } // if/else
        });        

        // FILTROS ******
        $("#btn-search").on("click", function() {

            var ss =  $("#status-selected").val();
            var rs =  $("#system-selected").val();
            var ls =  $("#location-selected").val();
            var ts =  $("#type-selected").val();
            var tx = $("#text-input").val();            
            var param = {status: ss, sids: rs, lids: ls, tids: ts, din: $dateInDefault, dout: $dateOutDefault, txt: tx};
            var info = $myTable.page.info(); 
            var url =  route.replace(':slug', JSON.stringify(param))

            console.log('Searching...');
            console.dir(JSON.stringify(param)); 
            
            if( (rs.length > 0) && (ls.length > 0) ) {
                // Ajustes a cambio
                $(".select-filter").html('');
                $(".input-filter").val('');
                $("#loading-image").show();
                $("#btn-filter").removeClass('btn-success').addClass('btn-primary');              
                // Ajax
                $myTable.ajax.url(url).load();
                $myTable.state.clear();
                // Storage
                isoSetStorage('iso_controlStatus', ss); 
                isoSetStorage('iso_controlSystems', rs);
                isoSetStorage('iso_controlLocations', ls);
                isoSetStorage('iso_controlTypes', ts);
                isoSetStorage('iso_controlDatein', $dateInDefault);
                isoSetStorage('iso_controlDateout', $dateOutDefault); //            
                isoSetStorage('iso_controlReturnRows', info.length);
            } else {
                swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Debe seleccionar al menos una opción de todos los selectores"
                });
            }
        });     

        // Efectos Botón
        $('#date-selected, #text-input').on('blur', function() {
            $("#btn-search").removeClass('btn-success').addClass('btn-primary');
        });

        $('#status-selected, #system-selected', '#location-selected', '#type-selected').on('change', function() {
            $("#btn-search").removeClass('btn-success').addClass('btn-primary');
        });        
        
        // TOOLS
        $('#documents-table').on('click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            } else {
                $myTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');                
            } // if selected
        }); // row selects

        // GENERA EL MODAL PARA OBSERVACIONES
        $('body').on('click', '#btn-sight', function (e) {
            e.preventDefault();
            var rowdata = $myTable.rows('.selected').data()[0];
            var route = "{{ route('documents.master.sightings', ':id') }}";
            var status = $("#status-selected").val();
            $('#example').dataTable().fnDestroy();

            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_sight') }}");
            //} else if( rowdata.control == 'show' ) {
            } else if( status != 0 ) {
                route = route.replace(':id', rowdata.document_id);
                var ourTable = $('#example').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: route,
                    columns: [
                        {
                            class: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                        },
                        { data: 'date' },
                        { data: 'name' },
                        { data: 'type' },
                        { data: 'page' },
                        { data: 'section' },
                        { data: 'checked', orderable: false },
                    ],
                    order: [[1, 'desc']],
                    paging: false,
                    info: false,
                    filter: false,
                    initComplete: function () {
                        var $this = this.api();
                        $this.on('draw', function () {
                            detailRows.forEach(function (id, i) {
                                $('#' + id + ' td.dt-control').trigger('click');
                            });
                        });
                        
                        // Array to track the ids of the details displayed rows
                        var detailRows = [];
                        
                        $('#example tbody').on('click', 'tr td.dt-control', function () {
                            var tr = $(this).closest('tr');
                            var row =  $this.row(tr);
                            var idx = detailRows.indexOf(tr.attr('id'));
                        
                            if (row.child.isShown()) {
                                tr.removeClass('details');
                                row.child.hide();
                        
                                // Remove from the 'open' array
                                detailRows.splice(idx, 1);
                            } else {
                                tr.addClass('details');
                                row.child(gridFormatSightings(row.data())).show();
                        
                                // Add to the 'open' array
                                if (idx === -1) {
                                    detailRows.push(tr.attr('id'));
                                }
                            }
                        });
                        
                        $("#modal-sightings-open")[0].click();

                    },
                    language: lang2
                }); // datatable

            } else {
                setSimpleNotification("{{ trans('document/document.grid.row_publish') }}");
            } // if else
            
        }); // #btn-sight
                
        // GENERA EL MODAL DE FICHA TECNICA
        $('body').on('click', '#btn-sheet', function (e) {
            e.preventDefault();
            var rowdata = $myTable.rows('.selected').data()[0];
            var route = "{{ route('documents.master.datasheet', ':hash') }}";

            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('document/document.grid.row_sheet') }}");
            } else { 
                route = route.replace(':hash', rowdata.hash);
                location.href = route;
            }
            
        }); // #btn-sheet        

    }); // document

    function setStorageArray(tag, storaged) {
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
    } // setStorageArray Fx     

    function checkSight(id) {
        var route = "{{ route('documents.master.check', ':id') }}";
        route = route.replace(':id', id);        
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',                
            success: function(json) {
                console.dir(json);
            } // success
        }); // ajax
    } // checkSight

    function gridFormatSightings(d) {
        return d.content;
    }


    //*** */ Revisando

    function setProcesses() {
        var current = $("#filter-process").val();
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
                $("#filter-process").html(output);
            } // success
        });
    } // setProcesses Fx
    
    function setTypes() {
        var current = $("#filter-type").val();
        $.ajax({
            type: 'POST',
            data: {'name':current},
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
                $("#filter-type").html(output);
            } // success
        });
    } // setTypes Fx 
    
    function setUsers() {
        var current = $("#filter-user").val();
        $.ajax({
            type: 'POST',
            data: {'name':current},
            dataType: 'json',
            url: '/documentos/master/listado/usuarios',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                //console.dir(data);
                var output = '<option value="">Todos</option>';
                $.each(data, function(i, value) {
                    output += '<option value="'+value.name+'"';
                    output += (value.selected) ? ' selected' : '';
                    output += '>'+value.name+'</option>';
                });
                $("#filter-user").html(output);
            } // success
        });
    } // setTypes Fx     
    
    function setStatus() {
        var statusVal = $("#status-selected").val();
        var output = '<option value="">Todos</option>';
        if(statusVal == 0) {
            var statusFilter = isoGetStorage('iso_controlStatus');
            var statusArray = ['Nuevo','En edición','En revisión','En aprobación','En Publicación'];
            $.each(statusArray, function(i, key) {
                output += '<option value="'+key+'"';
                output += ( statusFilter == key ) ? ' selected' : '';
                output += '>'+key+'</option>';
            }); 
        }
        $("#filter-status").html(output);
    } // statusFilter Fx  


</script>

@include('components.notification_index')

@endpush

</x-icewall> 