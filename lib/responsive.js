window.onresize = resize_cover;
jQuery(document).ready(function(){
	resize_cover();
  	var width = jQuery(".wpemfb-border").outerWidth();
	jQuery(".fb-post").attr("data-width",width+"px");  
});
function resize_cover(){
	var width = jQuery(".wpemfb-cover").width();
  	var height = 0.36867 * width;
  	jQuery(".wpemfb-cover").height(height);  	
}
