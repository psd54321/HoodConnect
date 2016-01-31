function fetchmessagelocation() {
  //$("#loaderIcon").show();
  jQuery.ajax({
  url: "fetchmessagewithloc.php",
  type: "POST",
  success:function(data){
    var stringdata = data.split(";");
    var messagearray = stringdata[1];
    var stringarray = messagearray[0].split(",");
    var userarray = stringdata[0].split(":");

      var map = new google.maps.Map(document.getElementById('messagemap'), {
                    zoom: 13,
                    center: new google.maps.LatLng(40.627600, -74.031558),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

    for (var i = 0; i < userarray.length; i++) {
    	var singleuser = userarray[i].split(",");

    	var userMarker = new google.maps.Marker({
                    position: new google.maps.LatLng(singleuser[3], singleuser[4]),
                    map: map,
                    draggable: true
                });

    	userMarker.info = new google.maps.InfoWindow({
 			 content: "<a href='showprofile.php?username="+singleuser[0]+"'>"+singleuser[1]+" "+singleuser[2]+"</a>"
			});

    	google.maps.event.addListener(userMarker, 'click', function() {  
    	var marker_map = this.getMap();
   		this.info.open(marker_map,this);
		});
    }

  

    //var myMarker = new google.maps.Marker({
      //              position: new google.maps.LatLng(stringarray[8], stringarray[9]),
        //            map: map,
          //          draggable: true
            //    });

    //var contentstring = "<div class='media' style='width:300px;' data-class='whole-story'><div style='margin-top:10px;'class='media-object'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></div><div class='media-body' ><div data-class='main-content-area'><div><h4 class='media-heading'><span class='subject'><a href='showmessage.php?mid="+stringarray[0]+"'>"+stringarray[1]+"</a> </span><span class='timestamp'>"+stringarray[5]+"</span></h4><h5 class='media-author'>"+stringarray[4]+"</h5><p data-class='post-content'>"+stringarray[3]+"</p></div><div class='metadata'></div><div class='media-scope' style='color:#9b9999;'>Shared with "+stringarray[7]+"</div></div>";
    //var infowindow = new google.maps.InfoWindow();

    //infowindow.setContent(contentstring);
    //infowindow.open(map, myMarker);

   // $("#loaderIcon").hide();
  },
  error:function (){}
  });
}


function fetchuserlocation(){

var username = document.getElementById('userdiv').value;

jQuery.ajax({
  url: "getuserlocation.php",
  data:"username="+username,
  type: "POST",
  success:function(data){
    var stringdata = data.split(",");
    var longi = stringdata[1];
    var lati = stringdata[2];
    var nlongi = stringdata[3].trim();
    var nlati = stringdata[4].trim();
    var slongi = stringdata[5].trim();
    var slati = stringdata[6].trim();

    var map = new google.maps.Map(document.getElementById('usermap'), {
                    zoom: 13,
                    center: new google.maps.LatLng(longi,lati),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

    var useeLocation = new google.maps.Marker({
                    position: new google.maps.LatLng(longi, lati),
                    map: map,
                    draggable: true
                });
    var boundobj = new google.maps.LatLngBounds(new google.maps.LatLng(nlongi,slati),new google.maps.LatLng(slongi,nlati));

    var rectangle = new google.maps.Rectangle({
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
    map: map,
    bounds: boundobj
  });

  },
  error:function (){}
  });

}

function fetchcombodata(visibility) {
  //$("#loaderIcon").show();
  visibility = visibility.trim();
  var username = $("#userdiv").val();
  $("#errordiv").html("");
  $("#refcombo").html("");
  $("#errordiv").css({"display":"none","color":"inherit"});
  if(visibility == 'Block'){
     $("#refcombo").prop('disabled', true);
  }
  else if(visibility == 'Neighbourhood'){
     $("#refcombo").prop('disabled', true);
  }
  else if(visibility == 'Friend'){
    jQuery.ajax({
    url: "getcombodata.php",
    data:'visibility='+visibility+'&username='+username,
    type: "POST",
    success:function(data){
      if(data==""){
        $("#refcombo").prop('disabled', true);
        $("#errordiv").html("You don't have any friends yet!");
        $("#errordiv").css({"display":"block","color":"red"});
      }
      else{
        $("#refcombo").html(data);
        $("#refcombo").prop('disabled', false);
        $("#refcombo").prop('required',true);
      }
     // $("#loaderIcon").hide();
    },
    error:function (){}
    });
    
  }
  else{
    jQuery.ajax({
    url: "getcombodata.php",
    data:'visibility='+visibility+'&username='+username,
    type: "POST",
    success:function(data){
      if(data==""){
        $("#refcombo").prop('disabled', true);
        $("#errordiv").html("You don't have any Neighbours yet!");
        $("#errordiv").css({"display":"block","color":"red"});
      }
      else{
        $("#refcombo").html(data);
        $("#refcombo").prop('disabled', false);
        $("#refcombo").prop('required',true);
      }
    },
    error:function (){}
    });
  }
}

function validatemessagecombo(){
  var visibilitycombo = $("#visibilitycombo").val();
  if((visibilitycombo == 'Friend' || visibilitycombo=='Neighbour') && $("#refcombo").prop("disabled")){
    $("#errordiv").html("Please choose Block/Neighbourhood as you currently do not have any Friends/Neighbours. ");
     $("#errordiv").css({"display":"block","color":"red"});
     return false;
  }
  else{
    $("#errordiv").html("");
    $("#errordiv").css({"display":"none","color":"inherit"});
    return true;
  }

}