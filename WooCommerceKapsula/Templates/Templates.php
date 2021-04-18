<?php

namespace WooKapsula;

class Templates {

  public function popup_modal($id='kapsula_popup_modal', $title='Popup', $message=''){
    ?>
    <div id="<?=$id?>" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?= $title ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p><?= $message ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" >OK</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>  
    <?php
  }
}
