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



                    </div>
                    <div class="flex justify-center">
                        <a href="{{ route('documents.master.index') }}" class="btn btn-primary w-50 mr-2 mb-2"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Listado Maestro </a>
                    </div>                    
                </div>
                <!-- END: Content -->              


</x-icewall>