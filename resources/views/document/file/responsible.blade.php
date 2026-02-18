<!-- resources/views/document/type.index.blade.php -->
<x-icewall>

    <x-slot:title>
            Responsables  - Administrar
    </x-slot:title>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Edición de responsables para administrar archivos
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button type="submit" form="validation-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>

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
                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box p-5 mt-5">
                        <div>
                            <form id="validation-form" action="{{ route('files.settings.responsibles.store') }}" method="POST">
                                @csrf 
                                <div class="input-group mt-3">
                                   <table width="100%">

                                        @php($previous = '')                                       
                                        @foreach($DATA as $line)
                                            @if($previous != $line->location_id)
                                                @if(!$loop->first)
                                                </select></td><td><select id="job_{{ $line->location_id }}" name="job[]"><option value=0>Seleccione Cargos</option></select></td><td><select id="user_{{ $line->location_id }}" name="user[]"><option value=0>Seleccione Usuarios</option></select></td></tr>
                                                @endif                                            
                                                <tr><td>{{ $line->lName }}<input type="hidden" name="location[]" value={{ $line->location_id }}></td><td><select id="dpto_{{ $line->location_id }}" name="department[]"><option value=0>Seleccione Departamento</option>
                                                @php($previous = $line->location_id)                                                
                                            @endif   
                                            <option value={{ $line->department_id }}>{{ $line->dName }}</option>                                            
                                            @if ($loop->last)
                                             </select></td><td><select id="job_{{ $line->location_id }}" name="job[]"><option value=0>Seleccione Cargos</option></select></td><td><select id="user_{{ $line->location_id }}" name="user[]"><option value=0>Seleccione Usuarios</option></select></td></tr> 
                                            @endif                                            
                                        @endforeach

                                   </table>
                                    
                                </div>                                                                                                                                                                   
                            </form>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->
                 
                </div>
                <!-- END: Content -->
@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/multiple-select-1.6.0/dist/multiple-select.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/multiple-select.min.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/locale/multiple-select-es-ES.min.js') }}"></script>
<script src="{{ url('assets/js/iso_scripts.js') }}"></script>
<script document="text/javascript">
    $(function () { 
        
        // Select de departamentos
        $("select[name='department[]']").on("change", function(e) {
            e.preventDefault();
            var did = this.value;
            var str = this.id; 
            var arr = str.split("_");
            var id = arr[1];
                       
            console.log('DID: '+did+' ID: '+id);
            if( did > 0 ) {
                //seJobsAjax(id, did);
            } else {
                // Cerrar
                $("#job_"+id).html('<option value=0>Seleccione Cargos</option>'); 
                $("#user_"+id).html('<option value=0>Seleccione Usuarios</option>'); 
            }
            //var didsArray = $("#department-selected").val();
        });

    }); // document

    function seJobsAjax(id, did) {        
        var route = "{{ route('files.settings.responsibles.jobs') }}";        
        console.log('Running setJobsAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {did: did},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                console.dir(data);
                if( data.success ) {
                    // Generar nuevo select de temas                
                    //generateJobsSelect(id, data.jobs);
                    // 
                    //$("#department-selected").trigger('change');
                } else {
                    setSuccessNotification('error', 'Oops!', data.message);
                    // Blanquear select 
                    $("#job_"+id).html('<option value=0>Seleccione Cargos</option>'); 
                }
            } // success
        }); // ajax         
    } // seJobsAjax Fx

    function generateJobsSelect(id, jobs) {
        var output = '';
        $("#job_"+id).multipleSelect('destroy').html('');
        if( jobs.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.job.no-exist") }}'); 
        } else {
            $.each(jobs, function(i, job) {
                output += '<option value="'+job.job_id+'"';
                output += (job.selected) ? ' selected' : '';
                output += '>'+job.name+'</option>';            
            });
            $("#job_"+id).html(output).multipleSelect();
        }        
    } // generateJobsSelect      


</script>

    @include('components.notification_index')

@endpush

</x-icewall> 