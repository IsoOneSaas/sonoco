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

    }); // document    
</script>
               
    @include('components.notification_index')

@endpush                
</x-icewall>     