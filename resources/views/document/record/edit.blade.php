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
                                <form id="record-form" action="{{ route('records.store', $DATA['record_id']) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="record_id"  id="record-id" value={{ $DATA['record_id'] }} >                             
                                    <input type="hidden" name="document_id" value={{ $DATA['document_id'] }} id="document-id">
                                    <input type="hidden" name="origin" value="{{ $origin }}">                                 
                                    <input type="hidden" name="xid" value={{ $DATA['xid'] }}>
                                    <input type="hidden" name="file" value="{{ $DATA['file'] }}"> 
                                    <input type="hidden" name="status_id" value={{ $DATA['status_id'] }} id="status-id">
                                    <input type="hidden" name="tab_active" value="{{ old('tab_active', '') }}">
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
                                            <h1>Content 2</h1>
                                        </div>
                                        <div id="record-tab-3" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-3-tab">                                            
                                            <!-- BEGIN: Topic -->
                                            <div class="intro-y box p-5 mt-5">
                                                <p>Clasifique el registro con un tema y subtema nuevo, o seleccione uno existente de la lista desplegable.</p>                                                              
                                                <div class="input-group mt-3">
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
                                            <h1>Content 4</h1>
                                        </div>
                                        <div id="record-tab-5" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-5-tab">
                                            <h1>Content 5</h1>
                                        </div>
                                        <div id="record-tab-6" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-6-tab">
                                            <h1>Content 6</h1>
                                        </div>
                                        <div id="record-tab-7" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="record-7-tab">
                                            <h1>Content 7</h1>
                                        </div>                                                                                                                                                                
                                    </div>
                                </form>
                                <!-- END: Form -->
                            </div> 
                        </div>
                    </div>        
                </div>
                <!-- END: Content -->

@push('meta')                
<meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/ckeditor_4.21.0_full/ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script document="text/javascript">
    $(function () {        
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
                $("#topic").val(topic);
                topicAjax(topic);
            } // if                
        }); // topic

        $("#topic").on("change", function() {
            $("#topic-selected").val("");
        });
        
        // Selección del subtema
        $("#subject-select").on("change", function() {
            var subject = this.value;
            if( subject != '' ) {
                $("#subject").val(subject);            
            }
        });

        $("#subject").on("change", function() {
            $("#subject-select").val("");
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
        console.log('Running topicAjax');
        var route = "{{ route('records.edit.subject') }}";
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt': param},
            dataType: 'json',            
            success: function(data) {
                console.dir(data);
                var output = '<option value="">{{ trans("document/record.form.subject.default") }}</option>';
                $.each(data, function(i, value) {
                    output += '<option value="'+value.subject+'">'+value.subject+'</option>';
                });
                $("#subject-select").html(output);
            } // success
        }); // ajax  
    } // topicAjax Fx

</script>
               

    @include('components.notification_index')

@endpush                
</x-icewall> 