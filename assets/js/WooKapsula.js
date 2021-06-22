var WooKapsula = null;

var jq = jQuery.noConflict();

(function( $ ) {
	WooKapsula = {
		init: ()=>{
			console.log('Iniciando WooKapsula...');
			WooKapsula.handlerFunctions();
		},
		handlerFunctions: ()=>{
			jq(document).on("click", '#button_update_kapsula_pedido_status', (e)=>{
				var loading = new Loading();
				jq.ajax({
						url: '/wp-json/kapsula/v1/update_pedido_status',
						data: {
							id: jq('#button_update_kapsula_pedido_status').data('order'),
							status: jq('#select_kapsula_pedido_status option:selected').val()
						},
						success: retorno=>{
							loading.out();
							if(retorno.code == 200){
								jq('#button_update_kapsula_pedido_status').notify(retorno.message, "success");
							}else{
								jq('#button_update_kapsula_pedido_status').notify(retorno[0].message, "error");
							}

						},
						error: (xhr, textStatus, error)=>{
							loading.out();
							jq('#button_update_kapsula_pedido_status').notify(textStatus + ' ' +error, "error");
						}

					});
			});
			jq(document).on("click", '#puxa_prod_kapsula', (e)=>{
				var loading = new Loading();

				jq.ajax({
					url: '/wp-json/kapsula/v1/integra/produtos',
					data: {

					},
					success: retorno=>{
						loading.out();
						if(retorno.code == 200){
							jq('#puxa_prod_kapsula').notify(retorno.message, "success");
						}else{
							jq('#puxa_prod_kapsula').notify(retorno[0].message, "error");
						}

					},
					error: (xhr, textStatus, error)=>{
						loading.out();
						jq('#puxa_prod_kapsula').notify(textStatus + ' ' +error, "error");
					}
				});
			});

			jq(document).on("click", '#puxa_cli_kapsula', (e)=>{
				var loading = new Loading();

				jq.ajax({
					url: '/wp-json/kapsula/v1/integra/clientes',
					data: {

					},
					success: retorno=>{
						loading.out();
						if(retorno.code == 200){
							jq('#puxa_cli_kapsula').notify(retorno.message, "success");
						}else{
							jq('#puxa_cli_kapsula').notify(retorno[0].message, "error");
						}

					},
					error: (xhr, textStatus, error)=>{
						loading.out();
						jq('#puxa_cli_kapsula').notify(textStatus + ' ' +error, "error");
					}
				});
			});

			jq(document).on("click", '#limpar_integracao', (e)=>{
				var loading = new Loading();

				jq.ajax({
					url: '/wp-json/kapsula/v1/integra/limpar',
					data: {

					},
					success: retorno=>{
						loading.out();
						if(retorno.code == 200){
							jq('#limpar_integracao').notify(retorno.message, "success");
						}else{
							jq('#limpar_integracao').notify(retorno[0].message, "error");
						}

					},
					error: (xhr, textStatus, error)=>{
						loading.out();
						jq('#limpar_integracao').notify(textStatus + ' ' +error, "error");
					}
				});
			});

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
