$(function () {

	

    //alert($('#datetimepicker-fi').datetimepicker( 'date'));
    if ($('#datetimepicker-fi').datetimepicker( 'date') == null)
    	$('#datetimepicker-fi').datetimepicker( 'date', moment( new Date() ) );
    

                        
                            //alert('asdadas');
                            $('#datetimepicker-fi-day').datetimepicker({
                                inline: true,
                                sideBySide: true,
                                format: 'L',
								locale: 'es',
								autoclose: false,
                            });
                            

    //$('#datetimepicker-hi').datetimepicker( 'date', moment().set('hour',8).set('minutes',30) );
    //$('#datetimepicker-hf').datetimepicker( 'date', moment().set('hour',21).set('minutes',30) );
});