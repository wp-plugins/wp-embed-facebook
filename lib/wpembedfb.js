window.onresize = resize_cover;

jQuery(document).ready(function() {
  	jQuery.ajaxSetup({ cache: true });
  	var script_name = '//connect.facebook.net/'+ WEF.local +'/all.js';
  	if(WEF.fb_root !== 'false'){
	  	jQuery.getScript(script_name, function(){
	    	FB.init({
	      		appId: WEF.fb_id,
			    status: true, 
			    cookie: true, 
			    xfbml: true  	      		
	   		});     
	  	});  		
  	}
  	//responsive things
	resize_cover();
  	var width = jQuery(".wpemfb-border").outerWidth();
	jQuery(".fb-post").attr("data-width",width+"px");   	
});

function resize_cover(){
	var width = jQuery(".wpemfb-cover").width();
  	var height = 0.36867 * width;
  	jQuery(".wpemfb-cover").height(height);  	
}