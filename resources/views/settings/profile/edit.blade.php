<!-- resources/views/settings/profile.edit.blade.php -->
<x-icewall>

    <x-slot:title>
       Perfil de Usuario - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">                            
                            Editar Perfil
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="user-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="grid grid-cols-12 gap-6">
                        <!-- BEGIN: Profile Menu -->
                        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                            <div class="intro-y box mt-5">
                                <div class="relative flex items-center p-5">
                                    <div class="w-12 h-12 image-fit">
                                        <img id="avatar-tiny" alt="Avatar" class="rounded-full" src="{{ url($profile->avatar) }}">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium text-base">{{ $profile->name }}</div>
                                        <div class="text-slate-500">
                                            @foreach( $profile->jobs as $job )
                                                <p>{{ $job->name }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    @php($previous = '')                                      
                                    @foreach($profile->locations as $location) 
                                        @if($previous != $location->department )
                                            @if(!$loop->first)
                                            <br>
                                            @endif 
                                        <a class="flex items-center text-primary font-medium" href="javascript:;"> <i data-lucide="at-sign" class="w-4 h-4 mr-2"></i> {{ $location->department }}</a>
                                        @php($previous = $location->department )
                                        @endif
                                        <a class="flex items-center text-primary font-medium pl-5" href="javascript:;"> <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> {{ $location->location }}</a>                                        
                                    @endforeach                                    
                                </div>

                                @if( in_array($profile->role, config('settings.roles_admin')) )
                                    @if( $profile->hasRole('ADMIN') )                                                               
                                    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">Administrador - Localizaciones</h2> 
                                        @foreach($profile->adminLocations as $location)
                                        <a class="flex items-center mt-3" href="javascript:;"> <i data-lucide="box" class="w-4 h-4 mr-2"></i> {{ $location }} </a>
                                        @endforeach
                                    </div>
                                    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">Administrador - Requisitos</h2> 
                                        @foreach($profile->adminSystems as $system)
                                        <a class="flex items-center mt-3" href="javascript:;"> <i data-lucide="settings" class="w-4 h-4 mr-2"></i> {{ $system }} </a>
                                        @endforeach
                                    </div>
                                    @endif
                                @endif
                            </div>

                            <div class="intro-y box p-5 bg-primary mt-5">
                                <div class="flex items-center">
                                    <div class="font-medium text-lg text-white">Cambiar Contraseña</div>
                                    <!-- <div class="text-xs bg-white dark:bg-primary dark:text-white text-slate-700 px-1 rounded-md ml-auto">New</div> -->
                                </div>
                                <div class="mt-4 text-white">Escriba su nueva contraseña la cual debe ser de más de 8 caracteres, contener al menos un dígito y una letra en mayúscula.</div>
                                <div class="font-medium flex mt-0">
                                    <form id="password-form" action="{{ route('perfil.password') }}" method="POST"  onSubmit="return false;" role="form">
                                        @csrf
                                        <input type="hidden" name="uid" value={{ $profile->user_id }} />

                                        <div id="vertical-form" class="p-5">
                                            <div class="preview">
                                                <div>
                                                    <label for="password" class="form-label text-white">Contraseña</label>
                                                    <input id="password" type="text" name="password" class="form-control" required>
                                                </div>
                                                <div class="mt-3">
                                                    <label for="password-repeat" class="form-label text-white">Repita la contraseña</label>
                                                    <input id="password-repeat" type="text" name="passwordR" class="form-control" required>
                                                </div>
                                                <div class="font-medium flex mt-5">
                                                    <button type="button" id="btn-password-save" class="btn py-1 px-2 border-white text-white dark:text-slate-300 dark:bg-darkmode-400 dark:border-darkmode-400">Cambiar</button>
                                                    <button type="button" id="btn-password-clear" class="btn py-1 px-2 border-transparent text-white dark:border-transparent ml-auto">Limpiar</button>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="intro-y box mt-5">
                                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                    <h2 class="font-medium text-base mr-auto">
                                        Ajustes Personales
                                    </h2>
                                </div>
                                <div class="p-5">
                                    <h3 class="font-light text-lg mr-auto">Página de inicio</h3>
                                    <ul>
                                        @foreach($pages as $page)
                                        <li><input id="start-{{ $page['id'] }}" type="radio" name="start" value={{ $page['id'] }} >&nbsp; {{ $page['name'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>                            

                        </div>
                        <!-- END: Profile Menu -->
                        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">

                            <!-- BEGIN: Personal Information -->
                            <div class="intro-y box mt-5">
                                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                    <h2 class="font-medium text-base mr-auto">
                                        Información Personal
                                    </h2>
                                </div>
                                <div class="p-5">
                                    <div class="flex flex-col-reverse xl:flex-row flex-col">

                                        <form id="user-form" action="{{ route('perfil.update', $profile->user_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="page" value={{ $profile->page }} >
                                        <div class="flex-1 mt-6 xl:mt-0">
                                            <div class="grid grid-cols-12 gap-x-5">
                                                <div class="col-span-12 2xl:col-span-6">
                                                    <div>
                                                        <label for="update-profile-form-6" class="form-label">Email</label>
                                                        <input id="update-profile-form-6" type="text" class="form-control" placeholder="Digite su correo electrónico" value="{{ $profile->email }}" disabled>
                                                    </div>
                                                    <div class="mt-3">
                                                        <label for="update-profile-form-7" class="form-label">Nombre</label>
                                                        <input id="update-profile-form-7" type="text"  name="name" class="form-control" placeholder="Digite su nombre completo" value="{{ $profile->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-span-12 2xl:col-span-6 mb-3">
                                                    <div class="mt-3 2xl:mt-0">
                                                        <label for="update-profile-form-8" class="form-label">Género</label>
                                                        <select id="update-profile-form-8" name="genre" class="form-select">
                                                            <option value="">Seleccione género</option>
                                                            <option value="F" @if( isset($profile->genre) && ($profile->genre == 'F') ) selected @endif >Femenino</option>
                                                            <option value="M" @if( isset($profile->genre) && ($profile->genre == 'M') ) selected @endif >Masculino</option>
                                                        </select>
                                                    </div>
                                                    <div class="mt-3">
                                                        <label for="update-profile-form-9" class="form-label">Fecha nacimiento</label>
                                                        <input id="update-profile-form-9" type="text" name="birth" class="datepicker form-control" data-single-mode="true" data-auto-apply="true" data-show-week-numbers="false" {{ $pickerDefault['start'] }} data-format="{{ $pickerDefault['format'] }}"  data-min-year="{{ $pickerDefault['min'] }}"  data-max-year="{{ $pickerDefault['max'] }}" placeholder="Digite su fecha de nacimiento" value="{{ $profile->birth ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-span-12 2xl:col-span-6">
                                                    <div class="mt-3 2xl:mt-0">
                                                        <label for="update-profile-form-10" class="form-label">Teléfono fijo</label>
                                                        <input id="update-profile-form-10" type="text" name="phone" class="form-control" placeholder="Digite su número telefonico" value="{{ $profile->phone ?? '' }}">
                                                    </div>
                                                    <div class="mt-3">
                                                        <label for="update-profile-form-13" class="form-label">Teléfono Móvil</label>
                                                        <input id="update-profile-form-13" type="text" name="mobile" class="form-control" placeholder="Digite su número móvil" value="{{ $profile->mobile ?? '' }}">                                                        
                                                    </div>
                                                </div>                                              

                                                <div class="col-span-12">
                                                    <div class="mt-3">
                                                        <label for="update-profile-form-5" class="form-label">Dirección</label>
                                                        <textarea id="update-profile-form-5" name="address" class="form-control" placeholder="Escriba su dirección">{{ $profile->address ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>                                                                                                      
                                        </div>
                                        </form>
                                        <form id="avatar-form" action="{{ route('perfil.store') }}" method="POST" enctype="multipart/form-data" role="form" onSubmit="return false;">
                                        @csrf
                                        <input type="hidden" name="uid" value="{{ $profile->user_uid }}">
                                        <div class="w-52 mx-auto xl:mr-0 xl:ml-6">
                                            <div class="border-2 border-dashed shadow-sm border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                                                <div class="h-40 relative image-fit zoom-in mx-auto">
                                                    <img id="avatar-img" class="rounded-md" alt="Avatar" src="{{ url($profile->avatar) }}">                                                    
                                                </div>
                                                <div class="mx-auto relative mt-5">
                                                    <button type="button" class="btn btn-primary w-full cursor-pointer">Seleccionar</button>
                                                    <input type="file" name="avatar" class="w-full h-full top-0 left-0 absolute opacity-0 cursor-pointer" onChange="setAvatar();">
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>                                   
                                </div>
                            </div>
                            <!-- END: Personal Information -->
                            

                            <!-- BEGIN: Display Information -->
                            <div class="intro-y box lg:mt-5">
                                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                    <h2 class="font-medium text-base mr-auto">
                                        Firma
                                    </h2>
                                </div>
                                <div class="p-5">
                                    <div id="sign-message"></div>
                                    <div class="flex flex-col-reverse xl:flex-row flex-col">
                                        <form id="sign-form" action="{{ route('perfil.upload') }}" method="POST" role="form" onSubmit="return false;">
                                            @csrf
                                            <input type="hidden" name="uid" value="{{ $profile->user_uid }}">
                                            <div class="mx-auto xl:mr-0 xl:ml-6">
                                                <div class="grid grid-cols-2 gap-2 mb-5 width-full">                                                    
                                                    <div class=""><canvas id="signature-pad" class="cursor-pointer border-2 border-dashed shadow-sm border-slate-200/60 dark:border-darkmode-400 rounded-md p-5" width="300" height="300"></canvas></div>
                                                    <div class="border-2 border-dashed shadow-sm border-slate-200/60 dark:border-darkmode-400 rounded-md p-5"><img id="sign-img" src="{{ url($profile->sign) }}" ></div>
                                                    <textarea id="signature64" name="signed" style="display: none"></textarea>                                                
                                                </div>
                                                <div class="flex justify-center">
                                                    <button id="btn-signing-clear" type="button" class="btn btn-secondary mr-10">Borrar</button>
                                                    <button id="btn-signing-upload" type="button" class="btn btn-primary mr-5">Cargar</button>                                                    
                                                    <button id="btn-signing-save" type="button" class="btn btn-primary mr-10">Salvar</button>
                                                    <button id="btn-signing-delete" type="button" class="btn btn-danger">Eliminar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- END: Display Information -->

                        </div>
                    </div>
                    <!-- END: Form -->
                </div>
                <div class="hidden">
                    <form id="image-form" action="{{ route('perfil.image') }}" method="POST" enctype="multipart/form-data" role="form" onSubmit="return false;">
                        @csrf
                        <input type="hidden" name="uid" value="{{ $profile->user_uid }}">
                        <input type="file" id="btn-file-upload" name="image" class="w-full h-full top-0 left-0 absolute opacity-0 cursor-pointer" onChange="setImage();">
                    </form>
                </div>                
                <!-- END: Content -->

@push('meta')                
    <meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush                

@push('scripts-bottom')                
    <script src="{{ url('assets/js/signature_pad-4.1.5/signature_pad.umd.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>    
    <script type="text/javascript">
        var $signaturePad = new SignaturePad(document.getElementById('signature-pad'));
        $(function () {  
            var startPage = $('input[name="page"]').val();

            $('#start-'+startPage).attr('checked', true);

            $('body').on('click', '#btn-signing-clear', function (e) {                
                e.preventDefault();
                $signaturePad.clear();                
            });
            
            $('body').on('click', '#btn-signing-save', function (e) {
                e.preventDefault();                
                setSignature();
            }); //btn-signing-ok  
            
            $('body').on('click', '#btn-password-save', function (e) {
                e.preventDefault();              
                setPassword();
            }); //btn-signing-ok    

            $('body').on('click', '#btn-password-clear', function (e) {
                e.preventDefault();              
                $("#password-form")[0].reset();
            }); //btn-signing-clear

            $('body').on('click', '#btn-signing-delete', function (e) {
                e.preventDefault();              
                deleteSignature();
            }); //btn-signing-delete'             

            $('body').on('change', 'input[name="start"]', function (e) {
                e.preventDefault();              
                var v = $(this).val();
                $('input[name="page"]').val(v);
            }); //btn-signing-clear 
            
            $('body').on('click', '#btn-signing-upload', function (e) {
                $('#sign-message').html("<div class='flex items-center text-sm bg-warning text-white font-bold px-4 py-3' role='alert'><svg class='fill-current w-4 h-4 mr-2' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><path d='M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z'/></svg><p>{{ trans('profile.image.directions') }}</p></div>");
                $('#btn-file-upload').trigger("click");
            }); // btn-signing-upload

        }); // document

        function setAvatar() {
            var form = $("#avatar-form");
            $.ajax({
                type: form.attr("method"),
                data: new FormData(form[0]),
                dataType: 'json',
                url: form.attr("action"),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                processData: false,
                contentType: false,
                success: function(json) {
                    //console.dir(json);
                    if( json.success ) {
                        setSuccessNotification('success', '', json.message);
                        $("#avatar-img").attr('src', json.url+"?"+(new Date()).getTime());
                        $("#avatar-tiny").attr('src', json.url+"?"+(new Date()).getTime());
                    } else {
                        setSuccessNotification('error', 'Oops!', json.message);
                    }                  
                } // success
            }); // ajax 
        } // setAvatar Fx

        function setSignature() {
            var form = $("#sign-form");
            $("#signature64").html($signaturePad.toDataURL());
            $.ajax({
                type: form.attr("method"),
                data: new FormData(form[0]),
                dataType: 'json',
                url: form.attr("action"),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                processData: false,
                contentType: false,                
                success: function(json) {
                    //console.dir(json);
                    if( json.success ) {
                        setSuccessNotification('success', '', json.message);
						$signaturePad.clear();                                                
                        $("#sign-img").attr('src', json.url+"?"+(new Date()).getTime());    
                        $("#btn-signing-save").prop( "disabled", false );                                             
                    } else {
                        setSuccessNotification('error', 'Oops!', json.message);
                    }                  
                } // success
            }); // ajax         
        } // setSignature Fx

        function setPassword() {
            var form = $("#password-form");
            $.ajax({
                type: form.attr("method"),
                data: new FormData(form[0]),
                dataType: 'json',
                url: form.attr("action"),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                processData: false,
                contentType: false,                
                success: function(json) {
                    console.dir(json);
                    if( json.success ) {
                        $("#password-form")[0].reset();
                        setSuccessNotification('success', '', json.message);                       
                    } else {
                        setSuccessNotification('error', 'Oops!', json.message);
                    }                  
                } // success
            }); // ajax         
        } // setPassword Fx 

        function setImage() {
            var form = $("#image-form");
            $.ajax({
                type: form.attr("method"),
                data: new FormData(form[0]),
                dataType: 'json',
                url: form.attr("action"),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                processData: false,
                contentType: false,                
                success: function(json) {
                    console.dir(json);
                    if( json.success ) {
                        setSuccessNotification('success', '', json.message);
                        $("#sign-img").attr('src', json.url+"?"+(new Date()).getTime());  
                        $("#btn-signing-save").prop( "disabled", true );                                            
                    } else {
                        setSuccessNotification('error', 'Oops!', json.message);
                    }                  
                } // success
            }); // ajax   
        } // seImage Fx
        
        function deleteSignature() {
            swal({
                title: "{{ trans('profile.delete.title') }}",
                text: "{{ trans('profile.delete.text') }}",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    console.log('>>> Imagen Eliminada');
                    var uid = $("input[name='uid']").val();
                    var action = "{{ route('perfil.destroy', ':uid') }}"; 
                    action = action.replace(':uid', uid);                    
                    $.ajax({
                        url: action,
                        //type: 'GET',
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }, 
                        success: function(json) {
                            console.dir(json);
                            if(json.success) {
						        $signaturePad.clear();                                                
                                $("#sign-img").attr('src', '');                                                                         
                                setSuccessNotification('success', '', json.message);                                    
                            } else {
                                setSuccessNotification('error', 'Oops!', json.message);
                            }
                        } // success
                    }); // ajax                         
                }
            }); 
        } // delete signature

    </script>

@if ($message = Session::get('success'))
    <script>
        setSuccessNotification('success', 'Felicitaciones!', '{{ $message }}');
    </script>    
@endif

@if ($message = Session::get('error'))
    <script>
        setSuccessNotification('error', 'Oops!', '{{ $message }}');
    </script> 
@endif 


@endpush

</x-icewall> 