var WooKapsula = null;

var jq = jQuery.noConflict();

(function( $ ) {
	WooKapsula = {
		init: ()=>{
			console.log('Iniciando WooKapsula...');
			WooKapsula.handlerFunctions();
		},
		handlerFunctions: ()=>{
			
			jq(document).on("click", '#button_send_to_kapsula', (e)=>{
				var loading = new Loading();
			
				jq.ajax({
					url: '/wp-json/kapsula/v1/send/pedido',
					data: {
						id: jq('#button_send_to_kapsula').data('order') 
					},
					success: retorno=>{
						loading.out();
						if(retorno.code == 200){
							jq('#button_send_to_kapsula').notify(retorno.message, "success");	
						}else{
							jq('#button_send_to_kapsula').notify(retorno[0].message, "error");
						}
						
					},
					error: (xhr, textStatus, error)=>{
						loading.out();
						jq('#button_send_to_kapsula').notify(textStatus + ' ' +error, "error");
					}

				});
			});
		}
	}

	WooKapsula.init();
	
}) (jQuery);