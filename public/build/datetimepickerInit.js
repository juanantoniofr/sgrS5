$(function () {
	$('#datetimepicker-fi, #datetimepicker-fi-newSgrEvento').datetimepicker({
		format: 'L',
		locale: 'es',
	});

	$('#datetimepicker-ff, #datetimepicker-ff-newSgrEvento').datetimepicker({
		format: 'L',
		locale: 'es',
	});

	$('#datetimepicker-hi, #datetimepicker-hf').datetimepicker({
		format: 'LT',
		locale: 'es',
		//disabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21 ],
        stepping: 15,
        defaultDate: false,
	});

	
	//alert($('#datetimepicker-hi').datetimepicker('date'));
	//if($.trim($("selector").html())=='')
	//$time_fi = $('#datetimepicker-hi').datetimepicker('date');
	//$('#datetimepicker-hf').datetimepicker('date', $time_fi.add(1,'hours'));

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