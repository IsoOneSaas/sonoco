<!-- resources/views/document/authorization.index.blade.php -->
<x-icewall>

    <x-slot:title>
       Authorizaciones - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Edición de autorizaciones
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-refresh"><i data-lucide="refresh-ccw" class="w-5 h-5"></i></a>
                            <button type="submit" form="authorization-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>

                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-show-documents" href="javascript:;" class="dropdown-item"> <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Documentos </a>
                                        </li>                                        
                                        <li>
                                            <a id="btn-show-users" href="javascript:;" class="dropdown-item"> <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Usuarios </a>
                                        </li>                                       
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="authorization-form"  action="{{ route('documents.settings.autorizaciones.store') }}" method="POST">
                                @csrf 
                                <input type="hidden" id="json-docs" value="">
                                <input type="hidden" id="json-users" value="">

                                <div class="input-group mt-3">
                                    <div id="document" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/authorization.form.document.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/authorization.form.document.title') }}</div>                                
                                    <select multiple id="document-ids" name="document_ids[]" class="form-control ml-2" size="3" required>
                                        <option value=''>{{ trans('document/authorization.form.document.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-document" class="btn btn-primary shadow-md" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>                                    
                                    <div id="input-group-10" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/authorization.form.document.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="flex justify-center mt-2">
                                    <div class="form-check form-switch inline-block">
                                        <label class="form-check-label" for="checkbox-switch-7">Ver</label>
                                        <input id="checkbox-switch-7" class="form-check-input" type="checkbox" name="auth_view" checked>                                        
                                    </div>
                                    <div class="form-check form-switch inline-block">
                                        <label class="form-check-label" for="checkbox-switch-8">Imprimir</label>
                                        <input id="checkbox-switch-8" class="form-check-input" type="checkbox" name="auth_print" checked>                                        
                                    </div> 
<!--                                     <div class="form-check form-switch inline-block">
                                        <label class="form-check-label" for="checkbox-switch-9">Exportar</label>
                                        <input id="checkbox-switch-9" class="form-check-input" type="checkbox" name="auth_export" checked>                                        
                                    </div>   -->                                                                                                            
                                </div>
                                <div class="input-group mt-3">
                                    <div id="user" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/authorization.form.user.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/authorization.form.user.title') }}</div>                                
                                    <select multiple id="user-ids" name="user_ids[]" class="form-control ml-2" size="3" required>
                                        <option value=''>{{ trans('document/authorization.form.user.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-user" class="btn btn-primary shadow-md" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>                                    
                                    <div id="input-group-10" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/authorization.form.user.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                
                            </form>

                        </div>
                    </div>


                    <!-- BEGIN: Modal Document -->
                    <div id="modal-document" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xxl">
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
                                                    <th><input type="checkbox" name="checkout-doc" /></th>
                                                    <th>id</th>
                                                    <th>Código</th>
                                                    <th>Nombre</th>
                                                    <th>Proceso</th>
                                                    <th>Tipo</th>
                                                    <th>Localización</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th><input type="text" class="col-filter" placeholder="Código" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Nombre" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Proceso" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Tipo" /></th>                                                    
                                                    <th><input type="text" class="col-filter" placeholder="Localización" /></th>
                                                </tr>
                                            </tfoot>                                                                                                                          
                                        </table>                       

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <img id="loading-modal-document-1" alt="Cargando..." class="h-12 inline-flex float-left" src="{{ url('/assets/images/loading_small.gif') }}" sytle="display:none">
                                    <button id="btn-document-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-document-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-document-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-document" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Document --> 

                    <!-- BEGIN: Modal User -->
                    <div id="modal-user" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xxl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-user-title" class="font-medium text-base mr-auto">Selección de usuarios</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                        <table id="users-table" class="table table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" name="checkout-user" /></th>
                                                    <th>Nombre</th>
                                                    <th>Localización</th>
                                                    <th>Departamento</th>
                                                    <th>Cargo</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th><input type="text" class="col-filter" placeholder="Nombre" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Localización" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Departamento" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Cargo" /></th>                                                    
                                                </tr>
                                            </tfoot>                                                                                                                          
                                        </table>                       

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <img id="loading-modal-user-1" alt="Cargando..." class="h-12 inline-flex float-left" src="{{ url('/assets/images/loading_small.gif') }}" sytle="display:none">
                                    <button id="btn-user-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-user-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-user-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-user" class="">.</a>
                                    
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal User --> 
                    
                    <!-- BEGIN: Modal Documents Valid -->
                    <div id="modal-document-valid" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xxl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-document-valid-title" class="font-medium text-base mr-auto">Listado de documentos autorizados</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                        <table id="documents-valid-table" class="table table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nombre</th>
                                                    <th>Proceso</th>
                                                    <th>Tipo</th>
                                                    <th>Localización</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th><input type="text" class="col-filter" placeholder="Código" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Nombre" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Proceso" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Tipo" /></th>                                                    
                                                    <th><input type="text" class="col-filter" placeholder="Localización" /></th>
                                                </tr>
                                            </tfoot>                                                                                                                          
                                        </table>                       

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <img id="loading-modal-document-2" alt="Cargando..." class="h-12 inline-flex float-left" src="{{ url('/assets/images/loading_small.gif') }}" sytle="display:none">
                                    <button id="btn-document-valid-close" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cerrar</button>
                                    <a id="modal-document-valid-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-document-valid" class="">.</a>
                                    <a id="modal-document-valid-close" href="javascript:;" data-tw-dismiss="modal" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Documents Valid --> 
                    
                    <!-- BEGIN: Modal Users Valid -->
                    <div id="modal-user-valid" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xxl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-user-valid-title" class="font-medium text-base mr-auto">Listado de usuarios autorizados</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">                                    
                                    <table id="users-valid-table" class="table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Localización</th>
                                                <th>Departamento</th>
                                                <th>Cargo</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th><input type="text" class="col-filter" placeholder="Nombre" /></th>
                                                <th><input type="text" class="col-filter" placeholder="Localización" /></th>
                                                <th><input type="text" class="col-filter" placeholder="Departamento" /></th>
                                                <th><input type="text" class="col-filter" placeholder="Cargo" /></th>                                                    
                                            </tr>
                                        </tfoot>                                                                                                                          
                                    </table>                       
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <img id="loading-modal-user-2" alt="Cargando..." class="h-12 inline-flex float-left" src="{{ url('/assets/images/loading_small.gif') }}" sytle="display:none">
                                    <button id="btn-user-valid-close" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cerrar</button>
                                    <a id="modal-user-valid-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-user-valid" class="">.</a>
                                    <a id="modal-user-valid-close" href="javascript:;" data-tw-dismiss="modal" class="">.</a>                                    
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Users Valid -->                      


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
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script>
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />              

<script type="text/javascript">
    var $typeTable, $documentTable;
    $(function () {


        $('body').on('click', '#btn-refresh', function (e) {
            $("#json-docs").val(''); 
            $("#json-users").val('');
            $('#document-ids').find('option').remove().end().append('<option value="">{{ trans("document/authorization.form.document.placeholder") }}</option>').val('');  
            $('#user-ids').find('option').remove().end().append('<option value="">{{ trans("document/authorization.form.user.placeholder") }}</option>').val('');  
            //$(".check").attr("checked", false);
            if ( $.fn.DataTable.isDataTable('#users-table') )   {
                $("#checkout-user").attr('checked', false); 
                $userTable.$('tr', {"filter":"applied"}).each( function () {
                    var td = $(this).find("td:eq(0)");
                    td.find('input').prop('checked', false);                 
                });
            }
            if ( $.fn.DataTable.isDataTable('#documents-table') ) {
                $("#checkout-doc").attr('checked', false); 
                $documentTable.$('tr', {"filter":"applied"}).each( function () {
                    var td = $(this).find("td:eq(0)");
                    td.find('input').prop('checked', false);                 
                }); 
            }                       
        });

        // DOCUMENTOS

        // Genera el modal y tabla para los documentos
        $('body').on('click', '#btn-modal-document', function (e) {
            e.preventDefault();
            var lang = {!! $set['documentGridLanguage'] !!};
            var dids = $("#document-ids").val();

            $("#json-docs").val('');            
            $("#modal-document-open")[0].click();
            $("#loading-modal-document-1").show(); 

            // Generar la tabla
            $.ajax({
                type: 'POST',
                data: {'dids':dids},
                dataType: 'json',
                url: '/documentos/ajustes/autorizaciones/documentos',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(json) {  
                    if( json.success) {
                        //$('#document-table').dataTable().fnDestroy();
                        dataSet = $.parseJSON(json.grid);
                        console.dir(dataSet);
                        var selects = [4,5,6];
                        var inputs = [2,3];
                                            
                        $documentTable = new DataTable("#documents-table", {
                            data: dataSet,                      
                            order: [[2, 'asc' ]],
                            columnDefs: [
                                { targets: 0, orderable: false },
                                { targets: 0, searchable: false }
                            ],
                            retrieve: true, // Probando para evitar error t=3 
                            processing: true,                       
                            //stateSave: true,
                            initComplete: function () {
                                var $this = this.api();
                                // Fitros
                                setBottomFilter($this, selects, inputs);                            
                                // columna invisible
                                $this.columns( [1] ).visible( false );
                                // Modal
                                $("#loading-modal-document-1").hide(); 
                            },
                            language: lang                      
                        }); // datatable

                        // $documentTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                        //     var data = this.data();
                        //     console.log(data[1] +' | '+ data[7]);
                        //     if (data[7] == '1') {
                        //         this.select();                            
                        //     }                        
                        // });

                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/authorization.message.document_error_fatal') }}"); 
                    }
                } // success
            }); // ajax                        
        }); // btn-modal-document 

        // Boton de confirmación para documentos        
        $('body').on('click', '#btn-document-ok', function (e) {
            e.preventDefault();
            var totalselected = false;
            // Recorrer y selected todos
            $('#document-ids option').each(function () {
                if( $(this).val() != '' ) {
                    this.selected = true; 
                    totalselected = true;
                }                     
            });

            if( !totalselected ) {
                $("#document-ids option:selected").prop('selected', false);
                setSuccessNotification('error', 'Oops!', '{{ trans("document/authorization.message.document_error_no-selected") }}');
            }
            $("#btn-document-ko").click();
        }); // btn-document-ok 

        $("input[name='checkout-doc']").click(function() {
            var id, code;
            var now = this.checked;  
            var ids = [];
            var output = '<option value="">{{ trans("document/authorization.message.document_ids_default") }}</option> ';
            $documentTable.$('tr', {"filter":"applied"}).each( function () {
                var td = $(this).find("td:eq(0)");
                td.find('input').prop('checked', now);
                if(now) {
                    id = td.find('input').data("id");                        
                    code = td.find('input').data("code");
                    //console.log(code);       
                    ids.push(id);
                    output += '<option value=' + id + ' selected>' + code + '</option> ';
                }                  
            });

            $("#json-docs").val(JSON.stringify(ids)); 
            $("#document-ids").html(output);   
            console.log('Documents selected: ' + ids.length);
            //console.dir(ids);
        });  // input[name=checkout-doc]
        
        // Listado de documentos para el usuario
        $('body').on('click', '#btn-show-documents', function (e) {
            e.preventDefault();
            // Validar si se ha seleccionado el usuario
            var uid = $("#user-ids").val();
            var txt = $("#user-ids option:selected").text();
            var lang = {!! $set['documentGridLanguage'] !!};
            console.log(uid.length);
            if( (uid.length == 1) && (uid[0] != '' ) ) {
                //alert(uid[0]);

                // Abrir Modal
                $("#modal-document-valid-open")[0].click();
                $("#loading-modal-document-2").show(); 

                // Generar la tabla
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '/documentos/ajustes/autorizaciones/documentos/'+ uid[0],
                    success: function(json) {  
                        if( json.success) {
                            dataSet = $.parseJSON(json.grid);
                            console.log('===Records for '+uid[0]);
                            console.dir(dataSet);
                            var selects = [2,3,4];
                            var inputs = [0,1];
                            $("#modal-document-valid-title").html('Listado de documentos autorizados para el usuario '+txt);                                                
                            $documentValidTable = new DataTable("#documents-valid-table", {
                                data: dataSet,                      
                                order: [[0, 'asc' ]],
                                processing: true,
                                initComplete: function () {
                                    var $this = this.api();
                                    // Fitros
                                    setBottomFilter($this, selects, inputs);
                                    $("#loading-modal-document-2").hide();                                                                 
                                },
                                language: lang                      
                            }); // datatable
                        } else {
                            setSuccessNotification('error', 'Oops!', "{{ trans('document/authorization.message.document_error_fatal') }}"); 
                        }
                    } // success
                }); // ajax   

            } else {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/authorization.message.document_error_no-unique") }}');
            }
        });

        $('body').on('click', '#btn-document-valid-close', function (e) {
            e.preventDefault();
            $("#modal-document-valid-close")[0].click();
            if ( $.fn.DataTable.isDataTable('#documents-valid-table') ) {
                $('#documents-valid-table').DataTable().destroy();
            }
        });
        
        // USUARIOS
        // Genera el modal y tabla para los USUARIOS
        $('body').on('click', '#btn-modal-user', function (e) {
            e.preventDefault();
            var lang = {!! $set['userGridLanguage'] !!};
            var uids = $("#user-ids").val();

            $("#json-users").val('');             
            $("#modal-user-open")[0].click();
            $("#loading-modal-user-1").show();

            // Generar la tabla
            $.ajax({
                type: 'POST',
                data: {'uids':uids},
                dataType: 'json',
                url: '/documentos/ajustes/autorizaciones/usuarios',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(json) {  
                    if( json.success) {
                        dataSet = $.parseJSON(json.grid);
                        console.dir(dataSet);
                        var selects = [];
                        var inputs = [1,2,3,4];
                                            
                        $userTable = new DataTable("#users-table", {
                            data: dataSet,                      
                            order: [[1, 'asc' ]],
                            columnDefs: [
                                { targets: 0, orderable: false },
                                { targets: 0, searchable: false }
                            ],
                            retrieve: true, // Probando para evitar error t=3
                            processing: true,                        
                            initComplete: function () {
                                var $this = this.api();
                                // Fitros
                                setBottomFilter($this, selects, inputs);                            
                                // columna invisible
                                //$this.columns( [1] ).visible( false );
                                // Modal
                                $("#loading-modal-user-1").hide();                                
                            },
                            language: lang                      
                        }); // datatable
                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/authorization.message.user_error_fatal') }}"); 
                    }
                } // success
            }); // ajax                        
        }); // btn-modal-user
        
        // Boton de confirmación para usuarios      
        $('body').on('click', '#btn-user-ok', function (e) {
            var totalselected = false;
            // Recorrer y selected todos
            $('#user-ids option').each(function () {
                if( $(this).val() != '' ) {
                    this.selected = true; 
                    totalselected = true;
                }                     
            });

            if( !totalselected ) {
                $("#user-ids option:selected").prop('selected', false);
                setSuccessNotification('error', 'Oops!', '{{ trans("document/authorization.message.user_error_no-selected") }}');
            }
            $("#btn-user-ko").click();
        }); // btn-user-ok 
        
        $("input[name='checkout-user']").click(function() {
            var id, code;
            var now = this.checked;  
            var ids = [];
            var output = '<option value="">{{ trans("document/authorization.message.user_ids_default") }}</option> ';
            $userTable.$('tr', {"filter":"applied"}).each( function () {
                var td = $(this).find("td:eq(0)");
                td.find('input').prop('checked', now);
                if(now) {
                    id = td.find('input').data("id");                        
                    code = td.find('input').data("code");
                    //console.log(code);       
                    ids.push(id);
                    output += '<option value=' + id + ' selected>' + code + '</option> ';
                }                  
            });

            $("#json-users").val(JSON.stringify(ids)); 
            $("#user-ids").html(output);
            console.log('Users selected: ' + ids.length);
            //console.dir(ids);
        });  // input[name=checkout-user] 

        // Listado de usuarios para el documento
        $('body').on('click', '#btn-show-users', function (e) {
            e.preventDefault();
            // Validar si se ha seleccionado el usuario
            var did = $("#document-ids").val();
            var txt = $("#document-ids option:selected").text();
            var lang = {!! $set['userGridLanguage'] !!};
            console.log(did.length);
            if( (did.length == 1) && (did[0] != '' ) ) {
                //alert(uid[0]);

                // Abrir Modal
                $("#modal-user-valid-open")[0].click();
                $("#loading-modal-user-2").show(); 

                // Generar la tabla
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '/documentos/ajustes/autorizaciones/usuarios/'+ did[0],
                    success: function(json) {  
                        if( json.success) {
                            dataSet = $.parseJSON(json.grid);
                            console.log('===Records for '+did[0]);
                            console.dir(dataSet);
                            var selects = [];
                            var inputs = [0,1,2,3];
                            $("#modal-user-valid-title").html('Listado de usuarios autorizados para documento '+txt);                    
                            $userValidTable = new DataTable("#users-valid-table", {
                                data: dataSet,                      
                                order: [[0, 'asc' ]],
                                processing: true,                       
                                initComplete: function () {
                                    var $this = this.api();
                                    // Fitros
                                    setBottomFilter($this, selects, inputs); 
                                    $("#loading-modal-user-2").hide();                                                                
                                },
                                language: lang                      
                            }); // datatable
                        } else {
                            setSuccessNotification('error', 'Oops!', "{{ trans('document/authorization.message.user_error_fatal') }}"); 
                        }
                    } // success
                }); // ajax   

            } else {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/authorization.message.user_error_no-unique") }}');
            }
        });

        $('body').on('click', '#btn-user-valid-close', function (e) {
            e.preventDefault();
            $("#modal-user-valid-close")[0].click();
            if ( $.fn.DataTable.isDataTable('#users-valid-table') ) {
                $('#users-valid-table').DataTable().destroy();
            }
        });        

    }); // document

    function checkBoxDocument(id) {
        //alert('Checking...');
        var now = $("#check-" + id).is(":checked");  
        var ids = [];
        //
        output = '<option value="">{{ trans("document/authorization.message.document_ids_default") }}</option> ';
        //console.log(now);
        if(now) {
            
            // Recuperar actuales
            $('#document-ids option').each(function () {  // :checked
                var aid = $(this).val();
                var code = $(this).text();
                if(aid != '') {
                    ids.push(aid);
                    output += '<option value=' + aid + ' selected>' + code + '</option> ';
                    //console.log('add old: ' + aid);
                } 
            });
            // agregar nuevo
            ids.push(id);
            var code =  $("#check-" + id).data('code');
            output += '<option value=' + id + ' selected>' + code + '</option> ';
            //console.log('add new: ' + id);

        } else {
            // Recupera actuales excepto el deseleccionado
            $('#document-ids option').each(function () {  // :checked
                var aid = $(this).val();
                var code = $(this).text();
                if(aid != '' && aid != id) {
                    ids.push(aid);
                    output += '<option value=' + aid + ' selected>' + code + '</option> ';
                    //console.log('add old*: ' + aid);
                } 
            });
        }
        $("#json-docs").val(JSON.stringify(ids));  
        $("#document-ids").html(output);             
        return false;
    } // checkBoxDocument Fx  
    
    function checkBoxUser(id) {
        //alert('Checking...');
        var now = $("#check-user-" + id).is(":checked");  
        var ids = [];
        //
        output = '<option value="">{{ trans("document/authorization.message.user_ids_default") }}</option> ';
        //console.log(now);
        if(now) {
            
            // Recuperar actuales
            $('#user-ids option').each(function () {  // :checked
                var aid = $(this).val();
                var code = $(this).text();
                if(aid != '') {
                    ids.push(aid);
                    output += '<option value=' + aid + ' selected>' + code + '</option> ';
                    //console.log('add old: ' + aid);
                } 
            });
            // agregar nuevo
            ids.push(id);
            var code =  $("#check-user-" + id).data('code');
            output += '<option value=' + id + ' selected>' + code + '</option> ';
            //console.log('add new: ' + id);

        } else {
            // Recupera actuales excepto el deseleccionado
            $('#user-ids option').each(function () {  // :checked
                var aid = $(this).val();
                var code = $(this).text();
                if(aid != '' && aid != id) {
                    ids.push(aid);
                    output += '<option value=' + aid + ' selected>' + code + '</option> ';
                    //console.log('add old*: ' + aid);
                } 
            });
        }
        $("#json-users").val(JSON.stringify(ids));  
        $("#user-ids").html(output);             
        return false;
    } // checkBoxUser Fx      

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