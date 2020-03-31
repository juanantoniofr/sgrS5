$(function () {
    //********************************
    //******* view Calendarios *******

    //Linked Pickers view Calendarios:
    if ($('#datetimepicker-ff').length != 0)
    	$("#datetimepicker-fi").on("change.datetimepicker", function (e) {
        	
        	var $f_fin = $('#datetimepicker-ff').datetimepicker('date');
            if ($f_fin.length == 0 || $f_fin.isBefore(e.date))
            	$('#datetimepicker-ff').datetimepicker('date', e.date);
        
        });

    if ($('#datetimepicker-ff').length != 0)
        $("#datetimepicker-ff").on("change.datetimepicker", function (e) {
            
            //$('#datetimepicker-fi-newSgrEvento').datetimepicker('maxDate', e.date);
            var $f_inicio = $('#datetimepicker-fi').datetimepicker('date');
            if ( $f_inicio == 0 || $f_inicio.isAfter(e.date) )
            	$('#datetimepicker-fi').datetimepicker('date', e.date);
        
        });

    //Default Value for filters sgrEventos in view Calendarios
	$current_month = moment().get('month');
	$current_year = moment().get('year');

	if ($current_month >= 8 ){
		//current_month >= 8 (September)
        //alert($current_month);
		$('#datetimepicker-fi').datetimepicker( 'date', moment().set('year',$current_year).set('month',8).set('date',1).format('D/M/YYYY') );
		$('#datetimepicker-ff').datetimepicker( 'date', moment().set('year',$current_year + 1).set('month',7).set('date',31) );
	}
	else{
        //alert($current_month);
        //alert($current_year);
        //alert(moment().set('year',$current_year).set('month',7).set('date',31));
		$('#datetimepicker-fi').datetimepicker( 'date', moment().set('year',$current_year-1).set('month',8).set('date',1) );
		$('#datetimepicker-ff').datetimepicker( 'date', moment().set('year',$current_year).set('month',7).set('date',31) );
    }

    $('#datetimepicker-hi').datetimepicker( 'date', moment().set('hour',8).set('minutes',30) );
    $('#datetimepicker-hf').datetimepicker( 'date', moment().set('hour',21).set('minutes',30) );
});