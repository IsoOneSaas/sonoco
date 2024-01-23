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
                                    <th class="all">Fecha</th>
                                    <th class="all">Código</th>
                                    <th class="all">Tipo</th>                                              
                                    <th class="all">Nombre</th>
                                    <th class="all">Usuario</th>
                                    <th class="all">Página</th>
                                    <th class="all">Sección</th>
                                    <th class="none">Comentario: </th>
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
        var scope = $("#scope-selected").val();
        var route = "{{ route('documents.control.observacion.show', ':slug') }}";

        $('#sightings-table thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#sightings-table thead');        

        $myTable = $('#sightings-table').DataTable({
            ajax: route.replace(':slug', scope),
            columns: [
                { data: 'empty', orderable: false },
                { data: 'date', class: 'no-wrap' },
                { data: 'code', class: 'no-wrap' },
                { data: 'type' },
                { data: 'name' },
                { data: 'user' },
                { data: 'page', class: 'dt-center', width: '30px' },
                { data: 'section' },
                //{ data: 'control', class: 'dt-center', width: '50px', orderable: false },
                { data: 'content', orderable: false },
                //{ data: null, class: 'dt-center', orderable: false, defaultContent: '<button><img alt="Ver" class="rounded-full" src="/assets/images/viewmag.png"></button>' },
            ],           
            order: [[1, 'desc']],
            columDefs: [{targets: 0, className: 'text-nowrap'}],
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
                        columns: [1,2,3,4,5,6,7,8]
                    }
                }
            ],
            initComplete: function () {
            var api = this.api();
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {

                    
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input type="text" style="padding:0; padding-left:1em;width:auto" placeholder="' + title + '" />');
 
                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; 
 
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();
 
                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
        },            
        }); // datatable

        // Array to track the ids of the details displayed rows
        const detailRows = [];
        
/*         $myTable.on('click', 'tbody td.dt-control', function () {
            let tr = event.target.closest('tr');
            let row = $myTable.row(tr);
            let idx = detailRows.indexOf(tr.id);
        
            if (row.child.isShown()) {
                tr.classList.remove('details');
                row.child.hide();
        
                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            }
            else {
                tr.classList.add('details');
                row.child(gridFormatSighting(row.data())).show();
        
                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.id);
                }
            }
        }); */
        
        // On each draw, loop over the `detailRows` array and show any child rows
/*         $myTable.on('draw', () => {
            detailRows.forEach((id, i) => {
                let el = document.querySelector('#' + id + ' td.dt-control');
        
                if (el) {
                    el.dispatchEvent(new Event('click', { bubbles: true }));
                }
            });
        }); */
        
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