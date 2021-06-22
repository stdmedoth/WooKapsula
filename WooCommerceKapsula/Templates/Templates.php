<?php

namespace WooKapsula;
use WooKapsula\Cliente_List_Table;
use WooKapsula\WCK_Order;

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
          if(isset($_POST['config_form'])){
            if(isset($_POST['wookapsula_envia_faturado'])){
              $wookapsula_envia_faturado = 1;
            }else{
              $wookapsula_envia_faturado = 0;
            }
            $option = get_option('wookapsula_envia_faturado');
            if($option == NULL){
              add_option('wookapsula_envia_faturado', $wookapsula_envia_faturado);
            }else{
              update_option('wookapsula_envia_faturado', $wookapsula_envia_faturado);
            }
          }



        ?>
        <form class="form-control" action='?page=wookapsula' method='post'>
          <div class="form-group">
            <textarea class="form-control" style="width: 100%; " name="wookapsula_token"><?=get_option('wookapsula_token')?></textarea>
          </div>
          <button type="submit" class="button button-primary">Atualizar token</button>

        </form>

        <form class="form-control" action='?page=wookapsula' method='post'>
          <h1 class='wp-heading-inline'>Pedidos</h1>
          <?php
            $orders_table = new Order_List_Table();
            $orders_table->prepare_items();
            $orders_table->display();
          ?>
        </form>

        <form class="form-control" action='?page=wookapsula' method='post'>
          <h1 class='wp-heading-inline'>Clientes</h1>
          <?php
            $clientes_table = new Cliente_List_Table();
            $clientes_table->prepare_items();
            $clientes_table->display();
          ?>
        </form>

        <form class="form-control" action='?page=wookapsula' method='post'>
          <h1 class='wp-heading-inline'>Produtos</h1>
          <button type="button" id="puxa_prod_kapsula" class="button button-rounded">Puxar produtos da Kapsula</button>
          <?php
            $clientes_table = new Produto_List_Table();
            $clientes_table->prepare_items();
            $clientes_table->display();
          ?>
        </form>

        <form class="form-control" action='?page=wookapsula' method='post'>
          <div class="row">
            <div class="col-lg">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Configurações Kapsula</h5>
                </div>
                <div class="card-body">
                  Enviar pedidos como faturados para KapSula?
                  <input
                    type="checkbox"
                    id="wookapsula_envia_faturado"
                    name="wookapsula_envia_faturado"
                    value="1"
                    <?php if(get_option('wookapsula_envia_faturado')==1) echo 'checked'; ?>>
                  <input type="hidden"  name="config_form" value="sended">
                </div>
                <div class="card-footer">
                    <button type="submit" class="button button-primary">Atualizar Configurações</button>
                </div>
              </div>
            </div>
          </form>
          <div class="col-lg">
            <div class="card" style="">
              <div class="card-header">
                <h5 class="card-title">Kapsula Logger</h5>
              </div>
              <div class="card-body">
                <p class="card-text">Analise tudo o que ocorre no backend do Plugin WooKapusla</p>
                <textarea class="form-control"><?php $logger = new Logger();echo $logger->get_log(); ?></textarea>
              </div>
            </div>
          </div>
        </div>

    </div>

    <?php
  }

}
