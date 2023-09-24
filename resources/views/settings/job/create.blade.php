<!-- resources/views/settings/job.create.blade.php -->
<x-icewall>

    <x-slot:title>
        Cargo - Crear
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">                            
                            Crear Cargo
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="job-form" class="btn btn-primary shadow-md mr-2" title="Salvar Formulario"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('cargos.index') }}" title="Regresar a la tabla"><i data-lucide="skip-back" class="w-5 h-5"></i></a>   
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="job-form" action="{{ route('cargos.store') }}" method="POST">
                                @csrf                                                        
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('job.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('job.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ old('name') }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('job.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('job.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="description" class="input-group-text flex"><i data-lucide="{{ trans('job.form.description.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('job.form.description.title') }}</div>
                                    <textarea id="validation-form-6" class="form-control" name="description" aria-describedby="description" placeholder="{{ trans('job.form.description.placeholder') }}" minlength="8" maxlength="255" required>{{ old('description') }}</textarea>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('job.form.description.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="department-id" class="input-group-text flex"><i data-lucide="{{ trans('job.form.department.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('job.form.department.title') }}</div>
                                    <select  id="department-id" name="department_id" class="form-control w-full">
                                        <option value=''>{{ trans('job.form.department.placeholder') }}</option>
                                        @foreach($departments as $department)   
                                        <option value={{ $department->department_id }} >{{ $department->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('job.form.department.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                                 
                                <div class="input-group mt-3">
                                    <div id="pre-id" class="input-group-text flex w-52"><i data-lucide="{{ trans('job.form.pre_id.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('job.form.pre_id.title') }}</div>
                                    <select  id="pre-id" name="pre_id" class="form-control w-full">
                                        <option value=0>{{ trans('job.form.pre_id.placeholder') }}</option>
                                        @php($previous = '')                                        
                                        @php($n = 1) 
                                        @foreach($prejobs as $prejob)
                                            @if( $previous != $prejob->department )
                                                @if( $n != 1 )
                                                </optgroup>
                                                @endif
                                                <optgroup label="{{ $prejob->department }}" class="text-lg">
                                                @php($previous = $prejob->department)
                                            @endif   
                                            <option value={{ $prejob->job_id }} @if( $prejob->gray ) class="bg-slate-100" @endif >{{ $prejob->name }}</option>
                                            @php($n++)                                             
                                        @endforeach
                                        </optgroup>                                        
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('job.form.pre_id.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
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
<!-- <script src="{{ url('assets/js/jquery/jquery-3.6.4.min.js') }}"></script>
<script type="text/javascript">

    $(function() {

        $('body').on('change', '#department-id', function (e) {
            e.preventDefault();
            var id = $("#department-id option:selected").val();

            $.ajax({
                url: '/parametrizacion/cargos/seleccion/'+ id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('=== AJAX GET');
                    console.dir(data);
                }
            });        
        });
    });

</script> -->
@endpush

</x-icewall> 