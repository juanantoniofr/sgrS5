$(function () {
    
	$('#datetimepicker-fi-day').datetimepicker({
		inline: true,
		sideBySide: true,
		format: 'L',
		locale: 'es',
		//autoclose: false,
		keepOpen: true,
	
	});
    //********************************
    //******* view Calendarios *******

    //Linked Pickers view Calendarios:
    $("#datetimepicker-fi").on("change.datetimepicker", function (e) {
        	
        var $f_fin = $('#datetimepicker-ff').datetimepicker('date');
        if ($f_fin != null)
            if($f_fin.isBefore(e.date))
                $('#datetimepicker-ff').datetimepicker('date', e.date);
    });

    $("#datetimepicker-ff").on("change.datetimepicker", function (e) {
            
            //$('#datetimepicker-fi-newSgrEvento').datetimepicker('maxDate', e.date);
            var $f_inicio = $('#datetimepicker-fi').datetimepicker('date');
            if ( $f_inicio != null)
                if( $f_inicio.isAfter(e.date) )
            	   $('#datetimepicker-fi').datetimepicker('date', e.date);
    });
    
    //console.log( $('#sgr_filters_sgr_eventos_f_inicio').val() );
    console.log('view week');
        if ( !$('#sgr_filters_sgr_eventos_f_inicio').val() )
    {
        $('#datetimepicker-fi-day').datetimepicker( 'date', moment().locale('es').startOf('week').format('D/M/YYYY') );
        $('#sgr_filters_sgr_eventos_f_inicio').val( moment().locale('es').startOf('week').format('D/M/YYYY') );
    	//$('#datetimepicker-ff').datetimepicker( 'date', moment().locale('es').endOf('week').format('D/M/YYYY') );
    }

    //$('#datetimepicker-hi').datetimepicker( 'date', moment().set('hour',8).set('minutes',30) );
    //$('#datetimepicker-hf').datetimepicker( 'date', moment().set('hour',21).set('minutes',30) );
});