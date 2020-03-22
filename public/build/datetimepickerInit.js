$(function () {
	
	//************************************************************
    //******* Init view Calendarios and new/edit sgrEvento *******
	$('#datetimepicker-fi, #datetimepicker-fi-newSgrEvento').datetimepicker({
		format: 'L',
		locale: 'es',
		autoclose: true,
	});

	$('#datetimepicker-ff, #datetimepicker-ff-newSgrEvento').datetimepicker({
		format: 'L',
		locale: 'es',
		autoclose: true,
	});

	$('#datetimepicker-hi, #datetimepicker-hf').datetimepicker({
		format: 'LT',
		locale: 'es',
		disabledTimeIntervals: [ [moment().hour(-1), moment().hour(8)], [moment().hour(21), moment().hour(23)] ],
		//enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21 ],
        stepping: 15,
        //defaultDate: false,
        //useCurrent:false,
	});

	//***************************************
    //******* view New/Edit sgrEvento *******
	
	//Linked Pickers f_fin/f_inicio new and edit sgrEvento: 
	$("#datetimepicker-fi-newSgrEvento").on("change.datetimepicker", function (e) {
    	
    	var $f_fin = $('#datetimepicker-ff-newSgrEvento').datetimepicker('date');
        if ($f_fin == null || $f_fin.isBefore(e.date))
        	$('#datetimepicker-ff-newSgrEvento').datetimepicker('date', e.date);
    
    });

    $("#datetimepicker-ff-newSgrEvento").on("change.datetimepicker", function (e) {
        
        var $f_inicio = $('#datetimepicker-fi-newSgrEvento').datetimepicker('date');
        if ( $f_inicio == null || $f_inicio.isAfter(e.date) )
        	$('#datetimepicker-fi-newSgrEvento').datetimepicker('date', e.date);
    
    });

    //Linked Pickers h_fin/fhinicio new and edit sgrEvento: 
	$("#datetimepicker-hi").on("change.datetimepicker", function (e) {
    	
    	var $h_fin = $('#datetimepicker-hf').datetimepicker('date');
        if ($h_fin == null || $h_fin.isBefore(e.date))
        	$('#datetimepicker-hf').datetimepicker('date', e.date);
       	//$(this).datetimepicker('hide');
    
    });

    $("#datetimepicker-hf").on("change.datetimepicker", function (e) {
        
        var $h_inicio = $('#datetimepicker-hi').datetimepicker('date');
        if ( $h_inicio == null || $h_inicio.isAfter(e.date) )
        	$('#datetimepicker-hi').datetimepicker('date', e.date);
    
    });

    //********************************
    //******* view Calendarios *******

    //Linked Pickers view Calendarios: 
	$("#datetimepicker-fi").on("change.datetimepicker", function (e) {
    	
    	//console.log($('#datetimepicker-ff-newSgrEvento').datetimepicker('date'));
        //$('#datetimepicker-ff-newSgrEvento').datetimepicker('minDate', e.date);
        var $f_fin = $('#datetimepicker-ff').datetimepicker('date');
        if ($f_fin == null || $f_fin.isBefore(e.date))
        	$('#datetimepicker-ff').datetimepicker('date', e.date);
    
    });

    $("#datetimepicker-ff").on("change.datetimepicker", function (e) {
        
        //$('#datetimepicker-fi-newSgrEvento').datetimepicker('maxDate', e.date);
        var $f_inicio = $('#datetimepicker-fi').datetimepicker('date');
        if ( $f_inicio == null || $f_inicio.isAfter(e.date) )
        	$('#datetimepicker-fi').datetimepicker('date', e.date);
    
    });

    //Default Value for filters sgrEventos in view Calendarios
	$current_month = moment().get('month');
	$current_year = moment().get('year');

	if ($current_month >= 8 ){
		//current_month >= 8 (September)
		$('#datetimepicker-fi').datetimepicker( 'defaultDate', moment().set('year',$current_year).set('month',8).set('date',1).format('D/M/YYYY') );
		$('#datetimepicker-ff').datetimepicker( 'defaultDate', moment().set('year',$current_year + 1).set('month',7).set('date',31) );
	}
	else{

		$('#datetimepicker-fi').datetimepicker( 'defaultDate', moment().set('year',$current_year-1).set('month',8).set('date',1) );
		$('#datetimepicker-ff').datetimepicker( 'defaultDate', moment().set('year',$current_year).set('month',7).set('date',31) );

	}
	
});