<div class="modal" id="modal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">
            
            <div class="modal-header">

                <h4 class="modal-title"><span id='titulo-modal'></span></h4>

                <button type="button" class="close" onclick="modalClose()" >
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                
            </div>

            

                <input type="hidden" id="id" name="id" value="">
                
                <div class="modal-body">

                        <div class="row">
                            <div class="col">
                            <label  class="control-label">Nome</label>
                                <input type="text" name="name" id="name" value="" readonly class="form-control">
                            </div>
                            <div class="col">
                            <label  class="control-label">Nickname</label>
                                <input type="text" name="nickname" id="nickname" value="" class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>

                        <div class="row">
                            <div class="col">
                                <label  class="col-md-2 control-label" style="width:40%;">Nível</label>
                                <input type="text" name="level" id="level" value="" class="form-control" autofocus>
                            </div>
                            <div class="col">
                                <label  class="col-md-3 control-label">Tipo</label>
                                <input type="text" name="type" id="type" value="" readonly class="form-control">
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>

                        <div class="row">
                            <div class="col">
                                <label  class="col-md-3 control-label">Altura</label>
                                <input type="text" name="height" id="height" value="" readonly class="form-control">
                            </div>
                            <div class="col">
                                <label  class="col-md-3 control-label">Peso</label>
                                <input type="text" name="weight" id="weight" value="" readonly class="form-control">
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>

                        <div class="row">
                            <div class="col">
                                <label  class="col-md-4 control-label">Experiência Base</label>
                                <input type="text" name="base_experience" id="base_experience" value="" readonly class="form-control">
                            </div>
                            
                        </div>
                        <div class="row">&nbsp;</div>

                        <div class="row">
                            <div class="col">
                            <label  class="col-md-4 control-label">Detalhes / Notas</label>
                            <input type="text" name="detail_note" id="detail_note" value="" class="form-control" autofocus>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col">
                            <label class="col-md-4 control-label">Evolução</label>
                            <img src='' id="image_evolution" />
                                        <span id="name_evolution"></span>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>

                </div>

                <div class="modal-footer">

                        <h6 class="modal-title"><span class="text-info" id="message"></span></h6>

                        <button id="submit" name="submit" class="btn text-primary" onclick="atualizar()">
                            Atualizar
                        </button>

                </div>

            
        </div>
    </div>
</div>
