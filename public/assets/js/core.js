$(function() {
    /** INITIALISATIONS OBJETS MATERIALIZE **/
    $('.sidenav').sidenav();
    $('.collapsible').collapsible();
    /** FIN INITIALISATIONS OBJETS MATERIALIZE **/
    var parallax = document.querySelectorAll('.parallax');
    for(var i = 0; i < parallax.length; i++) {
        parallax[i].style.background = 'url(' + parallax[i].dataset.img + ') no-repeat top fixed';
        parallax[i].style.backgroundSize = 'cover';
    }
    /** FONCTIONS PERSONNALISÉES **/
    /**
     * @type {{create: Window.form.create}}
     */
    window.form = {
        /**
         * @param $formId ID du formulaire ( id="id" )
         * @param $function La fonction qui va être executée après l'execution du PHP
         * @returns swal ( sweet-alert ) Retourne une alerte visuelle
         */
        create: function ($formId, $function) {
            $form = $('#' + $formId);
            $form.bind('submit', function () {
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (html) {
                        $function(html);
                    }
                });
                return false;
            });
        }
        /** FIN FONCTIONS PERSONNALISÉES **/


    };

});
