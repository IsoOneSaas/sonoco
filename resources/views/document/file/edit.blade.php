<!-- resources/views/document/file.edit.blade.php -->
<x-icewall>

    <x-slot:title>
        Archivo - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Editar Archivo
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button id="btn-save" type="submit" form="file-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('files.admin.index') }}" alt="Regresar a la tabla"><i data-lucide="menu" class="w-5 h-5"></i></a>    
                        </div>
                    </div>
                    <!-- BEGIN: Boxed Tab -->
                    <div class="intro-y box mt-5">
                        <div id="boxed-tab" class="p-5">
                            <div class="preview">
                                <!-- BEGIN: Form -->
                                <form id="file-form" action="{{ route('files.admin.store', $DATA->file_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="file_id"  id="file-id" value={{ old('file_id', isset($DATA) ? $DATA->file_id : '') }}>
                                    <input type="hidden" id="topic-is" value={{ isset($DATA->topic_id) ? $DATA->topic_id : '' }}>
                                    <input type="hidden" id="subtopic-is" value={{ isset($DATA->subtopic_id) ? $DATA->subtopic_id : '' }}>
                                    <input type="hidden" id="job-is" value={{ isset($DATA->job_id) ? $DATA->job_id : '' }}>
                                    <div class="input-group">
                                        <div id="system-id" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/file.form.system.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.system.title') }}</div>
                                        <select name="system_id" class="form-control w-full" required>
                                            <option value=''>{{ trans('document/file.form.system.placeholder') }}</option>
                                            @foreach($systems as $system)   
                                            <option value={{ $system->system_id }} {{ old("system_id", isset($DATA) ? $DATA->system_id : '' ) == $system->system_id ? 'selected ' : '' }}>{{ $system->name }}</option>
                                            @endforeach
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.system.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="location-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.location.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.location.title') }}</div>
                                        <select id="location-select" name="location_id" class="form-control w-full" required>
                                        @if( $locations['n'] == 1 )
                                            <option value={{ $locations['data']->location_id }} selected>{{ $locations['data']->name }}</option>
                                        @else
                                            <option value=''>{{ trans('document/file.form.location.placeholder') }}</option>
                                            @foreach($locations['data'] as $location)   
                                            <option value={{ $location->location_id }} {{ old("location_id", isset($DATA) ? $DATA->location_id : '' ) == $location->location_id ? 'selected ' : '' }}>{{ $location->name }}</option>
                                            @endforeach
                                        @endif
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.location.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="department-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.department.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.department.title') }}</div>
                                        <select id="department-select" name="department_id" class="form-control w-full" required>
                                        @if( $departments['n'] == 1 )
                                            <option value={{ $departments['data']->department_id }} selected>{{ $departments['data']->name }}</option>
                                        @else
                                            <option value=''>{{ trans('document/file.form.department.placeholder') }}</option>
                                            @foreach($departments['data'] as $department)   
                                            <option value={{ $department->department_id }} {{ old("department_id", isset($DATA) ? $DATA->department_id : '' ) == $department->department_id ? 'selected ' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        @endif                                          
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.department.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="topic-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.topic.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.topic.title') }}</div>
                                        <select id="topic-select" name="topic_id" class="form-control w-full mr-2" required>
                                            <option value=''>{{ trans('document/file.form.topic.placeholder1') }}</option>
                                            @foreach($topics as $topic)   
                                            <option value={{ $topic->topic_id }} {{ old("topic_id", isset($DATA) ? $DATA->topic_id : '' ) == $topic->topic_id ? 'selected ' : '' }}>{{ $topic->name }}</option>
                                            @endforeach                                                                                      
                                        </select>
                                        <input type="text" id="input-new-topic" class="form-control mr-2"  placeholder="{{ trans('document/file.form.topic.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-topic" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.topic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="subtopic-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.subtopic.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.subtopic.title') }}</div>
                                        <select id="subtopic-select" name="subtopic_id" class="form-control w-full" required>
                                            <option value=''>{{ trans('document/file.form.subtopic.placeholder1') }}</option>
                                            @foreach($subtopics as $subtopic)   
                                            <option value={{ $subtopic->subtopic_id }} {{ old("subtopic_id", isset($DATA) ? $DATA->subtopic_id : '' ) == $subtopic->subtopic_id ? 'selected ' : '' }}>{{ $subtopic->name }}</option>
                                            @endforeach                                             
                                        </select>
                                        <input type="text" id="input-new-subtopic" class="form-control mr-2"  placeholder="{{ trans('document/file.form.subtopic.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-subtopic" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>                                                                            
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.subtopic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div> 
                                    
                                    
                                    <div class="input-group mt-3">
                                        <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.code.title') }}</div>
                                        <input type="text" name="code" value="{{ old('code', isset($DATA) ? $DATA->code : '') }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('document/file.form.code.placeholder') }}"  maxlength="255" readonly required>
                                        <img id="loading-image" alt="Cargando..." class="h-auto max-w-xs mx-auto" width="30" height="30" src="{{ url('/assets/images/loading_small.gif') }}">
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>                                   
                                    <div class="input-group mt-3">
                                        <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.name.title') }}</div>
                                        <input type="text" name="name" value="{{ old('name', isset($DATA) ? $DATA->name : '') }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/file.form.name.placeholder') }}"  maxlength="255" minlength="2" required>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="job-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.job.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.job.title') }}</div>
                                        <select id="job-select"  name="job_id" class="form-control w-full" required>
                                            <option value=''>{{ trans('document/file.form.job.placeholder') }}</option>
                                            @foreach($jobs as $job)   
                                            <option value={{ $job->job_id }} {{ old("job_id", isset($DATA) ? $DATA->job_id : '' ) == $job->job_id ? 'selected ' : '' }}>{{ $job->name }}</option>
                                            @endforeach                                                                                
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.job.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="support" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/file.form.support.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.support.title') }}</div>
                                        <select name="support" class="form-control w-full">
                                            <option value=0>{{ trans('document/file.form.support.placeholder') }}</option>
                                            @foreach($supports as $key => $support)   
                                            <option value={{ $key }}  {{ old("support", isset($DATA) ? $DATA->support : 0 ) == $key ? 'selected ' : '' }}>{{ $support }}</option>
                                            @endforeach                                            
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.support.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="storage" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.storage.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.storage.title') }}</div>
                                        <input type="text" name="storage" value="{{ old('storage', isset($DATA) ? $DATA->storage : '') }}" class="form-control  w-full" aria-describedby="storage" placeholder="{{ trans('document/file.form.storage.placeholder') }}"  maxlength="255">
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.storage.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="classification" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.classification.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.classification.title') }}</div>
                                        <input type="text" name="classification" value="{{ old('classification', isset($DATA) ? $DATA->classification : '') }}" class="form-control  w-full" aria-describedby="classification" placeholder="{{ trans('document/file.form.classification.placeholder') }}"  maxlength="255">
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.classification.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="index-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.index.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.index.title') }}</div>
                                        <select name="index_id" class="form-control mr-2">
                                            <option value=''>{{ trans('document/file.form.index.placeholder1') }}</option>
                                            @foreach($indexes as $index)   
                                            <option value={{ $index->index_id }} {{ old("index_id", isset($DATA) ? $DATA->index_id : 0 ) == $index->index_id ? 'selected ' : '' }}>{{ $index->name }}</option>
                                            @endforeach                                            
                                        </select>
                                        <input type="text" id="input-new-index" class="form-control mr-2"  placeholder="{{ trans('document/file.form.index.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-index" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.index.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="disposal-id" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.disposal.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.disposal.title') }}</div>
                                        <select name="disposal_id" class="form-control mr-2">
                                            <option value=''>{{ trans('document/file.form.disposal.placeholder1') }}</option>
                                            @foreach($disposals as $disposal)   
                                            <option value={{ $disposal->disposal_id }} {{ old("disposal_id", isset($DATA) ? $DATA->disposal_id : 0 ) == $disposal->disposal_id ? 'selected ' : '' }}>{{ $disposal->name }}</option>
                                            @endforeach                                            
                                        </select>
                                        <input type="text" id="input-new-disposal" class="form-control mr-2"  placeholder="{{ trans('document/file.form.disposal.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-disposal" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.disposal.tooltip') }}" tabdisposal="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="dwell" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.dwell.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.dwell.title') }}</div>
                                        <input type="text" name="dwell_date" value="{{ old('dwell_date', isset($DATA) ? $DATA->dwell_date : '') }}" class="form-control mr-2" aria-describedby="dwell_date" placeholder="{{ trans('document/file.form.dwell.placeholder1') }}">
                                        <input type="number" name="dwell_value" value="{{ old('dwell_value', isset($DATA) ? $DATA->dwell_value : 0) }}" class="form-control mr-2" aria-describedby="dwell_value" placeholder="{{ trans('document/file.form.dwell.placeholder2') }}" min="0">
                                        <select name="dwell_frequency" class="form-control">
                                            <option value=''>{{ trans('document/file.form.dwell.placeholder3') }}</option>
                                            @foreach($frequencies as $frequency)   
                                            <option value="{{ $frequency }}" {{ old("dwell_frequency", isset($DATA) ? $DATA->dwell_frequency : 0 ) == $frequency ? 'selected ' : '' }}>{{ $frequency }}</option>
                                            @endforeach                                            
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.dwell.tooltip') }}" tabdwell="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="dead" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.dead.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.dead.title') }}</div>
                                        <input type="text" name="dead_date" value="{{ old('dead_date', isset($DATA) ? $DATA->dead_date : '') }}" class="form-control mr-2" aria-describedby="dead_date" placeholder="{{ trans('document/file.form.dead.placeholder1') }}">
                                        <input type="number" name="dead_value" value="{{ old('dead_value', isset($DATA) ? $DATA->dead_value : 0) }}" class="form-control mr-2" aria-describedby="dead_value" placeholder="{{ trans('document/file.form.dead.placeholder2') }}" min="0">
                                        <select name="dead_frequency" class="form-control">
                                            <option value=''>{{ trans('document/file.form.dead.placeholder3') }}</option>
                                            @foreach($frequencies as $frequency)   
                                            <option value="{{ $frequency }}" {{ old("dead_frequency", isset($DATA) ? $DATA->dead_frequency : 0 ) == $frequency ? 'selected ' : '' }}>{{ $frequency }}</option>
                                            @endforeach                                            
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.dead.tooltip') }}" tabdead="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="hold" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.hold.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.hold.title') }}</div>                                        
                                        <input type="number" name="hold_value" value="{{ old('hold_value', isset($DATA) ? $DATA->hold_value : 0) }}" class="form-control w-xs mr-2" aria-describedby="hold_value" placeholder="{{ trans('document/file.form.hold.placeholder1') }}" min="0">
                                        <select name="hold_frequency" class="form-control">
                                            <option value=''>{{ trans('document/file.form.hold.placeholder2') }}</option>
                                            @foreach($frequencies as $frequency)   
                                            <option value="{{ $frequency }}" {{ old("hold_frequency", isset($DATA) ? $DATA->hold_frequency : 0 ) == $frequency ? 'selected ' : '' }}>{{ $frequency }}</option>
                                            @endforeach                                            
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.hold.tooltip') }}" tabhold="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>                                                                                                                                                                                                                                                                                                                                         
                                </form>
                                <!-- END: Form -->
                            </div> 
                        </div>
                    </div>
                    <!-- END: Boxed Tab -->
                </div>
                <!-- END: Content -->                     

@push('meta')                
<meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/daterangepicker-master/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/js/daterangepicker-master/moment.min.js') }}"></script>
<script src="{{ url('assets/js/daterangepicker-master/daterangepicker.js') }}"></script>

    @include('document.file.javascript')

<script>
    $(document).ready(function() {
        $("#loading-image").hide();      
    });    
</script>
               
    @include('components.notification_index')

@endpush                
</x-icewall>     