<!-- resources/views/settings/template.create.blade.php -->
<x-icewall>

    <x-slot:title>
        Plantilla - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Editar Plantilla
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="template-form" class="btn btn-primary shadow-md mr-2" title="Salvar Formulario"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('documents.settings.plantillas.index') }}" title="Regresar a la tabla"><i data-lucide="skip-back" class="w-5 h-5"></i></a>   
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="template-form" action="{{ route('documents.settings.plantillas.update', $template->template_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="template_id" value={{ $template->template_id }}>                                                              
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('document/template.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/template.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ $template->name }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/template.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/template.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="description" class="input-group-text flex"><i data-lucide="{{ trans('document/template.form.description.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/template.form.description.title') }}</div>
                                    <textarea class="form-control" name="description" aria-describedby="description" placeholder="{{ trans('document/template.form.description.placeholder') }}" minlength="8" maxlength="255" required>{{ $template->description }}</textarea>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/template.form.description.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="content" class="input-group-text flex"><i data-lucide="{{ trans('document/template.form.content.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/template.form.content.title') }}</div>
                                    <textarea id="editor" class="form-control" name="content" aria-describedby="content" placeholder="{{ trans('document/template.form.content.placeholder') }}" minlength="8" maxlength="255" required>{{ $template->content }}</textarea>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/template.form.content.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                                                                                                                   
                            </form>                                
                        </div>
                    </div>
                    <!-- END: Form -->
                </div>
                <!-- END: Content -->

@push('scripts-bottom')

    @include('components.notification_error')
    <script src="{{ url('assets/js/ckeditor_4.21.0_full/ckeditor/ckeditor.js') }}"></script> 
     
    <script type="text/javascript">
         $(function () {
             CKEDITOR.replace('editor', {
                customConfig: 'custom/document_templates.js'
             });
         });
    </script>
@endpush
</x-icewall> 