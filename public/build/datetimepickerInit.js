$(function () {
	$('#datetimepicker-fi').datetimepicker({
		format: 'L',
		locale: 'es',
	});

	$('#datetimepicker-ff').datetimepicker({
		format: 'L',
		locale: 'es'
	});


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