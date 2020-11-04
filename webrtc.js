var peerConn = [];//peer connection
//caller identification no
var room_id=localStorage.getItem("room");
var user_id = Math.floor(Math.random() * 10);
console.log(user_id)
var user_name = 'sdjhsds';
var localStream;
var allSessionDescriptions = [];
var allParticipants = [];
var alert_content = document.getElementById('alert_content');
var alert_box = document.getElementById('alert_box');
var list_of_videos = document.getElementById('list_of_videos');


console.log("room_id");
console.log(room_id);

var userMedia = {
	'audio':true,
	'video':true
}
navigator.getUserMedia = navigator.mozGetUserMedia || navigator.webkitGetUserMedia;
navigator.mediaDevices.getUserMedia(userMedia).then((stream)=>{

	
	console.log("local stream");
	console.log(stream);
  localStream = stream;
	document.getElementById('localVideo').srcObject = stream;

  //set user id to db

  saveUserToDb();
  //setPeerConnection(user_id, true);


}).catch((err)=> {

	onUserMediaFailure(err);
});


function saveUserToDb() {

  showAlert('Joining room..');
  var form_d = new FormData();
      form_d.append("user_id", user_id);
      form_d.append("room_id", room_id);
  var controller = {
      url:'room_participants.php?action=set_participant',
      method: 'POST',
      data: form_d,
      callBackFunction : function (result) {

        if(result == 'successful') {

          getRoomParticipants();
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

function getRoomParticipants() {

  showAlert('Waiting for connection');

  var controller = {
      url:'room_participants.php?action=get_participant&room_id='+room_id+'&user_id='+user_id,
      method: 'GET',
      callBackFunction : function (result) {

        console.log(result);

        if(result) {

          var participants = JSON.parse(result);

          for(var key in participants) {

            if(participants.hasOwnProperty(key)){

              var user = participants[key]
              
              if(!allParticipants.includes(user)){

                allParticipants.push(user);
                //create a peer and create offer

                setPeerConnection(user, "dsfdsfdf", true);
              }

            }                     

          }

        }


        getRemoteData();
        
        

      },
      error: function(error) {
        console.log(error);
      }
    }
    accessServer(controller);
}

function setPeerConnection(id, u_name, init_call = false) {



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

    peerConn[id] = {

      user_name: u_name,
      id: id,
      pc : new PeerConnection(iceServers)

    }
    

      for (const track of localStream.getTracks()) {
        peerConn[id].pc.addTrack(track, localStream);
      }
    
      peerConn[id].pc.addEventListener('track' , function(evt) {

        console.log('other user video object');
        //console.log(evt.streams[0]);

        var stream = new MediaStream();
        stream.addTrack(evt.track);

        var col = document.createElement('div');
        col.className = 'col-md-4';       

        var video = document.createElement('video');

        video.className = 'video';
        video.srcObject = stream;
        video.autoplay = true;
        video.playsInline = true;
        video.muted = false;

        col.appendChild(video);

        list_of_videos.appendChild(col);

        /*


          <div class="col-md-4">
          
          <video id="remoteVideo" class="video" autoplay="autoplay"></video>

          <p class="friend_name">Dennis Gitonga</p>

        </div>


        */
        console.log(stream);    
        //console.log(remoteVideo.srcObject);
      });
      peerConn[id].pc.onicecandidate = function (evt) {
        if (evt.candidate) {


            console.log("evt.candidate");
            console.log(evt.candidate);
          //save all ice candidates to db;

          saveIceToDB(evt.candidate);

          }else{

            postLocalData(peerConn[id]);
            console.log("sent all ice candidates");
          }


      };

      peerConn[id].pc.onremovetrack = function() {

      }

    if (init_call){
      peerConn[id].pc.createOffer().then((desc)=>{

      peerConn[id].pc.setLocalDescription(desc).then(()=> {
      
        onLocalDescSuccess(peerConn[id].pc)
      }).catch((err)=>{

        onLocalDescError(err);
      }); 

      }).catch((err)=>{
        onCreateOfferError(err);
      });
    } 
}

function onUserMediaSuccess (stream) {
}

function onUserMediaFailure (error) {
	
	console.log('userMedia error');
	console.log(error);
}


function onCreateOfferError(err) {
  logError('Error creating offer: ' + err.name);
}


function onDescCreated(pc_obj,desc) {


  showAlert('Connecting to '+ pc_obj.user_name);
  pc_obj.pc.setLocalDescription(desc).then(()=>{

  	onLocalDescSuccess(pc_obj);

  }).catch((err)=>{
  	onLocalDescError(err)
  });

}

function onLocalDescSuccess(pc_obj) {
  console.log('Local sdp created: ' + JSON.stringify(pc_obj.pc.localDescription));
  if (pc_obj.pc.iceGatheringState == 'complete') {
  	postLocalData(pc_obj);
  }else{

  	console.log(pc_obj.pc.iceGatheringState);
  }
  }


function onLocalDescError(err) {
  console.log(err)
  logError("Local description could not be created: " + err.name);
  }


function postLocalData(pc_obj) {

  console.log('Storing local sdp');
	var formData = new FormData();
  		formData.append("room_id",room_id);
      formData.append("Sdp" , JSON.stringify(pc_obj.pc.localDescription));
      formData.append("sender" , user_id);
  		formData.append("receiver" , pc_obj.id);

  	var controller = {
  		url:'storeSdp.php',
  		method: 'POST',
  		data: formData,
  		callBackFunction : function (result) {

  			if(result == 'successful') {


          console.log('hurray');
          //getRemoteData();


          /*
  				if(isCaller){
				    var link = location.href + '/room_info.php?room=' + room_id;
				    alert('this is the share link '+ link);
				    console.log(link);

				    

			  	}else {
			  		console.log(result);
			  		waitForConnection();
			  	}

          */
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
  console.log(room_id);
  console.log(user_id);

  	var ice =  JSON.stringify(ice_candidate);

  	var data = {
  		room_id: room_id,
  		ice: ice,
  		sender: user_id
  	}


  		 $.ajax({
            url: 'send_ice.php',
            type: 'post',
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) {
                console.log("saving ice result");
  				console.log(res);
            },
            data: JSON.stringify(data)
        });

	
}




function receiveroom_id(context) {

  if (context.responseText) {

  	var response = context.responseText;
    
    }

  else{
    logError('Error connecting to database');
  }

}



function getRemoteData() {
  console.log("Checking db for remote sdp");
  var url = 'fetchSdp.php?room_id='+ room_id+'&user_id='+user_id;

  console.log(url);
  var controller = {
  		url: url,
  		method: 'GET',
  		callBackFunction : function (result) {

  			console.log(result);

        if(result) {

          var response = JSON.parse(result);
          console.log(response);


          for(var key in response) {

            if (response.hasOwnProperty(key)){

              var sdp__json = response[key].Sdp;
              console.log(sdp__json);

              var sender = response[key].sender; 

              //store the sdps to an array if its not in the array
              //set the peer connection if its an offer
              //then add to session description
              //only add sdp that is not in array to session desription

              if(!allSessionDescriptions.includes(sdp__json)) {
                
                allSessionDescriptions.push(sdp__json);
                var SessionDescription = window.RTCSessionDescription || window.mozRTCSessionDescription || window.webkitRTCSessionDescription;

                var __sdp = JSON.parse(sdp__json);

                if(__sdp.type == 'offer') {
                  setPeerConnection(sender, "sdfsdfdfd");
                  showAlert(peerConn[sender].user_name + ' has joined the room');
                }

                var sdp = new SessionDescription(__sdp);
                console.log(sdp);

                console.log( peerConn[sender].pc);

                peerConn[sender].pc.setRemoteDescription(sdp).then(()=>{

              
                  onRemoteDescSuccess(peerConn[sender],sdp);

                }).catch((err)=>{
                  onRemoteDescError(err);
                });
              }
              
            }
          }


          

        }

        setTimeout(getRemoteData(), 2000);

        

			

			//var sdp__json = (isCaller)?response[Object.keys(response)[0]].responderSdp:response[Object.keys(response)[0]].callerSdp;
			//console.log(sdp__json);

        	
        /*
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

        */
		
		   
  			
		  },
		  error: function(error) {
		  	console.log(error);
		  }
  	}
  	accessServer(controller);
}


function onRemoteDescSuccess(pc_obj, sdp) {
  console.log("Remote sdp successfully set");

  if(sdp.type = 'offer') {

    pc_obj.pc.createAnswer().then((desc)=>{

      console.log(desc);

      onDescCreated(pc_obj,desc);
    }).catch((err)=>{

      onCreateAnswerError(err);

    });
  }else {
    waitForConnection();
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
  var url = 'fetch_ice_candidates.php?isCaller=' +isCaller + '&room_id='+ room_id+'&user_id='+user_id;

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

}

function showAlert(msg) {
   alert_content.innerHTML = msg;
  alert_box.style.display = 'block';

  setTimeout(()=>{
    alert_content.innerHTML = '';
    alert_box.style.display = 'none';
  }, 7000);
}