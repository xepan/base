$.each({
	notify: function(xTitle, xText, xType, isDesktop, callback, sticky){
		if(isDesktop != undefined || isDesktop != null || isDesktop != false || isDesktop==true) {
			var nn = new PNotify(
			{ 
				title: xTitle?xTitle:"Notification",
				text: xText,
				type: xType==null?"notice":xType,
				hide: false,
				desktop: {
						desktop: true
					}
			});
		}

		if(sticky != undefined || sticky != null || sticky==true) {
			// But also show sticky notification on web page, in case user missed desktop notification
			var nn = new PNotify(
			{ 
				title: xTitle?xTitle:"Notification",
				text: xText,
				type: xType==null?"notice":xType,
				hide: false,
				desktop: {
						desktop: false
					},
				history: {
        			menu: true
    			}
			});
		}else{
			var nn = new PNotify(
			{ 
				title: xTitle,
				text: xText,
				type: xType==null?"success":xType,
				history: {
        			menu: false
    			}
    		}
			);
		}
	}
}, $.univ._import);