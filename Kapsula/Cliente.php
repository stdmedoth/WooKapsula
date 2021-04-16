<?php

namespace WooKapsula;

Class Cliente extends Element{

    public function __construct(){
        parent::__construct('cliente');    
        
    }
    
    public $cpf;
    public $nome;
    public $data_nascimento;
    public $email;
    public $telefone;
    public $sexo;
    public $cep;
    public $endereco;
    public $numero;
    public $bairro;
    public $cidade;
    public $estado;
    public $pais;
    public $complemento;
    public $referencia_externa;
}