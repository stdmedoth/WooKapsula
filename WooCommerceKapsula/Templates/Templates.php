<?php

namespace WooKapsula;

class Templates {

  public function wookapsula_page_display(){
    ?>
    <div class='wrap'>
      <h1 class='wp-heading-inline'>Kapsula</h1>
      <hr class='wp-head-end'>
        <?php
          if(isset($_POST['wookapsula_token'])){
            $wookapsula_token = $_POST['wookapsula_token'];
            $option = get_option('wookapsula_token');
            if(!$option){
              add_option('wookapsula_token', $wookapsula_token);
            }else{
              update_option('wookapsula_token', $wookapsula_token);
            }
          }

        ?>
        <form class="form-control" action='?page=wookapsula' method='post'>
          <div class="form-group">
            <textarea class="form-control" name="wookapsula_token"><?=get_option('wookapsula_token')?></textarea>    
          </div>
          <button type="submit" class="btn btn-primary">Atualizar token</button>
        </form>
    </div>

    <?php  
  }

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
