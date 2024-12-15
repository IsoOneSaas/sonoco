<!-- resources/views/document/record.edit.blade.php -->
<x-icewall>

    <x-slot:title>
        Registro - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Editar Registro
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button id="btn-save" type="button" form="record-form" class="btn btn-primary shadow-md mr-2" onClick="setStatus(0)" > <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <button id="btn-store" type="button" form="record-form" class="btn btn-success shadow-md mr-2" onClick="setStatus(1)" > <i data-lucide="archive" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('records.index') }}"><i data-lucide="skip-back" class="w-5 h-5"></i></a>    
                        </div>
                    </div>
                    <!-- BEGIN: Boxed Tab -->
                    <div class="intro-y box mt-5">
                        <div id="boxed-tab" class="p-5">
                            <div class="preview">
                                <ul class="nav nav-boxed-tabs" role="tablist">
                                    <li id="record-1-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-1-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-1" type="button" role="tab" aria-controls="record-tab-1" aria-selected="false" > Contenido </button>
                                    </li>
                                    <li id="record-2-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-2-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-2" type="button" role="tab" aria-controls="record-tab-2" aria-selected="false" > Soporte </button>
                                    </li>
                                    <li id="record-3-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-3-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-3" type="button" role="tab" aria-controls="record-tab-3" aria-selected="false" > Clasificación </button>
                                    </li>
                                    <li id="record-4-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-4-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-4" type="button" role="tab" aria-controls="record-tab-4" aria-selected="false" > Etiquetas </button>
                                    </li>
                                    <li id="record-5-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-5-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-5" type="button" role="tab" aria-controls="record-tab-5" aria-selected="false" > xxx </button>
                                    </li>
                                    <li id="record-6-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-6-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-6" type="button" role="tab" aria-controls="record-tab-6" aria-selected="false" > Anexos </button>
                                    </li>
                                    <li id="record-7-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-7-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-7" type="button" role="tab" aria-controls="record-tab-7" aria-selected="false" > Ajustes </button>
                                    </li>                                                                                                                                                                                    
                                </ul>
                                <!-- BEGIN: Form -->
                                <form id="record-form" action="{{ route('records.store', $DATA['record_id']) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="record_id"  id="record-id" value={{ $DATA['record_id'] }} >                             
                                    <input type="hidden" name="document_id" value={{ $DATA['document_id'] }} id="document-id">
                                    <input type="hidden" name="origin" value="{{ $origin }}">                                 
                                    <input type="hidden" name="xid" value={{ $DATA['xid'] }}>
                                    <input type="hidden" name="file" value="{{ $DATA['file'] }}"> 
                                    <input type="hidden" name="status_id" value={{ $DATA['status_id'] }} id="status-id">
                                    <input type="hidden" name="tab_active" value="{{ old('tab_active', isset($initTab) ? $initTab : '') }}">
                                    <div class="tab-content mt-5">
                                        <div id="record-tab-1" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-1-tab">
                                            <!-- BEGIN: Basic -->
                                            <div class="intro-y box p-5 mt-5">                                                              
                                                <div class="input-group">
                                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.name.title') }}</div>
                                                    <input type="text" name="name" value="{{ old('name', isset($DATA) ? $DATA['name'] : '') }}" class="form-control w-full input-status" aria-describedby="name" placeholder="{{ trans('document/record.form.name.placeholder') }}" minlength="2" maxlength="255" required>
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                </div>
                                                <div class="input-group mt-3">                                                
                                                    <textarea id="editor" name="content">{{ old('content', isset($DATA) ? $DATA['txt'] : '') }}</textarea> 
                                                </div>
                                            </div>
                                            <!-- END: Basic -->
                                        </div>
                                        <div id="record-tab-2" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-2-tab">
                                            <!-- BEGIN: Support -->
                                            <div class="intro-y box p-5 mt-5">
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Busque un archivo en el disco duro y seleccionelo para utilizarlo como archivo soporte del registro.</p>  
                                                <div class="input-group mt-5">
                                                    <div id="file" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.file.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.file.title') }}</div>
                                                    <input type="file" name="file" value="" class="form-control w-full input-status pl-4 pt-2" aria-describedby="file" placeholder="{{ trans('document/record.form.file.placeholder') }}">                                                    
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.file.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                </div>
                                                @if( isset($DATA) && ($DATA['file'] != '') )                                                
                                                <div class="input-group mt-5">                                                    
                                                    <button id="btn-file" type="button" data-name="{{ $DATA['file'] }}" class='btn btn-primary ml-5'><i data-lucide="file" class="w-12 h-12"></i></button>
                                                    <span>&nbsp;Archivo existente</span>
                                                </div>
                                                @endif                                                                                                
                                            </div>
                                            <!-- END: Support -->
                                        </div>
                                        <div id="record-tab-3" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-3-tab">                                            
                                            <!-- BEGIN: Topic -->
                                            <div class="intro-y box p-5 mt-5">
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Clasifique el registro con un tema y subtema nuevo, o seleccione uno existente de la lista desplegable.</p>                                                              
                                                <div class="input-group mt-5">
                                                    <div id="topic" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.topic.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.topic.title') }}</div>
                                                    <input type="text" name="topic" value="{{ old('topic', isset($DATA) ? $DATA['topic'] : '') }}" class="form-control w-full input-status" aria-describedby="topic" placeholder="{{ trans('document/record.form.topic.placeholder') }}" minlength="2" maxlength="255" required>
                                                    <select id="topic-select" class="form-control w-full input-status ml-2">
                                                        <option value="">{{ trans('document/record.form.topic.default') }}</option>
                                                        @if( $topics !== true )
                                                        @foreach($topics as $item)
                                                        <option value="{{ $item->topic }}">{{ $item->topic }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.topic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                </div>
                                                <div class="input-group mt-3">                                                
                                                    <div id="subject" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.subject.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.subject.title') }}</div>
                                                    <input type="text" name="subject" value="{{ old('subject', isset($DATA) ? $DATA['subject'] : '') }}" class="form-control w-full input-status" aria-describedby="subject" placeholder="{{ trans('document/record.form.subject.placeholder') }}" minlength="2" maxlength="255" required>
                                                    <select id="subject-select" class="form-control w-full input-status ml-2">
                                                        <option value="">{{ trans('document/record.form.subject.default') }}</option>
                                                    </select>                                                    
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.subject.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>                                                    
                                                </div>
                                            </div>
                                            <!-- END: Topic -->                                            
                                        </div>
                                        <div id="record-tab-4" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-4-tab">
                                            <!-- BEGIN: Tags -->
                                            <div id="div-tags" class="intro-y box p-5 mt-5">
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Crea o seleccione de la lista desplegable un nuevo grupo y luego crea o seleccione todas las etiquetas adecuadas para el registro.</p>                                                              
                                                <div class="input-group mt-5">
                                                    <div id="group" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.group.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.group.title') }}</div>
                                                    <input type="text" id="group-input" class="form-control w-full input-status" aria-describedby="group" placeholder="{{ trans('document/record.form.group.placeholder') }}">
                                                    <select id="group-select" class="form-control w-full input-status ml-2">
                                                        <option value="">{{ trans('document/record.form.group.default') }}</option>
                                                        @if( $groups !== true )
                                                        @foreach($groups as $item)
                                                        <option value="{{ $item->group }}">{{ $item->group }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>                                                    
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.group.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                    <button id="btn-group" type="button" class='btn btn-primary ml-5'><i data-lucide="plus" class="w-4 h-4"></i></button>
                                                </div>
                                                @foreach($DATA['tags'] as $group => $tag)
                                                    @foreach( $tag['labels'] as $n => $val )
                                                    <div id="div-{{ $n }}" class="input-group mt-5">
                                                    <div id="group" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.group.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.group.title') }}</div>
                                                        <input type="text" name="groups[]" value="{{ $group }}" class="form-control w-full input-status" readonly>
                                                        <div class="input-group-text flex ml-2"><i data-lucide="{{ trans('document/record.form.tag.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.tag.title') }}</div>                                                        
                                                        <input id="tag-input-{{ $n }}" type="text" name="tags[]" value="{{ $val }}" class="form-control w-full input-status" aria-describedby="tag" placeholder="{{ trans('document/record.form.tag.placeholder') }}">
                                                        <select class="form-control w-full input-status ml-2" onChange="selectTag({{ $n }},this.value)">
                                                            <option value="">{{ trans('document/record.form.tag.default') }}</option>
                                                            @foreach( $tag['options'] as $i => $option )
                                                            <option value="{{ $option->tag }}">{{ $option->tag }}</option>
                                                            @endforeach                                                            
                                                        </select>
                                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.tag.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                        <button type="button" class="btn btn-danger ml-5" onClick="deleteTag({{ $n }})"><i data-lucide="minus" class="w-4 h-4"></i></button>                                                        
                                                    </div>
                                                    @endforeach
                                                @endforeach                                                    
                                            </div>
                                            <!-- END: Tags -->  
                                        </div>
                                        <div id="record-tab-5" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-5-tab">
                                            <h1>Content 5</h1>
                                        </div>
                                        <div id="record-tab-6" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-6-tab">
                                            <!-- BEGIN: Attachment -->
                                            <div class="intro-y box p-5 mt-5">
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Ingrese tanto archivos adjuntos sea necesario. Pulse el botón para cargar uno nuevo.</p>  
                                                <button id="btn-attachments-modal" type="button" class='btn btn-primary ml-4'><i data-lucide="upload" class="w-8 h-8"></i></button>
                                                <div id="div-attachments">
                                                    @foreach( $DATA['files'] as $file )
                                                    <div class="input-group mt-5" id="link-{{ $file->link_id }}">
                                                        <div class="input-group-text flex"><i data-lucide="file" class="w-4 h-4 mr-1"></i> Archivo</div>
                                                        <input type="text" name="attachname[]" value="{{ $file->name }}" class="form-control w-full input-status" readonly>
                                                        <input type="text" name="attachsize[]" value="{{ $file->size }}" class="form-control w-full input-status" readonly>
                                                        <input type="text" name="attachmime[]" value="{{ $file->type }}" class="form-control w-full input-status" readonly>
                                                        <input type="hidden" name="attachfile[]" value="{{ $file->link }}" class="form-control w-full">
                                                        <button type="button" class="btn btn-primary ml-5" onClick="showFile('{{ $file->link }}')"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                                        <button type="button" class="btn btn-danger ml-5" onClick="deleteFile({{ $file->link_id }})"><i data-lucide="minus" class="w-4 h-4"></i></button>
                                                    </div>    
                                                    @endforeach
                                                </div>
                                            </div>
                                            <!-- END: Attachment -->  
                                        </div>
                                        <div id="record-tab-7" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-7-tab">
                                            <!-- BEGIN: Support -->
                                            <div class="intro-y box p-5 mt-5">
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Modifique los ajustes de impresión si es necesario.</p>  
                                                <div class="input-group mt-5">

                                                    <div id="direction" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.direction.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.direction.title') }}</div>
                                                    <select name="direction" class="form-control w-full input-status ml-2">
                                                        @foreach( $DATA['dir_select'] as $item )
                                                        <option value="{{ $item['value'] }}" @if($item['selected']) selected @endif >{{ $item['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.direction.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                                    <div id="size" class="input-group-text flex ml-3"><i data-lucide="{{ trans('document/record.form.size.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.size.title') }}</div>
                                                    <select name="size" class="form-control w-full input-status ml-2">
                                                        @foreach( $DATA['size_select'] as $item )
                                                        <option value="{{ $item['value'] }}" @if($item['selected']) selected @endif >{{ $item['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.size.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>                                                     

                                                </div>
                                            </div>
                                        </div>                                                                                                                                                                
                                    </div>
                                </form>
                                <!-- END: Form -->
                            </div> 
                        </div>
                    </div>
                                        
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
                                    <form id="upload-form" method="post" action="{{ route('records.edit.store') }}" enctype="multipart/form-data" class="dropzone">
                                        @csrf
                                        <div class="input-group mt-3">
                                            <div id="name" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/link.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/link.form.name.title') }}</div>
                                            <input type="text" id="file-name" name="filename" class="form-control w-full" aria-describedby="name" placeholder="{{ trans('document/link.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/link.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div id="upload-zone" >
                                            <div class="dz-default dz-message"><h4>Mueva los archivos aquí para ser cargados</h4></div>
                                        </div>
                                    </form>                                                                        
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

                </div>
                <!-- END: Content -->

                <!-- File Modal -->




@push('meta')                
<meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/dropzone-5.9.3/dropzone.min.css') }}" type="text/css" />
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/ckeditor_4.21.0_full/ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/js/dropzone-5.9.3/dropzone.min.js') }}"></script>
<script src="{{ url('assets/js/dropzone-5.9.3/config_record_edit.js') }}"></script> 
<script document="text/javascript">
    var $n = {{ count($DATA['tags']) }};
    $(function () {
        //const dropzone = new Dropzone("#upload-form");        
        var status = {{ $DATA['status_id'] }};
        var tab = $("input[name='tab_active']").val();
        var id = ( tab == '') ? 'btn-1-tab' : tab;

        // TAG ACTIVO
        $("#"+id).addClass('active');
        $("#"+id).attr('aria-selected', true);
        $("#"+id).trigger("click");
        $('.nav-link').on("click", function()  {
            var id = $(this).attr('id');
            $("input[name='tab_active']").val(id);
        });        

        // ESTADO DEL DOCUMENTO
        if( status == 1 ) {
            // Bloquear inputs y botones
            $("#btn-save, #btn-store").attr('disabled', true);
            $(".input-status").attr('readonly', true);
        } // if

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
        
        // EVENTOS

        // Selección del tema
        $("#topic-select").on("change", function() {
            var topic = this.value;
            if( topic != '' ) {
                $("input[name='topic']").val(topic);
                topicAjax(topic);
            } // if                
        }); // topic

        $("input[name='topic']").on("change", function() {
            $("#topic-select").val("");
        });
        
        // Selección del subtema
        $("#subject-select").on("change", function() {
            var subject = this.value;
            if( subject != '' ) {
                $("input[name='subject']").val(subject);            
            }
        });

        $("input[name='subject']").on("change", function() {
            $("#subject-select").val("");
        });
        
        // Selección del grupo
        $("#btn-group").on("click", function() {
            var success = true;
            var newGroup = false;
            var group = '';
            var groupNew = $("#group-input").val();
            var newGroupVal = ( groupNew == '' ) ? $("#group-select").val() : groupNew;

            $('input[name="groups[]"]').each(function() {
                console.log('new group: ' + $(this).val());
                if( $(this).val() == newGroupVal ) {
                    setSimpleNotification("{{ trans('document/record.form.group.no-way') }}");
                    success = false;                        
                }
            });

            if( groupNew == '') {
                groupOld = $("#group-select").val();
                if( groupOld == '' ) {
                    success = false;
                }
                group = groupOld;
            } else {

                group = groupNew;
                newGroup = true;
            }

            if(success) {
                //alert(group);
                $n = $n + 1;
                var output = '<div id="div-'+$n+'" class="input-group mt-5">';
                output += '<div id="group" class="input-group-text flex"><i data-lucide="'+'{{ trans("document/record.form.group.icon") }}'+'" class="w-4 h-4 mr-1"></i>'+'{{ trans("document/record.form.group.title") }}'+'</div>';
                output += '<input type="text" name="groups[]" value="'+group+'" class="form-control w-full input-status" readonly>';
                output += '<div class="input-group-text flex ml-2"><i data-lucide="'+ '{{ trans("document/record.form.tag.icon") }}'+'" class="w-4 h-4 mr-1">@</i>'+'{{ trans("document/record.form.tag.title") }}' +'</div>';
                
                if( newGroup ) {
                    output += '<input id="tag-input-'+$n+'" type="text" name="tags[]" value="" class="form-control w-full input-status ml-2" aria-describedby="tag" placeholder="'+ '{{ trans("document/record.form.tag.placeholder") }}'+'">';
                    output += '<div class="input-group-text"><a href="javascript:;" class="tooltip" title="'+'{{ trans("document/record.form.tag.tooltip") }}'+'" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4">O</i></a></div>';
                    output += '<button type="button" class="btn btn-danger ml-5" onClick="deleteTag('+$n+')"><i data-lucide="minus" class="w-4 h-4">-</i></button>';
                    output += '</div>';
                    $("#div-tags").append(output);
                } else {
                    // Obtener etiquetas
                    tagAjax(group, output, $n);
                }
            } else {
                console.error('No se seleccionó grupo '+ group);
            }
            
        })

        $("#group-input").on("change", function() {
            $("#group-select").val("");
        });
        
        $("#group-select").on("change", function() {
            $("#group-input").val("");
        });              

        // Genera un modal para anexos
        $('body').on('click', '#btn-attachments-modal', function (e) {
            e.preventDefault();
            //setLinksGrid();
            $("input[name='filename']").val('');
            $("#btn-attachments-clear").trigger("click");
            $("#modal-attachments-open")[0].click();
        });        


    });

    function setStatus(status) {
         
        if( status == 1 ) {
            swal({
                title: "{{ trans('document/record.store.title') }}",
                text: "{{ trans('document/record.store.text') }}",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $("input[name='status_id']").val(status);
                    var form = $("#record-form");
                    form.submit();
                }
            });  
        } else {
            var form = $("#record-form");
            form.submit();
        }                
    } // set Status Fx

    function topicAjax(param) {        
        var route = "{{ route('records.edit.subject') }}";
        console.log('Running topicAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt':param},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },            
            success: function(data) {
                //console.dir(data);
                var output = '<option value="">{{ trans("document/record.form.subject.default") }}</option>';
                $.each(data, function(i, value) {
                    output += '<option value="'+value.subject+'">'+value.subject+'</option>';
                });
                $("#subject-select").html(output);
            } // success
        }); // ajax  
    } // topicAjax Fx

    function tagAjax(param, output, n) {
        var route = "{{ route('records.edit.tag') }}";
        console.log('Running tagAjax with route: '+route);
        //$n = $n + 1;
        //var output = '<tr id="tr-'+$n+'"><td><input name="group[]" value="'+param+'" class="form-control set" readonly></td>';
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt': param},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                console.dir(data);                                                                
                var options  = '<option value="">{{ trans("document/record.form.tag.default") }}</option>';                                
                $.each(data, function(i, value) {
                    options += '<option value="'+value.tag+'">'+value.tag+'</option>';
                });
                output += '<input id="tag-input-'+n+'" type="text" name="tags[]" value="" class="form-control w-full input-status" aria-describedby="tag" placeholder="'+'{{ trans("document/record.form.tag.placeholder") }}'+'">';
                output += '<select class="form-control w-full input-status ml-2" onChange="selectTag('+n+',this.value)">'+options+'</select>';
                output += '<div class="input-group-text"><a href="javascript:;" class="tooltip" title="'+'{{ trans("document/record.form.tag.tooltip") }}'+'" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4">O</i></a></div>';
                output += '<button type="button" class="btn btn-danger ml-5" onClick="deleteTag('+n+')"><i data-lucide="minus" class="w-4 h-4">-</i></button>';
                output += '</div>';
                $("#div-tags").append(output);
            } // success
        }); // ajax 
    } //tagAjax Fx 
    

    function selectTag(tagId, tagValue) {
        $("#tag-input-"+tagId).val(tagValue);
    }

    function deleteTag(tagId) {
        //console.log('eliminar tag tr-'+ tagId);
        $("#div-"+tagId).remove();
    } 
    
    function deleteFile(tagId) {
        $("#link-"+tagId).remove();
    }
    
    function showFile(file) {
        var url = "{{ route('records.edit.show', ':file') }}"; 
        url = url.replace(':file', file);
        var win = window.open(url, '_blank');
        win.focus();        
    } // showFile Fx

</script>
               

    @include('components.notification_index')

@endpush                
</x-icewall> 