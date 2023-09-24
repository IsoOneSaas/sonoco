<!-- resources/views/settings/type.create.blade.php -->
<x-icewall>

    <x-slot:title>
        Tipo de Documento - Crear
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Crear Tipo de Documento
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="type-form" class="btn btn-primary shadow-md mr-2" title="Salvar Formulario"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('documents.settings.tipos.index') }}" title="Regresar a la tabla"><i data-lucide="skip-back" class="w-5 h-5"></i></a>   
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="type-form" action="{{ route('documents.settings.tipos.store') }}" method="POST">
                                @csrf                            
                                <div class="input-group">
                                    <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('document/type.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/type.form.code.title') }}</div>
                                    <input type="text" name="code" value="{{ old('code') }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('document/type.form.code.placeholder') }}" minlength="2" maxlength="8" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/type.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                            
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/type.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/type.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ old('name') }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/type.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/type.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="category" class="input-group-text flex"><i data-lucide="{{ trans('document/type.form.category.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/type.form.category.title') }}</div>
                                    <select  name="category" class="form-control tom-select w-full" required>
                                        <option value=''>{{ trans('document/type.form.category.placeholder') }}</option>
                                        @foreach($categories as $category)   
                                        <option value="{{ $category}}">{{ $category }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/type.form.category.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="template" class="input-group-text flex"><i data-lucide="{{ trans('document/type.form.template.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/type.form.template.title') }}</div>
                                    <select  name="template_id" class="form-control tom-select w-full" required>
                                        <option value=''>{{ trans('document/type.form.template.placeholder') }}</option>
                                        @foreach($templates as $template)   
                                        <option value={{ $template->template_id }} >{{ $template->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/type.form.template.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                                                                                                                  
                            </form>                                
                        </div>
                    </div>
                    <!-- END: Form -->
                </div>
                <!-- END: Content -->

@push('scripts-bottom')

    @include('components.notification_error')

@endpush
</x-icewall> 