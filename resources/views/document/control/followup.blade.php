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
                            Listado de usuarios sin gestión documental
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <div class="dropdown ml-auto sm:ml-0">
                                <button id="btn-send"  class="btn btn-primary shadow-md mr-2" title="Enviar Mensaje"> <i data-lucide="send" class="w-5 h-5"></i> </button>
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

                        <table id="followup-table" class="display responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Usuario Responsable</th>
                                    <th># Documentos Vencidos</th>
                                    <th># Días Vencido (máximo)</th>                                              
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th class="th-filter">No</th>
                                    <th class="th-filter">Usuario Responsable</th>
                                    <th class="th-filter"># Documentos Vencidos</th>
                                    <th class="th-filter"># Días Vencido (máximo)</th>                                              
                                    <th class="th-filter"></th>
                                    <th></th>
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
                console.log('JSON:');
                console.dir(jsonObj);
            } else {
                swal({
                    icon: "error",
                    title: "Oops...",
                    text: "{{ trans('document/followup.send.empty') }}"
                });                 
            }




            let array = []; 
            $("input:checkbox[name=mail]:checked").each(function() { 
                array.push($(this).val()); 
            });
            
            if(array.length) {
                console.dir(array);
                var arrStr = encodeURIComponent(JSON.stringify(array));
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
                        $.ajax({
                            url: route.replace(':slug', arrStr),
                            type: 'GET',
                            dataType: 'json',                
                            success: function(json) {
                                console.dir(json);
                                if( json.success) {
                                    setSuccessNotification('success', '', json.message);
                                } else {
                                    setSuccessNotification('error', 'Oops!', json.message+' [error: '+json.error+']');
                                }

                            } // success
                        }); // ajax   
                    } // if
                }); 

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
        var scope = $("#scope-selected").val();
        var route = "{{ route('documents.control.seguimiento.show', ':slug') }}";

 

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