$(function() {
	$('.acao-remover').on('click', function(event) {
		var $this = $(this);
		if ( ! window.confirm($this.attr('title')))
			event.preventDefault();
	});
	$('#gg2-popup').modal('show');
});