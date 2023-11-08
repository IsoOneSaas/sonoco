<!-- resources/views/settings/user.index.blade.php -->
<x-icewall>

    <x-slot:title>
            Usuario - Administrar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Listado de Usuarios                            
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('usuarios.create') }}"><i data-lucide="plus" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-edit"><i data-lucide="edit" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-delete"><i data-lucide="trash" class="w-5 h-5"></i></a>
                            <form method="POST" id="form-delete" action="">
                                @method('DELETE')
                                @csrf                                
                            </form>
                            @can('admin_master')
                            <!-- <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-sheet"><i data-lucide="user-check" class="w-5 h-5"></i></a> -->
                            @endcan
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
                        <div id="striped-rows-table" class="p-5">
                            <div class="preview">
                                <div id="grid-filters" class="flex justify-end mb-1">
                                    <div>
                                        <label for="tag">Estado:&nbsp;</label>
                                        <select id="status-selected" name="status_selected" class="pr-1.5 py-0.5 outline-offset-2 border-slate-400 rounded">
                                            @foreach($gridSelect as $key => $value)
                                            <option value={{ $key }} >{{ $value }}</option>
                                            @endforeach
                                        </select>                                
                                    </div>
                                </div>
                                <div id="grid-table">                                   
                                    <table id="users-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Nombre</th>
                                                <th>Correo Electrónico</th>
                                                <th >Cargo</th>
                                                <th>Rol</th>
                                                <th>Estado</th>
                                                <th>H</th>
                                                <th>A</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>                                       
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->
                    
                </div>
                <!-- END: Content -->

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

<script type="text/javascript">
    $(function () {
        let columnsDef = {!! $gridColDef !!};
        let colFilter = {!! $gridColFil !!};
        let order = [[{{ $gridColOrd }}, 'asc']];
        let lang = {!! $gridLanguage !!};
        let columns = {!! $gridColExp !!};        
        let route = "{{ route('usuarios.show', ':id') }}";
        let param = 0; // provisional
        
        // Acondicionamiento
        route = route.replace(':id', param);
        setFooter('users-table', columnsDef);

        // Datatable
        let myTable = new DataTable("#users-table", {
            processing: true,
            serverSide: true,
            ajax: route,
            order: order,
            columns: columnsDef,
            language: lang,          
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
                },
                {
                    extend: 'colvis',
                    columns: columns
                }
            ],
            columnDefs: [
                {"targets": [3,5,6], "className": "dt-nowrap"}
            ],
            initComplete: function () {
                var $this = this.api();

                // Generar filtros de columnas
                setFilters($this, columnsDef);

                // Filtro inicial (select)
                $this .column(colFilter).search('1').draw();
            }
        });

        // Buttons        
        $('#btn-edit').on("click", function()  {
            var rowdata = myTable.rows('.selected').data()[0];
            var url = "{{ route('usuarios.edit', ':id') }}";
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('user.grid.row_edit') }}");
            } else {
                //console.log('ID: '+rowdata.user_id);                
                url = url.replace(':id', rowdata.hash);
                location.href = url;                
            }
        }); // btn-edit

        $('#btn-delete').on("click", function()  {
            var rowdata = myTable.rows('.selected').data()[0];
            var action = "{{ route('usuarios.destroy', ':id') }}";
            var form = $("#form-delete");            
            if (rowdata === undefined || rowdata === null) {
                setSimpleNotification("{{ trans('user.grid.row_delete') }}");
            } else {              
                action = action.replace(':id', rowdata.hash);
                form.attr('action', action);
                swal({
                    title: "{{ trans('user.delete.title') }}"+rowdata.name+"?",
                    text: "{{ trans('user.delete.text') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });                
            }
        }); // btn-delete        

        $('#btn-refresh').on("click", function() {
            myTable.search('').columns().draw();
        }); // btn-refresh
        
        $("#btn-download").on("click", function() {
            myTable.button('.buttons-excel').trigger();
        });
        
        $("#btn-print").on("click", function() {
            myTable.button('.buttons-pdf').trigger();
        });

        $("#btn-colvis").on("click", function() {
            myTable.button('.buttons-colvis').trigger();
        });

        // Selects
        $('#status-selected').on('change', function () {
            if( this.value == 9 ) {
                myTable.column(colFilter).search('0|1', true, false).draw();                    
            } else {
                myTable.column(colFilter).search(this.value).draw();
            }                 
        });
        
        // Tools
        $('#users-table').on('click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            } else {
                myTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');                
            } // if selected
        }); // row selects
                
    }); // document

    

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

@endpush

</x-icewall> 


