$(document).ready(function(){
 
    $(".comment-ico").click(function(){
        $(this).next().toggle("fast");
    });


var maxLength = 600;
$('.comments-form textarea').keyup(function() {
  var length = $(this).val().length;
  var length = maxLength-length;
  $(this).next().text(length);
});


});

