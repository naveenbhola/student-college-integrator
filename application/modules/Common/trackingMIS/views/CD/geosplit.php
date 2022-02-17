
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Unique Users <small>Top 20 Cities geo-presentation</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="dashboard-widget-content">
                                            <div id="world-map-gdp" class="col-md-8 col-sm-12 col-xs-12" style="height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
</div>


<script>
        
var mapData = JSON.parse('<?php echo $geosplit;?>');
var flag=0;
indiaMap = new jvm.Map({
    container: $('#world-map-gdp'),
    map: 'in_mill',       
    normalizeFunction: 'polynomial',        
    markerStyle: {
        initial: {
            fill: '#F8E23B',
            stroke: '#383f47'
        }
    },
    backgroundColor: '#383f47',
    markers: [],
    series: {
        markers: [{
            attribute: 'image',
            scale: {
                'mrk': 'marker.png'
            },
            values: [],
            }]
    }
}); 
//var hello = $('#world-map-gdp').vectorMap('get', 'mapObject');
var mapMarkers = [];
var mapMarkersValues = [];
mapMarkers.length = 0;
    mapMarkersValues.length = 0;
    j = 21;
for(i in mapData)
{
    //var mapLatLong = mapData[i]['latLng'];
    //alert(mapData[i]['latLng']);
    mapMarkers.push({name:"'"+mapData[i]['name']+"'",latLng: [mapData[i]['lat'],mapData[i]['long']],style: { r:j--}});
    //console.log({name:mapData[i]['name'],latLng: mapData[i]['latLng']});
    //mapMarkersValues.push('mrk');
}     
    indiaMap.addMarkers(mapMarkers, []);

    //hello.series.markers[0].setValues(mapMarkersValues); 
    </script>