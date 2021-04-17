<?php

namespace Kapsula;

Class Cliente extends Element{

    public function __construct($id = null){
        parent::__construct('clientes');    
        $this->data_obj = 'cliente';
        if($id){
            $this->id = $id;
            $this->get($this->id);
        }
        
    }
    
    public $id;
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