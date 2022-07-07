$(function(){

	$('.days .badge').click(function(){
		var id = $(this).data('id');
		var modal = $('#editModal');
		var href = base_url+'agenda/edit/'+id;
		modal.find('.modal-content').load(href, function(){
			$('.datepicker').each(function(){
				datepicker_options.datesDisabled = $(this).data('disabled');
				$(this).datepicker(datepicker_options);
			});
		});
		modal.modal('show');
	});

});