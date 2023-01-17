<!-- Script leaflet -->
{literal}
<script type="text/javascript">
var curLocation=[0,0];
if(curLocation[0]==0 && curLocation[1]==0){
    curLocation=[-7.75977181147709, 110.40897296360752];
}

var map = L.map('mapid').setView([-7.75977181147709, 110.40897296360752], 15);
L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1
}).addTo(map);

map.attributionControl.setPrefix(false);

var marker = new L.marker(curLocation,{
    draggable:'true'
});

marker.on('dragend', function(event){
var position = marker.getLatLng();
marker.settLatLng(position,{
    draggable:'true'
}).bindPopup(position).update();
$("#Latitude").val(position.lat);
$("#Longitude").val(position.lng).keyup;
});

$("#Lalitude, Longitude").change(function(){
    var position=[parseInt($("#Latitude").val()), parseInt($("#Longitude").val())];
    marker.setLatLng(position,{
        draggable:'true'
    }).bindPopup(position).update();
    map.panTo(position);
});

map.addLayer(marker);
</script>
{/literal}
<!-- End Script leaflet -->