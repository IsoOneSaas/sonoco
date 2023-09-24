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
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url($profile->avatar) }}">
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
                                    @foreach($profile->locations as $department => $locations) 
                                        <a class="flex items-center text-primary font-medium" href="javascript:;"> <i data-lucide="at-sign" class="w-4 h-4 mr-2"></i> {{ $department }}</a>
                                        @foreach($locations as $location)
                                        <a class="flex items-center text-primary font-medium pl-5" href="javascript:;"> <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> {{ $location }}</a>
                                        @endforeach
                                        <br>
                                    @endforeach                                    
                                </div>
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
                                                    <div class="col-span-12 2xl:col-span-6">
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
                                                    <div class="h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                                        <img id="avatar-img" class="rounded-md" alt="Avatar" src="{{ url($profile->avatar) }}">                                                    
                                                    </div>
                                                    <div class="mx-auto cursor-pointer relative mt-5">
                                                        <button type="button" class="btn btn-primary w-full">Seleccionar</button>
                                                        <input type="file" name="avatar" class="w-full h-full top-0 left-0 absolute opacity-0" onChange="setAvatar();">
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
                                                    <button id="btn-signing-clear" type="button" class="btn btn-secondary mr-5">Borrar</button>
                                                    <button id="btn-signing-save" type="button" class="btn btn-primary">Salvar</button>
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
                <!-- END: Content -->

@push('meta')                
    <meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush                

@push('scripts-bottom')                
<script src="{{ url('assets/js/signature_pad-4.1.5/signature_pad.umd.min.js') }}"></script>
    <script type="text/javascript">
        var $signaturePad = new SignaturePad(document.getElementById('signature-pad'));
        $(function () {            
            $('body').on('click', '#btn-signing-clear', function (e) {
                e.preventDefault();
                $signaturePad.clear();
                $("#signature64").val('');
            });
            
            $('body').on('click', '#btn-signing-save', function (e) {
                e.preventDefault();                
                setSignature();
            }); //btn-signing-ok            

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
                        // $("#avatar-img").attr('src', json.url).load( function() {
                        //     $(this).width(this.width).height(this.height).appendTo('#avatar-img');
                        // })
                        $("#avatar-img").attr('src', json.url);
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
                        $("#sign-img").attr('src', json.url);
                        $signaturePad.clear();
                        $("#signature64").val('');                        
                    } else {
                        setSuccessNotification('error', 'Oops!', json.message);
                    }                  
                } // success
            }); // ajax         
        } // setSignature Fx

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