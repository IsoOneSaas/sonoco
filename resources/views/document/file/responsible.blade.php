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
                            <button type="submit" form="responsible-form" class="btn btn-primary shadow-md mr-2"> <i data-lucide="save" class="w-5 h-5"></i> </button>

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
                            <form id="responsible-form" action="{{ route('files.settings.responsibles.store') }}" method="POST" onSubmit="return false;">
                                @csrf 
                                <div class="input-group mt-3">
                                   <table width="100%">

                                        @php($previous = 0)                                       
                                        @foreach($DATA as $line)
                                            @if($previous != $line->location_id )
                                                @if(!$loop->first)
                                                </select></td><td><select id="job_{{ $previous }}" class="job" name="job[{{ $previous }}]" size="1"><option value=0>{{ trans("document/responsible.form.job.placeholder") }}</option></select></td><td><select multiple id="user_{{ $previous }}" class="user" name="user[{{ $previous }}][]" size="1"></select></td></tr>
                                                @endif                                            
                                                <tr><td class="{{ $line->style }}">{{ $line->lName }}<input type="hidden" name="location[]" value={{ $line->location_id }}></td><td><select id="dpto_{{ $line->location_id }}" class="department" name="department[{{ $line->location_id }}]"><option value=0>{{ trans("document/responsible.form.department.placeholder") }}</option>
                                                @php($previous = $line->location_id )                                                
                                            @endif   
                                            <option class="{{ $line->style }}" value={{ $line->department_id }}>{{ $line->dName }}</option>                                            
                                            @if ($loop->last)
                                             </select></td><td><select id="job_{{ $line->location_id }}" class="job" name="job[{{ $line->location_id }}]" size="1"><option value=0>{{ trans("document/responsible.form.job.placeholder") }}</option></select></td><td><select multiple  id="user_{{ $line->location_id }}" class="user" name="user[{{ $line->location_id }}][]" size="1"></select></td></tr> 
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
@push('meta')                
    <meta name="csrf-token" content="{{ csrf_token() }}">                
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/js/multiple-select-1.6.0/dist/multiple-select.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/iso.css') }}" />
    <style>
        .option-gray {
            background-color: #f0f0f0;
        }

        .option-blank {
            background-color: transparent;
        }        
    </style>
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/multiple-select.min.js') }}"></script>
<script src="{{ url('assets/js/multiple-select-1.6.0/dist/locale/multiple-select-es-ES.min.js') }}"></script>
<script src="{{ url('assets/js/iso_scripts.js') }}"></script>
<script document="text/javascript">
    $(function () { 

        $('#responsible-form').submit(function(e) {
            console.log('Submiting Form...');
            e.preventDefault();
            var url = $(this).attr('action');
            var method = $(this).attr('method');
            
            // Validar Departamentos
            var departmentValues = [];
            $('.department').find('option:selected').each(function() {
                departmentValues.push($(this).val());
            });
            console.log(departmentValues);

            // Validar Cargos
            var jobValues = [];
            $('.job').find('option:selected').each(function() {
                jobValues.push($(this).val());
            });
            console.log(jobValues);   
            
            // Validar Usuarios
            var userValues = [];
            $('.user').find('option:selected').each(function() {
                userValues.push($(this).val());
            });
            console.log(userValues);               

            if( validSelect(departmentValues) ) {
                    if( validSelect(jobValues) ) {
                        if( userValues.length != 0 ) {
                            var formData = new FormData(this);
                            // Enviar
                            $.ajax({
                                type: method,
                                url: url,
                                data: formData,
                                contentType: false, 
                                processData: false,                 
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
                                success: function(response) {
                                    console.dir(response);
                                    if( response.status == 'success' ) {
                                        setSuccessNotification('success', '', response.message);
                                    } else {
                                        setSuccessNotification('error', 'Oops!', response.message); 
                                    }                    
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error: " + error);
                                    setSuccessNotification('error', 'Oops!', error); 
                                }
                            }); // ajax
                        } else {
                        setSuccessNotification('error', 'Oops!', '{{ trans("document/responsible.request.user.empty") }}'); 
                    }
                    } else {
                        setSuccessNotification('error', 'Oops!', '{{ trans("document/responsible.request.job.empty") }}'); 
                    }
            } else {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/responsible.request.department.empty") }}'); 
            }

        });
        
        // Select de departamentos
        //$("select[name='department[]']").on("change", function(e) {
        $(".department").on("change", function(e) {
            e.preventDefault();
            var did = this.value;
            var id = getId(this.id); 
                       
            console.log('DID: '+did+' ID: '+id);
            if( did > 0 ) {
                seJobsAjax(id, did);
            } else {
                // Cerrar
                $("#job_"+id).html('<option value=0>Seleccione un cargo</option>'); // .multipleSelect('destroy')
                $("#user_"+id).multipleSelect('destroy').html('');
                //$("#user_"+id).html('');
            }
        }); // select-department

        // Select de cargos
        //$("select[name='job[]']").on("change", function(e) {
        $(".job").on("change", function(e) {
            e.preventDefault();
            var id = getId(this.id); 
            //var jidsArray = $("#job_"+id).val();
            var jid = this.value;
            var did = $("#dpto_"+id).val();
                       
            console.log('ID: '+id+' DID : '+did+' JID: '+jid);
            //console.dir(jidsArray);
            if( jid > 0 ) {
                setUsersAjax(id, did, jid);
            } else {
                // Cerrar
                $("#user_"+id).multipleSelect('destroy').html('');
                //$("#user_"+id).html('');
            }
        }); // select-department        

    }); // document

    $(document).ready(function() {
        //$(".department").trigger('change');        
    });
    

    function seJobsAjax(id, did) {        
        var route = "{{ route('files.settings.responsibles.jobs') }}";        
        console.log('Running setJobsAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {lid: id, did: did},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                console.dir(data);
                if( data.success ) {
                    // Generar nuevo select de cargos               
                    generateJobsSelect(id, data.jobs);

                } else {
                    setSuccessNotification('error', 'Oops!', data.message);
                    // Blanquear select 
                    $("#job_"+id).html('');
                }
            } // success
        }); // ajax         
    } // seJobsAjax Fx

    function generateJobsSelect(id, jobs) {
        console.log('Generate Jobs Select to id='+id);
        console.dir(jobs);
        var output = '<option value=0>{{ trans("document/responsible.form.job.placeholder") }}</option>';
        //$("#job_"+id).multipleSelect('destroy').html('');
        if( jobs.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/responsible.request.job.no-exist") }}'); 
        } else {
            $.each(jobs, function(i, job) {
                output += '<option class="'+job.style+'" value='+job.job_id+'>'+job.name+'</option>';            
            });
            $("#job_"+id).html(output); //.multipleSelect();
        }        
    } // generateJobsSelect     
    
    function setUsersAjax(id, did, jid) {        
        var route = "{{ route('files.settings.responsibles.users') }}";        
        console.log('Running setUsersAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {lid: id, did: did, jid: jid},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(data) {
                console.dir(data);
                if( data.success ) {
                    // Generar nuevo select de cargos               
                    generateUsersSelect(id, data.users);
                } else {
                    setSuccessNotification('error', 'Oops!', data.message);
                    // Blanquear select 
                    $("#user_"+id).multipleSelect('destroy').html('');
                    //$("#user_"+id).html('');
                }
            } // success
        }); // ajax         
    } // seUsersAjax Fx

    function generateUsersSelect(id, users) {
        var output = '';
        $("#user_"+id).multipleSelect('destroy').html('');
        if( users.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/responsible.request.user.no-exist") }}'); 
        } else {
            $.each(users, function(i, user) {
                output += '<option value='+user.user_id;
                output += (user.selected) ? ' selected' : '';
                output += '>'+user.name+'</option>';            
            });
            $("#user_"+id).html(output).multipleSelect();
            //$("#user_"+id).html(output);
        }        
    } // generateUsersSelect   
    
    function getId(str) {
        var arr = str.split("_");
        return arr[1];
    }


    function validSelect(array) {
        var exist = false;
        console.log('Validate...');
        console.dir(array);
        $.each(array, function(i, val) {
            //console.log('val: '+ val);
            if( val != "0" ) {
                exist = true;
            }
        });
        return exist;
    }
    
</script>

    @include('components.notification_index')

@endpush

</x-icewall> 