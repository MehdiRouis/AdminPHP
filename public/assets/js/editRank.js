$(function() {
    let $rankIcon = $('#rankIcon');
    $rankIcon.bind('keyup', function() {
        $('#iconView').html('<i class="' + $rankIcon.val() + '"></i>');
    });
    let $rankColor = $('#rankColor');
    let $modelColor = $('#modelColor');
    $modelColor.css('background-color', '#000');
    $modelColor.css('width', '100%');
    $modelColor.css('height', '30px');
    $rankColor.bind('keyup', function() {
        $modelColor.css('background-color', $rankColor.val());
    });
});