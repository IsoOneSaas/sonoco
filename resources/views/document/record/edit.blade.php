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
                            @if( $DATA['author'] )
                            <button id="btn-store" type="button" form="record-form" class="btn btn-success shadow-md mr-2" onClick="setStatus(1)" > <i data-lucide="archive" class="w-5 h-5"></i> </button>
                            @else
                            <button id="btn-store" type="button" form="record-form" class="btn btn-success shadow-md mr-2" title="Sin autorización" disabled > <i data-lucide="archive" class="w-5 h-5"></i> </button>
                            @endif
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('records.index') }}" alt="Regresar a la tabla"><i data-lucide="menu" class="w-5 h-5"></i></a>    
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
                                    @if( $DATA['author'] )  
                                    <li id="record-3-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-3-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-3" type="button" role="tab" aria-controls="record-tab-3" aria-selected="false" > Clasificación </button>
                                    </li>
                                    @endif
                                    <li id="record-4-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-4-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-4" type="button" role="tab" aria-controls="record-tab-4" aria-selected="false" > Etiquetas </button>
                                    </li>
                                    
                                    <li id="record-5-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-5-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-5" type="button" role="tab" aria-controls="record-tab-5" aria-selected="false" > Participantes </button>
                                    </li>
                                    <li id="record-6-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-6-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-6" type="button" role="tab" aria-controls="record-tab-6" aria-selected="false" > Anexos </button>
                                    </li>
                                    <li id="record-7-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-7-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#record-tab-7" type="button" role="tab" aria-controls="record-tab-7" aria-selected="false" > Ajustes </button>
                                    </li>                                                                                                                                                                                    
                                </ul>
                                <!-- BEGIN: Form -->
                                <form id="record-form" action="{{ route('records.store', $DATA['record_id']) }}" method="POST" enctype="multipart/form-data" onSubmit="return false;">
                                    @csrf
                                    <input type="hidden" name="record_id"  id="record-id" value={{ $DATA['record_id'] }} >                             
                                    <input type="hidden" name="document_id" value={{ $DATA['document_id'] }} id="document-id">
                                    <input type="hidden" name="origin" value="{{ $origin }}">                                 
                                    <input type="hidden" name="xid" value={{ $DATA['xid'] }}>
                                    <input type="hidden" name="file" value="{{ $DATA['file'] }}"> 
                                    <input type="hidden" name="status_id" value={{ $DATA['status_id'] }} id="status-id">
                                    <input type="hidden" name="tab_active" value="{{ old('tab_active', isset($initTab) ? $initTab : '') }}">
                                    <input type="hidden" id="json-users" value="">
                                    <input type="hidden" id="countDepartments" value="{{ $countDepartments }}">
                                    <input type="hidden" id="countLocations" value="{{ $countLocations }}">
                                    <input type="hidden" name="department_id" value= @if( isset($FILE->department_id) ) {{ $FILE->department_id }} @else 0 @endif >
                                    <input type="hidden" id="subject-id" value= @if( isset($FILE->subtopic_id) ) {{ $FILE->subtopic_id }} @else 0 @endif >
                                    
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
                                                <p class="inline-flex items-baseline">
                                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Busque un archivo en el disco duro y seleccionelo para utilizarlo como archivo soporte del registro.
                                                </p>  
                                                <div class="input-group mt-5 mb-5">
                                                    <div id="file" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.file.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.file.title') }}</div>
                                                    <input type="file" name="file" value="" class="form-control w-full input-status pl-4 pt-2" aria-describedby="file" placeholder="{{ trans('document/record.form.file.placeholder') }}">                                                    
                                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.file.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                </div>
                                                @if( isset($DATA) && $DATA['spt'] )                                                
                                                <p class="inline-flex items-baseline"><i data-lucide="info" class="w-4 h-4"></i>&nbsp;Hay un archivo de soporte proveniente del documento fuente que puede consultar y utilizar como archivo soporte después de ser modificado.</p>
                                                <div class="input-group mt-5"> 
                                                    <a href="javascript:;" class="btn btn-primary" onClick="showSupport('{!! $DATA['spt'] !!}'); return false;" title="Abrir archivo soporte del documento"><i data-lucide="download" class="w-12 h-12"></i></a>
                                                    <span>&nbsp;&nbsp;Archivo soporte del documento fuente</span>
                                                </div>
                                                @endif                                                
                                                @if( isset($DATA) && ($DATA['file'] != '') )                                                
                                                <div class="input-group mt-5">
                                                    <a href="javascript:;" class="btn btn-primary input-status" onClick="showFile('{!! $DATA['file'] !!}'); return false;" title="Abrir archivo soporte"><i data-lucide="file" class="w-12 h-12"></i></a>                                                    
                                                    <span>&nbsp;&nbsp;Archivo existente</span>
                                                </div>
                                                @endif                                                                                                
                                            </div>
                                            <!-- END: Support -->
                                        </div>
                                        <div id="record-tab-3" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-3-tab">
                                            @if( $DATA['author'] )
                                                <!-- BEGIN: Location -->
                                                @if( $countLocations > 1 )
                                                <div class="intro-y box p-5 mt-5">
                                                    <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Clasifique el registro en una localización de la lista desplegable.</p>                                                              
                                                    <div class="input-group mt-5">
                                                        <div id="topic" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.location.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.location.title') }}</div>                                                    
                                                        <select id="location-select" name="location_id" class="form-control w-full input-status">
                                                            <option value="">{{ trans('document/record.form.location.default') }}</option>
                                                            @foreach($locations as $location)  
                                                            <option value="{{ $location->location_id }}" @if( isset($FILE->location_id) && ($FILE->location_id == $location->location_id) ) selected @endif >[{{ $location->newCode }}] {{ $location->name }}</option>
                                                            @endforeach                                                      
                                                        </select>
                                                    </div>
                                                </div>
                                                @else
                                                    <input type="hidden" name="location_id" value="{{ $locations->location_id }}">
                                                @endif
                                                <!-- END: Location -->  
                                                <!-- BEGIN: Topic -->
                                                <div class="intro-y box p-5 mt-5">
                                                    <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Clasifique el registro con un tema y subtema nuevo, o seleccione uno existente de la lista desplegable.</p>                                                              
                                                    <div class="input-group mt-5">
                                                        <div id="topic" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.topic.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.topic.title') }}</div>                                                    
                                                        <select id="topic-select" name="topic_id" class="form-control w-full input-status">
                                                            <option value="">{{ trans('document/record.form.topic.default') }}</option>
                                                            @php($previous = '')
                                                            @foreach($topics as $topic)
                                                                @if( $topic->department != $previous )
                                                                    @if( $previous != '' )
                                                                        </optgroup>
                                                                    @endif
                                                                    <optgroup id="dpt{{ $topic->department_id }}" label="{{ $topic->department }}">
                                                                    @php($previous = $topic->department)
                                                                @endif
                                                                <option value="{{ $topic->topic_id }}" @if( isset($FILE->topic_id) && ($FILE->topic_id == $topic->topic_id) ) selected @endif >[{{ $topic->newCode }}] {{ $topic->name }}</option>
                                                            @endforeach
                                                            </optgroup>
                                                        </select>                                                    
                                                        <input type="text" id="topic-name" name="topic" value="" class="form-control w-full input-status  ml-2" aria-describedby="topic" placeholder="{{ trans('document/record.form.topic.placeholder') }}" minlength="2" maxlength="48">
                                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.topic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                        <button id="btn-new-topic" type="button" class='btn btn-primary input-status ml-5'><i data-lucide="plus" class="w-4 h-4"></i></button>
                                                    </div>
                                                    <div class="input-group mt-3">                                                
                                                        <div id="subject" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.subject.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.subject.title') }}</div>                                                    
                                                        <select id="subject-select" name="subject_id" class="form-control w-full input-status">
                                                            <option value="">{{ trans('document/record.form.subject.default') }}</option>
                                                        </select>
                                                        <input type="text" id="subject-name" name="subject" value="" class="form-control w-full input-status ml-2" aria-describedby="subject" placeholder="{{ trans('document/record.form.subject.placeholder') }}" minlength="2" maxlength="48">
                                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.subject.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                        <button id="btn-new-subject" type="button" class='btn btn-primary input-status ml-5'><i data-lucide="plus" class="w-4 h-4"></i></button>
                                                    </div>
                                                </div>
                                                <!-- END: Topic -->
                                            @else
                                                <input type="hidden" name="location_id" value="{{ $FILE->location_id }}">
                                                <input type="hidden" name="topic_id" value="{{ $FILE->topic_id }}">
                                                <input type="hidden" name="subject_id" value="{{ $FILE->subtopic_id }}">
                                            @endif
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
                                                    <button id="btn-group" type="button" class='btn btn-primary input-status ml-5'><i data-lucide="plus" class="w-4 h-4"></i></button>
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
                                            <!-- BEGIN: Users -->
                                            @if( $DATA['auth'] )
                                            <div class="intro-y box p-5 mt-5">
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Acepte su participación en el registros pulsando sobre la caja de chequeo.</p>
                                                <div class="input-group mt-5">
                                                    <div id="user-check" class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/record.form.check.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/record.form.check.title') }}</div>
                                                    <div class="form-switch mt-2 ml-4 mr-2  w-fit">
                                                        <input type="checkbox" class="form-check-input input-status" name="user_check" @if( old('user_check', $DATA['user_check'] ?? false) ) checked @endif>
                                                    </div>                                             
                                                    <div id="input-group-50" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.check.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if( $DATA['record_id'] != '' )
                                            <div class="intro-y box p-5 mt-5">
                                                @if( $DATA['auth'] || $DATA['author'] )
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Participe en la elaboración del registro con sus comentarios o sugerencias.</p>
                                                <div id="messages-list">
                                                    @foreach($DATA['messages'] as $message)
                                                    <div class="input-group mt-5" id="msg_{{ $message['id'] }}">
                                                        <div class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/record.form.feedback.icon') }}" class="w-3 h-3 mr-1"></i>{{ $message['date'] }}</div>
                                                        <textarea class="form-control" rows="2" readonly>{{ $message['author'] }}:  {{ $message['message'] }}</textarea>
                                                        @if( $message['auth'] )
                                                        <button class="btn btn-primary shadow-md input-status btn-feedback-delete" type="button" data-id="{{ $message['id'] }}" data-te-ripple-init><i data-lucide="trash" class="w-4 h-4"></i></button>
                                                        <div class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.feedback.tooltip2') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="input-group mt-5">
                                                    <div class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/record.form.feedback.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/record.form.feedback.title') }}</div>
                                                    <textarea id="feedback" class="form-control input-status" rows="2"></textarea>
                                                    <button id="btn-feedback-save" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="send" class="w-4 h-4"></i></button>
                                                    <div class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.feedback.tooltip1') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                </div>
                                                @else
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Chat del registro</p>  
                                                @endif
                                            </div>
                                            @endif
                                            @if( $DATA['author'] )                                            
                                            <div class="intro-y box p-5 mt-5">
                                                <p class="inline-flex items-baseline"><i data-lucide="alert-circle" class="w-4 h-4"></i>&nbsp;Seleccione los usuarios relacionados con este registro.</p>  
                                                <div class="input-group mt-5">
                                                    <div id="user" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/record.form.user.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.user.title') }}</div>                                
                                                    <select multiple id="user-ids" name="user_ids[]" class="form-control ml-2" size="10" required>
                                                        <option value=''>{{ trans('document/record.form.user.placeholder') }}</option>
                                                        @foreach($DATA['users'] as $user)
                                                        <option value={{ $user['id'] }} selected> {{ $user['name'] }} {{ $user['status'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button id="btn-modal-user" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>                                    
                                                    <div id="input-group-10" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.user.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!-- END: Users -->
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
                                                        <button type="button" class="btn btn-primary input-status ml-5" onClick="showFile('{{ $file->link }}')"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                                        <button type="button" class="btn btn-danger input-status ml-5" onClick="deleteFile({{ $file->link_id }})"><i data-lucide="minus" class="w-4 h-4"></i></button>
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
                                        <div><input type="radio" id="radio-signature-origin-manual" name="signature_origin" value="manual" @if( $disabled ) checked @endif ></div>
                                        <div><input type="radio" id="radio-signature-origin-file" name="signature_origin" value="file" @if( $disabled ) disabled @else checked @endif ></div>
                                        <div><canvas id="signature-pad" class="signature-pad" width="400px" height="300px" style='border:2px solid #000'></canvas></div>                                        
                                        <div class="border-solid border-2 border-black p-4"><img id="sign-img" src="{{ $signUrl }}" ></div>
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

                    <!-- BEGIN: Modal Department -->
                    <div id="modal-department" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-s">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-department-title" class="font-medium text-base mr-auto">Seleccionar departamento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <div id="modal-department-message"></div>
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            @if( $countDepartments == 0 )
                                                @foreach($departments as $department)                                                
                                                <div class="radio mb-2">
                                                    <label>
                                                        <input type="radio" name="department_id" value="{{ $department->department_id }}" @if( isset($FILE->department_id) && ($FILE->department_id == $department->department_id) ) checked @endif >
                                                        <code>{{ $department->name }}</code>
                                                    </label>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="radio"><label><input type="radio" name="department_id" value="{{ $departments->department_id }}" class="mr+1" @if( isset($FILE->department_id) && ($FILE->department_id == $departments->department_id) ) checked @endif ><code>{{ $departments->name }}</code></label></div>
                                            @endif                                                    
                                        </div>
                                    </div>                                                                                                 
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-department-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-department-ok" type="button" class="btn btn-primary w-20">Guardar</button>
                                    <a id="modal-department-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-department" class="">.</a>                                    
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Topic -->                                                           

                </div>
                <!-- END: Content -->

                <!-- File Modal -->




@push('meta')                
<meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Buttons-2.3.6/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Select-1.6.2/css/select.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/dropzone-5.9.3/dropzone.min.css') }}" type="text/css" />
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/ckeditor_4.21.0_full/ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/js/dropzone-5.9.3/dropzone.min.js') }}"></script>
<script src="{{ url('assets/js/signature_pad-4.1.5/signature_pad.umd.min.js') }}"></script> 
<script src="{{ url('assets/js/dropzone-5.9.3/config_record_edit.js') }}"></script>
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script> 
<script document="text/javascript">
    var $n = {{ count($DATA['tags']) }};
    var $userTable;
    $(function () {      
        var status = {{ $DATA['status_id'] }};
        var tab = $("input[name='tab_active']").val();
        var id = ( tab == '') ? 'btn-1-tab' : tab;
        //var lang = { !! $gridLanguage !! };
        // SIGNATURE
        const signaturePad = new SignaturePad(document.getElementById('signature-pad'));        

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
            $("#btn-save, #btn-store, #editor, .input-status").attr('disabled', true);
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
            var tid = this.value;
            console.log(':: tid='+tid);
            if(tid != '') {
                var selectedOption = $('option:selected', this);
                var optgroupId = selectedOption.closest('optgroup').attr('id');
                console.log(optgroupId);
                var did = optgroupId.substring(3);
                $("input[name='department_id']").val(did);
                console.log('Selected department id:'+did+' and topic id: '+tid);          
                topicAjax(tid);
            } else {
                generateSubtopicsSelect(0, 0, null);
            }               
        }); // topic-select
        
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
                output += '<div class="input-group-text flex ml-2"><i data-lucide="'+ '{{ trans("document/record.form.tag.icon") }}'+'" class="w-4 h-4">@</i>'+'{{ trans("document/record.form.tag.title") }}' +'</div>';
                
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

        // Genera un modal para usuarios
        $('body').on('click', '#btn-modal-user', function (e) {
            e.preventDefault();            
            var uids = $("#user-ids").val();

            $("#json-users").val('');             
            $("#modal-user-open")[0].click();
            $("#loading-modal-user-1").show();

            // Generar la tabla
            $.ajax({
                type: 'POST',
                data: {'uids':uids},
                dataType: 'json',
                url: '/documentos/registro/usuarios/recuperar',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(json) {         
                    if( json.success) {
                        dataSet = $.parseJSON(json.grid);
                        // Generar Tabla
                        var selects = [];
                        var inputs = [1,2,3,4];        
                        $userTable = new DataTable("#users-table", {
                            data: dataSet,                      
                            order: [[1, 'asc' ]],
                            columnDefs: [
                                { targets: 0, orderable: false },
                                { targets: 0, searchable: false }
                            ],
                            retrieve: true,
                            processing: true,                        
                            initComplete: function () {
                                var $this = this.api();
                                // Fitros
                                setBottomFilter($this, selects, inputs);                            
                                // Modal
                                $("#loading-modal-user-1").hide();                                
                            },
                            //language: lang                      
                        }); // datatable
                    } else {
                        setSuccessNotification('error', 'Oops!', "{{ trans('document/record.message.user.error_fatal') }}"); 
                    }
                    $("#loading-modal-user-1").hide();
                } // success
            }); // ajax
        }); // btn-modal-user

        // Boton de confirmación para usuarios seleccionados      
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
                setSuccessNotification('error', 'Oops!', '{{ trans("document/record.message.user.error_no-selected") }}');
            }
            $("#btn-user-ko").click();
        }); // btn-user-ok 
        
        // Check On/Off
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

        // Enviar Mensaje
        $('body').on('click', '#btn-feedback-save', function (e) {
            e.preventDefault(); 
            var txt = $("#feedback").val();
            var rid = $("#record-id").val();            
            var route = "{{ route('records.chat.store') }}";
            if( txt != '' ) {                
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {'id':rid,'txt':txt},
                    dataType: 'json',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },            
                    success: function(json) {
                        console.dir(json);
                        var output = '';
                        if( json.success ) {
                            setSuccessNotification('success', '', json.message);
                            $("#feedback").val('');
                            // Agregar mensaje
                            var msg = '{{ trans("document/record.form.feedback.tooltip2") }}';
                            output += '<div class="input-group mt-5" id="msg_'+ json.id +'">';
                            output += '<div class="input-group-text flex w-fit"><i data-lucide="quote" class="w-3 h-3 mr-1"></i>'+ json.date +'</div>';
                            output += '<textarea class="form-control" rows="2" readonly>'+ json.author +':  '+ json.text +'</textarea>';
                            output += '<button class="btn btn-primary shadow-md btn-feedback-delete" type="button" data-id="'+ json.id +'" data-te-ripple-init><i data-lucide="trash" class="w-4 h-4"></i></button>';
                            output += '<div class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="'+msg+'" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>';
                            output += '</div>';
                            $("#messages-list").prepend(output);
                        } else {
                            setSuccessNotification('error', 'Oops!', json.message);
                        } 
                    } // success
                }); // ajax                 
            }
        }); // btn-feedback-save

        $('body').on('click', '.btn-feedback-delete', function (e) {
            e.preventDefault(); 
            var id = $(this).data('id');
            var route = '/documentos/registro/chat/eliminar/'+id;
            swal({
                title: "{{ trans('document/record.message.feedback.delete_title') }}",
                text: "{{ trans('document/record.message.feedback.delete_text') }}?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //console.log('URL: '+ action);
                    $.ajax({
                        url: route,
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },                                             
                        success: function(json) {  
                            console.dir(json);
                            if( json.success ) {
                                setSuccessNotification('success', '', json.message);
                                // Remover el mensaje
                                $("#msg_"+id).remove();
                            } else {
                                setSuccessNotification('error', 'Oops!', json.message);
                            }
                        } // success
                    }); // ajax
                } // if
            });            
            
        }); // btn-feedback-delete

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

        // MODAL PARA TEMAS
        $('body').on('click', '#btn-new-topic', function (e) {
            e.preventDefault();            
            var txt = $("#topic-name").val();
            var no = $("#countDepartments").val();
            console.log('# departamento: '+no+' txt: '+txt);            

            if( txt != '' ) {
                //no = 0;   // FIXME: se activa para depuración (un sólo departamento)
                if( no == '0' ) {  
                    $("#modal-department-open")[0].click();
                } else {
                    $("input[name='department_id']").val(no);
                    setTopicAjax(no, txt);
                }
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/file.error.topic.empty') }}");
            }            
        }); // 

        $('body').on('click', '#btn-department-ok', function (e) {
            var no = $('input[name="department_id"]:checked').val();
            var txt = $("#topic-name").val();
            console.log('# departamento: '+no+' txt: '+txt);
            if( no > 0 ) {
                $("#btn-department-ko").click();
                $("input[name='department_id']").val(no);
                setTopicAjax(no, txt);
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/file.error.department.empty') }}");
            }
        }); // btn-department-ok

        $('body').on('click', '#btn-new-subject', function (e) {
            e.preventDefault();            
            var txt = $("#subject-name").val();
            var no = $("#topic-select").val();
            console.log('# tema: '+no+' txt: '+txt);
            
            if(txt == '') {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/file.error.subtopic.empty') }}");
            } else {
                if(no == '') {                
                    setSuccessNotification('error', 'Oops!', "{{ trans('document/file.error.subtopic.no-topic') }}");
                } else {
                    setSubjectAjax(no, txt);
                }  
            }          
        }); //         
          
    }); // document

    $(document).ready(function() {
        // CARGAR SUBTEMAS si es edición
        var rid = $("#record-form input[name='record_id']").val();
        var tid = $("#record-form select[name='topic_id']").val();
        if(rid > 0) {
            console.log('Trigger topic-select with rid='+rid+' tid='+tid);
            $("#topic-select").val(tid).trigger('change');
        }
    });

    function setStatus(status) {
        console.log('Saving...');
        if ($('#record-form input[name="name"]').val() == "") {                
            setSuccessNotification('error', 'Oops!', "{{ trans('document/record.request.name.required') }}");
        } else if($('#record-form select[name="topic_id"]').val() == "") {
            setSuccessNotification('error', 'Oops!', "{{ trans('document/record.request.topic.required') }}");
        } else if($('#record-form select[name="subject_id"]').val() == "") {
            setSuccessNotification('error', 'Oops!', "{{ trans('document/record.request.subject.required') }}");
        } else {

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
                        //var form = $("#record-form");
                        //form.submit();
                        $('#record-form')[0].submit();
                    }
                });  
            } else {
                //var form = $("#record-form");
                //form.submit();
                $('#record-form')[0].submit();
            }
        }                
    } // set Status Fx

    function topicAjax(id) {        
        var route = '/documentos/archivo/listar/subtemas/'+id;
        console.log('Running topicAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },            
            success: function(data) {
                console.dir(data);
                if( data.success ) {
                    var sid = $("#subject-id").val();
                    generateSubtopicsSelect(id, sid, data.subtopics);
                    $("#subject-select").focus();
                } else {
                    setSuccessNotification('error', 'Oops!', data.message);
                    generateSubtopicsSelect(0, 0, null);
                    $("#subject-name").focus();
                }                
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
        //console.log('OPEN: '+url);
        var win = window.open(url, '_blank');
        win.focus();        
    } // showFile Fx

    function showSupport(file) {        
        var url = "{{ route('records.support.show', ':file') }}"; 
        url = url.replace(':file', file);
        var win = window.open(url, '_blank');
        win.focus();        
    } // showSupport Fx    

    function checkBoxUser(id) {
        //alert('Checking...');
        var now = $("#check-user-" + id).is(":checked");  
        var ids = [];
        //
        output = '<option value="">{{ trans("document/record.message.user.ids_default") }}</option> ';
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
    
    // Eventos Editor
    function setTemplatesGrid() {
        $('#templates-table').dataTable().fnDestroy();
        let lang = {!! $templatesLang !!};
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
        let lang = {!! $referencesLang !!};
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

    function setTopicAjax(no, txt) {        
        var route = "{{ route('files.save.topic') }}";
        console.log('Running setTopicAjax with route: '+route);
        console.log('Voy a crear Tema con nombre '+txt+' para el departamento '+no);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'department_id': no, 'topic': txt},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                console.dir(data);
                if( data.success ) {
                    setSuccessNotification('success', '', data.message);
                    // Limpia
                    $("#topic-name").val('');
                    $("#subject-name").val('');
                    // Generar nuevo select de temas                
                    generateTopicsSelect(data.tid, data.topics);
                    // Generar nuevo select de subtemas
                    generateSubtopicsSelect(0, 0, null);
                    $("#subject-name").focus();
                } else {
                    setSuccessNotification('error', 'Oops!', data.message); 
                }
            } // success
        }); // ajax         
    } // setTopicAjax Fx

    function generateTopicsSelect(id, topics) {
        var previous = '';
        var output = '<option value="">{{ trans("document/record.form.topic.default") }}</option>';
        if( topics.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/error.topic.no-exist") }}'); 
            $("#topic-name").focus();
        } else {
            $.each(topics, function(i, topic) {
                if( topic.department != previous ) {
                    if(previous != '') {
                        output += '</optgroup>';
                    } // if
                    output += '<optgroup id="dpt'+topic.department_id+'" label="'+topic.department+'">';
                    previous = topic.department;
                } // if
                output += '<option value="'+topic.topic_id+'"';
                output += ( topic.topic_id == id ) ? ' selected' : '';
                output += '>['+topic.newCode+'] '+topic.name+'</option>';            
            });
        }
        $("#topic-select").html(output);
    } // generateTopicsSelect

    function setSubjectAjax(no, txt) {        
        var route = "{{ route('files.save.subject') }}";
        console.log('Running setSubjectAjax with route: '+route);
        console.log('Voy a crear SUBtema con nombre '+txt+' para el tema '+no);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'topic_id': no, 'subject': txt},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                console.dir(data);
                if( data.success ) {
                    setSuccessNotification('success', '', data.message);
                    // Limpia
                    $("#topic-name").val('');
                    $("#subject-name").val('');
                    // Generar nuevo select de subtemas
                    generateSubtopicsSelect(no, data.sid, data.subtopics);
                } else {
                    setSuccessNotification('error', 'Oops!', data.message); 
                }
            } // success
        }); // ajax         
    } // setTopicAjax Fx


    function generateSubtopicsSelect(tid, sid, subtopics) {
        var output = '<option value="">{{ trans("document/record.form.subject.default") }}</option>';
        console.log('Subtopic existing: '+sid);
        console.table(subtopics);
        if(tid > 0) {
            $.each(subtopics, function(i, subtopic) {
                output += '<option value="'+subtopic.subtopic_id+'"';
                output += ( subtopic.subtopic_id == sid ) ? ' selected' : '';
                output += '>['+subtopic.newCode+'] '+subtopic.name+'</option>';
            });
        }
        $("#subject-select").html(output);
    } // generateSubtopicsSelect    
     

</script>
               

    @include('components.notification_index')

@endpush                
</x-icewall> 