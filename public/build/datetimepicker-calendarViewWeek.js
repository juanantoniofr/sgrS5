$(function () {
    
	//********************************
    //******* view Calendarios *******

    //console.log('view week');
    if ( !$('input[name="sgr_filters_sgr_eventos[f_inicio]"').val() )
    {
        $('#datetimepicker-fi').datetimepicker( 'date', moment().locale('es').startOf('week').format('DD/MM/Y') );
    }

    /*if ( !$('input[name="sgr_filters_sgr_eventos[f_fin]"').val() )
    {
        $('#datetimepicker-ff').datetimepicker( 'date', moment().locale('es').endOf('week').format('DD/MM/Y') );
    }*/
   
    //if (!$('#sgr_filters_sgr_eventos_f_inicio').val())
    //{
    //    $('#datetimepicker-fi').datetimepicker( 'date', moment(new Date()).format('DD/MM/Y') );
    //    $('#sgr_filters_sgr_eventos_f_inicio').val( $('#datetimepicker-fi').datetimepicker('date').format('DD/MM/Y') );
    //}


    $("#datetimepicker-fi").on("change.datetimepicker", function (e) {
        console.log(e.oldDate != null && e.date != e.oldDate);
        if (e.oldDate != null && e.date != e.oldDate){
            //$('form[name="sgr_filters_sgr_eventos"]').submit();
        }
    });

    $('#thisWeek').on('click',function(){

        //set f_inicio
        $('#datetimepicker-fi').datetimepicker( 'date', moment().locale('es').startOf('week').format('DD/MM/YYYY') );
        $('#sgr_filters_sgr_eventos_f_inicio').val( moment().locale('es').startOf('week').format('DD/MM/YYYY') );
    });

    $('#nextWeek').on('click',function(){

        var date = $('#datetimepicker-fi').datetimepicker( 'date' ).locale('es').add( 1, 'weeks' );

        //set f_inicio
        $('#datetimepicker-fi').datetimepicker( 'date', date.locale('es').startOf('week').format('DD/MM/YYYY') );
        $('#sgr_filters_sgr_eventos_f_inicio').val( date.locale('es').startOf('week').format('DD/MM/YYYY') );
    });

    $('#prevWeek').on('click',function(){

        var date = $('#datetimepicker-fi').datetimepicker( 'date' ).locale('es').subtract( 1, 'weeks' );

        //set f_inicio
        $('#datetimepicker-fi').datetimepicker( 'date', date.locale('es').startOf('week').format('DD/MM/YYYY') );
        $('#sgr_filters_sgr_eventos_f_inicio').val( date.locale('es').startOf('week').format('DD/MM/YYYY') );
    });
});