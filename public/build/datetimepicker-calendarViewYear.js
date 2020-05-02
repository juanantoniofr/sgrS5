$(function () {
    //********************************
    //******* view Years Calendarios *******

    //Default Value for filters sgrEventos in view Calendarios
    $current_month = moment().get('month');
    $current_year = moment().get('year');
    

    //set default value
    if ( !$('#sgr_filters_sgr_eventos_f_inicio').val() ) 
    {
        if ($current_month >= 8 ){
            $('#datetimepicker-fi').datetimepicker( 'date', moment().locale('es').set('year',$current_year).set('month',8).set('date',1).format('D/MM/Y') );
        }
        else{
            $('#datetimepicker-fi').datetimepicker( 'date', moment().locale('es').set('year',$current_year-1).set('month',8).set('date',1).format('D/MM/Y') );
        }
    }

    if ( !$('#sgr_filters_sgr_eventos_f_fin').val() )
    {

        if ($current_month >= 8 ){
            $('#datetimepicker-ff').datetimepicker( 'date', moment().locale('es').set('year',$current_year + 1).set('month',7).set('date',31).format('D/MM/Y') );
        }
        else{
            $('#datetimepicker-ff').datetimepicker( 'date', moment().locale('es').set('year',$current_year).set('month',7).set('date',31).format('D/MM/Y') );
        }
    }

    
    $("#datetimepicker-ff").on("change.datetimepicker", function (e) {
        
        if (e.oldDate != null) {
                $('form[name="sgr_filters_sgr_eventos"]').submit();
        }
    });
});