      var map;
      var geocoder;
      var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };

      function initialize() {
var myOptions = {
                center: new google.maps.LatLng(40.627600, -74.031558 ),
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });

            var marker;
            function placeMarker(location) {
                if(marker){ //on vérifie si le marqueur existe
                    marker.setPosition(location); //on change sa position
                }else{
                    marker = new google.maps.Marker({ //on créé le marqueur
                        position: location, 
                        map: map
                    });
                }
                document.getElementById('lat').value=location.lat();
                document.getElementById('lng').value=location.lng();
                getAddress(location);
            }

      function getAddress(latLng) {
        geocoder.geocode( {'latLng': latLng},
          function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
              if(results[0]) {
                var address = results[0].formatted_address.split(",");
                var streetaddress = address[0].split(" ");
                var bno = streetaddress[0];
                var streetname = streetaddress[1];
                var zipaddress = address[2].split(" ");
                var zip = zipaddress[2];
                document.getElementById("bno").value = bno;
                document.getElementById("street").value = streetname;
                document.getElementById("zip").value = zip;
              }
              else {
                document.getElementById("address").value = "No results";
              }
            }
            else {
              document.getElementById("address").value = status;
            }
          });
        }
      }
      google.maps.event.addDomListener(window, 'load', initialize);


function checkAvailability() {
  $("#loaderIcon").show();
  jQuery.ajax({
  url: "checkusername.php",
  data:'username='+$("#username").val(),
  type: "POST",
  success:function(data){
    $("#user-availability-status").html(data);
    $("#loaderIcon").hide();
  },
  error:function (){}
  });
}



function validate_register(){
  //alert("I am here");
  var alertstring="";
  var flag = "false";
  var userflag = "false";
  if($("#user-availability-status").children().html() == " Username Not Available."){
      alertstring = alertstring+"Please choose avaialable username\n";
      userflag ="true";
      flag ="true";
  }
  if($("#zip").val().length == 0){
    alertstring = alertstring+"Please select your address on the map!";
    flag = "true";
  }
  if(userflag == "true"){
    document.getElementById("username").focus();
  }
  if(userflag == "true" || flag == "true"){
    alert(alertstring);
    return false;
  }
  return true;
}


