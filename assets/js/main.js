var datepicker_options;

$(function(){

	$(document).on('drop dragover', function (e) {
		e.preventDefault();
	});

	$('select[data-select]').each(function(){
		var option_selected = $(this).data('select');
		$(this).find('option[value="'+option_selected+'"]').attr('selected', 'selected');
	});

	$('.custom-file .delete').click(function(){
		$(this).hide();
		var custom_file = $(this).parent();
		custom_file.find('.preview-image').removeAttr('src');
		custom_file.find('.custom-file-input').val('');
	});

	$('form').submit(function(e){
		var button = $(this).find('button[type="submit"]')
		var texto = button.text();
		button.html('<i class="fas fa-circle-notch fa-spin"></i> '+texto).attr('disabled', 'disabled');
	});

	$.fn.datepicker.dates.es = {
		days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
		daysShort:["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
		daysMin:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
		months:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		monthsShort:["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		today: "Hoy",
		monthsTitle: "Meses",
		clear: "Borrar",
		weekStart: 1,
		format: "dd/mm/yyyy"
	};

	datepicker_options = {
		language: "es",
		startDate: '+0d',
		autoclose: true,
		keyboardNavigation: false,
		format: 'yyyy-mm-dd'
	};
	$('.datepicker').each(function(){
		datepicker_options.datesDisabled = $(this).data('disabled');
		datepicker_options.daysOfWeekDisabled = $(this).data('daysofweekdisabled') || "06";
		$(this).datepicker(datepicker_options);
	});

	$('.select2').select2();

	$('#editModal, #addModal').on('show.bs.modal', function (e) {
		var button = $(e.relatedTarget);
		var href = button.attr('href');
		var modal = $(this);
		if(href) {
			modal.find('.modal-content').load(href, function(){
				modal.find('.select2').select2();
				modal.find('.datepicker').datepicker(datepicker_options);
			});
		}
	});

	update_selects();

});

function update_selects() {
	$('[data-selected]').each(function(){
		var value = $(this).data('selected');
		if($(this).hasClass('select2')) {
			$(this).val(value).trigger('change');
		}
		else {
			$(this).find('option[value="'+ value +'"]').attr('selected', 'selected');
		}
	});
}

//JavaScript confirm() Replacement
window.confirm = function(t, b){
	var modal = $('#deleteModal');
	var action = $(b).attr('href');
	modal.find('.h5').text(t);
	modal.modal('show');
	modal.find('.btn-confirm').click(function(){
		$('#deleteModal').modal('hide');
		
		//loader
		var texto_boton = $(b).text();
		$(b).html('<i class="fas fa-circle-notch fa-spin"></i> '+texto_boton).attr('disabled', 'disabled');
		
		$.post(action, {}, function(r){
			if(r=='ok') {
				var row = $(b).parents('tr');
				row.addClass('bg-danger').fadeOut('slow');
			}
			else {
				showNotification('Ha ocurrido un error. Vuelve a intentar', true);
			}
			$('.modal').modal('hide');
		});
	});
	return false;
}

function readURL(input) {
	if(input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$(input).siblings('.delete').show();
			$(input).siblings('.preview-image').attr('src', e.target.result);
		};

		reader.readAsDataURL(input.files[0]);
	}
	else {
		$(input).siblings('.delete').hide();
		$(input).siblings('.preview-image').removeAttr('src');
	}
}

function showNotification(str, error=false) {
	$('.toast .toast-body').text(str);
	if(error) {
		$('.toast').removeClass('alert-success').addClass('alert-danger');
	}
	else {
		$('.toast').removeClass('alert-danger').addClass('alert-success');
	}
	$('.toast').toast('show');
}