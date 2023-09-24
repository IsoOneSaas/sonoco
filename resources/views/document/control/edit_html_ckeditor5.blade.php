<!-- resources/views/document/control/edit.blade.php -->
<x-icewall>

    <x-slot:title>
        Documento - Editar
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar Documento Nuevo</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Editar Documento
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <a class="btn btn-secondary shadow-md mr-2" href="javascript:;" id="btn-send"><i data-lucide="play" class="w-5 h-5"></i></a>
                            @if( $document->backUrl )
                            <a class="btn btn-secondary shadow-md mr-3" href="javascript:;" id="btn-back"><i data-lucide="rewind" class="w-5 h-5"></i></a>
                            @endif
                            <button type="button" id="btn-submit" form="document-form" class="btn btn-primary shadow-md mr-1" title="Salvar Formulario"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" data-href="{{ $document->indexUrl }}" title="Regresar a la tabla" id="btn-exit"><i data-lucide="menu" class="w-5 h-5"></i></a>                               
                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-modal-back" href="javascript:;" class="dropdown-item"> <i data-lucide="message-square" class="w-4 h-4 mr-2"></i> Comentario </a>
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
                            <textarea name="content" hidden></textarea>                            
                        </form>
                        <div class=" flex w-full" >
                            <button id="btn-modal-template"  type="button"><i class="w-4 h-4" data-lucide="file"></i></button>
                        </div>
                        <div id="editor">{{ old('content', $document->content) }}</div>
                    </div>
                    <!-- END: Editor -->
                    
                    <!-- BEGIN: Modal Comment -->
                    <div id="modal-back" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-back-title" class="font-medium text-base mr-auto">Editar Comentario para el Documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                    <form id="disaproval-form" action="" method="POST">
                                        @csrf
                                        <div class="input-group mt-3">
                                            <div id="commentId" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.comment.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/document.form.comment.title') }}</div>
                                            <textarea  class="form-control" id="comment" aria-describedby="commentId" placeholder="{{ trans('document/document.form.comment.placeholder') }}" minlength="8" rows="10"  required>{{ old('comment') }}</textarea>                                           
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.comment.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                    </form>                                                          

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-back-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-back-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-back-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-back" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
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
                                        <tbody>
                                            @foreach($templates as $template)
                                            <tr><td>{{ $template->template_id }}</td><td>{{ $template->name }}</td></tr>
                                            @endforeach
                                        </tbody>                                                                              
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
<style>
    .swal-button--confirm {
    appearance: none;
    background-color: #2ea44f;
    border: 1px solid rgba(27, 31, 35, .15);
    border-radius: 6px;
    box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
    box-sizing: border-box;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-family: -apple-system,system-ui,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
    font-size: 14px;
    font-weight: 600;
    line-height: 20px;
    padding: 6px 16px;
    position: relative;
    text-align: center;
    text-decoration: none;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    vertical-align: middle;
    white-space: nowrap;
    }

    .swal-button--confirm:focus:not(:focus-visible):not(.focus-visible) {
    box-shadow: none;
    outline: none;
    }

    .swal-button--confirm:hover {
    background-color: #2c974b;
    }

    .swal-button--confirm:focus {
    box-shadow: rgba(46, 164, 79, .4) 0 0 0 3px;
    outline: none;
    }

    .swal-button--confirm:disabled {
    background-color: #94d3a2;
    border-color: rgba(27, 31, 35, .1);
    color: rgba(255, 255, 255, .8);
    cursor: default;
    }

    .swal-button--confirm:active {
    background-color: #298e46;
    box-shadow: rgba(20, 70, 32, .2) 0 1px 0 inset;
    }    
  
</style>
@endpush

@push('scripts-bottom')
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
    <script src="{{ url('assets/js/ckeditor5-classic/build/ckeditor.js') }}"></script> 
    <script src="{{ url('assets/js/ckeditor5-classic/build/translations/es-co.js') }}"></script>   
    @include('components.notification_error')
    @if ($message = Session::get('success'))
    <script>
        setSuccessNotification('success', 'Felicitaciones!', '{{ $message }}');
    </script> 
    @endif
    <script type="text/javascript">
        var $isSaved = true;
        var $theEditor = '';
        $(function () {

            ClassicEditor
                .create(document.querySelector('#editor'), {  // 'fontColor',  'mediaEmbed',
                    licenseKey: '',
                    language: 'es',
                    toolbar: {
                        items: [
                            'undo', 'redo', 'selectAll',
                            '|', 'heading', 'style',
                            '|', 'fontfamily', 'fontsize',  'fontBackgroundColor',
                            '|', 'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript',                      
                            '|', 'alignment',
                            '|', 'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent',
                            '|', 'insertTable', 'uploadImage', 'blockQuote',
                            '|',  'pageBreak', 'specialCharacters', 'highlight', 'horizontalLine'
                        ],
                        shouldNotGroupWhenFull: false
                    },  
                    removePlugins: ["MediaEmbedToolbar"],
                })
                .then(editor => {
                    $theEditor = editor;
                    editor.model.document.on('change:data', () => {         
                        $isSaved = false;
                    })
                }).catch(error => {
                    handleError( error );
                } );
			

            // BOTON AVANZAR
            $('#btn-send').on("click", function()  {
                var hash = $("#hash").val();
                var exit;

                if( hash == '') {
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

            // BTN SALIR
            $('#btn-exit').on("click", function() {
                var url = $(this).data('href');
                var exit = checkExit('salir');
                if( exit ) {
                    setTimeout(goLocation, 10, url);
                }
            }); // btn-exit 
            
            
            $('body').on( "click", '#btn-submit', function( e ) {
                e.preventDefault();                
                // validar vacio
                $("textarea[name='content']").html(getDataFromTheEditor());
                if( $("textarea[name='content']").html().length > 10 ) {                  
                    $("#document-form").submit();
                } else {
                    swal("{{ trans('document/document.swal.empty.text') }}", {
                        button: "{{ trans('document/document.swal.empty.button') }}",
                    }); 
                }                                
            }); // btn-submit            
                      
            // GEMERA EL MODAL PARA COMENTARIOS
            $('body').on('click', '#btn-modal-back', function (e) {
                e.preventDefault();
                $("#comment").html($("input[name='comment']").val());
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

            // TEMPLATE MODAL

            // Genera el modal y tabla para los usuarios
            $('body').on('click', '#btn-modal-template', function (e) {
                e.preventDefault();
                //setTemplateTable();                
                $("#modal-template-open")[0].click(); 
            }); // btn-model-template

            $('body').on('click', '#btn-template-ok', function (e) {
                e.preventDefault();
                var count = $templatesTable.rows( { selected: true } ).count();
                if( count > 0 ) {
                    var selected = $templatesTable.rows( { selected: true } ).data();
                    console.dir(selected[0][0]);
                    
                    $.ajax({
                        url: '/documentos/control/gestion/plantilla/recuperar/'+selected[0][0],
                        type: 'GET',
                        dataType: 'json', 
                        contentType:"application/json; charset=utf-8",
                        async: false,               
                        success: function(json) { 
                            console.log('Plantilla:');
                            console.log(json);
                            // Set texto
                            setDataToTheEditor(json);
                            // Cerrar modal
                            $("#btn-template-ko").click();
                        },
                        error: function (request, status, error) {
                            alert(request.responseText);
                        } // success
                    }); // Ajax
                } else {
                    setSuccessNotification('error', 'Oops!', "{{ trans('document/document.grid.templates.error.no_selected') }}");
                } 
            });


            
            $templatesTable = new DataTable("#templates-table", {
                order: [[ 1, 'asc' ]],
                select: true  
            });
            
        }); // document
        

        function goLocation(url) {
            //alert(url);
            location.href = url;
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

        function setTemplateTable() {
            $('#templates-table').dataTable().fnDestroy();
            //var lang = { !! json_decode($gridTemplatesLanguage, true) !! };
            alert('Hello world');
            $.ajax({
                url: '/documentos/control/gestion/plantillas',
                type: 'GET',
                dataType: 'json',                
                success: function(json) {               
                    if( json.success) {
                        dataSet = $.parseJSON(json.grid);
                        console.log('Resultado:');
                        console.dir(dataSet);
                        // // DATATABLE
                        // $templateTable = new DataTable("#templates-table", {
                        //     data: dataSet,                        
                        //     order: [[ 1, 'asc' ]],                        
                        //     initComplete: function () {
                        //         var $this = this.api();
                        //         // Modal
                        //         $("#modal-template-open")[0].click();
                        //     },
                        //     //language: lang                        
                        // }); // datatable

                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/document.tamplates.error.generic') }}"); // FIXME: to document.php
                    }
                } // success
            }); // ajax        
    
        } // setTemplateTable

        // CKEDITOR FUNCTIONS

        function setDataToTheEditor(txt) {
            $theEditor.focus();
            $theEditor.model.change((writer) => {
            const insertPosition = $theEditor.model.document.selection.getFirstPosition();
                writer.insertText(txt, insertPosition);
            });           
        }

        function getDataFromTheEditor() {
            return $theEditor.getData();
        }

        function handleError( error ) {
            console.error( 'Oops, something went wrong!' );
            console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
            console.warn( 'Build id: nj6iasi99iho-z19rmd3rhk7x' );
            console.error( error );
        }          
    </script>     

@endpush
</x-icewall> 