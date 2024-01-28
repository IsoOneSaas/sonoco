<!-- resources/views/document/control.followup.blade.php -->
<x-icewall>

    <x-slot:title>
            Seguimiento a la gestión
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Gestión Documental</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                        <img id="loading-image" alt="Cargando..." class="h-12 inline-flex mr-20" src="{{ url('/assets/images/loading_small.gif') }}">Listado de usuarios sin gestión documental
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <div class="dropdown ml-auto sm:ml-0">
                                <button id="btn-send"  class="btn btn-secondary shadow-md mr-2" title="Enviar Mensaje"> <i data-lucide="send" class="w-5 h-5"></i> </button>
                                <button id="btn-modal-send"  class="btn btn-primary shadow-md mr-2" title="Enviar Mensaje"> <i data-lucide="send" class="w-5 h-5"></i> </button>
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

<!--                         <div id="horizontal-form" class="pb-3">
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
                        </div>  -->                       

                        <table id="followup-table" class="display responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Usuario Responsable</th>
                                    <th># Documentos Vencidos</th>
                                    <th># Días Vencido (máximo)</th>                                              
                                    <th></th>
                                </tr>
                                <tr>
                                    <th class="th-filter">No</th>
                                    <th class="th-filter">Usuario Responsable</th>
                                    <th class="th-filter"># Documentos Vencidos</th>
                                    <th class="th-filter"># Días Vencido (máximo)</th>                                              
                                    <th></th>
                                </tr>                                
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                    <!-- END: HTML Table Data -->

                    <!-- BEGIN: Send Modal Content -->
                    <div id="modal-due" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-due-title" class="font-medium text-base mr-auto">Envío de solicitudes de gestión</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <form id="due-form" action="" method="POST">
                                        @csrf
                                        <input type="hidden" id="status" value="">
                                        <div class="input-group  w-1/3">
                                            <div id="comment" class="input-group-text flex"><i data-lucide="{{ trans('document/followup.form.comment.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/followup.form.comment.title') }}</div>
                                            <textarea name="comment" class="form-control" aria-describedby="comment" placeholder="{{ trans('document/followup.form.comment.placeholder') }}" rows="3"  required>{{ old('comment') ?? '' }}</textarea>
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/followup.form.comment.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                    </form>
                                    <br>
                                    <table id="users-table" class="table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Nombre del Usuario</th>
                                                <th>Cargo</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>                                                                            
                                    </table>                       
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-due-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-due-ok" type="button" class="btn btn-primary w-20">Enviar</button>
                                    <a id="modal-due-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-due" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Send Modal Content -->  


                </div>
                <!-- END: Content -->

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Buttons-2.3.6/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Select-1.6.2/css/select.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />

@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script>
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

        $('body').on('click', '#btn-modal-send', function (e) {
            e.preventDefault();
            var jsonObj = [];
            var arrStr = [];
            var route = "{{ route('documents.control.follow.get', ':slug') }}";
            var count = $myTable.rows( { selected: true } ).count();            
            if( count > 0 ) {
                //alert('Open Modal');
                // Generar Modal
                var selected = $myTable.rows( { selected: true } ).data();
                $.each(selected, function(i, value) {    
                    console.dir(value);                
                    jsonObj.push(value.uid);
                });
                arrStr = encodeURIComponent(JSON.stringify(jsonObj));                
                $.ajax({
                    url: route.replace(':slug', arrStr),
                    type: 'GET',
                    dataType: 'json',                
                    success: function(data) {
                        console.dir(data);                        
                        if( data.success) {
                            $("textarea[name='comment']").val(data.message);
                            // Generar tabla
                            var output = '<tbody>';
                            var id = 0;
                            // $.each(data.list, function(uid, row) {
                            //     if( uid != id ) {
                            //         output += '<tr><th colspan="4">'+uid+'</th></tr>';
                            //         id = uid;
                            //     }
                            //     output += '<tr><td>'+row.name+'</td><td>'+row.code+'</td><td>'+row.due+'</td><td>'+row.status+'</td></tr>';
                            // });
                            $.each(data.list, function(uid, rows) {
                                output += '<tr><th colspan="4">'+uid+'</th></tr>';
                                $.each(rows, function(i, row) {
                                    output += '<tr><td>'+row.name+'</td><td>'+row.code+'</td><td>'+row.due+'</td><td>'+row.status+'</td></tr>';
                                });
                            });
                            output += '</tbody>';
                            $("table#users-table thead").append(output);
                            $("#modal-due-open")[0].click();
                        } else {
                            setSuccessNotification('error', 'Oops!', data.message+' [error: '+data.error+']');
                        }

                    } // success
                }); // ajax 
                
            } else {
                swal({
                    icon: "error",
                    title: "Oops...",
                    text: "{{ trans('document/followup.send.empty') }}"
                });                 
            }                
        }); // btn-modal-send            

        $('#btn-send').on("click", function(e) {
            e.preventDefault();
            //alert('Sending...');
            var jsonObj = [];
            var count = $myTable.rows( { selected: true } ).count();            
            if( count > 0 ) {
                var selected = $myTable.rows( { selected: true } ).data();
                $.each(selected, function(i, value) {    
                    console.dir(value);                
                    jsonObj.push(value.uid);
                });
                var arrStr = encodeURIComponent(JSON.stringify(jsonObj));
                swal({
                    title: "{{ trans('document/followup.send.title') }}",
                    text: "{{ trans('document/followup.send.text') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var route = "{{ route('documents.control.follow.send', ':slug') }}";
                        console.log('Enviando con '+ route.replace(':slug', arrStr));
                        $("#loading-image").show();
                        $.ajax({
                            url: route.replace(':slug', arrStr),
                            type: 'GET',
                            dataType: 'json',                
                            success: function(json) {
                                console.dir(json);
                                $("#loading-image").hide();
                                if( json.success) {
                                    setSuccessNotification('success', '', json.message);
                                } else {
                                    setSuccessNotification('error', 'Oops!', json.message+' [error: '+json.error+']');
                                }

                            } // success
                        }); // ajax   
                    } // if
                })                                                    

                //console.log('JSON:');
                //console.dir(jsonObj);
            } else {
                swal({
                    icon: "error",
                    title: "Oops...",
                    text: "{{ trans('document/followup.send.empty') }}"
                });                 
            }

        }); // #btn-send         

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
        //var scope = $("#scope-selected").val();
        var scope = 0; // alternativo por si se habilita algún filtro inicial
        var route = "{{ route('documents.control.seguimiento.show', ':slug') }}";

        $("#loading-image").show();

        $myTable = $('#followup-table').DataTable({
            ajax: route.replace(':slug', scope),
            columns: columnsDef,
            order: [[col, 'asc']],
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
            select: {
                blurable: true,
                style: 'multi'
            },                         
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
                $("#loading-image").hide();
            }            
        }); // datatable

        // Filtros : generación
        $('#followup-table thead tr:eq(1) th').each( function (i) {
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

    } // setTable

    function gridFormatFollowup(d) {     
        return '<table class=""display compact responsive" width="100%"><tr><th>Fecha</th><th>Usuario</th><th>Tipo</th><th>Página</th><th>Sección</th><th>Contenido</th></tr>'+d.sights+'</table>';
    } // gridFormatFollowup
    
    function checkFollowup(id) {
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
    } // checkFollowup




        

</script>


@include('components.notification_index')

@endpush

</x-icewall> 