<!-- resources/views/document/type.index.blade.php -->
<x-icewall>

    <x-slot:title>
            Validación - Administrar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Edición de la configuración de documentos
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
                                        <button id="btn-3-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#example-tab-4" type="button" role="tab" aria-controls="example-tab-4" aria-selected="false" > Mensaje de Correo </button>
                                    </li>
                                    <li id="example-5-tab" class="nav-item flex-1" role="presentation">
                                        <button id="btn-5-tab" class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#example-tab-5" type="button" role="tab" aria-controls="example-tab-4" aria-selected="false" > Notificaciones </button>
                                    </li>                                    
                                </ul>
                                <form id="customize-form" action="{{ route('documents.settings.personalizar.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tab_active" value="{{ old('tab_active', '') }}">
                                <div class="tab-content mt-5">
                                    <div id="example-tab-3" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="example-3-tab">
                                        <div class="input-group mt-3">
                                            <div id="code-format" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/customize.form.code_format.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.code_format.title') }}</div>
                                            <input type="text"  name="code_format" value="{{ old('code_format', $data['code_format']) }}" class="form-control  w-full" aria-describedby="code_format" placeholder="{{ trans('document/customize.form.code_format.placeholder') }}" required>
                                            <div id="input-group-11" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.code_format.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                            <div id="date-format" class="input-group-text flex w-full ml-1"><i data-lucide="{{ trans('document/customize.form.date_format.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.date_format.title') }}</div>
                                            <input type="text"  name="date_format" value="{{ old('date_format', $data['date_format']) }}" class="form-control  w-full" aria-describedby="date_format" placeholder="{{ trans('document/customize.form.date_format.placeholder') }}" required>
                                            <div id="input-group-12" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.date_format.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <br>
                                                <p>
                                                    Puede utilizar la siguiente codificación válida:
                                                </p>
                                                <ul class="list-inside">
                                                    <li><strong>S</strong> : Código de Sistema de Gestión (p.e. GC : Gestión de Calidad)</li>
                                                    <li><strong>P</strong> : Código de Proceso (p.e. CO : Comercial)</li>
                                                    <li><strong>T</strong> : Código de Tipo de documento (p.e. FO : Formato)</li>
                                                    <li><strong>L</strong> : Código de Localización del usuario del documento (p.e. P1 : Planta 1)</li>                                                    
                                                    <li><strong>#</strong> : Número consecutivo (lo asigna el sistema)</li>
                                                </ul>
                                                <p>&nbsp;&nbsp;Los siguientes simbolos son permitidos : <strong>/ : ( ) [ ] | - _ .</strong></p>
                                                <p>&nbsp;&nbsp;No se permiten espacios en blanco o repetir código</p>                                            
                                            </div>
                                            <div>
                                                <br>
                                                <p>
                                                    Puede utilizar la siguiente codificación válida:
                                                </p>
                                                <ul>
                                                    <li><strong>d</strong> : Día del mes, dos dígitos con ceros iniciales</li>
                                                    <li><strong>D</strong> : Una representación textual de un día, tres letras</li>
                                                    <li><strong>j</strong> : Día del mes sin ceros iniciales</li>
                                                    <li><strong>-----------------------------------------------------------</strong></li>
                                                    <li><strong>m</strong> : Representación numérica de una mes, con ceros iniciales</li>
                                                    <li><strong>M</strong> : Una representación textual corta de un mes, tres letras</li>
                                                    <li><strong>n</strong> : Representación numérica de un mes, sin ceros iniciales</li>
                                                    <li><strong>-----------------------------------------------------------</strong></li>
                                                    <li><strong>Y</strong> : Una representación numérica completa de un año, cuatro dígitos</li>
                                                    <li><strong>y</strong> : Una representación de dos dígitos de un año</li>
                                                </ul>
                                                <p>&nbsp;&nbsp;Los siguientes simbolos son permitidos : <strong>/ : ( ) [ ] | - _ .</strong></p>
                                                <p>&nbsp;&nbsp;Espacio en blanco es permitido</p>
                                            </div> 
                                        </div>
                                    </div>
                                    <div id="example-tab-4" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="example-4-tab">
                                        <div class="input-group mt-3">
                                            <div id="from-name-edit" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/customize.form.from_name_edit.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.from_name_edit.title') }}</div>
                                            <input type="text"  name="from_name_edit" value="{{ old('from_name_edit', $data['from_name_edit']) }}" class="form-control w-full" aria-describedby="from_name_edit" placeholder="{{ trans('document/customize.form.from_name_edit.placeholder') }}" required>
                                            <div id="input-group-21" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.from_name_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                            <div id="from-email-edit" class="input-group-text flex w-full ml-1"><i data-lucide="{{ trans('document/customize.form.from_email_edit.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.from_email_edit.title') }}</div>
                                            <input type="email"  name="from_email_edit" value="{{ old('from_email_edit', $data['from_email_edit']) }}" class="form-control  w-full" aria-describedby="from_email_edit" placeholder="{{ trans('document/customize.form.from_email_edit.placeholder') }}" required>
                                            <div id="input-group-22" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.from_email_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="subject-edit" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/customize.form.subject_edit.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.subject_edit.title') }}</div>
                                            <input type="text"  name="subject_edit" value="{{ old('subject_edit', $data['subject_edit']) }}" class="form-control w-full" aria-describedby="subject_edit" placeholder="{{ trans('document/customize.form.subject_edit.placeholder') }}" required>
                                            <div id="input-group-23" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.subject_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                            <div id="signature-edit" class="input-group-text flex w-full ml-1"><i data-lucide="{{ trans('document/customize.form.signature_edit.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.signature_edit.title') }}</div>
                                            <input type="text"  name="signature_edit" value="{{ old('signature_edit', $data['signature_edit']) }}" class="form-control  w-full" aria-describedby="signature_edit" placeholder="{{ trans('document/customize.form.signature_edit.placeholder') }}" required>
                                            <div id="input-group-24" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.signature_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="bcc-edit" class="input-group-text flex w-full"><i data-lucide="{{ trans('document/customize.form.bcc_edit.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.bcc_edit.title') }}</div>
                                            <input type="email"  name="bcc_edit" value="{{ old('bcc_edit', $data['bcc_edit']) }}" class="form-control" aria-describedby="bcc_edit" placeholder="{{ trans('document/customize.form.bcc_edit.placeholder') }}">
                                            <div id="input-group-25" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.bcc_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                            <div id="reply-to-edit" class="input-group-text flex w-full ml-1"><i data-lucide="{{ trans('document/customize.form.reply_to_edit.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.reply_to_edit.title') }}</div>
                                            <input type="email"  name="reply_to_edit" value="{{ old('reply_to_edit', $data['reply_to_edit']) }}" class="form-control  w-full" aria-describedby="reply_to_edit" placeholder="{{ trans('document/customize.form.reply_to_edit.placeholder') }}">
                                            <div id="input-group-26" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.reply_to_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div> 
                                        <div class="input-group mt-3">
                                            <div id="confirm-reading-edit" class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/customize.form.confirm_reading_edit.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.confirm_reading_edit.title') }}</div>
                                            <div class="form-switch mt-2 ml-4 mr-2  w-fit">
                                                <input type="checkbox" class="form-check-input" id="confirm-reading-edit" name="confirm_reading_edit" @if( old('confirm_reading_edit', $data['confirm_reading_edit']) ) checked @endif>
                                            </div>                                             
                                            <div id="input-group-27" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.confirm_reading_edit.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>                                                                                                                       
                                    </div>
                                    <div id="example-tab-5" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="example-5-tab">
                                        <div class="input-group mt-3">
                                            <div id="notice-new-suggestion" class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/customize.form.notice_new_suggestion.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.notice_new_suggestion.title') }}</div>
                                            <div class="form-switch mt-2 ml-4 mr-2  w-fit">
                                                <input type="checkbox" class="form-check-input" id="notice-new-suggestion" name="notice_new_suggestion" @if( old('notice_new_suggestion', $data['notice_new_suggestion']) ) checked @endif>
                                            </div>                                             
                                            <div id="input-group-31" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.notice_new_suggestion.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="notice-new-sighting" class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/customize.form.notice_new_sighting.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.notice_new_sighting.title') }}</div>
                                            <div class="form-switch mt-2 ml-4 mr-2  w-fit">
                                                <input type="checkbox" class="form-check-input" id="notice-new-sighting" name="notice_new_sighting" @if( old('notice_new_sighting', $data['notice_new_sighting']) ) checked @endif>
                                            </div>                                             
                                            <div id="input-group-33" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.notice_new_sighting.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="notice-new-document" class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/customize.form.notice_new_document.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.notice_new_document.title') }}</div>
                                            <div class="form-switch mt-2 ml-4 mr-2  w-fit">
                                                <input type="checkbox" class="form-check-input" id="notice-new-document" name="notice_new_document" @if( old('notice_new_document', $data['notice_new_document']) ) checked @endif>
                                            </div>                                             
                                            <div id="input-group-35" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.notice_new_document.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="notice-master-document" class="input-group-text flex w-fit"><i data-lucide="{{ trans('document/customize.form.notice_master_document.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/customize.form.notice_master_document.title') }}</div>
                                            <textarea  name="master_text" class="form-control  w-full" aria-describedby="master_text" placeholder="{{ trans('document/customize.form.notice_master_document.placeholder') }}">{{ old('master_text', $data['notice_master']['text']) }}</textarea>
                                            <select  name="master_alert" class="form-control w-48" required>
                                                <option value='danger' @if( old('master_alert', $data['notice_master']['alert']) == 'danger' ) selected @endif >Peligro</option>
                                                <option value='warning' @if( old('master_alert', $data['notice_master']['alert']) == 'warning' ) selected @endif >Alarma</option>
                                                <option value='info' @if( old('master_alert', $data['notice_master']['alert']) == 'info' ) selected @endif >Información</option>
                                                <option value='dark' @if( old('master_alert', $data['notice_master']['alert']) == 'dark' ) selected @endif >Neutro</option>
                                            </select>                                             
                                            <div id="input-group-35" class="input-group-text mr-1"><a href="javascript:;" class="tooltip" title="{{ trans('document/customize.form.notice_master_document.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>                                                                                                                                                                                                   
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