$("#productsList").addClass("active");



function load_images(ROUTE)
{
    $.ajax({
      url: ROUTE,
      type: "GET",
      success:function(data)
      {
        $('#uploaded_image').html(data);
      }
    })
}

load_images(ROUTE);

$(document).on('click', '.remove_image', function(){
    var pic = $(this).attr('id');
    $.ajax({
      url: delete_route,
      type:"DELETE",
      data:{pic : pic, _token:token},
      success:function(data){
        load_images(ROUTE);
      }
    })
  });
