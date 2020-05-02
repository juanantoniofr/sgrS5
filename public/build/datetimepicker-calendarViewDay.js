$(function () {

	//console.log('day js');
	//console.log($('#sgr_filters_sgr_eventos_f_inicio').val());
	if (!$('#sgr_filters_sgr_eventos_f_inicio').val())
	{
		$('#datetimepicker-fi').datetimepicker( 'date', moment(new Date()).format('DD/MM/Y') );
		$('#sgr_filters_sgr_eventos_f_inicio').val( $('#datetimepicker-fi').datetimepicker('date').format('DD/MM/Y') );
	}
	/*else{
		console.log('else day js');
		console.log($('input[name="sgr_filters_sgr_eventos[f_inicio]"]'));
		//$('#datetimepicker-fi').datetimepicker( 'date', $('#sgr_filters_sgr_eventos_f_inicio').val() );
    	//$('#sgr_filters_sgr_eventos_f_inicio').val( $('#datetimepicker-fi').datetimepicker('date').format('DD/MM/Y') );
	}*/
    
    /*$("#datetimepicker-fi-day").on("change.datetimepicker", function (e) {
    	
    	e.preventDefault();

		$('#sgr_filters_sgr_eventos_f_inicio').val($('#datetimepicker-fi-day').datetimepicker( 'date').format('DD/MM/Y'));
		
		
		$('form[name="sgr_filters_sgr_eventos"]').submit();
	});*/                     
                            

});