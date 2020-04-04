$(function () {

	
	//alert('asdadas');
	$('#datetimepicker-fi-day').datetimepicker({
		inline: true,
		sideBySide: true,
		format: 'L',
		locale: 'es',
		//autoclose: false,
		keepOpen: true,
	
	});

    
	if (!$('#sgr_filters_sgr_eventos_f_inicio').val()){
		$('#datetimepicker-fi-day').datetimepicker( 'date', moment(new Date()).format('DD/MM/Y') );
		$('#sgr_filters_sgr_eventos_f_inicio').val( $('#datetimepicker-fi-day').datetimepicker('date').format('DD/MM/Y') );
		
	}
	else{
		
		$('#datetimepicker-fi-day').datetimepicker( 'date', $('#sgr_filters_sgr_eventos_f_inicio').val() );
    	$('#sgr_filters_sgr_eventos_f_inicio').val( $('#datetimepicker-fi-day').datetimepicker('date').format('DD/MM/Y') );
	}
    
    $("#datetimepicker-fi-day").on("change.datetimepicker", function (e) {
    	
    	e.preventDefault();

		$('#sgr_filters_sgr_eventos_f_inicio').val($('#datetimepicker-fi-day').datetimepicker( 'date').format('DD/MM/Y'));
		
		
		$('form[name="sgr_filters_sgr_eventos"]').submit();
	});                     
                            

});