<!-- resources/views/document/control/edit_file.blade.php -->
<x-icewall>

    <x-slot:title>
        Documento - Gestión
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Gestionar Documento</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Gestionar Documento
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            @if( $document->publish )
                            <a class="btn btn-success shadow-md mr-2" href="javascript:;" id="btn-publish" data-count="{{ $document->history ?? '0' }}" title="Publicar documento"><i data-lucide="book-open" class="w-5 h-5"></i></a>
                            @else
                            <a class="btn btn-secondary shadow-md mr-2" href="javascript:;" id="btn-send" title="Confirmar documento"><i data-lucide="play" class="w-5 h-5"></i></a>
                            @endif 
                            @if( $document->backUrl )
                            <a class="btn btn-secondary shadow-md mr-3" href="javascript:;" id="btn-back" title="Regresar documento"><i data-lucide="rewind" class="w-5 h-5"></i></a>                                                         
                            @endif                          
                            <a class="btn btn-primary shadow-md mr-2" href="javascript:;" data-href="{{ $document->indexUrl }}" title="Regresar a la tabla" id="btn-exit"><i data-lucide="menu" class="w-5 h-5"></i></a>                               
                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-modal-flow" href="javascript:;" class="dropdown-item"> <i data-lucide="move" class="w-4 h-4 mr-2"></i> Flujo </a>
                                        </li>                                     
                                        <li>
                                            <a id="btn-modal-image" href="javascript:;" class="dropdown-item"> <i data-lucide="grid" class="w-4 h-4 mr-2"></i> Encabezado </a>
                                        </li>
                                        <li>
                                            <a id="btn-modal-support" href="javascript:;" class="dropdown-item"> <i data-lucide="link" class="w-4 h-4 mr-2"></i> Soporte </a>
                                        </li>
                                        <li>
                                            <a id="btn-modal-back" href="javascript:;" class="dropdown-item"> <i data-lucide="message-square" class="w-4 h-4 mr-2"></i> Comentario </a>
                                        </li>
                                        <li>
                                            <a id="btn-modal-change" href="javascript:;" class="dropdown-item"> <i data-lucide="volume-2" class="w-4 h-4 mr-2"></i> Cambios </a>
                                        </li>                                                                                                                                                            
                                    </ul>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- BEGIN: Editor -->
                    <div class="intro-y box p-5 mt-5">
                        <div id="my-node">
                            @include('document/document/head_default')
                        </div>
                        <div class="bg-success text-white shadow-lg pt-1 pb-1 flex width-full justify-center">
                            {{ $document->statusTitle ?? '' }}
                        </div>
                        @if( isset($document->support['file']) )
                        <div id="document-sheet">
                            <table>
                                <tr>
                                    <td rowspan="2" colspan="2">
                                        <a id="btn-support-show" href="javascript:;" target="_blank" title="abrir el documento soporte"><img src="{{ url($document->url) }}" alt="Mime" /></i></a>         
                                    </td>
                                    <td><span>Sistema de Gestión:</span><p>{{ $document->system ?? '' }}</p><br><br></td>
                                    <td><span>Proceso:</span><p>{{ $document->process ?? '' }}</p><br><br></td>
                                </tr>
                                <tr>
                                    <td rowspan="2"><span>Tipo de documento:</span><p>{{ $document->type ?? '' }}</p><br><br></td>
                                    <td rowspan="2"><span>Palabras clave:</span>
                                        <p>
                                            @foreach($document->tags as $key => $values)
                                                <em>{{ $key }} : </em>
                                                @foreach($values as $value)
                                                {{ $value }} - 
                                                @endforeach
                                            @endforeach
                                        </p>
                                        <br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span>Tamaño:</span><p>{{ round($document->support['size']/1000, 0) ?? '' }} kB</p></td>
                                    <td><span>Tipo:</span><p>{{ $document->mime ?? '' }}</p></td>
                                </tr>
                            </table>
                        </div>
                        @include('document/document/footer_default')

                        @else
                        <div class="text-center w-full p-8">
                            <h1 class="text-xl pb-4">Configure el archivo soporte para el documento</h1>
                            <a id="btn-modal-support" href="javascript:;" class="btn btn-success"> <i data-lucide="link" class="w-8 h-8 mr-2"></i> Soporte </a>
                        </div>
                        @endif

                        <form id="document-form" action="{{ route('documents.control.manage.comment', $document->document_id) }}" method="POST">
                            @csrf
                            <input type="hidden" id="hash" name="hash" value="{{ old('hash', $document->hash) }}">
                            <input type="hidden" name="version" value={{ old('version', $document->version) }} >
                            <input type="hidden" name="action" value="{{ old('action', $document->action) }}" >
                            <input type="hidden" name="comment" value="{{ old('comment', $document->comment) }}" >
                        </form>                        

                    </div>
                    <!-- END: Editor -->
                    
                    <!-- BEGIN: Modal Support -->
                    <div id="modal-support" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-support-title" class="font-medium text-base mr-auto">Diligenciar Archivo soporte</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <form id="uploadForm" method="post" action="{{ route('documents.control.anexos.store') }}" enctype="multipart/form-data" class="dropzone">
                                        @csrf
                                        <input type="hidden" name="did" value={{ $document->document_id }} />
                                        <input type="hidden" name="ver" value={{ $document->version }} />
                                        <input type="hidden" name="route" value="support" />
                                        <div class="input-group mt-3">
                                            <div id="date" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/link.form.date.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/link.form.date.title') }}</div>
                                            <input type="text" name="date" class="datepicker form-control  w-full" aria-describedby="date" placeholder="{{ trans('document/link.form.date.placeholder') }}"  data-single-mode="true" data-date-format="{{ $set['dateFormat'] }}" required>
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/link.form.date.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div id="upload-zone" >
                                            <div class="dz-default dz-message"><h4>Mueva el archivo soporte aquí para ser cargados</h4></div>
                                        </div>
                                    </form>
                                    <table id="support-table" class="table table-striped display compact" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Archivo</th>
                                                <th>Tipo</th>
                                                <th>Tamaño, bytes</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="support-table-body"></tbody>                                                                              
                                    </table>                                                                        
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-support-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Salir</button>
                                    <button id="btn-support-ok" type="button" class="btn btn-primary">Salvar</button>
                                    <a id="modal-support-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-support" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Support --> 
                    
                    <!-- BEGIN: Modal Image -->
                    <div id="modal-image" class="modal  justify-center" tabindex="-1" aria-hidden="true">
                        <div class="modal-overlay w-10/12">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-image-title" class="font-medium text-base mr-auto">Descargar imagen de encabezado</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <div id="my-image"></div>
                                    <p id="my-txt">Generando imagen...</p>
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-image-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Salir</button>
                                    <a id="modal-image-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-image" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Image -->

                    <!-- BEGIN: Modal Comment -->
                    @include('components/modal_document_comment')
                    <!-- END: Modal Comment -->                    

                    <!-- BEGIN: Modal Changes -->
                    @include('components/modal_document_history')
                    <!-- END: Modal Changes -->                    
                    
                    <!-- BEGIN: Modal Flow -->
                    @include('components/modal_document_flow')
                    <!-- END: Modal Fllow -->                      
                  
                </div>
                <!-- END: Content -->

@push('meta')                
    <meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Select-1.6.2/css/select.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/head.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/document_edit.css') }}" />
@endpush

@push('scripts-bottom')
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
    <script src="{{ url('assets/js/dropzone-5.9.3/dropzone.min.js') }}"></script>
    <script src="{{ url('assets/js/dropzone-5.9.3/config_document_support.js') }}"></script> 
    <script src="{{ url('assets/js/html2canvas.min.js') }}"></script> 
  
    @include('components.notification_error')
    @if ($message = Session::get('success'))
    <script>
        setSuccessNotification('success', 'Felicitaciones!', '{{ $message }}');
    </script> 
    @endif    
    
    <script type="text/javascript">
        var $herTable;
        var $ourTable;
        var $isSaved = true;
        $(function () {
            var referrer =  document.referrer;
            //console.dir(referrer);

            // Inicializar
            setSupportData();
            $("#comment").html('');

            // BOTON AVANZAR
            $('#btn-send').on("click", function()  {
                var hash = $("#hash").val();
                var exit;

                if( (typeof hash === 'undefined') || (hash == '') ) {
                    setSimpleNotification("{{ trans('document/document.get.no-success') }}");
                } else {
                    exit = checkExit('enviar');                    
                    if(exit) {                        
                        swal({ 
                            title: "{{ $document->sendTitle }}",
                            text: "{{ $document->sendText }}",
                            icon: "warning",
                            buttons: {
                                confirm: {text: 'Confirmar', className:'swal-button'},
                                cancel: 'Cancelar'
                            },
                            dangerMode: true,
                        })
                        .then((willSend) => {
                            if (willSend) {
                                $.ajax({
                                    url: '/documentos/control/gestion/enviar/{{ $document->slug }}/'+hash,
                                    type: 'GET',
                                    dataType: 'json',
                                    async: false,
                                    success: function(json) {
                                        console.dir(json);
                                        if(json.success) {                                        
                                            setSuccessNotification('success', '', json.message);
                                            setTimeout(goLocation, 4000, "{{ $document->sendUrl }}");                                        
                                        } else {
                                            setSuccessNotification('error', 'Oops!', json.message);
                                        }
                                    } // success
                                }); // ajax                            
                            } // if
                        });   // swal
                    } // if isSaved                  
                } // else

            }); // btn-send

            // BOTON RETROCEDER           
            $('#btn-back').on("click", function()  {
                var hash = $("#hash").val();
                var check, exit;
                
                if( hash == '') {
                    setSimpleNotification("{{ trans('document/document.get.no-success') }}");
                } else {

                    check = checkComment();
                    if( check ) {
                        exit = checkExit('regresar el documento');
                        if( exit ) {
                            swal({ 
                                title: "{{ $document->backTitle }}",
                                text: "{{ $document->backText }}",
                                icon: "warning",
                                buttons: {
                                    confirm: {text: 'Confirmar', className:'swal-button'},
                                    cancel: 'Cancelar'
                                },
                                dangerMode: true,
                            })
                            .then((willSend) => {
                                if (willSend) {
                                    $.ajax({
                                        url: '/documentos/control/gestion/retroceder/'+hash,
                                        type: 'GET',
                                        dataType: 'json',
                                        async: false,
                                        success: function(json) {
                                            //console.dir(json);
                                            if(json.success) {                                        
                                                setSuccessNotification('success', '', json.message);
                                                setTimeout(goLocation, 4000, "{{ $document->backUrl }}");                                        
                                            } else {
                                                setSuccessNotification('error', 'Oops!', json.message);
                                            }
                                        } // success
                                    }); // ajax                            
                                } // if
                            });   // swal                             
                        } else {

                        }// if saved
                    } // if check                 
                } // else
            }); // btn-back 
            
            // BOTON PUBLICAR
            $('#btn-publish').on("click", function()  {
                var hash = $("#hash").val();
                var count = $(this).data("count");

                console.log('count: '+  parseInt(count) );            

                if( hash == '') {
                    setSimpleNotification("{{ trans('document/document.get.no-success') }}");
                } else if( parseInt(count) > 0 ) {
                    swal({ 
                        title: "{{ trans('document/document.swal.publish.title') }}",
                        text: "{{ trans('document/document.swal.publish.text') }}",
                        icon: "warning",
                        buttons: {
                            confirm: {text: 'Confirmar', className:'swal-button'},
                            cancel: 'Cancelar'
                        },
                        dangerMode: true,
                    })
                    .then((willSend) => {
                        if (willSend) {
                            $.ajax({
                                url: '/documentos/control/gestion/publicar/'+hash,
                                type: 'GET',
                                dataType: 'json',
                                async: false,
                                success: function(json) {
                                    console.dir(json);
                                    if(json.success) {                                        
                                        setSuccessNotification('success', '', json.message);
                                        setTimeout(goLocation, 4000, "{{ $document->sendUrl }}");                                        
                                    } else {
                                        setSuccessNotification('error', 'Oops!', json.message);
                                    }
                                } // success
                            }); // ajax                            
                        } // if
                    });   // swal 
                } else {
                    setSimpleNotification("{{ trans('document/document.publish.no-history') }}");
                } // else
            }); // btn-publish
            
            // BTN SALIR
            $('#btn-exit').on("click", function() {
                var url = $(this).data('href');
                console.log(referrer);
                if( referrer.indexOf('control/documento') >= 0 ) {
                    history.back();
                } else {
                    setTimeout(goLocation, 10, url);   
                }                             
            }); // btn-exit
                   

            // MOSTRAR ARCHIVO SOPORTE
            $('body').on('click', '#btn-support-show', function (e) {
                e.preventDefault();
                var win;
                var file = "{{ $document->support['file'] ?? '' }}";                 
                var uri = "{{ route('documents.control.manage.support.show', ':name') }}"; 

                if( file != '' ) {
                    uri = uri.replace(':name', file);
                    win = window.open(uri, '_blank');
                    win.focus();
                } else {
                    setSuccessNotification('error', 'Oops!', 'Documento soporte no encontrado!'); // FIXME: 
                }
            });
            
            // DESCARGAR ARCHIVO SOPORTE
            $('body').on('click', '#btn-support-download', function (e) {
                e.preventDefault();
                var win;
                var file = "{{ $document->support['file'] ?? '' }}";
                var uri = "{{ route('documents.control.manage.support.down', ':name') }}";                 

                if( file != '') {
                    uri = uri.replace(':name', file);
                    win = window.open(uri, '_blank');
                } else {
                    setSuccessNotification('error', 'Oops!', 'Documento soporte no encontrado!'); // FIXME: 
                }
            });             

            // GEMERA EL MODAL PARA ARCHIVO SOPORTE
            $('body').on('click', '#btn-modal-support', function (e) {
                e.preventDefault();
                $("#modal-support-open")[0].click();
            }); 
            
            // GEMERA EL MODAL PARA IMAGEN DE ENCABEZADO
            $('body').on('click', '#btn-modal-image', function (e) {
                e.preventDefault();
                $("#my-image").html('');
                var node = document.getElementById('my-node');
                html2canvas(node, {
                    logging: false,
                    allowTaint: true
                }).then(function (canvas) {
                    document.getElementById("my-image").appendChild(canvas);
                    $('#my-txt').html('Pulse botón derecho sobre la imagen y luego seleccione &laquo;Guardar imagen como...&raquo;'); 
                    $("#modal-image-open")[0].click();                                     
                }).catch(function (error) {
                    setSuccessNotification('error', 'Oops!', 'Algo salió mal, favor inténtelo más tarde. [' + error + ']');
                }); 

                
            });            
               
            // MODAL PARA ESTADO DE FLUJO
            $('body').on('click', '#btn-modal-flow', function (e) {
                e.preventDefault();
                $("#modal-flow-open")[0].click();
            });

            // GEMERA EL MODAL PARA COMENTARIOS
            $('body').on('click', '#btn-modal-back', function (e) {
                e.preventDefault();
                var hash = $("#hash").val();
                var route = "{{ route('documents.control.comment.get', ':hash') }}";
                var lang = {!! $set['disclaimerLang'] !!};

                //$("#comment").html($("input[name='comment']").val());            
                route = route.replace(':hash', hash);

                $ourTable = $('#comments-table').DataTable({
                    processing: true,
                    serverSide: true,
                    retrieve: true,
                    ajax: route,
                    columns: [
                        {
                            class: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                        },
                        { data: 'date' },
                        { data: 'user' },
                        { data: 'status' },
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
                        
                        $('#comments-table tbody').on('click', 'tr td.dt-control', function () {
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
                                row.child(format(row.data())).show();
                        
                                // Add to the 'open' array
                                if (idx === -1) {
                                    detailRows.push(tr.attr('id'));
                                }
                            }
                        });                        

                    }, // init
                    language: lang 
                }); // datatable


                $("#modal-back-open")[0].click();
            });
            
            $('body').on('click', '#btn-back-ok', function (e) {
                e.preventDefault();  
                $("input[name='comment']").val($("#comment").val());
                $("#document-form").submit(); 
                $("#btn-back-ko").click();
            });
            
            $('body').on('change', '#comment', function (e) {
                $isSaved = false;
            });              
            
            // GENERA EL MODAL PARA CAMBIOS
            $('body').on('click', '#btn-modal-change', function (e) {
                e.preventDefault();
                var hash = $("#hash").val();
                var ver = $("input[name='version']").val();
                if( ver > 1 ) {

                    var route = "{{ route('documents.control.change.get', ':hash') }}";
                    route = route.replace(':hash', hash);

                    $herTable = $('#change-table').DataTable({
                        processing: true,
                        serverSide: true,
                        retrieve: true,
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
                            
                            $('#change-table tbody').on('click', 'tr td.dt-control', function () {
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
                                    row.child(format(row.data())).show();
                            
                                    // Add to the 'open' array
                                    if (idx === -1) {
                                        detailRows.push(tr.attr('id'));
                                    }
                                }
                            });                        

                        } // init
                    }); // datatable

                    $("#modal-changes-open")[0].click();
                } else {
                    setSuccessNotification('error', 'Oops!', "{{ trans('document/change.store.no-version') }}");
                }
                
            });
            
            $("#btn-changes-ok").on("click", function(e) {
                e.preventDefault();
                var data = $("#change-form").serializeArray();
                var uri =  $("#change-form").attr('action');
                console.dir(data); console.log(uri);
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: uri,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: false,
                    success: function(json) {
                        console.log('=== AJAX CODE');
                        console.dir(json);
                        if( json.status === 'success' ) {
                            //$myTable.destroy(); 
                            $("#btn-publish").data("count", json.countH);                            
                            $("#btn-changes-ko")[0].click();
                            setSuccessNotification('success', '', json.message);
                        } else {
                            setSuccessNotification('error', 'Oops!', json.message);
                        }
                    }, // success
                    error: function(jqXHR, exception) {
                        setAjaxError(jqXHR, exception, 'document.control.edit_html.blade@btn-changes-ok');
                    } 
                }); // ajax                                  
            }); // btn-changes-ok              

        }); // document

        function goLocation(url) {
            //alert(url);
            location.href = url;
            //history.back();
        }   // change Document        

        function checkExit(txt) {
            var role = "{{ $document->status }}";
            var file = "{{ $document->support['file'] ?? '' }}";
            var goal = "{{ config('settings.document_status.create') }}";

            if( role == goal ) {
                return true;
            } else {
                if( file == "" ) {
                    swal("{{ trans('document/document.swal.file.text') }}"+txt, {
                        button: "{{ trans('document/document.swal.file.button') }}",
                    });
                    return false;                        
                }
            }

            return true;
        } // checkExit

        function checkComment() {
            if( $("input[name='comment']").val().length < 10 ) {
                swal("{{ trans('document/document.swal.commented.text') }}", {
                    button: "{{ trans('document/document.swal.commented.button') }}",
                });                
                return false;
            }
            return true;
        } // checkComment        

        function setSupportData() {
            var id = $("input[name='did']").val();
            //alert('Hello World: '+ id);
            $.ajax({
                url: '/documentos/control/gestion/soporte/recuperar/'+id,
                type: 'GET',
                dataType: 'json',                
                success: function(data) {
                    console.dir(data);
                    //alert('Hello World');
                    var output = '<tr><td colspan="4">No se ha asociado ningún archivo soporte</td></tr>';
                    if(data) {
                        output = '<tr><td>'+data.file+'</td><td>'+data.mime+'</td><td>'+data.size+'</td><td><button id="btn-support-show" class="mr-2" title="Ver el archivo"><img alt="ver" src="'+"{{ url('assets/images/viewmag.png') }}"+'"></button><button id="btn-support-download" title="Descargar el archivo"><img alt="descargar" src="'+"{{ url('assets/images/download.png') }}"+'"></button></td></tr>';                        
                    }
                    $("#support-table-body").html(output);
                } // success
            }); // ajax
        } // setSupportData        

        function format(d) {
            return d.text;
        }           
    
    </script>     

@endpush

</x-icewall> 