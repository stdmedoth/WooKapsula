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
				jq.ajax({
					url: '/wp-json/kapsula/v1/pedido/add',
					data: {
						id: jq('#button_send_to_kapsula').data('order') 
					}
				})	
			});
		}
	}

	WooKapsula.init();
}) (jQuery);