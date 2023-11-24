<!-- resources/views/settings/process.edit.blade.php -->
<x-icewall>

    <x-slot:title>job.
        Proceso - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">                            
                            Editar Proceso
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="process-form" class="btn btn-primary shadow-md mr-2" title="Salvar Formulario"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('procesos.index') }}" title="Regresar a la tabla"><i data-lucide="skip-back" class="w-5 h-5"></i></a>   
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="process-form" action="{{ route('procesos.update', $process->process_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="process_id" value={{ $process->process_id }}>                                
                                <div class="input-group mt-3">
                                    <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('process.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('process.form.code.title') }}</div>
                                    <input type="text"  name="code" value="{{ $process->code }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('process.form.code.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                                                                        
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('process.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('process.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ $process->name }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('process.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="version" class="input-group-text flex"><i data-lucide="{{ trans('process.form.version.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('process.form.version.title') }}</div>
                                    <input type="text"  name="version" value="{{ $process->version }}" class="form-control  w-full" aria-describedby="version" placeholder="{{ trans('process.form.version.placeholder') }}" maxlength="24" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.version.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div> 
                                <div class="input-group mt-3">
                                    <div id="target" class="input-group-text flex"><i data-lucide="{{ trans('process.form.target.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('process.form.target.title') }}</div>
                                    <textarea id="validation-form-6" class="form-control" name="target" aria-describedby="target" placeholder="{{ trans('process.form.target.placeholder') }}" minlength="8" required>{{ $process->target }}</textarea>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.target.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>

                                <div class="input-group mt-3">
                                    <div id="department-id" class="input-group-text flex"><i data-lucide="{{ trans('process.form.department.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('process.form.department.title') }}</div>
                                    <select id="select-department" name="department_id[]" class="form-control tom-select w-full" multiple required>
                                        <option value=''>{{ trans('process.form.department.placeholder') }}</option>
                                        @foreach($departments as $department)   
                                        <option value={{ $department->department_id }} @if( $department->selected ) selected @endif >{{ $department->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.department.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div> 


                                    {{-- <div class="input-group mt-3">
                                    <div id="department-id" class="input-group-text flex"><i data-lucide="{{ trans('process.form.department.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('process.form.department.title') }}</div>
                                    <select id="select-department" name="department_id[]" class="form-control tom-select w-full" multiple required>
                                        <option value=''>{{ trans('process.form.department.placeholder') }}</option>
                                        @foreach($departments as $department)   
                                        <option value={{ $department->department_id }} @if( $department->selected ) selected @endif >{{ $department->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text  mr-2"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.department.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                    <div id="auth-id" class="input-group-text flex"><i data-lucide="{{ trans('process.form.auth.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('process.form.auth.title') }}</div>
                                    <select multiple  id="auth-id" name="auth_id[]" class="form-control tom-select w-full">
                                        <option value=''>{{ trans('process.form.auth.placeholder') }}</option>
                                        @foreach($auths as $auth)   
                                        <option value={{ $auth->job_id }} @if( $auth->selected ) selected @endif >{{ $auth->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.auth.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div> --}} 

                                <div class="input-group mt-3">
                                    <div id="job-id" class="input-group-text flex w-52"><i data-lucide="{{ trans('process.form.job.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('process.form.job.title') }}</div>
                                    <select  name="job_id" class="form-control w-full" style="z-index:1" required>
                                        <option value=''>{{ trans('process.form.job.placeholder') }}</option>
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('process.form.job.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
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

<script type="text/javascript">
    $(function () {
        
        var pid = $("input[name=process_id]").val();
        var ids = $("#select-department").val();
        var txt = "{{ trans('process.form.job.placeholder') }}";
        var url = '/parametrizacion/procesos/cargoslider/'+pid+'/'+JSON.stringify(ids); 
        //console.dir(ids);
        setSelectGroup(url, "select[name=job_id]", txt);            

        $('body').on('change', '#select-department', function (e) {
            e.preventDefault();
            ids = $("#select-department").val();
            url = '/parametrizacion/procesos/cargoslider/'+pid+'/'+JSON.stringify(ids);            
            //console.dir(ids);
            setSelectGroup(url, "select[name=job_id]", txt);            
        }); // change
    }); // document
</script>       

@endpush

</x-icewall> 