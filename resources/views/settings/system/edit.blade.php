<!-- resources/views/settings/system.edit.blade.php -->
<x-icewall>

    <x-slot:title>
            Requisito - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Editar Requisito
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="system-form" class="btn btn-success shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('requisitos.index') }}"><i data-lucide="skip-back" class="w-5 h-5"></i></a>                                                                                
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="system-form" action="{{ route('requisitos.update', $system->system_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="system_id" value={{ $system->system_id }}>                           
                                <div class="input-group">
                                    <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('system.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('system.form.code.title') }}</div>
                                    <input type="text" name="code" value="{{ $system->code }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('system.form.code.placeholder') }}" minlength="2" maxlength="8" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('system.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                            
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('system.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('system.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ $system->name }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('system.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('system.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="description" class="input-group-text flex"><i data-lucide="{{ trans('system.form.description.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('system.form.description.title') }}</div>
                                    <textarea id="validation-form-6" class="form-control" name="description" aria-describedby="description" placeholder="{{ trans('system.form.description.placeholder') }}" minlength="8" maxlength="255" required>{{ $system->description }}</textarea>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('system.form.description.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                                                
                            </form>                                
                        </div>
                    </div>
                    <!-- END: Form -->
                </div>
                <!-- END: Content -->
@push('scripts-bottom')

@if ($errors->any())
    <script>
        setSuccessNotification('error', 'Oops!', '{{ $errors->first() }}');
    </script>    
@endif

@endpush                  
    </x-icewall>     