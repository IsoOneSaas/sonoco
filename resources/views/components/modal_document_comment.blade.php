                    <div id="modal-back" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- BEGIN: Modal Header -->
                                <div class="modal-header">
                                    <h2 id="modal-back-title" class="font-medium text-base mr-auto">Editar Comentario para el Documento</h2>
                                </div>
                                <!-- END: Modal Header -->
                                <!-- BEGIN: Modal Body -->
                                <div class="modal-body intro-y box p-5 mt-5">
                                    
                                    <form id="disaproval-form" action="" method="POST">
                                        @csrf
                                        <div class="input-group mt-3">
                                            <div id="commentId" class="input-group-text flex"><i data-lucide="{{ trans('document/document.form.comment.icon') }}" class="w-5 h-5 mr-1"></i>{{ trans('document/document.form.comment.title') }}</div>
                                            <textarea  class="form-control" id="comment" aria-describedby="commentId" placeholder="{{ trans('document/document.form.comment.placeholder') }}" minlength="8" rows="10"  required>{{ old('comment') }}</textarea>                                           
                                            <div id="input-group-2" class="input-group-text"><a href="javascript:;" class="tooltip" title="{{ trans('document/document.form.comment.tooltip') }}" tabindex="-1"><i data-lucide="help-circle" class="w-4 h-4"></i></a> </div>
                                        </div>
                                    </form>                                                          

                                </div>
                                <!-- END: Modal Body -->
                                <!-- BEGIN: Modal Footer -->
                                <div class="modal-footer">
                                    <button id="btn-back-ko" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancelar</button>
                                    <button id="btn-back-ok" type="button" class="btn btn-primary w-20">Confirmar</button>
                                    <a id="modal-back-open" href="javascript:;" data-tw-toggle="modal" data-tw-target="#modal-back" class="">.</a>
                                </div>
                                <!-- END: Modal Footer -->
                            </div>
                        </div>
                    </div>