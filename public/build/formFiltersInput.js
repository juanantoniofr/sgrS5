$(function () {
        
        if ( $('form[name="sgr_evento"]').length) //form new//edit sgr_evento
        {
            $form = 'sgr_evento';
        }
        else if ( $('form[name="sgr_filters_sgr_eventos"]').length ) //form filters)
        {
            $form = 'sgr_filters_sgr_eventos';
        } 
        
        console.log($form);
        $('#'+$form+'_titulacion').length ? $titulacion = $('#'+$form+'_titulacion') : $titulacion = null;
        $('#'+$form+'_curso').length ? $curso = $('#'+$form+'_curso') : $curso = null;
        $('#'+$form+'_asignatura').length ? $asignatura = $('#'+$form+'_asignatura') : $asignatura = null;
        $('#'+$form+'_profesor').length ? $profesor = $('#'+$form+'_profesor') : $profesor = null;
        $('#'+$form+'_grupoAsignatura').length ? $grupoAsignatura = $('#'+$form+'_grupoAsignatura') : $grupoAsignatura = null;
        $('#'+$form+'_termino').length ? $termino = $('#'+$form+'_termino') : $termino = null;
        $('#'+$form+'_espacio').length ? $espacio = $('#'+$form+'_espacio') : $espacio = null;
            var $espacio = $('#'+$form+'_espacio');
        
        console.log($titulacion.val());
        
        $( $titulacion, $curso ).change(function(e) {
            // ... retrieve the corresponding form.
            e.preventDefault();
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            //console.log($titulacion);
            $titulacion !== null ? data[$titulacion.attr('name')] = $titulacion.val() : data[$form+'[titulacion]'] = null;
            $curso !== null ? data[$curso.attr('name')] = $curso.val() : data[$form+'[curso]'] = null;        
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

        $( $asignatura ).change(function(e){

            e.preventDefault();
            // ... retrieve the corresponding form.
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            $asignatura !== null ? data[$asignatura.attr('name')] = $asignatura.val() : data[$form+'[asignatura]'] = null;

           // Submit data via AJAX to the form's action path.
            $.ajax({
                url : '/admin/sgr/evento/ajax/getProfesores',
                type: 'GET',//$form.attr('method'),
                data : data,
                success: function(html) {
                    console.log(html.profesores.content);
                    //console.log(html.asignaturas.content);
                    $profesor.html(html.profesores.content).fadeOut().fadeIn();

                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
                }
            }); 
        });
    
        //if (null != $termino)
        $( $termino ).change(function(e) {
            // ... retrieve the corresponding form.
            e.preventDefault();
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            $termino !== null ? data[$termino.attr('name')] = $termino.val() : data[$form+'[termino]'] = null;
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