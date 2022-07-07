$(function(){

	$('#index-historiaclinica').each(function(){
		var paciente_id = $(this).data('paciente-id');
		$.get(base_url+'paciente/index_historiaclinica/'+paciente_id, function(res){
			if(res) {
				$('#index-historiaclinica').html(res);
			}
		});
	});

	// $('#index-evolutivo').each(function(){
	// 	var paciente_id = $(this).data('paciente-id');
	// 	$.get(base_url+'paciente/index_evolutivo/'+paciente_id, function(res){
	// 		if(res) {
	// 			$('#index-evolutivo').html(res);
	// 		}
	// 	});
	// });

	$('#index-citas').each(function(){
		var paciente_id = $(this).data('paciente-id');
		$.get(base_url+'paciente/index_citas/'+paciente_id, function(res){
			if(res) {
				$('#index-citas').html(res);
			}
		});
	});

	$(document).on('submit', '#addHistoriaClinicaForm', function(e) {
		e.preventDefault();
		var form = $('#addHistoriaClinicaForm');
		var formData = new FormData(document.getElementById("addHistoriaClinicaForm"));
		formData.append('userfile', $('.form-control-file').val());
		var url = form.attr('action');
		$.ajax({
			url: form.attr('action'),
			type: "post",
			dataType: "html",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(res){
			$('.modal.show').modal('hide');
			$('#index-historiaclinica').html(res);
			showNotification('Se agregó una nueva historia clínica');
		});
	});

	$(document).on('submit', '#addEvolutivoForm', function(e) {
		e.preventDefault();
		var form = $('#addEvolutivoForm');
		var formData = new FormData(document.getElementById("addEvolutivoForm"));
		formData.append('userfile', $('.form-control-file').val());
		var url = form.attr('action');
		$.ajax({
			url: form.attr('action'),
			type: "post",
			dataType: "html",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(res){
			$('.modal.show').modal('hide');
			$('#index-historiaclinica').html(res);
			showNotification('Se agregó un nuevo informe');
		});
	});

	$(document).on('submit', '#addCitaForm', function(e) {
		e.preventDefault();
		var form = $('#addCitaForm');
		var url = form.attr('action');
		$.post(url, form.serialize(), function(res){
			$('.modal.show').modal('hide');
			$('#index-citas').html(res);
			showNotification('Se agregó un nueva cita');
		});
	});

	$(document).on('submit', '#editHistoriaClinicaForm', function(e) {
		e.preventDefault();
		var form = $('#editHistoriaClinicaForm');
		var formData = new FormData(document.getElementById("editHistoriaClinicaForm"));
		formData.append('userfile', $('.form-control-file').val());
		$.ajax({
			url: form.attr('action'),
			type: "post",
			dataType: "html",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(res){
			$('.modal.show').modal('hide');
			$('#index-historiaclinica').html(res);
			showNotification('Los cambios se realizaron correctamente');
		});
	});

	$(document).on('submit', '#editEvolutivoForm', function(e) {
		e.preventDefault();
		var form = $('#editEvolutivoForm');
		var formData = new FormData(document.getElementById("editEvolutivoForm"));
		formData.append('userfile', $('.form-control-file').val());
		$.ajax({
			url: form.attr('action'),
			type: "post",
			dataType: "html",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(res){
			$('.modal.show').modal('hide');
			$('#index-historiaclinica').html(res);
			showNotification('Los cambios se realizaron correctamente');
		});
	});

	$(document).on('submit', '#editCitaForm', function(e) {
		e.preventDefault();
		var form = $('#editCitaForm');
		var url = form.attr('action');
		$.post(url, form.serialize(), function(res){
			$('.modal.show').modal('hide');
			$('#index-citas').html(res);
			showNotification('Los cambios se realizaron correctamente');
		});
	});

});