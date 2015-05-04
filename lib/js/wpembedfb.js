jQuery(document).ready(function() {
	resize_cover();
  	var v_width = jQuery(".wpemfb-container").outerWidth();
	jQuery(".fb-post").attr("data-width",v_width+"px");   	
});
jQuery(window).resize(function(){
	resize_cover();	
});
function resize_cover(){
	var v_width = jQuery(".wpemfb-cover").width();
  	var v_height = 0.368 * v_width;
  	jQuery(".wpemfb-cover").css("height",v_height );  	
  	//console.log(v_height);
  	//console.log(jQuery(".wpemfb-cover").height());
}