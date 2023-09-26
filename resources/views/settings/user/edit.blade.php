<!-- resources/views/settings/user.edit.blade.php -->
<x-icewall>

    <x-slot:title>
        Usuario - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">                            
                            Editar Usuario
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="user-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('usuarios.index') }}"><i data-lucide="skip-back" class="w-5 h-5"></i></a>                            
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="user-form" action="{{ route('usuarios.update', $user->user_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user_id" value={{ $user->user_id }}>
                                <input type="hidden" name="old_pass" value={{ $user->password }}>                                                                  
                                <div class="input-group mt-3">
                                    <div id="is-active" class="input-group-text flex"><i data-lucide="{{ trans('user.form.active.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('user.form.active.title') }}</div>
                                    <div class="form-switch mt-2 ml-4  w-full">
                                        <input type="checkbox" class="form-check-input" id="is-active" name="is_active" @if( $user->is_active ) checked @endif>
                                    </div>                                    
                                </div>                                
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('user.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('user.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ $user->name }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('user.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('user.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="email" class="input-group-text flex w-52"><i data-lucide="{{ trans('user.form.email.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('user.form.email.title') }}</div>
                                    <input type="email"  name="email" value="{{ $user->email }}" class="form-control  w-full" aria-describedby="email" placeholder="{{ trans('user.form.email.placeholder') }}" minlength="2" maxlength="64" readonly required>
                                    <div id="input-group-3" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('user.form.email.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                
                                <div class="input-group mt-3">
                                    <div id="password" class="input-group-text flex"><i data-lucide="{{ trans('user.form.password.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('user.form.password.title') }}</div>
                                    <input type="password"  name="password" value="" class="form-control  w-full" aria-describedby="password" placeholder="{{ trans('user.form.password.placeholder') }}" minlength="2" maxlength="64">
                                    <div id="input-group-4" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('user.form.password.tooltip_edit') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="role" class="input-group-text flex"><i data-lucide="{{ trans('user.form.role.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('user.form.role.title') }}</div>
                                    <select  id="role" name="role" class="form-control w-full" required>
                                        <option value=0>{{ trans('user.form.role.placeholder') }}</option>
                                        @foreach($roles as $key => $value)   
                                        <option value="{{ $key }}" @if( $key == $user->role ) selected @endif >{{ $value }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-5" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('user.form.role.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                               
                                <div class="input-group mt-3">
                                    <div id="job-id" class="input-group-text flex"><i data-lucide="{{ trans('user.form.job.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('user.form.job.title') }}</div>
                                    <select  id="select-job" name="job_id[]" class="form-control  w-full" size="6" multiple>
                                        <option value=''>{{ trans('user.form.job.placeholder') }}</option>
                                        @php($previous = '')                                        
                                        @php($n = 1) 
                                        @foreach($jobs as $job)
                                            @if( $previous != $job->department )
                                                @if( $n != 1 )
                                                </optgroup>
                                                @endif
                                                <optgroup label="{{ $job->department }}" class="text-lg">
                                                @php($previous = $job->department)
                                            @endif   
                                            <option value={{ $job->job_id }} @if( $job->selected ) selected @endif >{{ $job->name }}</option>
                                            @php($n++)                                             
                                        @endforeach
                                        </optgroup>
                                    </select>                                    
                                    <div id="input-group-6" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('user.form.job.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                               
                                <div class="input-group mt-3">
                                    <div id="location-id" class="input-group-text flex"><i data-lucide="{{ trans('user.form.location.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('user.form.location.title') }}</div>                                    
                                    <select id="select-location" name="location_id[]" class="form-control w-full" size="6" multiple>
                                        <option value=''>{{ trans('user.form.location.placeholder') }}</option>
                                    </select>                                    
                                    <div id="input-group-7" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('user.form.location.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
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

    @if ($errors->any())
    <script>
        setSuccessNotification('error', 'Oops!', '{{ $errors->first() }}');
    </script>    
    @endif

    <script type="text/javascript">
        $(function () {
            var jids = $("#select-job").val();
            var uid = $("input[name='user_id']").val();
            //console.dir(jids, uid);
            setLocations(uid, jids);

            $('body').on('change', '#select-job', function (e) {
                e.preventDefault();
                var jids = $("#select-job").val();
                var uid = $("input[name='user_id']").val();
                if( jids.length > 0 ) {
                    //console.dir(jids, uid);
                    setLocations(uid, jids);           
                } else {
                    $('#select-location').html('<option value="">{{ trans("user.form.location.placeholder") }}</option>');
                } // if
            }); // change        
        });

        function setLocations(uid, jids) {
            $.ajax({
                url: '/parametrizacion/usuarios/localizaciones',
                type: 'POST',
                dataType: 'json',
                data: {'jids': jids, 'uid': uid},
                //async: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(data) {                            
                    //console.dir(data);
                    if( data.success ) { 
                        var output = '<option value="">{{ trans("user.form.location.placeholder") }}</option>';                               
                        $.each(data.list, function(i, item) {
                            output += '<option class="text-base" value='+item.location_id;
                            output += ( item.selected ) ? ' selected' : '';
                            output += '>'+item.name+'</option>'; 
                        });
                        //console.log(output);
                        $('#select-location').html(output);
                    } else {
                        setSuccessNotification('error', 'Oops!', '{{ trans("user.form.location.error") }}');
                    }
                } // success
            }); // ajax  
        } // setLocations
    </script>    

@endpush

</x-icewall> 