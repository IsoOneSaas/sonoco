<!-- resources/views/document/control/edit_html.blade.php -->
<x-icewall>

    <x-slot:title>
        Documento - Visualizar
    </x-slot:title>

    <x-slot:breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('documents.control.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('documents.master.index') }}">Listado Maestro</a></li>
        <li class="breadcrumb-item active" aria-current="page">Visualizar Documento</li>
    </x-slot:breadcrumb>

                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Visualizar Documento
                        </h2>
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                        <a class="btn btn-primary shadow-md mr-2" href="javascript:;" id="btn-modal-sight"><i data-lucide="message-circle" class="w-5 h-5"></i></a>
                            <a class="btn btn-primary shadow-md mr-1" href="javascript:;" title="Regresar" id="btn-exit"><i data-lucide="menu" class="w-5 h-5"></i></a>                               
                            <div class="dropdown ml-auto sm:ml-0">
                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="more-vertical"></i> </span>
                                </button>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="btn-modal-history" href="javascript:;" class="dropdown-item"> <i data-lucide="cast" class="w-4 h-4 mr-2"></i> Historial </a>
                                        </li>
                                        @if($document->pattern == 'HTML')
                                        <li>
                                            <a id="btn-print" href="javascript:;" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Imprimir </a>
                                        </li> 
                                        @endif                                                                                                                   
                                    </ul>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- BEGIN: Editor -->
                    <div class="intro-y box p-5 mt-5 bg-slate-200 flex justify-center">

                        @if($document->pdf)
                            <div class="justify-center">
                                <embed id="pdf-render" type="application/pdf" src="{{ url($document->pdf) }}" width="640" height="1020" />
                            </div>                            
                        @else
                            @if($document->pattern == 'HTML')
                            <div class="iso-body iso-{{ $size ?? 'emtpy' }}">
                                <div class="iso-page iso-{{ $size ?? 'emtpy' }}">
                            @else
                            <div class="iso-body">
                                <div class="iso-page">                        
                            @endif
                                    <div class="w-full p-2">
                                        @include('document/document/head_default')
                                        <div class="overflow-x-auto my-4">
                                        @if($document->content != '')
                                            <div id="html-pattern">
                                            {!! $document->content !!}
                                            </div>
                                        @elseif( is_string($document->url) )
                                            <div id="file-pattern">

                                                <table id="iso-body-table">
                                                    <tr>
                                                        <td rowspan="2" colspan="2">
                                                            <a id="btn-show" href="javascript:;"><img src="{{ url($document->url) }}" alt="Mime" /></i></a>                                        
                                                        </td>
                                                        <td><span>Sistema de Gestión:</span><p>{{ $document->system ?? '' }}</p><br><br></td>
                                                        <td><span>Proceso:</span><p>{{ $document->process ?? '' }}</p><br><br></td>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="2"><span>Tipo de documento:</span><p>{{ $document->type ?? '' }}</p><br><br></td>
                                                        <td rowspan="2"><span>Palabras clave:</span>
                                                            <p>
                                                                @foreach($document->tags as $key => $values)
                                                                    <em>{{ $key }} : </em>
                                                                    @foreach($values as $value)
                                                                    {{ $value }} - 
                                                                    @endforeach
                                                                @endforeach
                                                            </p>
                                                            <br><br>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span>Tamaño:</span><p>{{ round($document->support['size']/1000, 0) ?? '' }} kB</p></td>
                                                        <td><span>Tipo:</span><p>{{ $document->mime ?? '' }}</p></td>
                                                    </tr>
                                                </table>

                                            </div>
                                        @else
                                            <div id="empty-pattern">
                                                &nbsp;
                                            </div>
                                        @endif                                
                                        </div>
                                        <div class="iso-link">
                                            @if( $attachment )
                                                <h2>Anexos: </h2>
                                                <ul>
                                                    @foreach($attachment as $item)
                                                    <li><a href="javascript:;" data-file="{{ $item->url }}" target="_blank" class="btn-link"><img src="{{ url($item->mime) }}" alt="Mime" style="width:1em"  class="inline-block" /> {{$item->name }} [{{ round($item->size/1000,0) }} kB]</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                        @include('document/document/footer_default')
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>                
                    <!-- END: Editor -->
                    
                    <!-- BEGIN: Modal Sighting -->
                    <div id="modal-sightings" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-sightings-title" class="font-medium text-base mr-auto">Editar observación para el documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <form id="sighting-form" method="post" action="{{ route('documents.master.sighting') }}">
                                        @csrf
                                        <input type="hidden" name="document_id" value={{ $document->document_id }} />

                                        <div class="input-group mt-0">
                                            <div id="type" class="input-group-text flex"><i data-lucide="{{ trans('document/sighting.form.type.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/sighting.form.type.title') }}</div>
                                            <select  name="type" class="form-control w-full" required>                                                
                                                @foreach($types as $item)   
                                                <option value="{{ $item }}"  {{ old('type') == $item ? 'selected ' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select> 
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/sighting.form.type.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                                                            
                                            <div id="page" class="input-group-text flex ml-2"><i data-lucide="{{ trans('document/sighting.form.page.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/sighting.form.page.title') }}</div>
                                            <input type="text"  name="page" value="{{ old('page', '') }}" class="form-control  w-full" aria-describedby="page" placeholder="{{ trans('document/sighting.form.page.placeholder') }}" maxlength="4" required>
                                            <div id="input-group-3" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.sightpage.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>

                                            <div id="section" class="input-group-text flex ml-2"><i data-lucide="{{ trans('document/sighting.form.section.icon') }}" class="w-4 h-4 mr-1"></i>{{ trans('document/sighting.form.section.title') }}</div>
                                            <input type="text"  name="section" value="{{ old('section', '') }}" class="form-control  w-full" aria-describedby="page" placeholder="{{ trans('document/sighting.form.section.placeholder') }}" maxlength="50" required>
                                            <div id="input-group-4" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/sighting.form.section.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>                                            
                                        </div>
                                        <div class="input-group mt-3">
                                            <div id="content" class="input-group-text flex"><i data-lucide="{{ trans('document/sighting.form.content.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/sighting.form.content.title') }}</div>
                                            <textarea name="content" class="form-control  w-full" aria-describedby="name" placeholder="{{ trans('document/sighting.form.content.placeholder') }}" rows="3" minlength="8" required></textarea>
                                            <div id="input-group-5" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/sighting.form.content.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                    <table id="example" class="display dataTable" style="width:100%" aria-describedby="example_info">
                                        <thead>
                                            <tr>
                                                <th class="dt-control sorting_disabled" rowspan="1" colspan="1" style="width: 22.9688px;"></th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Fecha</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Nombre</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Tipo</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Página</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Sección</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-sightings-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cancelar</button>
                                    <button id="btn-sightings-ok" type="button" form="sighting-form" class="btn btn-primary">Salvar</button>
                                    <a id="modal-sightings-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-sightings" class="">.</a>

                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Sighting -->                  
                  

                    <!-- BEGIN: Modal History -->
                    <div id="modal-histories" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-histories-title" class="font-medium text-base mr-auto">Listado de cambios</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                    <div class="overflow-x-auto">
                                        <table id="histories-table" class="table">
                                            <thead>
                                                <tr>
                                                    <th class="whitespace-nowrap">#</th>
                                                    <th class="whitespace-nowrap">Fecha</th>
                                                    <th class="whitespace-nowrap">Versión</th>
                                                    <th>Comentario</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-histories-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cerrar</button>
                                    <a id="modal-histories-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-histories" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal History -->                                  

                </div>
                <!-- END: Content -->



@push('styles')
<link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/js/datatables/DataTables-1.13.4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/head.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/preview.css') }}" />    
    <style>
        .iso-body {
            margin: 0;
        }
        td.stamp { 
            height: 40px;         
            text-align: center;
            opacity:0.5;
            z-index:99;
            color:#AAA; 
        }        
       h2 {
        font-weight: 700;
        font-size: 1.2em;
        margin-bottom: 0.3em;
       }
       p, li {
        margin-bottom: 0.2em
       }
            
    </style>
@endpush

@push('scripts-bottom')
<script src="{{ url('assets/js/datatables/DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/js/datatables/Buttons-2.3.6/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('assets/js/iso_scripts.js') }}"></script>
    <script>
        let $did = "{{ $document->document_id }}";
        let $session = '';
        var $myTable;
        var $route = "{{ route('documents.master.sightings', ':id') }}";
        $(function () {
            let uri = isoGetStorage('iso_returnUrl');
            let page = isoGetStorage('iso_returnPage');
            let orderCol = isoGetStorage('iso_returnCol');
            let orderDir = isoGetStorage('iso_returnDir');
            var width = $(window).width();
            var pdfWidth = Math.round(width * 0.8);
            

            $("#pdf-render").attr('width', pdfWidth);           
            //console.log('** Get Storage:');        
            //console.dir(uri);

            openDocument();

            $('body').on('click', '#btn-show', function (e) {
                e.preventDefault();
                var win;
                var file = "{{ $document->support['file'] ?? '' }}";                 
                var uri = "{{ route('documents.control.manage.support.show', ':name') }}"; 
                if( file != '' ) {
                    uri = uri.replace(':name', file);
                    win = window.open(uri, '_blank');
                    win.focus();
                } else {
                    setSuccessNotification('error', 'Oops!', 'Documento soporte no encontrado!'); // FIXME: 
                }
            });

            $('body').on('click', '.btn-link', function (e) {
                e.preventDefault();
                var win;
                var file = $(this).data('file'); 
                //alert(file);                
                var uri = "{{ route('documents.control.manage.support.show', ':name') }}"; 
                if( file != '' ) {
                    uri = uri.replace(':name', file);
                    win = window.open(uri, '_blank');
                    win.focus();
                } else {
                    setSuccessNotification('error', 'Oops!', 'Documento anexo no encontrado!'); // FIXME: 
                }
            });            
            
            $("#btn-exit").on("click", function() {
                // Establecer setup
                isoSetStorage('master_returnPage', page);
                isoSetStorage('master_returnCol', orderCol);
                isoSetStorage('master_returnDir', orderDir);
                // PREGUNTAR SI ESTÁ SEGURO?
                closeDocument();
                if ( uri !== null ) {
                    //location.href = uri;
                    history.back();
                }
                return false;
            });
            
            // GEMERA EL MODAL PARA OBSERVACIONES
            $('body').on('click', '#btn-modal-sight', function (e) {
                e.preventDefault();                             
                renderTable();
                $("#modal-sightings-open")[0].click();
            });

            $("#btn-sightings-ok").on("click", function(e) {
                e.preventDefault();
                var data = $("#sighting-form").serializeArray();
                var uri =  $("#sighting-form").attr('action');
                //console.dir(data); console.log(uri);
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: uri,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: false,
                    success: function(json) {
                        console.log('=== AJAX CODE');
                        console.dir(json);
                        if( json.status === 'success' ) {
                            //$("#btn-sightings-ko")[0].click();
                            //$("#sighting-form")[0].reset();                                    
                            setSuccessNotification('success', '', json.message +'... Recargando');
                            window.location.reload();
                        } else {
                            setSuccessNotification('error', 'Oops!', json.message);
                        }
                    }, // success
                    error: function(jqXHR, exception) {
                        setAjaxError(jqXHR, exception, 'document.document.html.blade@btn-sightings-ok');
                    } 
                }); // ajax                                  
            }); // btn-sightings-ok 

            // IMPRIMIR EL DOCUMENTO HTML
            $('body').on('click', '#btn-print', function (e) {
                //$did
                e.preventDefault();
                var win;
                var file = "{{ $document->filename ?? '' }}";
                var uri = "{{ route('documents.master.print', ':name') }}";                    
                if( file != '' ) {
                    uri = uri.replace(':name', file);
                    win = window.open(uri, '_blank');
                    win.focus();
                } else {
                    setSuccessNotification('error', 'Oops!', 'Documento PDF no encontrado!');
                }
            });
            
            // GENERA EL MODAL PARA EL HISTORIAL DE CAMBIOS
            $('body').on('click', '#btn-modal-history', function (e) {
                e.preventDefault();

                var route = "{{ route('documents.master.history', ':id') }}";
                route = route.replace(':id', $did);
                
                $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) { 
                        console.dir(data);
                        var output = '';
                        $.each(data, function(i, value) {
                            output += '<tr><td>'+(i+1)+'</td><td>'+value.date+'</td><td>'+value.version+'</td><td>'+value.text+'</td></tr>';
                        });
                        $("#histories-table tbody").html(output);
                        $("#modal-histories-open")[0].click();     
                    }
                });
                
            });   // #btn-modal-history 
            
        }); // document

        function renderTable() {
            let lang = {!! $modalLanguage !!};
            $route = $route.replace(':id', $did);

            if ( $.fn.DataTable.isDataTable('#example') ) {
                $('#example').DataTable().destroy();
            }

            $('#example tbody').empty();

            $myTable = $('#example').DataTable({
                    processing: true,
                    serverSide: true,
                    retrieve: true,
                    ajax: $route,
                    columns: [
                        {
                            class: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                        },
                        { data: 'date' },
                        { data: 'name' },
                        { data: 'type' },
                        { data: 'page' },
                        { data: 'section' },
                    ],
                    order: [[1, 'desc']],
                    paging: false,
                    info: false,
                    filter: false,
                    initComplete: function () {
                        var $this = this.api();
                        $this.on('draw', function () {
                            detailRows.forEach(function (id, i) {
                                $('#' + id + ' td.dt-control').trigger('click');
                            });
                        });
                        
                        // Array to track the ids of the details displayed rows
                        var detailRows = [];
                        
                        $('#example tbody').on('click', 'tr td.dt-control', function () {
                            var tr = $(this).closest('tr');
                            var row =  $this.row(tr);
                            var idx = detailRows.indexOf(tr.attr('id'));
                        
                            if (row.child.isShown()) {
                                tr.removeClass('details');
                                row.child.hide();
                        
                                // Remove from the 'open' array
                                detailRows.splice(idx, 1);
                            } else {
                                tr.addClass('details');
                                //row.child(format(row.data())).show();
                                var d = row.data();
                                row.child(d.content).show();
                                // Add to the 'open' array
                                if (idx === -1) {
                                    detailRows.push(tr.attr('id'));
                                }
                            }
                        });                        

                    }, // init
                    language: lang
                }); // datatable
        } // renderTable

        function openDocument() {
            $.ajax({
                url: '/documentos/master/publicado/abrir/'+$did,
                type: 'GET',
                dataType: 'json',
                //async: false,
                success: function(data) {
                    //console.dir(data);
                    if( data.success )  {
                        $session = data.session;
                        // Generar alertas
                        // TODO:  Aquí toma las alertas
                    }              

                } // success
            }); // ajax              
        } // openDocument

        function closeDocument() {
            $.ajax({
                url: '/documentos/master/publicado/cerrar/'+$did+'/'+$session,
                type: 'GET',
                dataType: 'json',
                //async: false,
                success: function(data) {
                    console.dir(data);                

                } // success
            }); // ajax              
        } // openDocument

    </script>
@endpush
</x-icewall> 