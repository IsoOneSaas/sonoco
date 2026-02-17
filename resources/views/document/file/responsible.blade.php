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
                                        @php($n = 0)
                                        
                                        @foreach($DATA as $line)
                                            @if( $previous != $line->location_id )
                                                @php($n++)
                                                @if( $n != 1 )
                                                </select></td><td><select id="job{{ $line->document_id }}" name="job[]"><option value=0>Seleccione Cargos</option></select></td><td><select id="user{{ $line->document_id }}" name="user[]"><option value=0>Seleccione Usuarios</option></select></td></tr>
                                                @endif                                            
                                                <tr><td>{{ $line->lName }} {{ $n }}<input type="hidden" name="location[]" value={{ $line->location_id }}></td><td><select name="department[]"><option value=0>Seleccione Departamento</option>
                                                @php($previous = $line->location_id )
                                                
                                            @endif   
                                            <option value={{ $line->department_id }}>{{ $line->dName }}</option>
                                            
                                            @if( $n == $N )
                                            
                                            @endif
                                            
                                        @endforeach
                                        </select></td><td><select id="job" name="job[]"><option value=0>Seleccione Cargos</option></select></td><td><select id="user" name="user[]"><option value=0>Seleccione Usuarios</option></select></td></tr>                                                                                
                                        <tr><td>{{ $N }} {{ $n }}</td><td colspan="3">&nbsp;</td></tr>
                                   </table>
                                    
                                </div>                                                                                                                                                                   
                            </form>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->
                 
                </div>
                <!-- END: Content -->


@push('scripts-bottom')   

    @include('components.notification_index')

@endpush

</x-icewall> 