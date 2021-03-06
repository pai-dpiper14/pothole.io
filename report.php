<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title>Pothole.io</title>
  
  <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
  <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
  <script src="my.js"></script>
  
  <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyANXql3mdY9Fbi1mUrz2Inmb1IH7U2zzj4&sensor=true">
  </script>
  <script src="https://google-maps-utility-library-v3.googlecode.com/svn/trunk/geolocationmarker/src/geolocationmarker-compiled.js"></script>

  
  <script>
  
  </script>
    
  <script>
  google.maps.event.addDomListener(window, 'load', initialize);
  var map = null; 
  var GeoMarker = null;
  var geocoder = null;
  var infowindow = null;
  function initialize()
  {
    geocoder = new google.maps.Geocoder();
    infowindow = new google.maps.InfoWindow();
    var mapProp = {
      center:new google.maps.LatLng(12.97196, 77.59410),
      zoom:9,
      mapTypeId:google.maps.MapTypeId.ROADMAP
        };
  
    map = new google.maps.Map(document.getElementById("map-canvas"),mapProp);
  
    GeoMarker = new GeolocationMarker(map);
    GeoMarker.setCircleOptions({fillColor: '#808080'});

        google.maps.event.addListenerOnce(GeoMarker, 'position_changed', function() {
          map.setCenter(this.getPosition());
          map.fitBounds(this.getBounds());
      
        var loc = this.getPosition();
        var latitude = loc.lat();
        var longtitude = loc.lng();

        });

        google.maps.event.addListener(GeoMarker, 'geolocation_error', function(e) {
          alert('There was an error obtaining your position. Message: ' + e.message);
        });

        GeoMarker.setMap(map);  

    makeRequest('get_locations.php', function(data) {
        var data = JSON.parse(data.responseText);
        for (var i = 0; i < data.length; i++) {
            displayLocation(data[i]);
        }
    });
      
  }  
  
  
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


  $(document).ready(function () {
      $('#submit_button').click(function(){
        var place = $('input:radio[name=typeButton]:checked').val();
        //var place   = $.trim($('#n_place').val());
        var description = $.trim($('#comment_box').val());
        var lat = GeoMarker.getPosition().lat();
        var lng = GeoMarker.getPosition().lng();

        $.post('save_place.php', {'place' : place, 'description' : description, 'lat' : lat, 'lng' : lng}, 
          function(data){
            console.log(data);
            location.reload();
            //var place_id = data;
            //var new_option = $('<option>').attr({'data-id' : place_id, 'data-place' : place, 'data-lat' : lat, 'data-lng' : lng, 'data-description' : description}).text(place);
            //new_option.appendTo($('#saved_places'));
          }
        );
        return false;

      });
  });
  </script>

  <script type="text/javascript">
    navigator.geolocation.getCurrentPosition (function (pos)
    {
      var latitude = pos.coords.latitude;
      var longitude = pos.coords.longitude;
    });
  </script>
  <!-- User-generated css -->
  <style>
    #map-canvas {
        position:relative;
        width:90%;
      height:330px;
    }    
  </style>

  <!-- User-generated js -->
  <script>
  
  </script>
  

  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
  <link rel="stylesheet" href="my.css" />
  
</head>

<body>
  
<!-- Report -->

<div data-role="page" id="report">
 <div data-theme="a" data-role="header">
     <h3>
        <a href="index.html" data-transition="flip"> 
         PotHole.io
    </a>  
     </h3>
        
        <div data-role="navbar" data-iconpos="top">
            <ul>
                <li>
                    <a href="report.html" data-transition="flip" data-theme="b" data-icon="" class="ui-btn-active ui-state-persist">
                        Report
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div data-role="content">
        
        <div id="main_greeting">
            <p style="text-align: justify;" data-mce-style="text-align: justify;">
                <strong>
                    <span> <!-- style="text-decoration: underline;" data-mce-style="text-decoration: underline;" -->
                        <span face="trebuchet ms, geneva" size="2" style="font-family: 'trebuchet ms', geneva; font-size: medium;"
                        data-mce-style="font-family: 'trebuchet ms', geneva; font-size: medium;">
                            Find yourself on the map. Explore your surroundings.
                        </span>
                    </span>
                </strong>
            </p>
        </div>
        
    <center>
          <div style="width:100%; height:330px;">
            <div id="map-canvas"></div>
          </div>
      </center>
    
        <h3>
            File your report.
        </h3>
        <div id="main_greeting">
            <p style="text-align: justify;" data-mce-style="text-align: justify;">
                <strong>
                    <span style="text-decoration: underline;" data-mce-style="text-decoration: underline;">
                        <span face="trebuchet ms, geneva" size="2" style="font-family: 'trebuchet ms', geneva; font-size: medium;"
                        data-mce-style="font-family: 'trebuchet ms', geneva; font-size: medium;">
                            1. Upload a photo
                        </span>
                    </span>
                </strong>
            </p>
        </div>
        
        <strong>
        <label style="color:blue">Take a Photo</label>

      <!--<form enctype="multipart/form-data" action="" id="uploadForm" data-ajax="false" method="POST">-->
        <input type="hidden" name="MAX_FILE_SIZE" value="3000000000" />
        Select Picture/File To Upload: <input type="file" accept="image/*" capture="camera" name="userfile" onchange="getFileName(this.files)" id="file" />
        
      
 
      <script>
        function getFileName(fileName) {
        //  alert(fileName[0].name);
        //  alert(fileName[0].size);
  
        }
      </script>
        </strong>
        
        <div id="main_greeting">
            <p style="text-align: justify;" data-mce-style="text-align: justify;">
                <strong>
                    <span style="text-decoration: underline;" data-mce-style="text-decoration: underline;">
                        <span face="trebuchet ms, geneva" size="2" style="font-family: 'trebuchet ms', geneva; font-size: medium;"
                        data-mce-style="font-family: 'trebuchet ms', geneva; font-size: medium;">
                            2. Categorize your report
                        </span>
                    </span>
                </strong>
            </p>
        </div>
        <div id="report_choices" data-role="fieldcontain">
            <fieldset data-role="controlgroup" data-type="vertical" data-mini="true">
                <legend>
                    What are you reporting?
                </legend>
                <input name="typeButton" id="checkbox1" value="Pothole" type="radio">
                <label for="checkbox1">
                    Pothole
                </label>
                <input name="typeButton" id="checkbox2" value="Garbage" type="radio">
                <label for="checkbox2">
                    Garbage
                </label>
                <input name="typeButton" id="checkbox3" value="Footpath" type="radio">
                <label for="checkbox3">
                    Footpath
                </label>
            </fieldset>
        </div>
    
        <div id="main_greeting">
            <p style="text-align: justify;" data-mce-style="text-align: justify;">
                <strong>
                    <span style="text-decoration: underline;" data-mce-style="text-decoration: underline;">
                        <span face="trebuchet ms, geneva" size="2" style="font-family: 'trebuchet ms', geneva; font-size: medium;"
                        data-mce-style="font-family: 'trebuchet ms', geneva; font-size: medium;">
                            3. Comment
                        </span>
                    </span>
                </strong>
            </p>
        </div>
    
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup">
                <input name="comment_box" id="comment_box" placeholder="Tell us about your photo...."
                value="" type="text">
            </fieldset>
        </div>
        
        <input id="submit_button" type="submit" data-theme="b" data-icon="check"
        data-iconpos="left" value="Submit">
  </form> 
     
    </div>
    
    <div data-theme="a" data-role="footer">
        <h3>
        </h3>
        <div id="footer_text">
        </div>
    </div>
</div>

</body>
</html>