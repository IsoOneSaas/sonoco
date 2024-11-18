<!-- resources/views/dashboard/intro.blade.php -->
<x-icewall>

    <x-slot:title>
            Dashboard
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </x-slot:breadcrumb>       

                <!-- BEGIN: Content -->
                <div class="content background-dashboard">
                    <div class="intro-y col-span-11 alert alert-warning show flex items-center mb-6" role="alert">
                        <span><i data-lucide="info" class="w-4 h-4 mr-2"></i></span>
                        <span>Bienvenido a ISO-ONE... un momento mientras se prepara su página inicial</span>
                    </div>                    
                    <div class="text-center">
                        <img id="loading-image" alt="Cargando..." class="h-auto max-w-xs mx-auto" width="140" height="140" src="{{ url('/assets/images/loading.gif') }}">
                    </div>
                </div>
                <!-- END: Content -->
@push('scripts-bottom')
<script type="text/javascript">
    // Check if the page has loaded completely                                         
    $(document).ready( function() {
        var url = '{{ $link }}';
        window.location.href = url;
        //window.location.href = '/documentos/dashboard';
    }); 
</script>               
@endpush 

</x-icewall>