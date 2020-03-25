$(function () {
        
        if ( $('form[name="sgr_evento"]').length) //form new//edit sgr_evento
        {
            $form = 'sgr_evento';
        }
        else if ( $('form[name="sgr_filters_sgr_eventos"]').length ) //form filters)
        {
            $form = 'sgr_filters_sgr_eventos';
        } 
        
        //console.log($form);
        $('#'+$form+'_titulacion').length ? $titulacion = $('#'+$form+'_titulacion') : $titulacion = null;
        $('#'+$form+'_curso').length ? $curso = $('#'+$form+'_curso') : $curso = null;
        $('#'+$form+'_asignatura').length ? $asignatura = $('#'+$form+'_asignatura') : $asignatura = null;
        $('#'+$form+'_profesor').length ? $profesor = $('#'+$form+'_profesor') : $profesor = null;
        $('#'+$form+'_grupoAsignatura').length ? $grupoAsignatura = $('#'+$form+'_grupoAsignatura') : $grupoAsignatura = null;
        $('#'+$form+'_termino').length ? $termino = $('#'+$form+'_termino') : $termino = null;
        $('#'+$form+'_espacio').length ? $espacio = $('#'+$form+'_espacio') : $espacio = null;
            var $espacio = $('#'+$form+'_espacio');
        
        //console.log($titulacion.val());
        
        //$( $titulacion, $curso ).change(function(e) {
        $( '#sgr_evento_titulacion, #sgr_filters_sgr_eventos_titulacion').change(function(e) {
            
            var $form = $(this).closest('form');
            var $form_name = $form.attr('name');
            //console.log($(this).val());
            /*$('#'+$form+'_titulacion').length ? $titulacion = $('#'+$form+'_titulacion') : $titulacion = null;
            */
            $('#'+$form_name+'_curso').length ? $curso = $('#'+$form_name+'_curso') : $curso = null;
            $('#'+$form_name+'_asignatura').length ? $asignatura = $('#'+$form_name+'_asignatura') : $asignatura = null;
            $('#'+$form_name+'_profesor').length ? $profesor = $('#'+$form_name+'_profesor') : $profesor = null;
            $('#'+$form_name+'_grupoAsignatura').length ? $grupoAsignatura = $('#'+$form_name+'_grupoAsignatura') : $grupoAsignatura = null;
            // ... retrieve the corresponding form.
            e.preventDefault();
            
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            //console.log($titulacion);
            data[$(this).attr('name')] = $(this).val();
            $curso ? data[$curso.attr('name')] = $curso.val() : data[$form_name+'[curso]'] = null;
            //console.log(data);
            // Submit data via AJAX to the form's action path.
            $.ajax({
                url : '/admin/sgr/evento/ajax/getAsignaturas',
                type: 'GET',//$form.attr('method'),
                data : data,
                success: function(html) {
                    //console.log(html);
                    $asignatura ? $asignatura.html(html.asignaturas.content).fadeOut().fadeIn() : null;
                    $profesor ? $profesor.html(html.profesores.content).fadeOut().fadeIn() : null;
                    $grupoAsignatura ? $grupoAsignatura.html(html.grupos.content).fadeOut().fadeIn() : null;

                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
                }
            });
        });

        $( '#sgr_evento_asignatura, #sgr_filters_sgr_eventos_asignatura' ).change(function(e){
            
            e.preventDefault();
            // ... retrieve the corresponding form.
            var $form = $(this).closest('form');
            var $form_name = $form.attr('name');

            $('#'+$form_name+'_asignatura').length ? $asignatura = $('#'+$form_name+'_asignatura') : $asignatura = null;
            $('#'+$form_name+'_profesor').length ? $profesor = $('#'+$form_name+'_profesor') : $profesor = null;
            
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            $asignatura !== null ? data[$asignatura.attr('name')] = $asignatura.val() : data[$form_name+'[asignatura]'] = null;

           // Submit data via AJAX to the form's action path.
            $.ajax({
                url : '/admin/sgr/evento/ajax/getProfesores',
                type: 'GET',//$form.attr('method'),
                data : data,
                success: function(html) {
                    //console.log(html.profesores.content);
                    //console.log(html.asignaturas.content);
                    if(html){
                        $profesor.html(html.profesores.content).fadeOut().fadeIn();    
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
                }
            }); 
        });
    
        //if (null != $termino)
        $( '#sgr_filters_sgr_eventos_termino' ).change(function(e) {
            
            // ... retrieve the corresponding form.
            e.preventDefault();
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            data[$('#sgr_filters_sgr_eventos_termino').attr('name')] = $('#sgr_filters_sgr_eventos_termino').val();
            // Submit data via AJAX to the form's action path.
            $.ajax({
                url : '/admin/sgr/espacio/ajax/getEspacios',
                type: 'GET',//$form.attr('method'),
                data : data,
                success: function(html) {
                    //console.log(html.sgrEspacios.content);//.espacio.content);
                    //console.log(html.asignaturas.content);
                    $espacio.html(html.sgrEspacios.content).fadeOut().fadeIn();
                   // $('#sgr_filters_sgr_eventos_profesor').html(html.profesores.content).fadeOut().fadeIn();

                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
                }
            });
        });
});