$.each({
	runWebSocketClient: function(host, uu_id){
		try {
		      socket = new WebSocket(host);
		      socket.onopen = function () {
		          console.log('connection is opened');
		          socket.send(JSON.stringify({'cmd':'register','uu_id':uu_id}));
		          return;
		      };
		      socket.onmessage = function (msg) {
		          console.log(msg);
		          if(msg.data.length>0)
			          $.univ().successMessage(msg.data);
		          return;
		      };
		      socket.onclose = function () {
		          console.log('connection is closed');

		          return;
		      };
		  } catch (e) {
		      console.log(e);
		  }
	}
}, $.univ._import);