$(function () {
        

        
        $( '#sgr_evento_titulacion, #sgr_filters_sgr_eventos_curso, #sgr_filters_sgr_eventos_titulacion').change(function(e) {
            
            // ... retrieve the corresponding form name.
            var $form = $(this).closest('form');
            var $form_name = $form.attr('name');
            //console.log($form_name);
            
            //.... set object input form
            $('#'+$form_name+'_titulacion').length ? $titulacion = $('#'+$form_name+'_titulacion') : $titulacion = null;
            $('#'+$form_name+'_curso').length ? $curso = $('#'+$form_name+'_curso') : $curso = null;
            $('#'+$form_name+'_asignatura').length ? $asignatura = $('#'+$form_name+'_asignatura') : $asignatura = null;
            $('#'+$form_name+'_profesor').length ? $profesor = $('#'+$form_name+'_profesor') : $profesor = null;
            $('#'+$form_name+'_grupoAsignatura').length ? $grupoAsignatura = $('#'+$form_name+'_grupoAsignatura') : $grupoAsignatura = null;
            
            e.preventDefault();
            
            // Simulate form data, but only include the selected for titulacion (and/or) titulacion and curso input selects.
            var data = {};
            //console.log($titulacion);
            //data[$(this).attr('name')] = $(this).val();
            $titulacion ? data[$titulacion.attr('name')] = $titulacion.val() : data[$form_name+'[titulacion]'] = null;
            $curso ? data[$curso.attr('name')] = $curso.val() : data[$form_name+'[curso]'] = null;
            //console.log(data);
            // Submit data via AJAX to the form's action path.
            $.ajax({
                //url : '/admin/sgr/evento/ajax/getAsignaturas',
                url : '/reservasfgh/admin/sgr/evento/ajax/getAsignaturas',
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
                //url : '/admin/sgr/evento/ajax/getProfesores',
                url : '/reservasfgh/admin/sgr/evento/ajax/getProfesores',
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
    
        $( '#sgr_filters_sgr_eventos_termino' ).change(function(e) {
            
            // ... retrieve the corresponding form.
            e.preventDefault();
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            data[$('#sgr_filters_sgr_eventos_termino').attr('name')] = $('#sgr_filters_sgr_eventos_termino').val();
            // Submit data via AJAX to the form's action path.
            $.ajax({
                //url : '/admin/sgr/espacio/ajax/getEspacios',
                url : '/reservasfgh/admin/sgr/espacio/ajax/getEspacios',
                type: 'GET',//$form.attr('method'),
                data : data,
                beforeSend: loadStart('#sgr_filters_sgr_eventos_espacio'),
                complete: loadStop('#sgr_filters_sgr_eventos_espacio'),
                success: function(html) {
                    //console.log(html.sgrEspacios.content);//.espacio.content);
                    //console.log(html.asignaturas.content);
                    $('#sgr_filters_sgr_eventos_espacio').html(html.sgrEspacios.content).fadeOut().fadeIn();
                   // $('#sgr_filters_sgr_eventos_profesor').html(html.profesores.content).fadeOut().fadeIn();

                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
                }
            });
        });

        function loadStart(div) {
            //console.log($(div).next(".spinner-border"));
            $(div).next(".spinner-border").removeClass('d-none').fadeIn('slow');
            //sleep(3000);
        }
        function loadStop(div) {
            $(div).next(".spinner-border").addClass('d-none');
        }
});