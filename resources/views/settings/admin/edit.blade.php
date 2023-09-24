<!-- resources/views/settings/admin.edit.blade.php -->
<x-icewall>

    <x-slot:title>
       Administrador - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">                            
                            Editar Administrador
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="user-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('administradores.index') }}"><i data-lucide="skip-back" class="w-5 h-5"></i></a>                            
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="user-form" action="{{ route('administradores.update', $admin->user_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user_id" value={{ $admin->user_id }}>                                                                                               
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('admin.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('admin.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ $admin->name }}" class="form-control w-full" readonly>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="system-id" class="input-group-text flex"><i data-lucide="{{ trans('admin.form.system.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('admin.form.system.title') }}</div>
                                    <select  id="system-id" name="system_id[]" class="form-control tom-select w-full" multiple>
                                        <option value=''>{{ trans('admin.form.system.placeholder') }}</option>                                      
                                        @foreach($systems as $system)  
                                            <option value={{ $system->system_id }} @if( $system->selected ) selected @endif >{{ $system->name }}</option>                                           
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('admin.form.system.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                
                                <div class="input-group mt-3">
                                    <div id="location-id" class="input-group-text flex"><i data-lucide="{{ trans('admin.form.location.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('admin.form.location.title') }}</div>
                                    <select  id="location-id" name="location_id[]" class="form-control tom-select w-full" multiple>
                                        <option value=''>{{ trans('admin.form.location.placeholder') }}</option>                                      
                                        @foreach($locations as $location)  
                                            <option value={{ $location->location_id }} @if( $location->selected ) selected @endif >{{ $location->name }}</option>                                           
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('admin.form.location.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                               
                                <div class="input-group mt-3">
                                    <div id="department-auth" class="input-group-text flex w-56"><i data-lucide="{{ trans('admin.form.dauth.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('admin.form.dauth.title') }}</div>
                                    <div class="form-switch mt-2 ml-4  w-full">
                                        <input type="checkbox" class="form-check-input" id="department-auth" name="department_auth" @if( $admin->department_auth ) checked @endif>
                                    </div>                                    
                                </div>
                                <div class="input-group mt-3">
                                    <div id="job-auth" class="input-group-text flex w-56"><i data-lucide="{{ trans('admin.form.jauth.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('admin.form.jauth.title') }}</div>
                                    <div class="form-switch mt-2 ml-4  w-full">
                                        <input type="checkbox" class="form-check-input" id="job-auth" name="job_auth" @if( $admin->job_auth ) checked @endif>
                                    </div>                                    
                                </div>
                                <div class="input-group mt-3">
                                    <div id="process-auth" class="input-group-text flex w-56"><i data-lucide="{{ trans('admin.form.pauth.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('admin.form.pauth.title') }}</div>
                                    <div class="form-switch mt-2 ml-4  w-full">
                                        <input type="checkbox" class="form-check-input" id="process-auth" name="process_auth" @if( $admin->process_auth ) checked @endif>
                                    </div>                                    
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