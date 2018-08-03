
<!DOCTYPE html>
<?php
session_start();
 global $con ;
 $con=mysqli_connect("localhost","mourad","password","my_db");
$_SESSION["con"]=$con;
 // Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else{
	// echo "Success" ;
	 $query ="SELECT * FROM my_db.markers;";
	 $_SESSION["markers-rows"]=  json_encode(exe($query)); 
  //echo $_SESSION["markers-rows"];
}
function exe($query){

		global $_sys;
		//$_sys['last_q']=$query;
		//echo $query;exit;	
		$con= $_SESSION["con"];
		//$con=mysqli_connect("localhost","mourad","logic151","my_db");
		$res = mysqli_query($con,$query) or die("Failed to connect to MySQL: " . mysqli_connect_error());
		$rows=array();
		//display the results 
		while ($row  = mysqli_fetch_assoc($res)) {
		   	$rows[]=$row;
		}
			
		return $rows ;
	}
?>
<html>
  <head>
    <style>
      /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
    </style>
  </head>
  <body>
    <h3>Smart Waste Boxes Demo</h3>
    <!--The div element for the map -->
    <div id="map"></div>
    <script>
// Initialize and add the map
function initMap() {
  // The location of Uluru, 'lat: 25.344, lng: 131.036?>"
   
  //{lat: 25.344, lng: 131.036}
 // var uluru = ;
 //var uluru = {lat: 25.344, lng: 131.036};
  <?php
  $json= json_decode($_SESSION["markers-rows"]);
  
  ?>
   var locations =  <?php echo json_encode( $json ) ?>;

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(-33.92, 151.25),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
        var row = locations[i];  
      if (row.type == 'filled' ){
        
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(row.lat, row.lng),
        map: map,
        animation: google.maps.Animation.BOUNCE
        
      })
        }else{
        	iconOptions = {
        		    path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
        		    scale: 9,
        		    strokeColor: 'black',
        		    strokeOpacity: 0.6,
        		    strokeWeight: 1.0,
        		    fillColor:'green',
        		    fillOpacity: 1
        		}
        		
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(row.lat, row.lng),
            map: map,
            animation: google.maps.Animation.DROP,
            path: google.maps.SymbolPath.CIRCLE,
            icon:iconOptions
             });
        }
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
        	var row = locations[i];
          infowindow.setContent(row.name);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUqcGGqSBW0DpIzDYIKxECQmmLqMKahxU&callback=initMap">
    </script>
  </body>
</html>
