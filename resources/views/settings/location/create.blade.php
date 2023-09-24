<!-- resources/views/settings/location.create.blade.php -->
<x-icewall>

    <x-slot:title>
            Localización - Crear
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('localizaciones.index') }}">Parametrización Localizaciones</a></li>
        <li class="breadcrumb-item active" aria-current="page">Crear</li>
    </x-slot:breadcrumb>    

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Crear Localización
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="location-form" class="btn btn-success shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('localizaciones.index') }}"><i data-lucide="skip-back" class="w-5 h-5"></i></a>                                                                                    
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="location-form" action="{{ route('localizaciones.store') }}" method="POST">
                                @csrf                            
                                <div class="input-group">
                                    <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('location.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('location.form.code.title') }}</div>
                                    <input type="text" name="code" value="{{ old('code') }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('location.form.code.placeholder') }}" minlength="2" maxlength="8" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('location.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                            
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('location.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('location.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ old('name') }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('location.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('location.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="description" class="input-group-text flex"><i data-lucide="{{ trans('location.form.description.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('location.form.description.title') }}</div>
                                    <textarea id="validation-form-6" class="form-control" name="description" aria-describedby="description" placeholder="{{ trans('location.form.description.placeholder') }}" minlength="8" maxlength="255" required>{{ old('description') }}</textarea>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('location.form.description.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
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