$(function() {
    $('.modal').modal();
   $('.delete-user').click(function() {
       $('#confirm-delete-user')[0].href = $(this)[0].dataset.link;
   });
});