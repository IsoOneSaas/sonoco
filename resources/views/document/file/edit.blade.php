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
                                    <input type="hidden" id="code-is" value="{{ isset($DATA->code) ? $DATA->code : '' }}">
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
                                        <select id="location-select" name="location_id" class="form-control w-full">
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
                                        <select id="topic-select" name="topic_id" class="form-control w-full mr-2">
                                            <option value=0>{{ trans('document/file.form.topic.placeholder1') }}</option>                                          
                                        </select>
                                        <input type="text" id="input-new-topic" class="form-control mr-2"  placeholder="{{ trans('document/file.form.topic.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-topic" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.topic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="subtopic-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.subtopic.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.subtopic.title') }}</div>
                                        <select id="subtopic-select" name="subtopic_id" class="form-control w-full">
                                            <option value=0>{{ trans('document/file.form.subtopic.placeholder1') }}</option>                                          
                                        </select>
                                        <input type="text" id="input-new-subtopic" class="form-control mr-2"  placeholder="{{ trans('document/file.form.subtopic.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-subtopic" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>                                                                            
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.subtopic.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div> 
                                    
                                    
                                    <div class="input-group mt-3">
                                        <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.code.title') }}</div>
                                        <input type="text" name="code" value="{{ $DATA->code }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('document/file.form.code.placeholder') }}"  maxlength="255" readonly required>
                                        <img id="loading-image" alt="Cargando..." class="h-auto max-w-xs mx-auto" width="30" height="30" src="{{ url('/assets/images/loading_small.gif') }}">
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
                                    <div class="input-group mt-3">
                                        <div id="support" class="input-group-text flex w-56"><i data-lucide="{{ trans('document/file.form.support.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.support.title') }}</div>
                                        <select name="support" class="form-control w-full">
                                            <option value=0>{{ trans('document/file.form.support.placeholder') }}</option>
                                            @foreach($supports as $key => $support)   
                                            <option value={{ $key }} @if( $key == $DATA->support ) selected @endif >{{ $support }}</option>
                                            @endforeach                                            
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.support.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="storage" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.storage.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.storage.title') }}</div>
                                        <input type="text" name="storage" value="{{ $DATA->storage }}" class="form-control  w-full" aria-describedby="storage" placeholder="{{ trans('document/file.form.storage.placeholder') }}"  maxlength="255">
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.storage.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="classification" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.classification.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.classification.title') }}</div>
                                        <input type="text" name="classification" value="{{ $DATA->classification }}" class="form-control  w-full" aria-describedby="classification" placeholder="{{ trans('document/file.form.classification.placeholder') }}"  maxlength="255">
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.classification.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="index-id" class="input-group-text flex"><i data-lucide="{{ trans('document/file.form.index.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.index.title') }}</div>
                                        <select name="index_id" class="form-control mr-2">
                                            <option value=0>{{ trans('document/file.form.index.placeholder1') }}</option>
                                            @foreach($indexes as $index)   
                                            <option value={{ $index->index_id }} @if( $index->index_id == $DATA->index_id ) selected @endif >{{ $index->name }}</option>
                                            @endforeach                                            
                                        </select>
                                        <input type="text" id="input-new-index" class="form-control mr-2"  placeholder="{{ trans('document/file.form.index.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-index" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.index.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="disposal-id" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.disposal.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.disposal.title') }}</div>
                                        <select name="disposal_id" class="form-control mr-2">
                                            <option value=0>{{ trans('document/file.form.disposal.placeholder1') }}</option>
                                            @foreach($disposals as $disposal)   
                                            <option value={{ $disposal->disposal_id }} @if( $disposal->disposal_id == $DATA->disposal_id ) selected @endif >{{ $disposal->name }}</option>
                                            @endforeach                                            
                                        </select>
                                        <input type="text" id="input-new-disposal" class="form-control mr-2"  placeholder="{{ trans('document/file.form.disposal.placeholder2') }}"  maxlength="255">
                                        <button id="btn-new-disposal" class="btn btn-primary shadow-md input-status" type="button" data-te-ripple-init><i data-lucide="plus" class="w-4 h-4"></i></button>
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.disposal.tooltip') }}" tabdisposal="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>

                                    <div class="input-group mt-3">
                                        <div id="dwell" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.dwell.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.dwell.title') }}</div>
                                        <input type="text" name="dwell_date" value="{{ $DATA->dwell_date }}" class="form-control mr-2" aria-describedby="dwell_date" placeholder="{{ trans('document/file.form.dwell.placeholder1') }}">
                                        <input type="number" name="dwell_value" value="{{ $DATA->dwell_value }}" class="form-control mr-2" aria-describedby="dwell_value" placeholder="{{ trans('document/file.form.dwell.placeholder2') }}" min="0">
                                        <select name="dwell_frequency" class="form-control">
                                            <option value=''>{{ trans('document/file.form.dwell.placeholder3') }}</option>
                                            @foreach($frequencies as $frequency)   
                                            <option value="{{ $frequency }}" @if( $frequency == $DATA->dwell_frequency ) selected @endif >{{ $frequency }}</option>
                                            @endforeach                                            
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.dwell.tooltip') }}" tabdwell="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="dead" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.dead.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.dead.title') }}</div>
                                        <input type="text" name="dead_date" value="{{ $DATA->dead_date }}" class="form-control mr-2" aria-describedby="dead_date" placeholder="{{ trans('document/file.form.dead.placeholder1') }}">
                                        <input type="number" name="dead_value" value="{{ $DATA->dead_value }}" class="form-control mr-2" aria-describedby="dead_value" placeholder="{{ trans('document/file.form.dead.placeholder2') }}" min="0">
                                        <select name="dead_frequency" class="form-control">
                                            <option value=''>{{ trans('document/file.form.dead.placeholder3') }}</option>
                                            @foreach($frequencies as $frequency)   
                                            <option value="{{ $frequency }}" @if( $frequency == $DATA->dead_frequency ) selected @endif >{{ $frequency }}</option>
                                            @endforeach                                            
                                        </select>                                    
                                        <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/file.form.dead.tooltip') }}" tabdead="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                    </div>
                                    <div class="input-group mt-3">
                                        <div id="hold" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/file.form.hold.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/file.form.hold.title') }}</div>                                        
                                        <input type="number" name="hold_value" value="{{ $DATA->hold_value }}" class="form-control w-xs mr-2" aria-describedby="hold_value" placeholder="{{ trans('document/file.form.hold.placeholder1') }}" min="0">
                                        <select name="hold_frequency" class="form-control">
                                            <option value=''>{{ trans('document/file.form.hold.placeholder2') }}</option>
                                            @foreach($frequencies as $frequency)   
                                            <option value="{{ $frequency }}" @if( $frequency == $DATA->hold_frequency ) selected @endif >{{ $frequency }}</option>
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
<script document="text/javascript">
    $(function () {

        

        // *** SELECTS
        $('body').on('change', '#location-select', function (e) {
            var lid = $(this).val();
            console.log('LID: '+lid);
            if( lid > 0 ) {
                setDepartmentsAjax(lid);
            } else {
                $("#department-select").html('<option value=0>{{ trans("document/file.form.department.placeholder") }}</option>');
            }
        }); // change #location-select Event

        $('body').on('change', '#department-select', function (e)  {
            e.preventDefault();
            var did = this.value;
            console.log('DID: '+did);
            if( did > 0 ) {
                setTopicsAjax(did);
                setJobsAjax(did);          
            } else {
                $("#topic-select").html('<option value=0>{{ trans("document/file.form.topic.placeholder1") }}</option>');
                $("#subtopic-select").html('<option value=0>{{ trans("document/file.form.subtopic.placeholder1") }}</option>');
                $("#job-select").html('<option value=0>{{ trans("document/file.form.job.placeholder") }}</option>');
                $('input[name=code]').val('');
            }            
        }); // change #department-select Event

        $('body').on('change', '#topic-select', function (e)  {
            e.preventDefault();
            var tid = this.value;
            console.log('TID: '+tid);            
            if( tid > 0 ) {
                setSubtopicsAjax(tid);                            
            } else {
                $("#subtopic-select").html('<option value=0>{{ trans("document/file.form.subtopic.placeholder1") }}</option>');
            }           
        }); // change #topic-select Event

        $('body').on('change', '#location-select, #department-select, #topic-select, #subtopic-select', function (e) {
            setCode();
        }) // change to generate code

        // *** BOTONES
        $('body').on('click', '#btn-new-index', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-index").val();
            if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.index.empty") }}');
                $("#input-new-index").focus(); 
            } else {
                setIndexAjax(txt);
            }
        }); // click #btn-new-index Event 
        
        $('body').on('click', '#btn-new-disposal', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-disposal").val();
            if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.disposal.empty") }}');
                $("#input-new-disposal").focus(); 
            } else {
                setDisposalAjax(txt);
            }
        }); // click #btn-new-disposal Event

        $('body').on('click', '#btn-new-topic', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-topic").val();
            var did = $("#department-select option:selected").val();
            console.log('TXT: '+txt+' DID: '+did);
            if( did == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.topic.no-department") }}');
                $("#department-select").focus(); 
            } else if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.topic.empty") }}');
                $("#input-new-topic").focus(); 
            } else {
                setTopicAjax(did, txt);
            }
        }); // click #btn-new-topic Event        
        
        $('body').on('click', '#btn-new-subtopic', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-subtopic").val();
            var tid = $("#topic-select option:selected").val();
            console.log('TXT: '+txt+' TID: '+tid);
            if( tid == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.subtopic.no-topic") }}');
                $("#topic-select").focus(); 
            } else if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.subtopic.empty") }}');
                $("#input-new-subtopic").focus(); 
            } else {
                setSubtopicAjax(tid, txt);
            }
        }); // click #btn-new-subtopic Event            

        // *** DATE PICKER
        $('input[name="dwell_date"]').daterangepicker({
            locale: {
                format: '{{ $DATA->dateFormat }}',
                daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
                monthNames: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                customRangeLabel: "-"
            },            
            singleDatePicker: true,
            showDropdowns: true,
            minYear: parseInt(moment().subtract(10, 'years').format('YYYY'),10),
            maxYear: parseInt(moment().add(10, 'years').format('YYYY'),10)
        });
        $('input[name="dead_date"]').daterangepicker({
            locale: {
                format: '{{ $DATA->dateFormat }}',
                daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
                monthNames: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                customRangeLabel: "-"
            },            
            singleDatePicker: true,
            showDropdowns: true,
            minYear: parseInt(moment().subtract(10, 'years').format('YYYY'),10),
            maxYear: parseInt(moment().add(10, 'years').format('YYYY'),10)
        });                         

    }); // document
    
    $(document).ready(function() {
        $("#department-select").trigger('change');        
    });

    function setDepartmentsAjax(id) {
        var url = "{{ route('files.list.departments', ':id') }}";
        //console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
                if( json.success ) {
                    generateDepartmentsSelect(0, json.n, json.departments);
                    $("#topic-select").trigger('change');
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setDepartmentsAjax   
    
    function generateDepartmentsSelect(id, n, departments) {
        var output = '<option value=0>{{ trans("document/file.form.department.placeholder") }}</option>';
        if( n == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.department.no-exist") }}'); 
        } else if( n == 1 ) {
           //$.each(departments, function(i, department) {
                output = '<option value='+departments.department_id+' selected>'+departments.name+'</option>';
            //});            
        } else {
            $.each(departments, function(i, department) {
                output += '<option value='+department.department_id;
                output += ( department.department_id == id ) ? ' selected' : '';
                output += '>'+department.name+'</option>';
            });
        }
        $("#department-select").html(output);
    } // generateDepartmentsSelect     

    function setTopicsAjax(id) {
        var url = "{{ route('files.list.topics', ':id') }}";
        //console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
                if( json.success ) {
                    //setSuccessNotification('success', '', json.message);
                    var tid = $("#topic-is").val();
                    console.log('TID*: '+tid);
                    generateTopicsSelect(tid, json.topics);
                    $("#topic-select").trigger('change');
                     $("#topic-is").val(0);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setTopicsAjax

    function generateTopicsSelect(id, topics) {
        var output = '<option value=0>{{ trans("document/file.form.topic.placeholder1") }}</option>';
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
        //console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
                if( json.success ) {
                    var sid = $("#subtopic-is").val();
                    console.log('SIP*: '+sid);
                    generateSubtopicsSelect(sid, json.subtopics);
                    $("#subtopic-is").val(0);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setSubtopicsAjax

    function generateSubtopicsSelect(id, subtopics) {
        var output = '<option value=0>{{ trans("document/file.form.subtopic.placeholder1") }}</option>';
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
        // Código
        //console.log('CODE1: '+$("#code-is").val());
        //$('input[name=code]').val($("#code-is").val());
        $("#loading-image").hide(); 
    } // generateSubtopicsSelect

    function setJobsAjax(id) {
        var url = "{{ route('files.list.jobs', ':id') }}";
        //console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
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

    function setIndexAjax(txt) {
        var route = "{{ route('files.save.index') }}";
        var str = $.trim(txt);
        //console.log('Running setIndexAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt': str},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-index").val('');
                    // Generar Select
                    var output  = '<option value=0>{{ trans("document/file.form.index.placeholder1") }}</option>';  
                    $.each(json.data, function(i, option) {
                        output += '<option value='+option.index_id;
                        output += ( option.name == str ) ? ' selected' : '';
                        output += '>'+option.name+'</option>';
                    });
                    $("select[name='index_id']").html(output);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setIndexAjax Fx

    function setDisposalAjax(txt) {
        var route = "{{ route('files.save.disposal') }}";
        var str = $.trim(txt);
        //console.log('Running setDisposalAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt': str},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-disposal").val('');
                    // Generar Select
                    var output  = '<option value=0>{{ trans("document/file.form.disposal.placeholder1") }}</option>';  
                    $.each(json.data, function(i, option) {
                        output += '<option value='+option.disposal_id;
                        output += ( option.name == str ) ? ' selected' : '';
                        output += '>'+option.name+'</option>';
                    });
                    $("select[name='disposal_id']").html(output);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setDisposalAjax Fx    

    function setTopicAjax(id, txt) {
        var route = "{{ route('files.save.topic') }}";
        var str = $.trim(txt);
        //console.log('Running setTopicAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'department_id': id, 'topic': str, 'filter': true},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-topic").val('');
                    // Generar select de temas
                    generateTopicsSelect(json.tid, json.topics);
                    // Resetea select de subtemas
                    generateSubtopicsSelect(0, []);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setTopiclAjax Fx
    
    function setSubtopicAjax(id, txt) {
        var route = "{{ route('files.save.subject') }}";
        var str = $.trim(txt);
        //console.log('Running setSubtopicAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'topic_id': id, 'subject': str},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-subtopic").val('');
                    // Generar select de subtemas
                    generateSubtopicsSelect(json.sid, json.subtopics);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setSubtopiclAjax Fx 
    
    function setCode() {        
        var route = "{{ route('files.get.code') }}";
        var lid = $('#location-select option:selected').val();
        var did = $('#department-select option:selected').val();
        var tid = $('#topic-select option:selected').val();
        var sid = $('#subtopic-select option:selected').val();
        console.log('SET CODE :: lid:'+lid+' did:'+did+' tid:'+tid+' sid:'+sid);

        if( lid > 0 && did > 0 && tid > 0 && sid > 0 )  {
            console.log(':: Searching by code...');
            $("#loading-image").show();
            $('input[name=code]').val('');
            $.ajax({
                url: route,
                type: 'POST',
                data: {'lid':lid,'did':did,'tid':tid,'sid':sid},
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
                success: function(json) {
                    //console.dir(json);
                    if( json.success ) {
                        // Establecer código
                        $('input[name=code]').val(json.code);
                        //$("#code-is").val(json.code);
                        $("#loading-image").hide();
                    } else {
                        setSuccessNotification('error', 'Oops!', json.message);
                    }                
                } // success
            }); // ajax 
        } else {
            var existingCode = $("#code-is").val('');
            if( existingCode == '' ) {
                $('input[name=code]').val('');
            }
        }        
    } // setCode

</script>
               
    @include('components.notification_index')

@endpush                
</x-icewall>     