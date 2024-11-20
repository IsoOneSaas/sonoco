<!-- resources/views/dashboard/master.blade.php -->
<x-icewall>

    <x-slot:title>
            Dashboard de Usuario
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </x-slot:breadcrumb>       

                <!-- BEGIN: Content -->
                <div class="content background-dashboard">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 2xl:col-span-9">
                            <div class="grid grid-cols-12 gap-6 background-documents">                                                    

                                <div class="col-span-12 mt-8">
                                    <div class="intro-y flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            Inicio
                                        </h2>
                                        <!-- <a href="" class="ml-auto flex items-center text-primary"> <i data-lucide="refresh-ccw" class="w-4 h-4 mr-3"></i> Recargar Datos </a> -->
                                    </div>
                                    <div class="">
                                        <div class="intro-y box mt-5">
                                            
                                            <div  class='flex bg-white shadow-md justify-start md:justify-center rounded-lg overflow-x-scroll mx-auto py-4 px-2  md:mx-12 w-full'>
        
                                                @foreach($week as $day)
                                                    @if( $day['today'] )
                                                    <div class='flex group bg-purple-600 shadow-lg dark-shadow rounded-lg mx-1 cursor-pointer justify-center relative  w-full'>
                                                        <span class="flex h-3 w-3 absolute -top-1 -right-1">
                                                            <span class="animate-ping absolute group-hover:opacity-75 opacity-0 inline-flex h-full w-full rounded-full bg-purple-400 "></span>
                                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-100"></span>
                                                        </span>
                                                        <div class='flex items-center px-4 py-4'>
                                                            <div class='text-center'>
                                                                <p class='text-gray-100 text-sm'> {{ $day['name'] }} </p>
                                                                <p class='text-gray-100  mt-3 font-bold'> {{ $day['number'] }} </p>
                                                                <div>
                                                                    <p>Este es un evento</p>
                                                                </div>                                                                
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    @else
                                                    <div class='flex group hover:bg-purple-500 hover:shadow-lg hover-dark-shadow rounded-lg mx-1 transition-all	duration-300	 cursor-pointer justify-center w-full'>
                                                        <div class='flex items-center px-4 py-4'>
                                                            <div class='text-center'>
                                                            <p class='text-gray-900 group-hover:text-gray-100 text-sm transition-all	duration-300'>{{ $day['name'] }} </p>
                                                            <p class='text-gray-900 group-hover:text-gray-100 mt-3 group-hover:font-bold transition-all	duration-300'> {{ $day['number'] }} </p>
                                                            <div>
                                                                    
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    @endif
                                                @endforeach
                                                                                    
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <div class="intro-y box mt-5">
                                        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                            <h2 class="font-medium text-base mr-auto">
                                                Documentos vistos
                                            </h2>
                                        </div>
                                        <div class="p-5">
                                            <h2>Contenido</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <div class="intro-y box mt-5">
                                        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                            <h2 class="font-medium text-base mr-auto">
                                                Registros creados
                                            </h2>
                                        </div>
                                        <div class="p-5">
                                            <h2>Contenido</h2>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>                   
                </div>

              
                <!-- END: Content -->              


</x-icewall>