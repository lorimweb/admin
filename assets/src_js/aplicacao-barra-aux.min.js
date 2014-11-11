$(function() {
	var barra = function() {
		var elemento = $('#gg2-barra-aux'),
			n_selecionados, n_itens, todos, itens_selecionados;
		function mostra() {
			elemento.removeClass('hide').show();
			$('#gg2-barra-texto').html('Você tem ' + n_selecionados + ' Iten(s) selecionado(s)');
			$('#gg2-seleciona_todos').prop("checked", todos);
		}
		function atualiza() {
			itens_selecionados = $(".gg2-listagem tbody input:checkbox:checked");
			n_selecionados = itens_selecionados.length;
			n_itens = $('.gg2-listagem tbody input:checkbox').length;
			todos = (n_selecionados === n_itens);
		}
		function selecionados() {
			var valores = [];
			for (var i=0;i<n_selecionados;i++) {
				valores.push(itens_selecionados[i].val());
			}
			return valores;
		}
		var inicializa = function() {
			var $botoes = $('#gg2-barra-botoes');
			//remover muitos
			if (typeof(ACAO_REMOVER) == 'string') {
				$('<button />')
					.attr('class', 'btn btn-default remover')
					.attr('type', 'button')
					.html('<i class="glyphicon glyphicon-trash"></i> Remover')
					.click(function() {
						var $url = ACAO_REMOVER;
						var $selecionados = selecionados();
						if (confirm('Deseja realmente remover os registros selecionados?')) {
							$.post($url, {'selecionados': $selecionados}, function(data) {
								window.location.reload();
							});
						}
					}).appendTo($botoes);
			}
		}();
		return {
			selecionados: function() {
				selecionados();
			},
			verifica: function() {
				atualiza();
				if (n_selecionados > 0)
					mostra();
				else
					elemento.hide();
			}
		};
	}();
	$('#gg2-seleciona_todos').click(function(event) {
		var selecionado = $(this).prop("checked"),
			elemento;
		$(".gg2-listagem tbody input:checkbox").each(function(i, o) {
			elemento = $(o);
			elemento.prop('checked', selecionado);
		});
		barra.verifica();
	});
	$('.gg2-listagem tbody input:checkbox').on('click', function(event) {
		barra.verifica();
	});
});