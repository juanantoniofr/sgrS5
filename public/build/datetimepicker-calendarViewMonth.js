$(function () {
    
	//********************************
    //******* view Calendarios *******

    if ( !$('#sgr_filters_sgr_eventos_f_inicio').val() )
    {
        $('#datetimepicker-fi').datetimepicker( 'date', moment().locale('es').startOf('month').format('D/MM/YYYY') );
        $('#sgr_filters_sgr_eventos_f_inicio').val( moment().locale('es').startOf('month').format('D/MM/YYYY') );
    	
    }

    /*
    if ( !$('#sgr_filters_sgr_eventos_f_fin').val() )
    {
        $('#datetimepicker-ff').datetimepicker( 'date', moment().locale('es').endOf('month').format('D/MM/YYYY') );
        $('#sgr_filters_sgr_eventos_f_fin').val( moment().locale('es').endOf('month').format('D/MM/YYYY') );

    }
    */
});