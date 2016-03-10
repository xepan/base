
$.each({
	notify: function (icon, message, layout, effect,ttl,type){
		
		if(icon!=null) icon= '<span class="fa fa-'+ icon + ' fa-3x pull-left"></span> ';
		if(layout ==null) layout = 'bar';
		if(effect == null) effect = 'slidetop';
		if(ttl==null) ttl = 5000;
		if(type==null) type ='success';

		var notification = new NotificationFx({
							message : icon + message,
							layout : layout,
							effect : effect,
							ttl : ttl,
							type : type, // notice, warning or error
						});
		notification.show();
	},
	successMessage: function(msg){
		$.univ().notify('thumbs-up',msg);
	},
	errorMessage: function(msg){
		$.univ().notify('times-circle',msg,null,null,null,'error');
	}
},$.univ._import);