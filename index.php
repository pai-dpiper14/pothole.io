<style>
#map_container{ 
  width: 1067px;
  height: 100%;
  float: left;
}
#map_canvas{
  width: 90%;
  height: 97%;
  margin-left:40px;
  border: 1px solid darkgrey;
}
</style>
<div id="map_container">
  <div id="map_canvas"></div>
</div>

<input type="hidden" name="place_id" id="place_id"/>

<p>
<label for="place">Type</label>
<input type="text" name="n_place" id="n_place"/>  
</p>

<p>
<label for="description">Comment</label>
<input type="text" name="n_description" id="n_description"/>  
</p>

<p> 
<input type="button" id="btn_save" value="save place"/>
<input type="button" id="plot_marker" value="plot marker"/>  
</p>
 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>             
<script type="text/javascript"
  src="http://maps.googleapis.com/maps/api/js?key=AIzaSyD9uiyFbXfbSOsY6umTUtl4_rvl_LkQKxE&sensor=true&libraries=places">
</script>
<script src="https://google-maps-utility-library-v3.googlecode.com/svn/trunk/geolocationmarker/src/geolocationmarker-compiled.js"></script>
  <script>
    var lat = 12.97196; //default latitude
    var lng = 77.59410; //default longitude
    
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow();
  
    makeRequest('get_locations.php', function(data) {
        var data = JSON.parse(data.responseText);
        //console.log(data);
        for (var i = 0; i < data.length; i++) {
            displayLocation(data[i]);
        }
    });

    
    var myOptions = {
      center: new google.maps.LatLng(lat, lng), //set map center
      zoom: 17, //set zoom level to 17
      mapTypeId: google.maps.MapTypeId.ROADMAP //set map type to road map
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"),
        myOptions); //initialize the map

    var GeoMarker = new GeolocationMarker(map);
    GeoMarker.setCircleOptions({fillColor: '#808080'});

    google.maps.event.addListenerOnce(GeoMarker, 'position_changed', function() {
      map.setCenter(this.getPosition());
      map.fitBounds(this.getBounds());
    });


    google.maps.event.addListener(GeoMarker, 'geolocation_error', function(e) {
      alert('There was an error obtaining your position. Message: ' + e.message);
    });

    GeoMarker.setMap(map);    
    




    $('#form').submit(function(){
      var place   = $.trim($('#n_place').val());
      var description = $.trim($('#n_description').val());
      var lat = GeoMarker.getPosition().lat();
      var lng = GeoMarker.getPosition().lng();
      
      $.post('save_place.php', {'place' : place, 'description' : description, 'lat' : lat, 'lng' : lng}, 
        function(data){
          location.reload();
          //var place_id = data;
          //var new_option = $('<option>').attr({'data-id' : place_id, 'data-place' : place, 'data-lat' : lat, 'data-lng' : lng, 'data-description' : description}).text(place);
          //new_option.appendTo($('#saved_places'));
        }
      );
      
      $('input[type=text], input[type=hidden]').val('');
    });

    function makeRequest(url, callback) {
        var request;
        if (window.XMLHttpRequest) {
            request = new XMLHttpRequest(); // IE7+, Firefox, Chrome, Opera, Safari
        } else {
            request = new ActiveXObject("Microsoft.XMLHTTP"); // IE6, IE5
        }
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                callback(request);
            }
        }
        request.open("GET", url, true);
        request.send();
    }

    function displayLocation(location) {
     
        var content =   '<div class="infoWindow"><strong>'  + location.place + '</strong>'
                        + '<br/>'     + location.description + '</div>';

        if (parseInt(location.lat) == 0) {
            geocoder.geocode( { 'address': location.address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                     
                    var marker = new google.maps.Marker({
                        map: map, 
                        position: results[0].geometry.location,
                        title: location.name
                    });
                     
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(content);
                        infowindow.open(map,marker);
                    });
                     
                    /* Save geocoding result to the Database
                    var url =   'set_coords.php?id=' + location.id 
                                + '&lat=' + results[0].geometry.location.lat() 
                                + '&lon=' + results[0].geometry.location.lng();
                     
                    makeRequest(url, function(data) {
                        if (data.responseText == 'OK') {
                            // Success
                        }
                    });*/
                }
            });
        } else {
             
            var position = new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lng));
            var marker = new google.maps.Marker({
                map: map, 
                position: position,
                title: location.name
            });
             
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(content);
                infowindow.open(map,marker);
            });
        }
    }
</script>