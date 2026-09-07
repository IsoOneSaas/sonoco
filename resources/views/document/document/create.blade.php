<!-- resources/views/document/document.create.blade.php -->
<x-icewall>

    <x-slot:title>
        Documento - Configurar
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Configurar Documento Nuevo</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">


                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            <img id="loading-image" alt="Cargando..." class="h-12 inline-flex mr-20" src="{{ url('/assets/images/loading_small.gif') }}"> Configurar Documento
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button id="btn-submit" type="submit" form="document-form" class="btn btn-primary shadow-md mr-2" title="Salvar Formulario"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <!-- <a class="btn btn-primary shadow-md mr-2" href="{ { route('documents.control.documento.index') } }" title="Regresar a la tabla"><i data-lucide="skip-back" class="w-5 h-5"></i></a> -->
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" data-href="{{ route('documents.control.documento.index') }}" title="Regresar a la tabla" id="btn-exit"><i data-lucide="menu" class="w-5 h-5"></i></a>   
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>

                            <div id="flow-alert" class="alert alert-dismissible show box bg-danger text-white flex items-center mb-6" role="alert" style="display:none">
                                 {{ trans('document/document.swal.flow.text') }} 
                                <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-3 h-3"></i> </button>
                            </div>                        
                            
                            <form id="document-form" action="{{ route('documents.control.documento.store') }}" method="POST">
                                @csrf
                                @if( isset($document) ) 
                                    <input type="hidden" name="document_id" value={{ $document->document_id }}>
                                @endif
                                <input type="hidden" id="joker" value="">
                                <input type="hidden" name="serial" value="{{ old('serial', isset($document) ? $document->serial : 1) }}">
                                <input type="hidden" name="status" value="{{ old('status', isset($document) ? $document->status : config('settings.document_columns_default.status') ) }}">
                                <input type="hidden" name="deadline_edit" value="{{ old('deadline_edit', isset($document) ? $document->deadline_edit : 5) }}" ><input type="hidden" name="link_edit" value="{{ old('link_edit', isset($document) ? $document->link_edit : '') }}" >
                                <input type="hidden" name="deadline_review" value="{{ old('deadline_review', isset($document) ? $document->deadline_review : 10) }}" ><input type="hidden" name="link_review" value="{{ old('link_review', isset($document) ? $document->link_review : '') }}" >
                                <input type="hidden" name="deadline_approve" value="{{ old('deadline_approve', isset($document) ? $document->deadline_approve : 15) }}" ><input type="hidden" name="link_approve" value="{{ old('link_approve', isset($document) ? $document->link_approve : '') }}" >                               
                                <div class="input-group">
                                    <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.code.title') }}</div>
                                    <input readonly type="text" name="code" value="{{ old('code', isset($document) ? $document->code : '') }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('document/document.form.code.placeholder') }}" minlength="2" maxlength="8" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                            
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ old('name', isset($document) ? $document->name : '') }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/document.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="version" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.version.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.version.title') }}</div>
                                    <input type="number" name="version" value="{{ old('version', isset($document) ? $document->version : 1) }}" class="form-control  w-full" aria-describedby="version" placeholder="{{ trans('document/document.form.version.placeholder') }}" min="1" step="1" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.version.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                

                                <div class="input-group mt-3">
                                    <div id="system" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/document.form.system.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.system.title') }}</div>
                                    <select name="system_id" id="system-id" class="form-control w-full" required>
                                        <option value=''>{{ trans('document/document.form.system.placeholder') }}</option>
                                        @foreach($systems as $system)   
                                        <option value="{{ $system->system_id }}" {{ old('system_id', isset($document) ? $document->system_id : 0 ) == $system->system_id ? 'selected ' : '' }}>{{ $system->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.system.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="location" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.location.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.location.title') }}</div>
                                    <select id="select-location" name="location_id" class="form-control w-full" required>
                                        <option value=''>{{ trans('document/document.form.location.placeholder') }}</option>
                                        @foreach($locations as $location)   
                                        <option value="{{ $location->location_id }}" {{ old('location_id', isset($document) ? $document->location_id : 0 ) == $location->location_id ? 'selected ' : '' }}>{{ $location->name }}</option>
                                        @endforeach                                        
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.location.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="department" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.department.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.department.title') }}</div>
                                    <select id="select-department"  name="department_id" class="form-control col-span-6" required>
                                        <option value=''>{{ trans('document/document.form.department.placeholder') }}</option>
                                    </select>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.department.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div> 
                                    <div id="process" class="input-group-text flex ml-2"><i data-lucide="{{ trans('document/document.form.process.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.process.title') }}</div>
                                    <select name="process_id" class="form-control col-span-6" readonly>
                                        <option value=''>{{ trans('document/document.form.process.placeholder') }}</option>
                                    </select>                                   
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.process.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                </div>                                                                                             
                                <div class="input-group mt-3">
                                    <div id="type" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.type.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.type.title') }}</div>
                                    <select  name="type_id" class="form-control w-full" required>
                                        <option value=''>{{ trans('document/document.form.type.placeholder') }}</option>
                                        @foreach($types as $type)   
                                        <option value="{{ $type->type_id }}"  {{ old('type_id', isset($document) ? $document->type_id : 0 ) == $type->type_id ? 'selected ' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.type.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                    <div id="pattern" class="input-group-text flex ml-2"><i data-lucide="{{ trans('document/document.form.pattern.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.pattern.title') }}</div>
                                    <select  name="pattern" class="form-control w-full" required>
                                        @foreach($patterns as $key => $value)   
                                        <option value="{{ $key }}"  {{ old('pattern', isset($document) ? $document->pattern : 0 ) == $key ? 'selected ' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.pattern.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>


                                <!-- Edit select -->                              
                                <div class="input-group mt-3">
                                    <div id="job-edit" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/document.form.job_edit.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.job_edit.title') }}</div>
                                    <select multiple id="select-job-edit" name="job_edit_id[]" class="form-control w-full" required>
                                        <option value=''>{{ trans('document/document.form.job_edit.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-job" data-id="edit" class="btn btn-primary shadow-md mr-2" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>
                                    <select multiple id="select-user-edit" name="user_edit_id[]" class="form-control w-full ml-2" required>
                                        <option value=''>{{ trans('document/document.form.user_edit.placeholder') }}</option>                                        
                                    </select>
                                    <button id="btn-modal-user" data-id="edit" class="btn btn-primary shadow-md" type="button" data-te-ripple-init><i data-lucide="users" class="w-4 h-4"></i></button>

                                    <div id="input-group-2" class="input-group-text"><br><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.job_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a></div>
                                </div>
                                <div id="flow-edit" class="alert alert-dismissible show box bg-danger text-white flex items-center mb-6" role="alert" style="display:none">
                                    Ya el responsable ha editado el documento. 
                                    <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-3 h-3"></i> </button>
                                </div>                                                                    
                                <!-- Review select -->                                  
                                <div class="input-group mt-3">
                                    <div id="job-review" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/document.form.job_review.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.job_review.title') }}</div>
                                    <select multiple id="select-job-review" name="job_review_id[]" class="form-control w-full" required>
                                        <option value=''>{{ trans('document/document.form.job_review.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-job" data-id="review" class="btn btn-primary shadow-md mr-2" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>
                                    <select multiple id="select-user-review" name="user_review_id[]" class="form-control w-full ml-2" required>
                                        <option value=''>{{ trans('document/document.form.user_review.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-user" data-id="review" class="btn btn-primary shadow-md" type="button" data-te-ripple-init><i data-lucide="users" class="w-4 h-4"></i></button>

                                    <div id="input-group-2" class="input-group-text"><br><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.job_review.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a></div>
                                </div>
                                <div id="flow-review" class="alert alert-dismissible show box bg-danger text-white flex items-center mb-6" role="alert" style="display:none">
                                    Ya el responsable ha revisado el documento. 
                                    <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-3 h-3"></i> </button>
                                </div>                                                                
                                <!-- Approve select -->                                
                                <div class="input-group mt-3">
                                    <div id="job-approve" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/document.form.job_approve.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.job_approve.title') }}</div>
                                    <select multiple id="select-job-approve" name="job_approve_id[]" class="form-control w-full" required>
                                        <option value=''>{{ trans('document/document.form.job_approve.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-job" data-id="approve" class="btn btn-primary shadow-md mr-2" type="button" data-te-ripple-init><i data-lucide="share2" class="w-4 h-4"></i></button>
                                    <select multiple id="select-user-approve" name="user_approve_id[]" class="form-control w-full ml-2" required>
                                        <option value=''>{{ trans('document/document.form.user_approve.placeholder') }}</option>
                                    </select>
                                    <button id="btn-modal-user" data-id="approve" class="btn btn-primary shadow-md" type="button" data-te-ripple-init><i data-lucide="users" class="w-4 h-4"></i></button>

                                    <div id="input-group-2" class="input-group-text"><br><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.job_approve.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a></div>
                                </div>
                                <div id="flow-approve" class="alert alert-dismissible show box bg-danger text-white flex items-center mb-6" role="alert" style="display:none">
                                    Ya el responsable ha aprobado el documento. 
                                    <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-3 h-3"></i> </button>
                                </div>
                                
                                <div class="input-group mt-3">
                                    <div id="switch" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.switch.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.switch.title') }}</div>
                                    <div class="form-switch mt-2 ml-4 mr-2  w-fit">
                                        Administrador&nbsp;&nbsp;<input type="checkbox" class="form-check-input" name="switch" @if( old('switch', isset($document) ? $document->switch : '') ) checked @endif >&nbsp;&nbsp;Usuario
                                    </div>                                             
                                    <div id="input-group-27" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.switch.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>                               
                                </div>                                 
                                                                                        
                                <div class="input-group mt-3">
                                    <div id="class" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.class.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.class.title') }}</div>
                                    <input id="select-classes" type="text" name="class" value="{{ old('class', isset($document) ? $document->class : '') }}" class="form-control col-span-6" placeholder="{{ trans('document/document.form.class.placeholder') }}" aria-label="Categoria" list="tag-classes" autocomplete="off">
                                    <datalist id="tag-classes">
                                        @foreach($classes as $topic)
                                        <option value="{{ $topic }}">
                                        @endforeach                                       
                                    </datalist>
                                    <div id="input-group-102" class="input-group-text"><a href="javascript:;" title="Limpiar" tabindex="-1"><i data-lucide="delete" class="w-4 h-4" onClick="$('#select-classes').val('')"></i></a> </div>                                    
                                    <div id="input-group-104" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.class.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                    <div id="tags" class="input-group-text flex ml-2"><i data-lucide="{{ trans('document/document.form.tags.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.tags.title') }}</div>
                                    <select multiple id="select-tags" name="tags[]" class="form-control col-span-6"></select>
                                    <div id="input-group-114" class="input-group-text"><a href="javascript:;" title="Limpiar" tabindex="-1"><i data-lucide="delete" class="w-4 h-4" onClick="$selectize.clear()"></i></a> </div>
                                    <div id="input-group-116" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.tags.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                

                            </form>                                                            
                        </div>
                    </div>
                    <!-- END: Form -->
                    
                    <!-- BEGIN: Modal Content -->
                    <div id="modal-job" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xxl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-job-title" class="font-medium text-base mr-auto"></h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                        <table id="jobs-table" class="table table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Nombre del Cargo</th>
                                                    <th>Departamento</th>
                                                    <th>Localizaciones</th>
                                                    <th>S</th>
                                                    <th>M</th>
                                                    <th>C</th>
                                                    <th>O</th>
                                                </tr>
                                            </thead>  
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th><input type="text" class="col-filter" placeholder="Cargo" /></th>
                                                    <th><input type="text" class="col-filter" placeholder="Departamento" /></th>
                                                    <th></th><th></th><th></th><th></th><th></th>                                                                                                                                                    
                                                </tr>
                                            </tfoot>                                                                              
                                        </table>                       

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-job-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-job-clear" type="button" class="btn btn-secondary w-20 mr-1">Limpiar</button>
                                    <button id="btn-job-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-job-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-job" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Content -->

                    <!-- BEGIN: Modal Content -->
                    <div id="modal-user" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xxl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-user-title" class="font-medium text-base mr-auto"></h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <form id="deadline-form" action="" method="POST">
                                        @csrf
                                        <input type="hidden" id="status" value="">
                                        <div class="input-group  w-1/3">
                                            <div id="deadline" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.deadline.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/document.form.deadline.title') }}</div>
                                            <input type="number" name="deadline" value="{{ old('deadline') ?? 5 }}" class="form-control" aria-describedby="deadline" placeholder="{{ trans('document/document.form.deadline.placeholder') }}" min="1" step="1" required>
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.deadline.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                    </form>
                                    <br>
                                    <table id="users-table" class="table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Nombre del Usuario</th>
                                                <th>Cargo</th>
                                                <th>Departamento</th>
                                                <th>S</th>
                                                <th>C</th>
                                                <th>JID</th>
                                            </tr>
                                        </thead>  
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th><input type="text" class="col-filter" placeholder="Nombre" /></th>
                                                <th><input type="text" class="col-filter" placeholder="Cargo" /></th>
                                                <th><input type="text" class="col-filter" placeholder="Departamento" /></th>
                                                <th></th><th></th><th></th>                                                                                              
                                            </tr>
                                        </tfoot>                                                                              
                                    </table>                       
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-user-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-user-clear" type="button" class="btn btn-secondary w-20 mr-1">Limpiar</button>
                                    <button id="btn-user-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-user-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-user" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Content -->                    

                </div>
                <!-- END: Content -->

@push('meta')                
<meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/Select-1.6.2/css/select.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/selectize.css') }}" />
    <style>
        #jobs-table td, #jobs-table th, #jobs-table label, #users-table td, #users-table th, #users-table label { font-size: 0.85em; padding: 0.2em }
        #jobs-table td, #users-table td { cursor: pointer }
        .col-filter { width: 100%; font-size: 0.9em; padding: 0.2em 0.5em }
    </style>
@endpush

@push('scripts-bottom')
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables/Select-1.6.2/js/dataTables.select.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>    
    <script src="{{ url('assets/js/selectize.min.js') }}"></script>
    <script src="{{ url('assets/js/iso.js') }}"></script>    
  
<script type="text/javascript">
    var $jobTable, $userTable;
    var $isSaved = true;  
    var $selectize; 
    $(function () {
        var json = '{!! $default !!}';
        var flow = "{{ isset($document) ? $document->flow : 0; }}";
        //console.log('json: '+json);
        if( (json !== '0') && (json !== '') ) {
            var obj = $.parseJSON(json);
            //console.log('Entering...');
            //console.dir(obj);
            $("input[name='name']").val(obj.name);
            $("#system-id option[value='"+obj.sid+"']").attr('selected', true);
        }
        
        var $select = $('#select-tags').selectize({
            theme: 'contacts',
            persist: false,
            maxItems: null,
            valueField: 'email',
            labelField: 'name',
            searchField: ['name', 'email'],
            // options: [
            //     {email: 'Clave1'},
            //     {email: 'Clave2'},
            //     {email: 'Clave3'}
            // ],
            render: {
                item: function(item) {
                    return '<div>' +
                        (item.name ? '<span class="name">' + item.name + '</span>' : '') +
                        (item.email ? '<span class="email">' + item.email + '</span>' : '') +
                    '</div>';
                },
                option: function(item) {
                    var label = item.name || item.email;
                    var caption = item.name ? item.email : null;
                    return '<div>' +
                        '<span class="label">' + label + '</span>' +
                        (caption ? '<span class="caption">' + caption + '</span>' : '') +
                    '</div>';
                }
            },
            create: function(input) {
                var words = input.split(' ');
                if( words.length == 1 ) {
                    return {
                        email : input,
                        name  : ''
                    };                    
                }                
                setSuccessNotification('error', 'Oops!', "La etiqueta debe ser una sólo palabra");                    
                return false;
            }
        });

       $selectize = $select[0].selectize;


       // ALERTA DE ESTADO
       //console.log('FLOW: '+flow);
       if( flow == '1' ) {
            //$("#btn-submit").attr('disabled', true);
            //swal("{{ trans('document/document.swal.flow.text') }}");
            $("#flow-alert").css('display', 'block');
            $isSaved = true;
       }

        // BTN SALIR
        $('#btn-exit').on("click", function() {
            var referrer =  document.referrer;
            var url = $(this).data('href');
            //var exit = checkExit();
            //console.log(referrer);

            if( !$isSaved && flow != '1' ) {
                swal({ 
                    title: "{{ trans('document/document.swal.forget.title') }}",
                    text: "{{ trans('document/document.swal.forget.text') }}",
                    icon: "warning",
                    buttons: {
                        //confirm: {text: 'No', className:'swal-button'},
                        confirm: 'Si',
                        cancel: 'No',
                    },
                    dangerMode: true,
                })
                .then((willSend) => {
                    if (willSend) {
                        if( referrer.indexOf('control/documento') >= 0 ) {
                            history.back();
                        } else {
                            location.href = url;  
                        } // if/else                                                
                    } else {
                        return false;
                    } // if/else
                });
            } else {
                if( referrer.indexOf('control/documento') >= 0 ) {
                    history.back();
                } else {
                    location.href = url;  
                } // if/else
            }  // if/else
        }); // btn-exit
        
        $('body').on('change', '.form-control', function (e) {
            $isSaved = false;
        });         

        $('body').on('change', '#select-location', function (e) {            
            e.preventDefault();
            var id = $("#select-location").val();
            txt1 = "{{ trans('document/document.form.department.placeholder') }}";
            txt2 = "{{ trans('document/document.form.process.placeholder') }}";
            setDepartmentSelect(id, txt1, txt2);
        });

        $('body').on('change', '#select-department', function (e) {
            e.preventDefault();
            var url, txt;
            var id = $("#select-department").val();

            if( id > 0 ) {
                // Clear job lists and users lists
                clearJobSelect();
                // Select de procesos
                txt = "{{ trans('document/document.form.process.placeholder') }}";
                setProcessSelect(id, txt);
            }
        }); // change

        $('body').on('change', '#select-classes', function (e) {
            var val = $(this).val();            
            if( val != '') {
                setTags(val, null);
            }  // if         
        });        

        // Obtiene el código automático del documento
        $('body').on('change', "select[name='system_id'], select[name='department_id'], select[name='type_id'], select[name='location_id']", function (e) {
            var sid = $('select[name=system_id]').val();
            var pid = $('select[name=process_id]').val();
            var tid = $('select[name=type_id]').val();
            var lid = $('select[name=location_id]').val();
            console.log('onChange::Parameters: sid='+sid+' lid='+lid+' pid='+pid+' tid='+tid);
            if( sid != '' && sid !== null && pid != '' && pid !== null && tid != '' && tid !== null && lid != '' && lid !== null ) {
                //console.log('GET CODE!');
                setCode(sid, lid, pid, tid);    // FIXME: esta recalculado al editar la configuración
            } else {
                $("input[name='code']").val('');
                $("input[name='serial']").val('');                
            } // if
        });  

        // Genera el modal y tabla para los cargos
        $('body').on('click', '#btn-modal-job', function (e) {
            e.preventDefault();
            var tag = $(this).data('id');           // Identificador de la etapa
            var jids = $("#select-job-"+tag).val(); // Cargos seleccionados actuales
            var did = $("#select-department").val();

            // Contenido del modal
            if( tag == 'edit' ) {
                var linkTitle = "{{ trans('document/document.grid.jobs.edit_title') }}";
            } else if ( tag  == 'review') {
                var linkTitle = "{{ trans('document/document.grid.jobs.review_title') }}";
            } else {
                var linkTitle = "{{ trans('document/document.grid.jobs.approve_title') }}";
            }
            // Limpiar Filtros
            $("#jobs-table .col-filter").val('');
                        
            if(did > 0) {
                $("#status").val(tag);
                // Personalización del modal
                $("#modal-job-title").html(linkTitle);
                // Valores pervios
                //obtener datos anteriores
                var sids = [];
                if( tag == 'review' ) {
                    sids = $("#select-job-edit").val();
                }
                if( tag == 'approve' ) {
                    sids = $("#select-job-review").val();
                }                   

                // Generar la tabla
                //console.log('*** GENERAR LA TABLA #select-job-'+tag);
                //console.dir(jids);
                setJobTable(tag, did, JSON.stringify(jids), JSON.stringify(sids));                 
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.form.department.no_selected') }}"); // FIXME: to document.php
            }            
        }); // 

        // Boton de confirmación para cargos
        $('body').on('click', '#btn-job-ok', function (e) {
            e.preventDefault();
            var output = '';
            var tag = $("#status").val(); 
            var count = $jobTable.rows( { selected: true } ).count();
            if( count > 0 ) {
                var selected = $jobTable.rows( { selected: true } ).data();
                $.each(selected, function(i, value) {
                    //console.dir(value);
                    // Construir select
                    output += '<option value='+value[0]+' selected >'+value[1]+' - '+value[2]+'</option>';

                });
                $("#select-job-"+tag).html(output);
                $("#btn-job-ko").click();
            }
            else {
                // TODO: poner select vacío con placeholder
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.grid.jobs.error.no_selected') }}");
            }             
        }); // btn-job-ok

        // Boton de deseleccionar cargos de la lista
        $('body').on('click', '#btn-job-clear', function (e) {
            $jobTable.rows().deselect();
        });

        // Genera el modal y tabla para los usuarios
        $('body').on('click', '#btn-modal-user', function (e) {
            e.preventDefault();
            var tag = $(this).data('id');           // Identificador de la etapa
            var jids = $("#select-job-"+tag).val(); // Cargos seleccionados actuales
            var uids = $("#select-user-"+tag).val(); // Usuarios seleccionados actuales

            // Contenido del modal
            if( tag == 'edit' ) {
                var linkTitle = "{{ trans('document/document.grid.users.edit_title') }}";
            } else if ( tag  == 'review') {
                var linkTitle = "{{ trans('document/document.grid.users.review_title') }}";
            } else {
                var linkTitle = "{{ trans('document/document.grid.users.approve_title') }}";
            }
            // Limpiar
            $("#users-table .col-filter").val('');

            if(jids.length > 0) {
                $("#status").val(tag);
                // Personalización del modal
                $("#modal-user-title").html(linkTitle);
                // Recuperar valor de deadline
                var dl = $("input[name='deadline_"+tag+"']").val();
                $("input[name='deadline']").val(dl);                
                // Generar la tabla              
                setUserTable(JSON.stringify(jids), JSON.stringify(uids));                 
            } else {
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.form.users.no_selected') }}");
            }                         
        }); // btn-model-user

        // Boton de confirmación para usuarios
        $('body').on('click', '#btn-user-ok', function (e) {
            e.preventDefault();
            var output = '';
            var jsonObj = [];
            var item = {};
            var tag = $("#status").val(); 
            var count = $userTable.rows( { selected: true } ).count();
            if( count > 0 ) {
                //console.log('SALVANDO USER');
                // Construir select
                var selected = $userTable.rows( { selected: true } ).data();
                $.each(selected, function(i, value) {                    
                    output += '<option value='+value[0]+' selected >'+value[1]+' - '+value[2]+'</option>';
                    // Formar la relacion usuario->cargo
                    item [value[0]] = value[6];                                                         
                });
                $("#select-user-"+tag).html(output);

                // Recolectar el valor de deadline
                var dl = $("input[name='deadline']").val();
                $("input[name='deadline_"+tag+"']").val(dl);
                //console.log('deadline_'+tag+' :'+dl);

                // Guardar relacion usuario->cargo
                jsonObj.push(item);
                $("input[name='link_"+tag+"']").val(JSON.stringify(jsonObj)); 

                // Cerrar modal
                $("#btn-user-ko").click();
            }
            else {
                // TODO: poner select vacío con placeholder
                $("#select-user-"+tag).html("");
                setSuccessNotification('error', 'Oops!', "{{ trans('document/document.grid.users.error.no_selected') }}");
            }             
        }); // btn-user-ok
        
        // Boton de deseleccionar usuarios de la lista
        $('body').on('click', '#btn-user-clear', function (e) {
            $userTable.rows().deselect();
        });        

        setupOld();

    }); // document

    function setDepartmentSelect(id, txt1, txt2) {                    
        $.ajax({
            url: '/documentos/control/documento/departamento/'+id,
            type: 'GET',
            dataType: 'json',
            async: false,
            success: function(data) {
                //console.dir(data);                
                var output = (txt1 !== null) ? '<option value="">'+txt1+'</option>' : '';
                $.each(data, function(i, item) {
                    output += '<option value='+item.department_id+' class="text-sm';
                    output += ( item.selected ) ? '" selected' : '"'; 
                    output += '>'+item.name+'</option>';
                });
                $('#select-department').html(output);
                $("select[name='process_id']").html(txt2);
            } // success
        }); // ajax                
    } // setProcessSelect     

    function setProcessSelect(id, txt) {                       
        $.ajax({
            url: '/documentos/control/documento/proceso/'+id,
            type: 'GET',
            dataType: 'json',
            async: false,
            success: function(data) {
                //console.dir(data);
                if(data.success) {
                    var output = '<option value='+data.id+'>'+data.name+'</option>'; 
                    $("select[name='process_id']").html(output);
                } else {
                    $("select[name='process_id']").html(txt);
                    setSuccessNotification('error', 'Oops!', data.message);
                }

            } // success
        }); // ajax                
    } // setProcessSelect 
    
    function setCode(sid, lid, pid, tid) {
        //console.log('SetCode::Parameters: sid='+sid+' lid='+lid+' pid='+pid+' tid='+tid);
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '/documentos/control/documento/codigo',
            type: 'POST',
            dataType: 'json',
            data: {'sid':sid,'pid':pid,'tid':tid,'lid':lid},
            async: false,
            success: function(data) {
                console.log('=== AJAX CODE');
                console.dir(data);
                if( data.success ) {
                    $("input[name='code']").val(data.code);
                    $("input[name='serial']").val(data.serial);
                } else {
                    $("input[name='code']").val('');
                    $("input[name='serial']").val('');
                    setSuccessNotification('error', 'Oops!', data.message);
                }
            } // success
        }); // ajax  
    } // setCode

    function setTags(className, tagsName) {
        //console.log('className: '+className);
        $.ajax({
            url: '/documentos/control/documento/etiquetas/'+className,
            type: 'GET',
            dataType: 'json',
            async: false,
            success: function(json) {
                //console.dir(json);
                if(json.success) {
                    //console.log('=== AJAX TAGS');
                    //$("input[name='tags']").val(json.tags);
                    //console.dir(json.tags);
                    $selectize.clearOptions();
                    $selectize.addOption(json.tags);

                    // $.each(json.tags, function(i, tag) {
                    //     //console.log(tag.email, tag);
                    //     $selectize.addOption(tag.email, {email: tag.email});
                    // });
                    if( tagsName !== null  ) {
                        $selectize.setValue(tagsName);
                    }                    
                }
            } // success
        }); // ajax    
    } // setTags

    function clearJobSelect() {
        $("#select-job-edit").html('<option value="">{{ trans("document/document.form.job_edit.placeholder") }}</option>');
        $("#select-user-edit").html('<option value="">{{ trans("document/document.form.user_edit.placeholder") }}</option>');
        $("#select-job-review").html('<option value="">{{ trans("document/document.form.job_review.placeholder") }}</option>');
        $("#select-user-review").html('<option value="">{{ trans("document/document.form.user_review.placeholder") }}</option>');
        $("#select-job-approve").html('<option value="">{{ trans("document/document.form.job_approve.placeholder") }}</option>');
        $("#select-user-approve").html('<option value="">{{ trans("document/document.form.user_approve.placeholder") }}</option>');        
    }  //  clearJobSelect 

    function setJobTable(tag, did, jsonJids, jsonSids) {
        $('#jobs-table').dataTable().fnDestroy();
        var selects = [2];
        var inputs = [1,3];
        var lang = {!! $gridJobsLanguage !!};

        // Generar la tabla
        $.ajax({
            type: 'POST',
            data: {'previous':jsonSids,'id':did,'json':jsonJids},
            dataType: 'json',
            url: '/documentos/control/documento/lista/cargos',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(json) {               
                if( json.success) {
                    var dataSet = $.parseJSON(json.grid);
                    // DATATABLE
                    $jobTable = new DataTable("#jobs-table", {
                        data: dataSet,
                        columnDefs: [
                            { targets: [4,5,6,7], visible: false, searchable: false },
                            //{ targets: 1, className: "dt-nowrap", },
                            { targets: 3, width: "50%", },
                        ],                        
                        order: [[ 7, 'asc' ]],
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
                            $this.columns( [0,4,5,6,7] ).visible( false );
                            // Modal
                            $("#modal-job-open")[0].click();
                        },
                        language: lang                        
                    }); // datatable

                    $jobTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                        var data = this.data();
                        if (data[4] == 1) {
                            this.select();
                        }                                                 
                        if (data[6] == 0) {
                            $(this.node()).addClass('redColorClass');
                        } else  if (data[5] == 1) {
                            $(this.node()).addClass('greenColorClass');
                        }                        
                    });
                } else {
                    setSuccessNotification('error', 'Oops!', "Se ha presentado un error al generar la tabla de cargos");  // FIXME: to document.php
                }
            } // success
        }); // ajax
       
    } // setJobTable()

    function setUserTable(jsonJids, jsonUids) {
        $('#users-table').dataTable().fnDestroy();
        var selects = [2,3];
        var inputs = [1];
        var lang = {!! $gridUsersLanguage !!};

        $.ajax({
            type: 'POST',
            data: {'jsonJ':jsonJids,'jsonU':jsonUids},
            dataType: 'json',
            url: '/documentos/control/documento/lista/usuarios',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(json) {               
                if( json.success) {
                    dataSet = $.parseJSON(json.grid);
                    //console.dir(dataSet);
                    // DATATABLE
                    $userTable = new DataTable("#users-table", {
                        data: dataSet,
                        columnDefs: [
                            { targets: [0,4,5,6], visible: false, searchable: false },
                            //{ targets: 1, className: "dt-nowrap", },
                        ],                        
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
                            $this.columns( [0] ).visible( false );
                            // Modal
                            $("#modal-user-open")[0].click();
                        },
                        language: lang                        
                    }); // datatable

                    $userTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                        var data = this.data();
                        if (data[4] == 1) {
                            this.select();
                        }                        
                        if (data[5] == 0) {
                            $(this.node()).addClass('redColorClass');
                        }                        
                    });
                } else {
                    setSuccessNotification('error', 'Oops!', "Se ha presentado un error al generar la tabla de cargos"); // FIXME: to document.php
                }
            } // success
        }); // ajax        
  
    } // setUserTable

    function checkExit() {
        if( !$isSaved ) {
            swal({ 
                title: "{{ trans('document/document.swal.forget.title') }}",
                text: "{{ trans('document/document.swal.forget.text') }}",
                icon: "warning",
                buttons: {
                    confirm: {text: 'No', className:'swal-button'},
                    cancel: 'Si'
                },
                dangerMode: true,
            })
            .then((willSend) => {
                if (willSend) {
                    return false;
                }
                return true;
            });
        } else {
            return true;
        } // if
    } // checkExit

    function setupOld() {
        var dpto = "{{ old('department_id', isset($document) ? $document->department_id : '' ) }}";
        var loct = "{{ old('location_id', isset($document) ? $document->location_id : '' ) }}";
        var proc = "{{ old('process_id', isset($document) ? $document->process_id : '' ) }}";
        var code = "{{ isset($document) ? $document->code : '' }}";
        var seri = "{{ isset($document) ? $document->serial : '' }}";
        var className = "{{ old('class', isset($document) ? $document->class : '' ) }}";
        var str = "{{ old('tags', isset($document) ? $document->tags : '' ) }}";
        var tags = new Array();
        var existsEditJob = false;
        var existsReviewJob = false;
        var existsApproveJob = false;
        //console.log('*** SETUP');

        if( dpto != '')  {
            $("#select-location").val(loct).trigger('change'); 
            $("#select-department").val(dpto).trigger('change');
            $("input[name='process_id']").val(proc);
            
            // Mantener el código y el serial original después de que recalcule el código !importante
            if( code != '' ) {
                $("input[name='code']").val(code);
                $("input[name='serial']").val(seri);
            }

            // Etiquetas
            //console.log('*** TAGS -------');
            tags = str.split(",");
            //console.dir(tags);
            setTags(className, tags);
            
            // cargos
            $.ajax({
                url: '/documentos/control/documento/selector/cargos',
                type: 'GET',
                dataType: 'json',
                async: false,
                success: function(data) {
                    //console.dir(data);
                    var editJobs =  {!! json_encode(old('job_edit_id', isset($document) ? $document->job_edit_id : [])) !!};
                    var reviewJobs =  {!! json_encode(old('job_review_id', isset($document) ? $document->job_review_id : [])) !!};
                    var approveJobs =  {!! json_encode(old('job_approve_id', isset($document) ? $document->job_approve_id : [])) !!};
                    var editOutput = '';
                    var reviewOutput = '';
                    var approveOutput = '';
                    var n;
                    //console.log('=== ARRAY JOBS ===');
                    //console.dir(editJobs);
                    $.each(data, function(i, job) {
                        n = job.job_id.toString();
                        //console.log('check: '+ job.job_id);
                        // Edit
                        if( $.inArray( n, editJobs) != -1 ) {
                            existsEditJob = true;
                            editOutput += '<option value='+job.job_id+' selected >'+job.name+'</option>';
                        }
                        // Review
                        if( $.inArray( n, reviewJobs) != -1 ) {
                            existsReviewJob = true;
                            reviewOutput += '<option value='+job.job_id+' selected >'+job.name+'</option>';
                        }
                        // Approve
                        if( $.inArray( n, approveJobs) != -1 ) {
                            existsApproveJob = true;
                            approveOutput += '<option value='+job.job_id+' selected >'+job.name+'</option>';
                        }                                                      
                    });
                    $("#select-job-edit").html(editOutput);
                    $("#select-job-review").html(reviewOutput);
                    $("#select-job-approve").html(approveOutput);
                } // success
            }); // ajax

            // usuarios
            $.ajax({
                url: '/documentos/control/documento/selector/usuarios',
                type: 'GET',
                dataType: 'json',
                async: false,
                success: function(data) {
                    //console.dir(data);
                    var editUsers =  {!! json_encode(old('user_edit_id', isset($document) ? $document->user_edit_id : [])) !!};
                    var reviewUsers =  {!! json_encode(old('user_review_id', isset($document) ? $document->user_review_id : [])) !!};
                    var approveUsers =  {!! json_encode(old('user_approve_id', isset($document) ? $document->user_approve_id : [])) !!};
                    var editOutput = '';
                    var reviewOutput = '';
                    var approveOutput = '';
                    var editChecks =  {!! json_encode(isset($document) ? $document->check_edit_id : []) !!};
                    var reviewChecks =  {!! json_encode(isset($document) ? $document->check_review_id : []) !!};
                    var approveChecks =  {!! json_encode(isset($document) ? $document->check_approve_id : []) !!};
                    var n;
                    var checked;
                    var j = 0;
                    var jobEditOptions = '';
                    var jobReviewOptions = '';
                    var jobApproveOptions = '';
                    //console.log('=== ARRAY USERS ===');
                    //console.dir(editUsers);
                    $.each(data, function(i, user) {
                        n = user.user_id.toString();
                        //console.log('check: '+n+' in ');
                        //console.dir(editUsers);
                        // Edit                        
                        if( ($.inArray( user.user_id, editUsers) != -1) || ($.inArray( n, editUsers) != -1) ) {
                            if( editChecks[n] == 'SI' ) {
                                checked = ' class="text-red-500"';
                                $("#flow-edit").css('display', 'block'); 
                            } else {
                                checked = '';    
                            }                            
                            editOutput += '<option'+checked+' value='+user.user_id+' selected >'+user.name+'</option>';
                            if( !existsEditJob ) {
                                console.log('Add Edit jobs...');
                                $.each(user.jobs, function(j, job) {
                                    console.log(j +' | '+ job.name);
                                    jobEditOptions += '<option value='+job.job_id+' selected >'+job.name+'</option>';
                                });                                
                            } // if
                        } // if
                        // Review
                        if( ($.inArray( user.user_id, reviewUsers) != -1) || ($.inArray( n, reviewUsers) != -1) ) {
                            if( reviewChecks[n] == 'SI' ) {
                                checked = ' class="text-red-500"';
                                $("#flow-review").css('display', 'block');
                            } else {
                                checked = '';
                            }                    
                            reviewOutput += '<option'+checked+' value='+user.user_id+' selected >'+user.name+'</option>';
                            if( !existsReviewJob ) {
                                console.log('Add Review jobs...');
                                $.each(user.jobs, function(j, job) {
                                    console.log(j +' | '+ job.name);
                                    jobReviewOptions += '<option value='+job.job_id+' selected >'+job.name+'</option>';
                                });                                
                            } // if                            
                        }
                        // Approve
                        if( ($.inArray( user.user_id, approveUsers) != -1) || ($.inArray( n, approveUsers) != -1) ) {
                            if( approveChecks[n] == 'SI' ) {
                                checked = ' class="text-red-500"';
                                $("#flow-approve").css('display', 'block');
                            } else {
                                checked = '';
                            }                          
                            approveOutput += '<option'+checked+' value='+user.user_id+' selected >'+user.name+'</option>';
                            if( !existsApproveJob ) {
                                console.log('Add Approve jobs...');
                                $.each(user.jobs, function(j, job) {
                                    console.log(j +' | '+ job.name);
                                    jobApproveOptions += '<option value='+job.job_id+' selected >'+job.name+'</option>';
                                });                                
                            } // if                              
                        } // if
                        j = j + 1;
                        
                        
                    });
                    $("#select-user-edit").html(editOutput);
                    $("#select-user-review").html(reviewOutput);
                    $("#select-user-approve").html(approveOutput);
                    $("#loading-image").hide();
                    if( jobEditOptions != '' ) {
                        $("#select-job-edit").html(jobEditOptions);
                    }
                    if( jobReviewOptions != '' ) {
                        $("#select-job-review").html(jobReviewOptions);
                    }
                    if( jobApproveOptions != '' ) {
                        $("#select-job-approve").html(jobApproveOptions);
                    }                                          
                } // success
            }); // ajax            
        } else {
            $("#loading-image").hide();
        } // if
    } // setupOld()    

</script>

@include('components.notification_error')

@endpush
</x-icewall> 