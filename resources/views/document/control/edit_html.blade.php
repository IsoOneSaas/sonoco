<!-- resources/views/document/control/edit_html.blade.php -->
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
                            <button type="button" id="btn-view" class="btn btn-primary shadow-md mr-1" title="Visualizar documento"> <i data-lucide="eye" class="w-5 h-5"></i> </button>
                            <button type="button" id="btn-print" class="btn btn-primary shadow-md mr-1" title="Imprimir documento"> <i data-lucide="printer" class="w-5 h-5"></i> </button>
                            <button type="button" id="btn-submit" form="document-form" class="btn btn-primary shadow-md mr-1" title="Salvar documento"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" data-href="{{ $document->indexUrl }}" title="Regresar a la tabla" id="btn-exit"><i data-lucide="menu" class="w-5 h-5"></i></a>                               
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
                                            <a id="btn-modal-back" href="javascript:;" class="dropdown-item"> <i data-lucide="message-square" class="w-4 h-4 mr-2"></i> Comentario </a>
                                        </li>
                                        <li>
                                            <a id="btn-modal-attach" href="javascript:;" class="dropdown-item"> <i data-lucide="paperclip" class="w-4 h-4 mr-2"></i> Anexos </a>
                                        </li>
                                        <li>
                                            <a id="btn-modal-format" href="javascript:;" class="dropdown-item"> <i data-lucide="type" class="w-4 h-4 mr-2"></i> Formato </a>
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
                         @include('document/document/head_default')
                         <div class="{{ $document->color }} text-white shadow-lg pt-1 pb-1 flex width-full justify-center">
                            {{ $document->statusTitle ?? '' }}
                         </div>

                        <form id="document-form" action="{{ route('documents.control.manage.update', $document->document_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="version" value={{ old('version', $document->version) }} >
                            <input type="hidden" name="action" value="{{ old('action', $document->action) }}" >
                            <input type="hidden" id="hash" value="{{ old('hash', $document->hash) }}" >
                            <input type="hidden" name="comment" value="{{ old('comment', $document->comment) }}" >
                            <textarea id="editor" name="content">{{ old('content', $document->content) }}</textarea>                            
                        </form>
                        @include('document/document/footer_default')
                    </div>                    
                    <!-- END: Editor -->
                    
                    <!-- BEGIN: Modal Comment -->
                    @include('components/modal_document_comment')
                    <!-- END: Modal Comment -->

                    <!-- BEGIN: Modal Template -->
                    <div id="modal-template" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-template-title" class="font-medium text-base mr-auto">Selección de la plantilla a incorporar</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <table id="templates-table" class="table table-bordered nowrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nombre</th>
                                            </tr>
                                        </thead>                                                                              
                                    </table>                       
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-template-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-template-ok" type="button" class="btn btn-primary w-20">Seleccionar</button>
                                    <a id="modal-template-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-template" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Template --> 
                    
                    <!-- BEGIN: Modal Reference -->
                    <div id="modal-reference" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-reference-title" class="font-medium text-base mr-auto">Selección de documento referencia</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <table id="references-table" class="table table-bordered nowrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Publicado</th>
                                            </tr>
                                        </thead>                                                                              
                                    </table>                       
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-reference-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-reference-ok" type="button" class="btn btn-primary w-20">Seleccionar</button>
                                    <a id="modal-reference-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-reference" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Reference -->
                    
                    <!-- BEGIN: Modal Signing -->
                    <div id="modal-signing" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-signing-title" class="font-medium text-base mr-auto">Diligenciar firmar</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <div class="grid grid-cols-2 gap-2 width-full">
                                        <div><input type="radio" id="radio-signature-origin-manual" name="signature_origin" value="manual" @if( $set['disabled'] ) checked @endif ></div>
                                        <div><input type="radio" id="radio-signature-origin-file" name="signature_origin" value="file" @if( $set['disabled'] ) disabled @else checked @endif ></div>
                                        <div><canvas id="signature-pad" class="signature-pad" width="400px" height="300px" style='border:2px solid #000'></canvas></div>                                        
                                        <div class="border-solid border-2 border-black p-4"><img id="sign-img" src="{{ $set['signUrl'] }}" ></div>
                                    </div>                                    
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-signing-clear" type="button" class="btn btn-outline-secondary w-20 mr-1">Borrar</button>
                                    <button id="btn-signing-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-signing-ok" type="button" class="btn btn-primary w-20">Seleccionar</button>
                                    <a id="modal-signing-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-signing" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Signing -->
                    
                    <!-- BEGIN: Modal Attachcment -->
                    <div id="modal-attachments" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-attachments-title" class="font-medium text-base mr-auto">Diligenciar Anexos</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5">
                                    <div id="modal-attachments-message"></div>
                                    <form id="uploadForm" method="post" action="{{ route('documents.control.anexos.store') }}" enctype="multipart/form-data" class="dropzone">
                                        @csrf
                                        <input type="hidden" name="did" value={{ $document->document_id }} />
                                        <input type="hidden" name="ver" value={{ $document->version }} />
                                        <input type="hidden" name="route" value="link" />
                                        <div class="input-group mt-3">
                                            <div id="name" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/link.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/link.form.name.title') }}</div>
                                            <input type="text" id="file-name" name="name" class="form-control w-full" aria-describedby="name" placeholder="{{ trans('document/link.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/link.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div id="upload-zone" >
                                            <div class="dz-default dz-message"><h4>Mueva los archivos aquí para ser cargados</h4></div>
                                        </div>
                                    </form>
                                    <table id="links-table" class="table table-striped display compact" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Tamaño, kB</th>
                                                <th>file</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>                                                                              
                                    </table>                                                                        
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-attachments-clear" type="button" class="btn btn-outline-secondary mr-1">Limpiar</button>
                                    <button id="btn-attachments-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cancelar</button>
                                    <button id="btn-attachments-ok" type="button" class="btn btn-primary">Cargar</button>
                                    <a id="modal-attachments-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-attachments" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Attachcment -->

                    <!-- BEGIN: Modal Format -->
                    <div id="modal-format" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-format-title" class="font-medium text-base mr-auto">Formato del Documento </h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">                                 
                                    <div>
                                        <form id="format-form" action="{{ route('documents.control.formato.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="did" value={{ $document->document_id }} />
                                            <div class="input-group">
                                                <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.orientation.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.orientation.title') }}</div>
                                                <select  name="orientation" class="form-control tom-select w-full" required>
                                                    @foreach($set['docFormat']['orientations'] as $value => $txt)   
                                                        <option value="{{ $value }}" {{ old('orientation', $document->orientation) == $value ? 'selected' : '' }}>{{ $txt }}</option>
                                                    @endforeach
                                                </select>                                                  
                                                <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.orientation.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                            </div>
                                            <div class="input-group mt-3">
                                                <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.size.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.size.title') }}</div>
                                                <select  name="size" class="form-control tom-select w-full" required>
                                                    @foreach($set['docFormat']['sizes'] as $value => $txt)   
                                                        <option value="{{ $value }}" {{ old('size', $document->size) == $value ? 'selected' : '' }}>{{ $txt }}</option>
                                                    @endforeach
                                                </select>                                                  
                                                <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.size.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                            </div>                                            
                                        </form>
                                    </div>                                                    
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-format-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-format-ok" type="button" class="btn btn-primary w-20">Aceptar</button>
                                    <a id="modal-format-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-format" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Format -->                     

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
    <link rel="stylesheet" href="{{ url('assets/js/dropzone-5.9.3/dropzone.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ url('assets/css/head.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/document_edit.css') }}" />
@endpush

@push('scripts-bottom')
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
    <script src="{{ url('assets/js/ckeditor_4.21.0_full/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ url('assets/js/signature_pad-4.1.5/signature_pad.umd.min.js') }}"></script> 
    <script src="{{ url('assets/js/dropzone-5.9.3/dropzone.min.js') }}"></script>
    <script src="{{ url('assets/js/dropzone-5.9.3/config_document_edit.js') }}"></script> 
    
    @include('components.notification_error')
    @if ($message = Session::get('success'))
    <script>
        setSuccessNotification('success', 'Felicitaciones!', '{{ $message }}');
    </script> 
    @endif
    <script type="text/javascript">
        var $isSaved = true;
        var $myTable, $ourTable, $hisTable, $herTable;
        Dropzone.autoDiscover = false;
        $(function () {
            // SIGNATURE
            const signaturePad = new SignaturePad(document.getElementById('signature-pad'));
            //const dropzone = new Dropzone("div#my-dropzone", { url: '/documentos/control/gestion/anexos/cargar' });
            const dropzone = new Dropzone("#uploadForm");

            var referrer =  document.referrer;

            $("#comment").html('');

            // EDITOR
            try{
                var editor = CKEDITOR.replace('editor', {
                    customConfig: 'custom/document_edit.js',
                    filebrowserUploadUrl: "{{ route('documents.control.manage.ckeditor.upload', ['_token' => csrf_token() ])}}",
                    filebrowserUploadMethod: 'form'
                });

                editor.on('change', function() {
                    $isSaved = false;
                });
                             
            } catch (err) {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.editor.error.load') }}" + err);
            }			

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

                //console.log('count: '+  parseInt(count) );

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
                var exit = checkExit('salir');
                console.log(referrer);
                if( exit ) {
                    if( referrer.indexOf('control/documento') >= 0 ) {
                        history.back();
                    } else {
                        setTimeout(goLocation, 10, url);   
                    } // if
                } // if
            }); // btn-exit 

            // BTN VER DOCUMENTO
            $('#btn-view').on("click", function() {
                if( !$isSaved ) {
                    swal({ 
                            title: "{{ trans('document/document.swal.view.title') }}",
                            text: "{{ trans('document/document.swal.view.text') }}",
                            icon: "info",
                            buttons: {
                                confirm: {text: 'Ver', className:'swal-button'},
                                cancel: 'Cancelar'
                            },
                            dangerMode: true,
                        })
                        .then((willSend) => {
                            if (willSend) {
                                viewDocument();
                            } // if
                        });   // swal  
                } else {
                    viewDocument();
                }
            }); // btn-view

            // BTN IMPRIMIR DOCUMENTO
            $('#btn-print').on("click", function() {
                if( !$isSaved ) {
                    swal({ 
                            title: "{{ trans('document/document.swal.view.title') }}",
                            text: "{{ trans('document/document.swal.view.text') }}",
                            icon: "info",
                            buttons: {
                                confirm: {text: 'Ver', className:'swal-button'},
                                cancel: 'Cancelar'
                            },
                            dangerMode: true,
                        })
                        .then((willSend) => {
                            if (willSend) {
                                printDocument();
                            } // if
                        });   // swal  
                } else {
                    printDocument();
                }
            }); // btn-print                
                         
            
            // BTN SUBMIT
            $('body').on( "click", '#btn-submit', function( e ) {
                e.preventDefault();
                editor.updateElement();              
                // validar vacio
                if( $("textarea[name='content']").val().length > 10 ) {                  
                    $("#document-form").submit();
                } else {
                    swal("{{ trans('document/document.swal.empty.text') }}", {
                        button: "{{ trans('document/document.swal.empty.button') }}",
                    }); 
                }                                
            }); // btn-submit
            
            
            //BTNO SHOW LINK
            $('body').on( "click", '#btn-link-show', function( e ) {
                e.preventDefault();
                var data = $hisTable.row($(this).parents('tr')).data();
                var url = "{{ route('documents.control.anexos.show', ':name') }}"; 
                url = url.replace(':name', data[4]);
                var win = window.open(url, '_blank');
                 win.focus();
            }); // btn-link-show

            //BTNO DELETE LINK
            $('body').on( "click", '#btn-link-delete', function( e ) {
                e.preventDefault();
                var data = $hisTable.row($(this).parents('tr')).data();
                var action = "{{ route('documents.control.anexos.destroy', ':id') }}"; 
                action = action.replace(':id', data[0]);
                swal({
                    title: "{{ trans('document/link.delete.title') }}"+data[1]+"?",
                    text: "{{ trans('document/link.delete.text') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        //console.log('URL: '+ action);
                        $.ajax({
                            url: action,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },                                             
                            success: function(json) {  
                                console.dir(json);
                                if( json.status == 'success' ) {
                                    setSuccessNotification('success', '', json.message);
                                    setLinksGrid();
                                } else {
                                    setSuccessNotification('error', 'Oops!', json.message);
                                }
                            } // success
                        }); // ajax
                    } // if
                }); 
            }); // btn-link-delete             
                      
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
                $("#btn-back-ko").click();
            });
            
            $('body').on('change', '#comment', function (e) {
                $isSaved = false;
            });            

            // MODAL PARA ESTADO DE FLUJO
            $('body').on('click', '#btn-modal-flow', function (e) {
                e.preventDefault();
                $("#modal-flow-open")[0].click();
            });            

            // MODAL PARA TEMPLATE
            $('body').on('click', '#btn-template-ok', function (e) {
                e.preventDefault();
                var count = $myTable.rows( { selected: true } ).count();
                if( count > 0 ) {
                    var selected = $myTable.rows( { selected: true } ).data();                   
                    $.ajax({
                        url: '/documentos/control/gestion/plantilla/recuperar/'+selected[0][0],
                        type: 'GET',
                        dataType: 'json',              
                        success: function(json) { 
                            if(json.success) {
                                // Insert Template
                                editor.insertHtml(json.text);
                                // Cerrar modal
                                $("#btn-template-ko").click();  
                            } else {
                                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.grid.templates.error.generic') }}");
                            }
                        },
                        error: function (request, status, error) {
                            setSuccessNotification('error', 'Oops!', "ERROR: " + request.responseText);
                            console.error(request.responseText);
                        } // success
                    }); // Ajax
                } else {
                    setSuccessNotification('error', 'Oops!', "{{ trans('document/document.grid.templates.error.no_selected') }}");
                } 
            }); //btn-template-ok

            // MODAL PARA REFERENCE
            $('body').on('click', '#btn-reference-ok', function (e) {
                e.preventDefault();
                var count = $ourTable.rows( { selected: true } ).count();
                if( count > 0 ) {
                    var selected = $ourTable.rows( { selected: true } ).data();                   
                    $.ajax({
                        url: '/documentos/control/gestion/referencia/recuperar/'+selected[0][0],
                        type: 'GET',
                        dataType: 'json',              
                        success: function(json) { 
                            if(json.success) {
                                // Insert Reference
                                editor.insertHtml('<em>'+json.text+'</em>');
                                // Cerrar modal
                                $("#btn-reference-ko").click();  
                            } else {
                                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.grid.references.error.generic') }}");
                            }
                        },
                        error: function (request, status, error) {
                            setSuccessNotification('error', 'Oops!', "ERROR: " + request.responseText);
                            console.error(request.responseText);
                        } // success
                    }); // Ajax
                } else {
                    setSuccessNotification('error', 'Oops!', "{{ trans('document/document.grid.references.error.no_selected') }}");
                } 
            }); //btn-reference-ok
            
            // MODAL PARA SIGNING
            $('body').on('click', '#btn-signing-ok', function (e) {
                e.preventDefault();
                var data;
                var option = $("input[name='signature_origin']:checked").val();

                if( option == 'manual') {
                    data = signaturePad.toDataURL('image/png');                    
                } else {
                    data = $("#sign-img").attr("src");
                }
                console.log('OPTION: '+option+' | SRC= '+data);
                // insert Sign
                editor.insertHtml('<img src="'+data+'" alt="Firma" />');
                // Cerrar Modal
                $("#btn-signing-ko").click(); 
            }); //btn-signing-ok
            
            $('body').on('click', '#btn-signing-clear', function (e) {
                signaturePad.clear();
            });

            // GENERA EL MODAL PARA ANEXOS
            $('body').on('click', '#btn-modal-attach', function (e) {
                e.preventDefault();
                setLinksGrid();
                $("input[name='name']").val('');
                $("#btn-attachments-clear").trigger("click");
                $("#modal-attachments-open")[0].click();
            }); // btn-modal-attach Modal

            // GENERA EL MODAL PARA EL FORMATO
            $('body').on('click', '#btn-modal-format', function (e) {
                e.preventDefault();
                $("#modal-format-open")[0].click();
            }); // btn-modal-format Modal

            $('body').on('click', '#btn-format-ok', function (e) {
                e.preventDefault();                 
                var data = $("#format-form").serializeArray();
                var uri =  $("#format-form").attr('action');
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
                            $("#btn-format-ko")[0].click();
                            setSuccessNotification('success', '', json.message);
                        } else {
                            setSuccessNotification('error', 'Oops!', json.message);
                        }
                    }, // success
                    error: function(jqXHR, exception) {
                        setAjaxError(jqXHR, exception, 'document.control.edit_html.blade@btn-format-ok');
                    } 
                }); // Ajax
 
            }); //btn-format-ok                        

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
            if( !$isSaved ) {
                swal("{{ trans('document/document.swal.saved.text') }}"+txt, {
                    button: "{{ trans('document/document.swal.saved.button') }}",
                });
                return false;
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

        function setTemplatesGrid() {
            $('#templates-table').dataTable().fnDestroy();
            let lang = {!! $set['templatesLang'] !!};
            $.ajax({
                url: '/documentos/control/gestion/plantillas/listado',
                type: 'GET',
                dataType: 'json',                
                success: function(json) {               
                    if( json.success) {
                        dataSet = $.parseJSON(json.grid);
                        // // DATATABLE
                        $myTable = new DataTable("#templates-table", {
                            data: dataSet,
                            columnDefs: [{target: 0, visible: false, searchable: false}],                          
                            order: [[ 1, 'asc' ]],
                            select: true,
                            language: lang,                        
                            initComplete: function () {
                                // Modal
                                $("#modal-template-open")[0].click();
                            },
                            //language: lang                        
                        }); // datatable

                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/document.templates.error.generic') }}");
                    }
                } // success
            }); // ajax        
    
        } // setTemplateTable

        function setReferencesGrid() {
            $('#references-table').dataTable().fnDestroy();
            let lang = {!! $set['referencesLang'] !!};
            // MODAL
            $("#modal-reference-open")[0].click();            
            $.ajax({
                url: '/documentos/control/gestion/referencias/listado',
                type: 'GET',
                dataType: 'json',                
                success: function(json) {               
                    if( json.success) {
                        // DATA
                        dataSet = $.parseJSON(json.grid);
                        // DATATABLE
                        $ourTable = new DataTable("#references-table", {
                            data: dataSet,
                            columnDefs: [{target: 0, visible: false, searchable: false}],                     
                            order: [[ 1, 'asc' ]],
                            select: true,
                            language: lang                     
                        }); // datatable

                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/document.references.error.generic') }}"); // FIXME: to document.php
                    }
                } // success
            }); // ajax            
        } // setReferenceTable
        
        function renderSigning() {
            $("#modal-signing-open")[0].click();         
        } // renderSigning

        function setLinksGrid() {
            $('#links-table').dataTable().fnDestroy();
            var id = $("input[name='did']").val();
            $.ajax({
                url: '/documentos/control/anexos/recuperar/'+id,
                type: 'GET',
                dataType: 'json',                
                success: function(json) {               
                    console.dir(json);
                    if( json.success) {
                        var dataSet = $.parseJSON(json.grid);
                        // // DATATABLE
                        $hisTable = new DataTable("#links-table", {
                            data: dataSet,
                            responsive: true,
                            searching: false,
                            paging: false,
                            info: false,
                            columnDefs: [
                                {target: [0,4], visible: false, searchable: false},
                                {target: -1, orderable: false, data: null, className: 'dt-center', defaultContent: '<button id="btn-link-show" class="mr-2" title="Ver el archivo"><img alt="ver" src="'+"{{ url('assets/images/viewmag.png') }}"+'"></button><button id="btn-link-delete" title="Eliminar el archivo"><img alt="eliminar" src="'+"{{ url('assets/images/delete.png') }}"+'"></button>'}
                            ],                          
                            order: [[ 1, 'asc' ]],                       
                        }); // datatable

                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/document.links.error.generic') }}");
                    }
                } // success
            }); // ajax              
        }

        function viewDocument() {
            var hash = $("#hash").val();
            var uri = "{{ route('documents.control.manage.preview', ':hash') }}"; 
            uri = uri.replace(':hash', hash);
            var win = window.open(uri, '_blank');
            win.focus();            
        } // viewDocument

        function printDocument() {        
            var hash = $("#hash").val();
            var uri = "{{ route('documents.control.manage.print', ':hash') }}"; 
                uri = uri.replace(':hash', hash);
                var win = window.open(uri, '_blank');
                win.focus();            
        } // printDocument
        
        function format(d) {
            return d.text;
        }        
                    
    </script>     

@endpush
</x-icewall> 