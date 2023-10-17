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
                                    
                                    <div id="horizontal-form" class="pb-3">
                                        <div class="preview ml-auto w-3/4">
                                            <div class="form-inline">
                                                <label for="text-search" class="form-label sm:w-20 text-right">
                                                    <input type="radio" name="radio-search" value=2> Código&nbsp;&nbsp;
                                                    <input type="radio" name="radio-search" value=3 checked> Nombre
                                                </label>
                                                <input id="text-search" type="text" class="form-control pt-0 pb-0 mt-2 border-slate-500" aria-label="Texto" />
                                                <label for="time-selected" class="form-label sm:w-20 text-right pt-3">Periodo:</label>
                                                <select id="time-selected" class="form-control form-select-sm mt-2 border-slate-500" aria-label="Periodo">
                                                    <option value='week'>Última semana</option>
                                                    <option value='month'>Último mes</option>
                                                    <option value='semester'>Último semestre</option>
                                                    <option value="">Todos</option>
                                                </select>
                                                <label for="status-selected" class="form-label sm:w-20 text-right pt-3">Estado:</label>
                                                <select id="status-selected" class="form-control form-select-sm mt-2 border-slate-500" aria-label="Estado">
                                                    <option value=0>En proceso</option>
                                                    <option value=1>Publicados</option>
                                                    <option value=9>Obsoletos</option>
                                                    <option value=2>Cancelados</option>
                                                    <option value=3>Eliminados</option>
                                                    <option value=4>Desestimado</option>
                                                </select>
                                                <button id="btn-search" class="btn btn-primary shadow-md ml-3"><i data-lucide="search" class="w-4 h-4"></i></button>
                                            </div>                                                                                                                                     
                                        </div>
                                    </div>                                                           
                                 
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
                                        </thead>                                     
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
<script src="{{ url('assets/js/iso_scripts.js') }}"></script>

<script document="text/javascript">
    let $urlContent = '{{ $urlContent }}';
    let $myTable;
    let $beforeRow = 0;
    let $selectedRow = false;

    $(function () {
    
        let columnsConf = {!! $gridColDef !!};
        let col = {{ $gridColOrd }};
        let lang1 = {!! $gridLanguage !!};
        let lang2 = {!! $modalLanguage !!};
        let columns = {!! $gridColExp !!};        
        let route = "{{ route('documents.control.documento.show', ':slug') }}";
        let param = [];
        let filterColumn = 13;
        //let initPage = 1;
        //let initOrder =  [[ col, 'desc']]; 
        console.dir(lang1);
        
        // ACONDICIONAMIENTO        
        var sCol = isoGetStorage('control_returnCol');
        var sDir = isoGetStorage('control_returnDir');        
        var initPage = ( isoGetStorage('control_returnPage') === null ) ? 1 : isoGetStorage('control_returnPage'); 
        var initOrder = ( sCol === null ) ? [[ col, 'desc']] : [[ sCol, sDir]];
        
        var timeSelected = isoGetStorage('iso_selectTime');
        var currentTimeSelected = ( timeSelected === null) ? $("#time-selected").val() : timeSelected;
        var statusSelected = isoGetStorage('iso_selectStatus');
        var currentStatusSelected = ( (statusSelected === null) || (statusSelected === '')  ) ? $("#status-selected").val() : statusSelected; 
        var textSearch = isoGetStorage('iso_searchText');
        var currentSearchText = ( textSearch === null) ? $("#text-search").val() : textSearch;
        var radioSearch = isoGetStorage('iso_searchRadio');
        var currentSearchRadio = ( radioSearch === null) ? $("input[name='radio-search']:checked").val() : radioSearch;
        
        // PARAMETROS
        //console.log('timeSelected: '+timeSelected);        
        setFooter('documents-table', columnsConf);
        param = {time: currentTimeSelected, status: currentStatusSelected};
        //console.dir(JSON.stringify(param));   

        // FILTROS GENERALES
        setProcesses();
        setTypes();        
        setUsers();
        
        $('#time-selected option[value="'+timeSelected+'"]').prop('selected', 'selected');
        $('#status-selected option[value='+statusSelected+']').prop('selected', 'selected');
        $('#text-search').val(currentSearchText);
        $("input[name='radio-search']").filter("[value="+currentSearchRadio+"]").prop('checked', true);
                
        // DATATABLE
        var startTime = Date.now();
        console.log('Datatables init starts now: ', Date.now() - startTime);
        $myTable = $('#documents-table')
        .on('preXhr.dt', function () {

            console.log('Send ajax request ', Date.now() - startTime + ' milliseconds.');
        })
        .on('xhr.dt', function () {
            console.log('Received ajax response ', Date.now() - startTime + ' milliseconds.');
        })
        .DataTable({
            dom: 'lrtip',
            bProcessing: true,
            sAjaxSource: route.replace(':slug', JSON.stringify(param)),
            aoColumns: columnsConf,
            retrieve: true,
            pageLength: 10,
            order: initOrder,
            page: initPage,
            //filter: false,
            orderClasses: false,
            responsive: true,
            stateSave: true,
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
            initComplete: function() {
                console.log('DT init complete in ', Date.now() - startTime + ' milliseconds.');
                console.log('Total Rows: ' + this.api().data().count());
                // Filtros de input en eel footer
                this.api().columns().every( function (i) {
                    var column = this;
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( column.search() !== this.value ) {
                            column.search( this.value ).draw();
                        }
                    });   
                });

                // Filtro de estado
                setStatus();                

                // Filtro inicial
                //this.api().column(filterColumn).search(0).draw(); // hacia filtrado inicial
            },                                    
            language: lang1               
        }); // datatables
        
        
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

        // FILTROS
        $("#btn-search").on("click", function() {
            var ts =  $("#time-selected").val();
            var ss =  $("#status-selected").val();
            var tx =  $('#text-search').val();
            var rs = $("input[name='radio-search']:checked").val();
            //console.log('>', ts, ss, tx, rs);
            param = {time: ts, status: ss};
            var url =  route.replace(':slug', JSON.stringify(param))
            startTime = Date.now();
            //console.dir(JSON.stringify(param)); 

            $myTable.ajax.url(url).load();
            $myTable.state.clear();
            if( rs == 2 ) {
                $myTable.column(2).search(tx);
                $myTable.column(3).search('').draw();
            } else {
                $myTable.column(3).search(tx);
                $myTable.column(2).search('').draw();
            }
            

            setStatus();
            isoSetStorage('iso_selectTime', ts);
            isoSetStorage('iso_selectStatus', ss); 
            isoSetStorage('iso_searchText', tx);
            isoSetStorage('iso_searchRadio', rs);
        });



        $('#filter-process').on('change', function(){
            $myTable.column(5).search(this.value).draw();   
        }); // filter-process

        $('#filter-type').on('change', function(){
            $myTable.column(6).search(this.value).draw();   
        }); // filter-type
        
        $('#filter-user').on('change', function(){
            $myTable.column(7).search(this.value).draw();   
        }); // filter-user
        
        $('#filter-status').on('change', function(){
            $myTable.column(9).search(this.value).draw();   
        }); // filter-status          
        
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
            var statusFilter = isoGetStorage('iso_statusFilter');
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