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
		disabledTimeIntervals: [ [moment().hour(-1), moment().hour(8)], [moment().hour(22), moment().hour(23)] ],
		//enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21 ],
        stepping: 15,
        autoclose:true,
        //defaultDate: false,
        //useCurrent:false,
	});

});