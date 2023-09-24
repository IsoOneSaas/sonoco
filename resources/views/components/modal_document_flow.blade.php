                    <div id="modal-flow" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-flow-title" class="font-medium text-base mr-auto">Estado del flujo</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">

                                    <div class="overflow-x-auto">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Estado</th>
                                                <th>Responsables</th>
                                                <th>Cargo</th>
                                                <th>Fecha Límite</th>
                                                <th>Fecha Confirmado</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if( isset($flow['steps']) )
                                                @foreach( $flow['steps'] as $key => $item )                                                      
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>
                                                        @foreach($item as $row)
                                                            <p>{{ $row['name'][0] }}</p>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach($item as $row)
                                                            <p>{{ $row['job'][0] }}</p>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach($item as $row)
                                                            <p>{{ $row['deadline'][0] }}</p>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach($item as $row)
                                                            <p>{{ $row['updated'][0] }}</p>
                                                        @endforeach
                                                    </td>                                                                                                                                                                                                                                                                                                                                                           
                                                </tr>                                                            
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div  id="responsive-table" class="overflow-x-auto h-48 mt-4">
                                        <table class="table table-auto overflow-scroll w-full h-48">
                                            <thead><tr><th>Usuario</th><th>Cargo</th></tr></thead>
                                            <tbody>
                                                @if( isset($flow['users']) )
                                                @foreach( $flow['users'] as $item )
                                                <tr><td>{{ $item['name'] }}</td><td>{{ $item['jobs'] }}</td></tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-flow-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cerrar</button>
                                    <a id="modal-flow-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-flow" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>