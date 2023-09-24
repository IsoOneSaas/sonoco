<!-- resources/views/settings/department.edit.blade.php -->
<x-icewall>

    <x-slot:title>
        Departamento - Editar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Editar Departamento
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="department-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>
                            <a class="btn btn-primary shadow-md mr-2" href="{{ route('departamentos.index') }}"><i data-lucide="skip-back" class="w-5 h-5"></i></a>    
                        </div>
                    </div>
                    <!-- BEGIN: Form -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="department-form" action="{{ route('departamentos.update', $department->department_id) }}" method="POST">
                                @csrf 
                                @method('PUT')
                                <input type="hidden" name="department_id" value={{ $department->department_id }}>                             
                                <div class="input-group">
                                    <div id="code" class="input-group-text flex"><i data-lucide="{{ trans('department.form.code.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('department.form.code.title') }}</div>
                                    <input type="text" name="code" value="{{ $department->code }}" class="form-control  w-full" aria-describedby="code" placeholder="{{ trans('department.form.code.placeholder') }}" minlength="2" maxlength="8" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('department.form.code.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>                            
                                <div class="input-group mt-3">
                                    <div id="name" class="input-group-text flex"><i data-lucide="{{ trans('department.form.name.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('department.form.name.title') }}</div>
                                    <input type="text"  name="name" value="{{ $department->name }}" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('department.form.name.placeholder') }}" minlength="2" maxlength="64" required>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('department.form.name.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                    <div id="description" class="input-group-text flex"><i data-lucide="{{ trans('department.form.description.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('department.form.description.title') }}</div>
                                    <textarea id="validation-form-6" class="form-control" name="description" aria-describedby="description" placeholder="{{ trans('department.form.description.placeholder') }}" minlength="8" maxlength="255" required>{{ $department->description }}</textarea>
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('department.form.description.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                </div>
                                <div class="input-group mt-3">
                                <div id="loc" class="input-group-text flex"><i data-lucide="{{ trans('department.form.locations.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('department.form.locations.title') }}</div>
                                    <select  id="loc" name="locations[]" data-placeholder="{{ trans('department.form.locations.placeholder') }}" class="form-control tom-select w-full" multiple>
                                        @foreach($locations as $location)   
                                        <option value={{ $location->location_id }} @if( $location->selected ) selected @endif >{{ $location->name }}</option>
                                        @endforeach
                                    </select>                                    
                                    <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('department.form.locations.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
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