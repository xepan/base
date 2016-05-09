
$.each({
	successMessage: function(msg){
		$.univ().notify('thumbs-up',msg);
	},
	errorMessage: function(msg){
		$.univ().notify('times-circle',msg,null,null,null,'error');
	}
	
},$.univ._import);