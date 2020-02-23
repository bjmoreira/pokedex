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

                        <div class="form-group">
                            <label for="cnpj" class="col-md-3 control-label">Nome</label>
                            <div class="col-md-7">
                                <input type="text" name="name" id="name" value="" readonly class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cnpj" class="col-md-3 control-label">Tipo</label>
                            <div class="col-md-7">
                                <input type="text" name="type" id="type" value="" readonly class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cnpj" class="col-md-3 control-label">Altura</label>
                            <div class="col-md-7">
                                <input type="text" name="height" id="height" value="" readonly class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cnpj" class="col-md-3 control-label">Peso</label>
                            <div class="col-md-7">
                                <input type="text" name="weight" id="weight" value="" readonly class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cnpj" class="col-md-4 control-label">Experiência Base</label>
                            <div class="col-md-7">
                                <input type="text" name="base_experience" id="base_experience" value="" readonly class="form-control" autofocus>
                            </div>
                        </div>

                </div>

                <div class="modal-footer">


                        <button id="submit" name="submit" class="btn btn-default" onclick="incluiCapturado()">
                            Adicionar como capturado
                        </button>

                </div>

            
        </div>
    </div>
</div>
