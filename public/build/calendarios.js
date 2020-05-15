$(function () {

    //console.log(JSON.parse( sessionStorage.getItem("ui") ));
    if (window.sessionStorage) {
        //console.log('load page..');

        ui = JSON.parse( sessionStorage.getItem("ui") );
        //ui = null;
        if (ui == null)
        {
            var ui = new Object();
            ui.filters = 1;
            ui.tabActive = 1;
            ui.fInicio = $('#datetimepicker-fi').datetimepicker('date').format('DD/MM/Y');
            sessionStorage.setItem("ui",JSON.stringify(ui));
        }
        else
            $('input[name="sgr_filters_sgr_eventos[ui]"]').val(JSON.stringify(ui));

        
        //if (ui != null)
            //console.log(ui);
        
    }

    $('#collapseFiltros').on('hidden.bs.collapse', function () {
        
        $('#colCalendar').removeClass('col-10').addClass('col-12');
        
        if (window.sessionStorage) 
        {
            ui = JSON.parse( sessionStorage.getItem("ui") );
            ui.filters = 0;
            sessionStorage.setItem("ui",JSON.stringify(ui));
        }
        
        $('input[name="sgr_filters_sgr_eventos[ui]"]').val(JSON.stringify(ui));
        
        console.log('hidden');
        console.log(JSON.stringify(ui));
        console.log($('input[name="sgr_filters_sgr_eventos[ui]"]').val());
    });

    $('#collapseFiltros').on('show.bs.collapse', function () {
        
        $('#colCalendar').removeClass('col-12').addClass('col-10');
        
        if (window.sessionStorage) 
        {
            ui = JSON.parse( sessionStorage.getItem("ui") );
            ui.filters = 1;
            sessionStorage.setItem("ui",JSON.stringify(ui));
        }
        
        $('input[name="sgr_filters_sgr_eventos[ui]"]').val(JSON.stringify(ui));
                
        console.log('show');
        console.log(JSON.stringify(ui));
        console.log($('input[name="sgr_filters_sgr_eventos[ui]"]').val());
    });

    $('a.editarEvento').on('click',function(e){

        e.preventDefault();
        console.log(JSON.stringify(ui));
    });

    $('form[name="sgr_filters_sgr_eventos"]').on('submit', function(e){

        showGifEspera();
    });

    function showGifEspera(){
    
        $('#espera').css('display','inline').css('z-index','100');
    }

    function hideGifEspera(){
    

        $('#espera').css('display','none').css('z-index','-100');
    }
});