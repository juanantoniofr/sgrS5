
        
        var $titulacion = $('#sgr_filters_sgr_eventos_titulacion');
        var $curso = $('#sgr_filters_sgr_eventos_curso');
        
        $( '#sgr_filters_sgr_eventos_titulacion, #sgr_filters_sgr_eventos_curso' ).change(function(e) {
            // ... retrieve the corresponding form.
            e.preventDefault();
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            data[$titulacion.attr('name')] = $titulacion.val();
            data[$curso.attr('name')] = $curso.val();        
            // Submit data via AJAX to the form's action path.
            $.ajax({
                url : '/admin/sgr/evento/ajax/getAsignaturas',
                type: 'GET',//$form.attr('method'),
                data : data,
                success: function(html) {
                    //console.log(html.profesores.content);
                    //console.log(html.asignaturas.content);
                    $('#sgr_filters_sgr_eventos_asignatura').html(html.asignaturas.content).fadeOut().fadeIn();
                    $('#sgr_filters_sgr_eventos_profesor').html(html.profesores.content).fadeOut().fadeIn();

                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
                }
            });
        });

        var $asignatura = $('#sgr_filters_sgr_eventos_asignatura');
        
        $asignatura.change(function(e){

            e.preventDefault();
            // ... retrieve the corresponding form.
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            data[$asignatura.attr('name')] = $asignatura.val();

           // Submit data via AJAX to the form's action path.
            $.ajax({
                url : '/admin/sgr/evento/ajax/getProfesores',
                type: 'GET',//$form.attr('method'),
                data : data,
                success: function(html) {
                    console.log(html.profesores.content);
                    //console.log(html.asignaturas.content);
                    $('#sgr_filters_sgr_eventos_profesor').html(html.profesores.content).fadeOut().fadeIn();

                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
                }
            }); 
        });

        var $termino = $('#sgr_filters_sgr_eventos_termino');
        
        $( '#sgr_filters_sgr_eventos_termino' ).change(function(e) {
            // ... retrieve the corresponding form.
            e.preventDefault();
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected titulacion value.
            var data = {};
            data[$termino.attr('name')] = $termino.val();
            // Submit data via AJAX to the form's action path.
            $.ajax({
                url : '/admin/sgr/espacio/ajax/getEspacios',
                type: 'GET',//$form.attr('method'),
                data : data,
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
