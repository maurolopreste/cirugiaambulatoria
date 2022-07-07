$(function(){

	$(document).on('submit', '#agendarCitaForm', function(e) {
		e.preventDefault();
		var form = $('#agendarCitaForm');
		var url = form.attr('action');
		var btn = form.find('button');
		btn.html('<i class="fas fa-spinner fa-spin"></i>').attr('disabled');
		$.post(url, form.serialize(), function(res){
			form.hide();
			$('#agenda_alert').show();
			showNotification('Se agendó una nueva cita');
		});
	});

});