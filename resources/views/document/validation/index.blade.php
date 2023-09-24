<!-- resources/views/document/type.index.blade.php -->
<x-icewall>

    <x-slot:title>
            Validación - Administrar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Edición de la validez de documentos
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="validation-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>

                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-print" href="javascript:;" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Imprimir tabla </a>
                                        </li>
                                      
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="validation-form" action="{{ route('documents.settings.validez.store') }}" method="POST">
                                @csrf 
                                <div class="input-group mt-3">
                                    <div id="default" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/validation.form.default_value.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/validation.form.default_value.title') }}</div>
                                    <input type="number"  name="default_value" value="{{ old('default_value', isset($data) ? $data['default']['value'] : 1) }}" class="form-control  w-full" aria-describedby="default_value" placeholder="{{ trans('document/validation.form.default_value.placeholder') }}" min="1" step="1" required>

                                    <select name="default_text" class="form-control ml-2" required>
                                        <option value=''>{{ trans('document/validation.form.default_text.placeholder') }}</option>
                                        @foreach( trans('document/validation.select.period') as $key => $value )
                                        <option value="{{ $key }}" {{ old('default_text', isset($data) ? $data['default']['text'] : 0 ) == $key ? 'selected ' : ''}}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div id="input-group-10" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/validation.form.default_value.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="lapse" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/validation.form.lapse_value.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/validation.form.lapse_value.title') }}</div>
                                    <input type="number"  name="lapse_value" value="{{ old('lapse_value', isset($data) ? $data['lapse']['value'] : 1) }}" class="form-control  w-full" aria-describedby="lapse_value" placeholder="{{ trans('document/validation.form.lapse_value.placeholder') }}" min="1" step="1" required>

                                    <select name="lapse_text" class="form-control ml-2" required>
                                        <option value=''>{{ trans('document/validation.form.lapse_text.placeholder') }}</option>
                                        @foreach( trans('document/validation.select.lapse') as $key => $value )
                                        <option value="{{ $key }}" {{ old('lapse_text', isset($data) ? $data['lapse']['text'] : 0 ) == $key ? 'selected ' : ''}}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div id="input-group-20" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/validation.form.lapse_value.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div> 
                                <div class="input-group mt-3">
                                    <div id="lapse" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/validation.form.alert.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/validation.form.alert.title') }}</div>
                                    <select name="alert" class="form-control ml-2" required>
                                        <option value=''>{{ trans('document/validation.form.alert.placeholder') }}</option>
                                        @foreach( trans('document/validation.select.alert') as $key => $value )
                                        <option value="{{ $key }}" {{ old('alert', isset($data) ? $data['alert'] : 0 ) == $key ? 'selected ' : ''}}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div id="input-group-30" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/validation.form.alert.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="message" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/validation.form.message.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/validation.form.message.title') }}</div>
                                    <textarea  class="form-control" name="message" aria-describedby="message" placeholder="{{ trans('document/validation.form.message.placeholder') }}" minlength="8" maxlength="255" required>{{ old('message', isset($data) ? $data['message'] : config('settings.document_settings_default.validity.message') ) }}</textarea>
                                    <div id="input-group-40" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/validation.form.message.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="type" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/validation.form.type.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/validation.form.type.title') }}</div>                                

                                    <select multiple id="type-ids" name="type_ids[]" class="form-control ml-2" size="3" required>
                                        <option value=''>{{ trans('document/validation.form.type.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-type" class="btn btn-primary shadow-md mr-2" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>
                                    <input type="number"  name="type_value" value="{{ old('type_value') }}" class="form-control  w-full" aria-describedby="type_value" placeholder="{{ trans('document/validation.form.type_value.placeholder') }}" min="1" step="1" required>
                                    <select name="type_text" class="form-control ml-2" required>
                                        <option value=''>{{ trans('document/validation.form.type_text.placeholder') }}</option>
                                        @foreach( trans('document/validation.select.period') as $key => $value )
                                        <option value="{{ $key }}" {{ old('type_text') == $key ? 'selected ' : ''}}>{{ $value }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-10" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/validation.form.type.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="document" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/validation.form.document.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/validation.form.document.title') }}</div>                                

                                    <select multiple id="document-ids" name="document_ids[]" class="form-control ml-2" size="3" required>
                                        <option value=''>{{ trans('document/validation.form.document.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-document" class="btn btn-primary shadow-md mr-2" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>
                                    <input type="number"  name="document_value" value="{{ old('document_value') }}" class="form-control  w-full" aria-describedby="document_value" placeholder="{{ trans('document/validation.form.document_value.placeholder') }}" min="1" step="1" required>
                                    <select name="document_text" class="form-control ml-2" required>
                                        <option value=''>{{ trans('document/validation.form.document_text.placeholder') }}</option>
                                        @foreach( trans('document/validation.select.period') as $key => $value )
                                        <option value="{{ $key }}" {{ old('document_text') == $key ? 'selected ' : ''}}>{{ $value }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-10" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/validation.form.document.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                                                                                                                                                   
                            </form>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->

                    <!-- BEGIN: Modal Type -->
                    <div id="modal-type" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-type-title" class="font-medium text-base mr-auto">Selección de tipos de documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                        <table id="types-table" class="table table-bordered nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Nombre del tipo</th>
                                                    <th>Vigencia</th>
                                                    <th>S</th>
                                                </tr>
                                            </thead>  
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th><input type="text" class="col-filter" placeholder="Nombre" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Vigencia" /></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>                                                                              
                                        </table>                       

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-type-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-type-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-type-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-type" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Type -->

                    <!-- BEGIN: Modal Document -->
                    <div id="modal-document" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-document-title" class="font-medium text-base mr-auto">Selección de documentos</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                        <table id="documents-table" class="table table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Código</th>
                                                    <th>Nombre</th>
                                                    <th>Proceso</th>
                                                    <th>Tipo</th>
                                                    <th>Vigencia</th>
                                                    <th>Estado</th>
                                                    <th>S</th>
                                                </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th><input type="text" class="col-filter" placeholder="Código" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Nombre" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Proceso" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Tipo" /></th>                                                    
                                                    <th><input type="text" class="col-filter" placeholder="Vigencia" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Estado" /></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>                                                                              
                                        </table>                       

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-document-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-document-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-document-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-document" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Document -->                    

                </div>
                <!-- END: Content -->

@push('meta')                
<meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Buttons-2.3.6/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Select-1.6.2/css/select.dataTables.min.css') }}" />
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
<script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script>
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />

<script type="text/javascript">
    var $typeTable, $documentTable;
    $(function () {

        $("body").addClass("loading");
        // MODAL POR TIPO
        
        // Genera el modal y tabla para los tipos
        $('body').on('click', '#btn-modal-type', function (e) {
            e.preventDefault();
            var lang = {!! $gridTypesLanguage !!};
            var tids = $("#type-ids").val();

            // Generar la tabla
            $.ajax({
                type: 'POST',
                data: {'tids':tids},
                dataType: 'json',
                url: '/documentos/ajustes/validez/tipos',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(json) {  
                    if( json.success) {
                        $('#types-table').dataTable().fnDestroy();
                        dataSet = $.parseJSON(json.grid);
                        console.dir(dataSet);
                        var selects = [2];
                        var inputs = [1];
                        
                        $typeTable = new DataTable("#types-table", {
                            data: dataSet,                      
                            order: [[ 1, 'asc' ]],
                            select: {
                                blurable: true,
                                style: 'multi'
                            },                        
                            //stateSave: true,
                            initComplete: function () {
                                var $this = this.api();
                                // Fitros
                                setBottomFilter($this, selects, inputs);                            
                                // columna invisible
                                $this.columns( [0,3] ).visible( false );
                                // Modal
                                $("#modal-type-open")[0].click();
                            },
                            language: lang                      
                        }); // datatable

                        $typeTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                            var data = this.data();
                            if (data[3] == 1) {
                                this.select();                            
                            }                        
                        });
                        
                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/validation.grid.type.error_fatal') }}"); 
                    }
                } // success
            }); // ajax            
            

        }); // btn-modal-type

        // Boton de confirmación para tipos
        $('body').on('click', '#btn-type-ok', function (e) {
            e.preventDefault();
            var output = '';
            var count = $typeTable.rows( { selected: true } ).count();
            if( count > 0 ) {
                var selected = $typeTable.rows( { selected: true } ).data();
                $.each(selected, function(i, value) {
                    // Construir select
                    output += '<option value='+value[0]+' selected >'+value[1]+'</option>';
                });
                $("#type-ids").html(output);
                //$('#type-ids option').prop('selected', true);
                //$('#type-ids option').attr('selected', 'selected');
                $("#btn-type-ko").click();
            }
            else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/validation.grid.type.error_no-selected') }}");
            }             
        }); // btn-type-ok 
        
        // MODAL POR DOCUMENTO

        // Genera el modal y tabla para los documentos
        $('body').on('click', '#btn-modal-document', function (e) {
            e.preventDefault();
            var lang = {!! $gridDocumentsLanguage !!};
            var dids = $("#document-ids").val();

            // Modal
            $("#modal-document-open")[0].click();            

            // Generar la tabla
            $.ajax({
                type: 'POST',
                data: {'dids':dids},
                dataType: 'json',
                url: '/documentos/ajustes/validez/documentos',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(json) {  
                    if( json.success) {
                        //$('#document-table').dataTable().fnDestroy();
                        dataSet = $.parseJSON(json.grid);
                        console.dir(dataSet);
                        var selects = [3,4];
                        var inputs = [1,2,5,6];
                        
                        $documentTable = new DataTable("#documents-table", {
                            data: dataSet,                      
                            order: [[1, 'asc' ]],
                            select: {
                                blurable: true,
                                style: 'multi'
                            },
                            processing: true,                        
                            //stateSave: true,
                            initComplete: function () {
                                var $this = this.api();
                                // Fitros
                                setBottomFilter($this, selects, inputs);                            
                                // columna invisible
                                $this.columns( [0,7] ).visible( false );
                            },
                            language: lang                      
                        }); // datatable

                        $documentTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                            var data = this.data();
                            if (data[3] == 1) {
                                this.select();                            
                            }                        
                        });

                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/validation.grid.document.error_fatal') }}"); 
                    }
                } // success
            }); // ajax            
            

        }); // btn-modal-document
        

        // Boton de confirmación para documentos
        $('body').on('click', '#btn-document-ok', function (e) {
            e.preventDefault();
            var output = '';
            var count = $documentTable.rows( { selected: true } ).count();
            if( count > 0 ) {
                var selected = $documentTable.rows( { selected: true } ).data();
                $.each(selected, function(i, value) {
                    // Construir select
                    output += '<option value='+value[0]+' selected >'+value[1]+'</option>';
                });
                $("#document-ids").html(output);
                $("#btn-document-ko").click();
            }
            else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/validation.grid.document.error_no-selected') }}");
            }             
        }); // btn-document-ok         
                
    }); // document

    

</script>

    @include('components.notification_index')

@endpush

</x-icewall> 