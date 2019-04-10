
        <div class="col-lg-12">
            <div class="row">
                <div id="myMap" style="height: 500px;width: 100%;"></div>
            </div>
            <div class="row">
                 <div class="col-lg-12">
                     <div class="col-lg-2 control-label">
                        <label>
                            Address
                        </label>
                     </div>
                     <div class="col-lg-7 control-label">
                        <input id="address" class="form-control"  type="text" readonly>
                     </div>
                </div>
            </div>
            <div class="row">
                 <div class="col-lg-12">
                     <div class="col-lg-2 control-label">
                        <label>
                            Latitude
                        </label>
                     </div>
                     <div class="col-lg-7 control-label">
                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude">  
                     </div>
                </div>
            </div>
            <div class="row">
                 <div class="col-lg-12">
                     <div class="col-lg-2 control-label">
                        <label>
                            Longitude
                        </label>
                     </div>
                     <div class="col-lg-7 control-label">
                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude">
                     </div>
                </div>
            </div>
        </div>

<?PHP $GoogleMAP_Key = 'AIzaSyAGNN4oZDw83VPSHbkHGhz0hN3U3KTFCoQ'; ?>
        <script src="https://maps.googleapis.com/maps/api/js?key={{$GoogleMAP_Key}}&v=3.exp"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript"> 
             
            var latitude = {{ isset($latitude) ? $latitude :"13.7505082" }};
            var longitude = {{ isset($longitude) ? $longitude :"100.4929267" }};

            if( latitude == 0 && longitude == 0){
                latitude = '13.7505082';
                longitude = '100.4929267';
            }
            
            var map;
            var marker;
            var myLatlng = new google.maps.LatLng(latitude,longitude);
            var geocoder = new google.maps.Geocoder();
            var infowindow = new google.maps.InfoWindow();
            function initialize(){
                var mapOptions = {
                    zoom: 18,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
		       
                map = new google.maps.Map(document.getElementById("myMap"), mapOptions);
                
                marker = new google.maps.Marker({
                    map: map,
                    position: myLatlng,
                    draggable: true 
                });     
                
                geocoder.geocode({'latLng': myLatlng }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            $('#address').val(results[0].formatted_address);
                            $('#latitude').val(marker.getPosition().lat());
                            $('#longitude').val(marker.getPosition().lng());
                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        }
                    }
                });

                               
                google.maps.event.addListener(marker, 'dragend', function() {

                geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            $('#address').val(results[0].formatted_address);
                            $('#latitude').val(marker.getPosition().lat());
                            $('#longitude').val(marker.getPosition().lng());
                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        }
                    }
                });
            });


                // google.maps.event.addDomListener( $("#latitude") , 'keyup', function() {
                //     console.log('x');
                //     initialize();
                // });


            
            }
            

         $("#latitude").on('keyup',function(){
                latitude = $("#latitude").val();
                myLatlng = new google.maps.LatLng(latitude,longitude);
                initialize();
         });

          $("#longitude").on('keyup',function(){
                longitude = $("#longitude").val();
                myLatlng = new google.maps.LatLng(longitude,longitude);
                initialize();
         });

            google.maps.event.addDomListener(window, 'load', initialize);
        </script>  

    

