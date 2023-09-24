<!-- resources/views/document/document/head_default.blade.php -->
<div class="overflow-x-auto">
    <table id="iso-head-table" class="table border w-full">
            <tr>
                <td rowspan="5" width="20%">
                    <div class="flex mr-auto justify-center">
                        <img alt="Logo" src="{{ url('tenants/sonoco/images/logo.png') }}" class="">
                    </div>                                            
                </td>
                <td rowspan="5">
                    <h4 class="text-base font-medium leading-none">{{ $document->type ?? '' }}</h4>
                    <h4 class="mt-3 text-lg font-medium leading-none">{{ $document->process ?? '' }}</h4>
                    <h3 class="mt-3 text-xl font-medium leading-none">{{ $document->name ?? '' }}</h3>                    
                </td>
                <td>Código</td>
                <td>{{ $document->code ?? '' }}</td>
            </tr>
            <tr>
                <td>Versión</td>
                <td>{{ $document->version ?? '' }}</td>
            </tr>
            <tr>
                <td>Fecha Publicación</td>
                <td>{{ $document->date ?? ''}}</td>
            </tr>
            <tr>
                <td>Página</td>
                <td>{{ $document->page ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="2" class="stamp">{{ $document->STAMP or '' }}</td>
            </tr>             
    </table>
</div>  