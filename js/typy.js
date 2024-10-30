jQuery(document).ready(function ($) {
	
	if (screen.width <= 600) {
 document.getElementById("viewport").setAttribute("content", "width=device-width, initial-scale=0.8, maximum-scale=0.8");
}
fms_set_width();

window.onresize = function(){
	fms_set_width();
}

function fms_set_width(){
if( $('#fms_statistic_table').length) {
var twidth = document.getElementById("fms_statistic_table").parentElement.offsetWidth;	
$("#fms_statistic_ttable").width( twidth );
}
}

  $('tbody').scroll(function(e) { 
  
  $('thead').css("left", -$("tbody").scrollLeft());
    $('thead th:nth-child(1)').css("left", $("tbody").scrollLeft()); 
    $('tbody td:nth-child(1)').css("left", $("tbody").scrollLeft()); 
  });


  $(".old").on('click', function(e) {
     
	 if ($(e.target).hasClass("typowali") || $(e.target).hasClass("typ_user") || $(e.target).hasClass("typ_wynik"))
		return;
	
	if ( $( this ).hasClass( "oldclosed" ) ) {
		$(this).removeClass('oldclosed');
	}
	else $(this).addClass('oldclosed');
	
  });
  
 $(".typowali").on('click', function(e) {
     
	if ( $( this ).hasClass( "typopen" ) ) {
		$(this).removeClass('typopen');
	}
	else {
		$(this).addClass('typopen');
		$(this).closest('.mecz').find('.old').removeClass('oldclosed');
	}
	
  }); 
  

  
  });