# WooKapsula

O WooKapsula é um plugin de integração entre o WooCommerce e a plataforma Kapsula.
Tem como objetivo enviar automaticamente os clientes, produtos e pedidos do
WooCommerce para a Kapsula, onde os processos de emissão de NF-e e entrega de
mercadoria serão feitos separadamente.


## Fluxo do processo

![Fluxograma](https://i.imgur.com/Un7c57y.png)


## Dependencias do Projeto

* O projeto contém as dependencias a seguir para o desenvolvimento:

* WordPress (Testado em versões a partir de 5.6).

* Plugin WooCommerce (Testado em versões a partir de 5.2).

* Plugin Brazilian Market on WooCommerce (Testado em versões a partir de 3.7).

* Plugin Claudio Sanches - Correios for WooCommerce (Testado em versões a partir de

3.8).

* SDK Kapsula (Biblioteca criada pela Incipe desenvolvimentos para comunicação com a
Kapsula).

## Estrutura Projeto
O plugin foi desenvolvido com OOP, utilizando as actions, hooks e funções do Wordpress e
WooCommerce, para saber mais acesse Developer WooCommerce e WordPress
Developer.

O plugin utiliza dois namespaces para funcionar, sendo eles o WooKapsula e Kapsula:

* Kapsula: namespace contendo classes de referencia às rotas de GET e POST da API da
plataforma Kapsula.

* WooKapsula: Namespace base do plugin, responsável pela criação do frontend e
integração das classes do WooCommerce com o namespace Kapsula.

Para incluir esses dois namespaces, foi criado um autoloader no plugin ‘Autoloader.php, que
fará carregamento das classes no namespaces WooKapsula e Kapsula, contidos nos
diretórios /Kapsula e /WooCommerceKapsula. (O módulo Kapsula deve ser iniciado para
buscar os fontes).

### Classes em Kapsula

* Request: fazer requisições CURL na API, já utilizando o token de autenticação.

* Element: Classe pai para as rotas da api, responsável por chamar requisições e fazer
encode/decode dos resultados.

* Cliente: enviar e receber os clientes pela API Kapsula.

* Produto: enviar e receber os produtos pela API Kapsula.

* Pedido: enviar pedidos ou carrinhos e receber pedidos pela API Kapsula.

Para saber mais acessar documentação Kapsula.


### Classes em WooCommerceKapsula

* API: referente a api rest do Wordpress para o plugin WooKapsula, contem rotas para
comunicação com o Backend do plugin.

* Cliente_List_Table: classe para criação da tabela de listagem de clientes integrados com a
Kapsula.

* Order_List_Table: classe para criação da tabela de listagem de pedidos integrados com a
Kapsula.

* Produto_List_Table: classe para criação da tabela de listagem de produtos integrados
com a Kapsula.

* CustomField: classe para criação de campos personalizados no FrontEnd Wordpress.
Helpers: Classe helper, funções diversas

* Logger: Logger do Kapsula.
Templates: Classe para criação de views para o plugin (atualmente contém apenas a view
principal WooKapsula)

* WCK_Integration: Interface para integração das Classes WooCommerce e WooKapsula,
as classes WCK_* extendem essa interface para que as integrações possam ser
implementadas utilizando as classes base do WooCommerce.

* WCK_Customer: Classe WooKapsula integrada à classe WooCommerce WC_Customer.

* WCK_Order: Classe WooKapsula integrada à classe WooCommerce WC_Order

* WCK_Product: Classe WooKapsula integrada à classe WooCommerce WC_Product.
