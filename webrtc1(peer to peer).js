var pc;//peer connection
//caller identification no
var callNo=0;
var user_id = 2;
var isCaller = (location.search == "");

console.log(isCaller);

if(isCaller) {

	console.log('isCaller');
	user_id = 1;
	//document.getElementById('main_page').style.css ='block';
	//document.getElementById('video_container').style.css = 'none';

	//save room to db
	//for now we use synchronous later set to another page

	formData = new FormData();
		formData.append("user_id",user_id);

	$.ajax( {
      url:'saveroomtodb.php',
        processData: false,
        contentType: false,
        async: false,
        cache: false,
        enctype: 'multipart/form-data',
        data: formData,
      method:'POST',
      success:(response) => {
          
          callNo = response;
      },
      error: (error) => {
          console.log(error);
      }
    });

}

console.log("callNo");
console.log(callNo);

var userMedia = {
	'audio':true,
	'video':true
}
navigator.getUserMedia = navigator.mozGetUserMedia || navigator.webkitGetUserMedia;
navigator.mediaDevices.getUserMedia(userMedia).then((stream)=>{

	
	console.log("local stream");
	console.log(stream);
	document.getElementById('main_page').style.css ='none';
	
	//console.log(stream);
	document.getElementById('video_container').style.css = 'block';
	document.getElementById('localVideo').srcObject = stream;

	//just like getUserMedia above we get the peer connection depending on browser
	var PeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
	//used to navigate through web to allow connection
	var iceServers = {
		iceServers: [{url: "stun:stun.l.google.com:19302"},
		 {url: "stun:stun1.l.google.com:19302"},
		 {url: "stun:stun2.l.google.com:19302"},
		 {url:"stun:stun.services.mozilla.com"}]
	};

	//create peer connection;
	pc = new PeerConnection(iceServers);

	for (const track of stream.getTracks()) {
      pc.addTrack(track, stream);
    }
	
  	pc.addEventListener('track' , function(evt) {

  		console.log('other user video object');
  		//console.log(evt.streams[0]);

  		var remoteVideo = document.getElementById('remoteVideo');

  		var stream = new MediaStream();
  		stream.addTrack(evt.track);
    	remoteVideo.srcObject = stream; 

  		console.log(stream);   	
    	//console.log(remoteVideo.srcObject);
    	remoteVideo.autoplay = true;
    	remoteVideo.playsInline = true;
    	remoteVideo.muted = false;
    });
    pc.onicecandidate = function (evt) {
    	if (evt.candidate) {


	      	console.log("evt.candidate");
	      	console.log(evt.candidate);
	     	//save all ice candidates to db;

	     	saveIceToDB(evt.candidate);

      	}else{

      		postLocalData();
	      	console.log("sent all ice candidates");
      	}


    };

    pc.onremovetrack = function() {
    	
    }

  if (isCaller){
  	pc.createOffer().then((desc)=>{

		pc.setLocalDescription(desc).then(()=> {
		
			onLocalDescSuccess()
		}).catch((err)=>{

			onLocalDescError(err);
		});	

  	}).catch((err)=>{
  		onCreateOfferError(err);
  	});
  } 

  else {

    callNo = location.search.substr(location.search.indexOf('=') + 1);

    getRemoteData();
    }

  window.onbeforeunload = function() {


    if (isCaller && callNo > 0) navigator.sendBeacon('deleteCall.php', callNo);
    stream.stop();

  	};
}).catch((err)=> {

	onUserMediaFailure(err);
});


function onUserMediaSuccess (stream) {
}

function onUserMediaFailure (error) {
	
	console.log('userMedia error');
	console.log(error);
}


function onCreateOfferError(err) {
  logError('Error creating offer: ' + err.name);
}


function onDescCreated(desc) {



  pc.setLocalDescription(desc).then(()=>{

  	onLocalDescSuccess();

  }).catch((err)=>{
  	onLocalDescError(err)
  });

}

function onLocalDescSuccess() {
  console.log('Local sdp created: ' + JSON.stringify(pc.localDescription));
  if (pc.iceGatheringState == 'complete') {
  	postLocalData();
  }else{

  	console.log(pc.iceGatheringState);
  }
  }


function onLocalDescError(err) {
  logError("Local description could not be created: " + err.name);
  }


function postLocalData() {

  console.log('Storing local sdp');
	var formData = new FormData();
		formData.append('isCaller', (isCaller)?'true':'false');
  		formData.append("callNo",callNo);
  		formData.append("responderSdp" , JSON.stringify(pc.localDescription));

  	var controller = {
  		url:'storeSdp.php',
  		method: 'POST',
  		data: formData,
  		callBackFunction : function (result) {

  			if(result == 'successful') {

  				if(isCaller){
				    var link = location.href + '?callNo=' + callNo;
				    alert('this is the share link '+ link);
				    console.log(link);

				    getRemoteData();

			  	}else {
			  		console.log(result);
			  		waitForConnection();
			  	}
  			}else {

  				console.log(result);
  			}

		  },
		  error: function(error) {
		  	console.log(error);
		  }
  	}
  	accessServer(controller);
}

function saveIceToDB(ice_candidate){
	

  console.log('Storing ice');
  console.log(callNo);
  console.log(user_id);

  	var ice =  JSON.stringify(ice_candidate);

  	var data = {
  		callNo: callNo,
  		ice: ice,
  		sender: user_id
  	}


  		 $.ajax({
            url: 'send_ice.php',
            type: 'post',
            dataType: 'json',
            async:false,
            contentType: 'application/json',
            success: function (data) {
                console.log("saving ice result");
  				console.log(res);
            },
            data: JSON.stringify(data)
        });

	
}

//Now that everything has been set up we can actually send the request, which is executed asynchronously, meaning it can run in parallel with other code, so doesn't hold up the rest of the program while it's running. We send all the local data as a single string comprising the callNo followed by '~' (tilde) as a separator character followed by the local sdp string.


function receiveCallNo(context) {

  if (context.responseText) {

  	var response = context.responseText;
    
    }

  else{
    logError('Error connecting to database');
  }

}


function getRemoteData() {
  console.log("Checking db for remote sdp");
  var url = 'fetchSdp.php?isCaller=' +isCaller + '&callNo='+ callNo;

  console.log(url);
  var controller = {
  		url: url,
  		method: 'GET',
  		callBackFunction : function (result) {

  			console.log(result);

			var response = JSON.parse(result);
			console.log(response);

			var sdp__json = (isCaller)?response[Object.keys(response)[0]].responderSdp:response[Object.keys(response)[0]].callerSdp;
			console.log(sdp__json);

        	

		    if(sdp__json){

  				var SessionDescription = window.RTCSessionDescription || window.mozRTCSessionDescription || window.webkitRTCSessionDescription;

			    var sdp = new SessionDescription(JSON.parse(sdp__json));
			    console.log(sdp);

			     pc.setRemoteDescription(sdp).then(()=>{

				
					onRemoteDescSuccess(sdp);

			    }).catch((err)=>{
			    	onRemoteDescError(err);
			    });

		  	}else {
		  		setTimeout(getRemoteData(), 500);
		  	}
		
		   
  			
		  },
		  error: function(error) {
		  	console.log(error);
		  }
  	}
  	accessServer(controller);
}


function onRemoteDescSuccess(sdp) {
  console.log("Remote sdp successfully set");

  if (isCaller) {
    waitForConnection();
    
    //document.getElementById('remoteVideo').style.display = 'block';
  }else{
    
    if(sdp.type = 'offer') {

    	pc.createAnswer().then((desc)=>{

	    	console.log(desc);

	    	onDescCreated(desc);
	    }).catch((err)=>{

	    	onCreateAnswerError(err);

	    });
    }
  }

}


function onRemoteDescError(err) {
  logError("Remote description could not be set: " + err.name);
  console.log(err);

}


function onCreateAnswerError(err) {
  logError("Error creating answer: " + err.name);
}


function waitForConnection() {

	console.log("pc.iceConnectionState");
	console.log(pc.iceConnectionState);

  if (pc.iceConnectionState == 'connected' || pc.iceConnectionState == 'completed') {

    logError('Connection complete');

    isCaller = null;

    document.getElementById('remoteVideo').ondblclick = function() {

      this.requestFullscreen = this.mozRequestFullScreen || this.webkitRequestFullscreen;
      this.requestFullscreen();
      }
    }else if(pc.iceConnectionState == 'stable') {
    	getIceCandidates();
    }

  else{
    setTimeout(waitForConnection(), 1000);
  }
}


var ice_candidates =[];

function getIceCandidates() {

	//console.log("Checking db for ice_candidate");
  var url = 'fetch_ice_candidates.php?isCaller=' +isCaller + '&callNo='+ callNo+'&user_id='+user_id;

  //console.log(url);
  var controller = {
  		url: url,
  		method: 'GET',
  		callBackFunction : function (result) {

  			if(result) {

  				var response = JSON.parse(result);

	  			for (var key in response) {


					// skip loop if the property is from prototype
					if (response.hasOwnProperty(key)){


						//console.log(response[key].ice_candidate);

						var ice_cand = JSON.parse(response[key].ice_candidate);

						//only add new candidates
						if(!ice_candidates.includes(ice_cand)) {

							ice_candidates.push(ice_cand);
							pc.addIceCandidate(ice_cand).catch(e => {
						      console.log("Failure during addIceCandidate: " + e.name);
						    });

						}	

						
					}
				}
  			}

  			//checking for ice cand after 1 sec;
  			setTimeout(()=>{getIceCandidates();}, 1000);
  		}

  	}
  	accessServer(controller);

}


function logError(msg) {

  console.log(msg);
  if (isCaller && callNo > 0){

  	navigator.sendBeacon('deleteCall.php', callNo);

  } 

}



function accessServer(controller){
	$.ajax( {
      url:controller.url,
        processData: false,
        contentType: false,
        cache: false,
        enctype: 'multipart/form-data',
        data: (controller.data)?controller.data:"",
      method:controller.method,
      success:function(response) {
          controller.callBackFunction(response);
      },
      error: function(error) {
          if(controller.error) {
              
              controller.error(error);
          }
      }
    });
}