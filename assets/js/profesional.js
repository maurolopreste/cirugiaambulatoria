$(function(){

	$('#index-fechas-comprometidas').each(function(){
		var profesional_id = $(this).data('profesional-id');
		$.get(base_url+'profesional/index_fechas_comprometidas/'+profesional_id, function(res){
			if(res) {
				$('#index-fechas-comprometidas').html(res);
			}
		});
	});

	$('#index-dias-habilitados').each(function(){
		var profesional_id = $(this).data('profesional-id');
		$.get(base_url+'profesional/index_dias_habilitados/'+profesional_id, function(res){
			if(res) {
				$('#index-dias-habilitados').html(res);
			}
		});
	});

	$('.btn-add-horario').click(function(e){
		var horario = $('.form-check-days:eq(0)').clone();
		horario.find('input[name="dia_id"]').remove();
		horario.find('input').removeAttr('checked');
		$('#index-dias-habilitados').append(horario);
	});

	$(document).on('submit', '.formDiaHorario', function(e) {
		e.preventDefault();
		var form = $(e.target);
		var url = form.attr('action');
		$.post(url, form.serialize(), function(res){
			$('#index-dias-habilitados').html(res);
			showNotification('Se actualizaron los días habilitados');
		});
	});

	$('.days .badge').click(function(){
		var id = $(this).data('id');
		var modal = $('#editModal');
		var href = '/agenda/edit/'+id;
		modal.find('.modal-content').load(href);
		modal.modal('show');
	});

});