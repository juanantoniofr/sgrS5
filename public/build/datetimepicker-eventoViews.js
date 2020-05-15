$(function () {
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

    //***************************************
    //******* view New/Edit sgrEvento *******
    //Linked Pickers h_fin/fhinicio new and edit sgrEvento: 
    $("#datetimepicker-hi").on("change.datetimepicker", function (e) {
        
        var $h_fin = $('#datetimepicker-hf').datetimepicker('date');
        if ($h_fin == null || $h_fin.isBefore(e.date))
            $('#datetimepicker-hf').datetimepicker('date', e.date);
    });

    $("#datetimepicker-hf").on("change.datetimepicker", function (e) {
        
        var $h_inicio = $('#datetimepicker-hi').datetimepicker('date');
        if ( $h_inicio == null || $h_inicio.isAfter(e.date) )
            $('#datetimepicker-hi').datetimepicker('date', e.date);
    
    });
});