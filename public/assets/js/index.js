$(function() {
    /** AFFICHAGE DES ARTICLES DYNAMIQUEMENT **/
    $('.open-new').on('click', function() {
        $('.card-news').slideUp();
        $('#' + $(this)[0].dataset.open).slideDown();
    });
    /** FIN AFFICHAGE DES ARTICLES DYNAMIQUEMENT **/
    /** AFFICHAGE DES RESSOURCES **/
    $('#showAllSources').on('click', function() {
       $('#threeSources').slideUp();
       $('#allSources').slideDown();
    });
    /** FIN AFFICHAGE DES RESSOURCES **/
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.hostname == this.hostname && this.pathname.replace(/^\//,'') == location.pathname.replace(/^\//,'')) {
            var anchor = $(this.hash);
            anchor = anchor.length ? anchor : $('[name=' + this.hash.slice(1) +']');
            if ( anchor.length ) {
                $('html, body').animate( { scrollTop: anchor.offset().top - 64 }, 500);
            }
        }
    });
});