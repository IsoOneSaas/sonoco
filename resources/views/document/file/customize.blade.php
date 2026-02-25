<!-- resources/views/document/type.index.blade.php -->
<x-icewall>

    <x-slot:title>
            Configuración - Administrar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Edición de la configuración registros y archivos
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="customize-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>

                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-print" href="javascript:;" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Imprimir tabla </a>
                                        </li>
                                      
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Boxed Tab -->
                    <div class="intro-y box mt-5">
                        <div id="boxed-tab" class="p-5">
                            <div class="preview">
                                <ul class="nav nav-boxed-tabs" role="tablist">
                                    <li id="example-3-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-3-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#example-tab-3" type="button" role="tab" aria-controls="example-tab-3" aria-selected="true" > Formatos </button>
                                    </li>
                                    <li id="example-4-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-3-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#example-tab-4" type="button" role="tab" aria-controls="example-tab-4" aria-selected="false" > Archivo </button>
                                    </li>
                                    <li id="example-5-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-5-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#example-tab-5" type="button" role="tab" aria-controls="example-tab-4" aria-selected="false" > Ar </button>
                                    </li>                                    
                                </ul>
                                <form id="customize-form" action="{{ route('files.settings.personalizar.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tab_active" value="{{ old('tab_active', '') }}">
                                <div class="tab-content mt-5">
                                    <div id="example-tab-3" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="example-3-tab">
                                        <div class="input-group mt-3">
                                            <div id="file-code-format" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/customize.form.file_format.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.file_format.title') }}</div>
                                            <input type="text"  name="file_code_format" value="{{ old('file_code_format', $data['file_code_format']) }}" class="form-control  w-full" aria-describedby="file_format" placeholder="{{ trans('document/customize.form.file_format.placeholder') }}" required>
                                            <div id="input-group-11" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.file_format.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                            <div id="record-nui-format" class="input-group-text flex w-full ml-1"><i data-lucide="{{ trans('document/customize.form.nui_format.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.nui_format.title') }}</div>
                                            <input type="text"  name="record_nui_format" value="{{ old('record_nui_format', $data['record_nui_format']) }}" class="form-control  w-full" aria-describedby="nui_format" placeholder="{{ trans('document/customize.form.nui_format.placeholder') }}" required>
                                            <div id="input-group-12" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.nui_format.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <br>
                                                <p>
                                                    Puede utilizar la siguiente codificación válida:
                                                </p>
                                                <ul class="list-inside">
                                                    <li><strong>L</strong> : Código de Localización</li>
                                                    <li><strong>D</strong> : Código de Departamento</li>
                                                    <li><strong>T</strong> : Código de Tema</li>
                                                    <li><strong>S</strong> : Código de Subtema</li>
                                                </ul>
                                                <p>&nbsp;&nbsp;Los siguientes simbolos son permitidos : <strong>/ : ( ) [ ] | - _ .</strong></p>
                                                <p>&nbsp;&nbsp;No se permiten espacios en blanco o repetir código</p>                                            
                                            </div>
                                            <div>
                                                <br>
                                                <p>
                                                    Puede utilizar los siguientes símbolo de separación:
                                                </p>
                                                <p>&nbsp;&nbsp;<strong>/ : ( ) [ ] | - _ .</strong></p>
                                                <br>
                                                <p>&nbsp;&nbsp;No se permiten espacios en blanco</p>
                                                <br>                                                  
                                                <p>&nbsp;&nbsp;Los tres código de caracteres <strong>%s</strong> deben ir en cualquier posición del código siempre separados de un símbolo.</p>
                                            </div> 
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="file-code-pad" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/customize.form.file_pad.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.file_pad.title') }}</div>
                                            <input type="number"  name="file_code_pad" value="{{ old('file_code_pad', $data['file_code_pad']) }}" class="form-control  w-full" aria-describedby="file_pad" placeholder="{{ trans('document/customize.form.file_pad.placeholder') }}" min="1"  required>
                                            <div id="input-group-11" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.file_pad.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                            <div id="record-nui-pad" class="input-group-text flex w-full ml-1"><i data-lucide="{{ trans('document/customize.form.nui_pad.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.nui_pad.title') }}</div>
                                            <input type="number"  name="record_nui_pad" value="{{ old('record_nui_pad', $data['record_nui_pad']) }}" class="form-control  w-full" aria-describedby="nui_pad" placeholder="{{ trans('document/customize.form.nui_pad.placeholder') }}" min="1" required>
                                            <div id="input-group-12" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.nui_pad.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>                                        
                                    </div>
                                    <div id="example-tab-4" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="example-4-tab">
                                                                                                                                                              
                                    </div>

                                    <div id="example-tab-5" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="example-5-tab">
                                                                                                                                                                                                  
                                    </div>
                                </div>
                                </form>
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
<link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />

<script type="text/javascript">

    $(function () {

        var tab = $("input[name='tab_active']").val();
        var id = ( tab == '') ? 'btn-3-tab' : tab;
        $("#"+id).addClass('active');
        $("#"+id).attr('aria-selected', true);
        $("#"+id).trigger("click");

        $('.nav-link').on("click", function()  {
            var id = $(this).attr('id');
            $("input[name='tab_active']").val(id);
        });
                
    }); // document

    

</script>

    @include('components.notification_index')

@endpush

</x-icewall> 