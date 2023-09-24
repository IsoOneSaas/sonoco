                    <div id="modal-changes" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-changes-title" class="font-medium text-base mr-auto">Editar cambio para el historial del documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    <form id="change-form" method="post" action="{{ route('documents.control.change.store') }}">
                                        @csrf
                                        <input type="hidden" name="document_id" value={{ $document->document_id }} />
                                        <input type="hidden" name="version" value={{ $document->version }} />
                                        <div class="input-group mt-3">
                                            <div id="text" class="input-group-text flex"><i data-lucide="{{ trans('document/change.form.text.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/change.form.text.title') }}</div>
                                            <textarea name="text" class="form-control  w-full" aria-describedby="text" placeholder="{{ trans('document/change.form.text.placeholder') }}" rows="3" minlength="8" required>{{ $document->change ?? '' }}</textarea>
                                            <div id="input-group-5" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/change.form.text.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>                                        
                                    </form>
                                </div>
                                <div class="modal-body intro-y box p-5 mt-5">

                                    <table id="change-table" class="display dataTable" style="width:100%" aria-describedby="example_info">
                                        <thead>
                                            <tr>
                                                <th class="dt-control sorting_disabled" rowspan="1" colspan="1" style="width: 22.9688px;"></th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Fecha</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Nombre</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-changes-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cancelar</button>
                                    <button id="btn-changes-ok" type="button" class="btn btn-primary">Salvar</button>
                                    <a id="modal-changes-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-changes" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>                                    
                    </div>