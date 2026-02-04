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
                            <button id="btn-save" type="button" form="file-form" class="btn btn-primary shadow-md mr-2" onClick="setStatus(0)" > <i data-lucide="save" class="w-5 h-5"></i> </button>
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
                                    @method('PUT')
                                    <input type="hidden" name="file_id"  id="file-id" value={{ $DATA->file_id }}>
                                    <input type="hidden" id="topic-is" value={{ isset($DATA->topic_id) ? $DATA->topic_id : 0 }}>
                                    <input type="hidden" id="subtopic-is" value={{ isset($DATA->subtopic_id) ? $DATA->subtopic_id : 0 }}>
                                    <input type="hidden" id="job-is" value={{ isset($DATA->job_id) ? $DATA->job_id : 0 }}>
                                    <div class="input-group">
                                        <div id="system-id" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/file.form.system.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.system.title') }}</div>
                                        <select name="system_id" class="form-control w-full">
                                            <option value=0>{{ trans('document/file.form.system.placeholder') }}</option>
                                            @foreach($systems as $system)   
                                            <option value={{ $system->system_id }} @if( $system->system_id == $DATA->system_id ) selected @endif >{{ $system->name }}</option>
                                            @endforeach
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.system.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="location-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.location.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.location.title') }}</div>
                                        <select name="location_id" class="form-control w-full">
                                        @if( $locations['n'] == 1 )
                                            <option value={{ $locations['data']->location_id }} selected>{{ $locations['data']->name }}</option>
                                        @else
                                            <option value=0>{{ trans('document/file.form.location.placeholder') }}</option>
                                            @foreach($locations['data'] as $location)   
                                            <option value={{ $location->location_id }} @if( $location->location_id == $DATA->location_id ) selected @endif >{{ $location->name }}</option>
                                            @endforeach
                                        @endif
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.location.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="department-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.department.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.department.title') }}</div>
                                        <select id="department-select" name="department_id" class="form-control w-full">
                                        @if( $departments['n'] == 1 )
                                            <option value={{ $departments['data']->department_id }} selected>{{ $departments['data']->name }}</option>
                                        @else
                                            <option value=0>{{ trans('document/file.form.department.placeholder') }}</option>
                                            @foreach($departments['data'] as $department)   
                                            <option value={{ $department->department_id }} @if( $department->department_id == $DATA->department_id ) selected @endif >{{ $department->name }}</option>
                                            @endforeach
                                        @endif                                          
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.department.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="topic-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.topic.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.topic.title') }}</div>
                                        <select id="topic-select" name="topic_id" class="form-control w-full">
                                            <option value=0>{{ trans('document/file.form.topic.placeholder') }}</option>                                          
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.topic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="subtopic-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.subtopic.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.subtopic.title') }}</div>
                                        <select id="subtopic-select" name="subtopic_id" class="form-control w-full">
                                            <option value=0>{{ trans('document/file.form.subtopic.placeholder') }}</option>                                          
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.subtopic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>                                                                                                                                                                                   
                                    <div class="input-group mt-3">
                                        <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.code.title') }}</div>
                                        <input type="text" name="code" value="{{ $DATA->code }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('document/file.form.code.placeholder') }}"  maxlength="255" readonly required>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>                                   
                                    <div class="input-group mt-3">
                                        <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.name.title') }}</div>
                                        <input type="text" name="name" value="{{ $DATA->name }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/file.form.name.placeholder') }}"  maxlength="255" minlength="2" required>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="job-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.job.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.job.title') }}</div>
                                        <select id="job-select"  name="job_id" class="form-control w-full">
                                            <option value=0>{{ trans('document/file.form.job.placeholder') }}</option>                                    
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.job.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
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
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script document="text/javascript">
    $(function () {

        $('body').on('change', '#department-select', function (e)  {
            e.preventDefault();
            var did = this.value;
            console.log('DID: '+did);
            if( did > 0 ) {
                setTopicsAjax(did);
                setJobsAjax(did);
            }            
        }); // change #department-select Event

        $('body').on('change', '#topic-select', function (e)  {
            e.preventDefault();
            var tid = this.value;
            console.log('TID: '+tid);
            if( tid > 0 ) {
                setSubtopicsAjax(tid);                
            }            
        }); // change #topic-select Event
     

    }); // document
    
    $(document).ready(function() {
        $("#department-select").trigger('change');         
    });

    function setTopicsAjax(id) {
        var url = "{{ route('files.list.topics', ':id') }}";
        console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                console.dir(json);
                if( json.success ) {
                    //setSuccessNotification('success', '', json.message);
                    var tid = $("#topic-is").val();
                    console.log('TID*: '+tid);
                    generateTopicsSelect(tid, json.topics);
                    $("#topic-select").trigger('change');
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setTopicsAjax

    function generateTopicsSelect(id, topics) {
        var output = '<option value=0>{{ trans("document/file.form.topic.placeholder") }}</option>';
        if( topics.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.topic.no-exist") }}'); 
        } else {
            $.each(topics, function(i, topic) {
                output += '<option value='+topic.topic_id;
                output += ( topic.topic_id == id ) ? ' selected' : '';
                //output += '>['+topic.newCode+'] '+topic.name+'</option>';
                output += '>'+topic.name+'</option>';
            });
        }
        $("#topic-select").html(output);
    } // generateTopicsSelect 
    
    function setSubtopicsAjax(id) {
        var url = "{{ route('files.list.subtopics', ':id') }}";
        console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                console.dir(json);
                if( json.success ) {
                    var sid = $("#subtopic-is").val();
                    console.log('SIP: '+sid);
                    generateSubtopicsSelect(sid, json.subtopics);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setSubtopicsAjax

    function generateSubtopicsSelect(id, subtopics) {
        var output = '<option value=0>{{ trans("document/file.form.subtopic.placeholder") }}</option>';
        if( subtopics.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.subtopic.no-exist") }}'); 
        } else {
            $.each(subtopics, function(i, subtopic) {
                output += '<option value='+subtopic.subtopic_id;
                output += ( subtopic.subtopic_id == id ) ? ' selected' : '';
                output += '>'+subtopic.name+'</option>';
            });
        }
        $("#subtopic-select").html(output);
    } // generateSubtopicsSelect

    function setJobsAjax(id) {
        var url = "{{ route('files.list.jobs', ':id') }}";
        console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                console.dir(json);
                if( json.success ) {
                    var jid = $("#job-is").val();
                    console.log('JIP: '+jid);
                    generateJobsSelect(jid, json.jobs);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setJobsAjax

    function generateJobsSelect(id, jobs) {
        var output = '<option value=0>{{ trans("document/file.form.job.placeholder") }}</option>';
        if( jobs.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.job.no-exist") }}'); 
        } else {
            $.each(jobs, function(i, job) {
                output += '<option value='+job.job_id;
                output += ( job.job_id == id ) ? ' selected' : '';
                output += '>'+job.name+'</option>';
            });
        }
        $("#job-select").html(output);
    } // generateJobsSelect        


</script>
               
    @include('components.notification_index')

@endpush                
</x-icewall>     