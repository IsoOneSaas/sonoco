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
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="record-form" action="{{ route('records.store', $DATA['record_id']) }}" method="POST">
                                @csrf 
                                <input type="hidden" name="record_id"  id="record-id" value={{ $DATA['record_id'] }} >                             
                                <input type="hidden" name="document_id" value={{ $DATA['document_id'] }} id="document-id">
                                <input type="hidden" name="origin" value="{{ $origin }}">                                 
                                <input type="hidden" name="xid" value={{ $DATA['xid'] }}>
                                <input type="hidden" name="file" value="{{ $DATA['file'] }}"> 
                                <input type="hidden" name="status_id" value={{ $DATA['status_id'] }} id="status-id">                                                                 
                                <div class="input-group">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/record.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/record.form.name.title') }}</div>
                                    <input type="text" name="name" value="{{ old('name', isset($DATA) ? $DATA['name'] : '') }}" class="form-control w-full input-status" aria-describedby="name" placeholder="{{ trans('document/record.form.name.placeholder') }}" minlength="2" maxlength="255" required>
                                    <div class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/record.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                                                                                
                            </form>                                
                        </div>
                    </div>
                    <!-- END: Form -->
                </div>
                <!-- END: Content -->

@push('meta')                
<meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script document="text/javascript">
    $(function () {
        var status = {{ $DATA['status_id'] }};
        if( status == 1 ) {
            // Bloquear inputs y botones
            $("#btn-save, #btn-store").attr('disabled', true);
            $(".input-status").attr('readonly', true);
        } // if

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
</script>
               

    @include('components.notification_index')

@endpush                
</x-icewall> 