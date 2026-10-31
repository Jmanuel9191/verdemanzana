<?php
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use yii\helpers\Url;

$configuracion = \app\models\Configuracion::find()->one();

$coord = explode(',', $coord);
if (count($coord) == 2) {
    $lat = floatval(trim($coord[0]));
    $lng = floatval(trim($coord[1]));
    $coord = new LatLng(['lat' => $lat, 'lng' => $lng]);
}

if (count($location) == 1) {
    $location = explode(',', $location);
    if (count($location) == 2) {
        $location = new LatLng(['lat' => $location[0], 'lng' => $location[1]]);
    }
    else
        $location= $coord;
}


$map = new Map([
    'center' => $coord,
    'zoom' => 16,
    'width' => '100%',
    'height' => '455',
]);

//$waypoints = new LatLng(['lat' => $dir[0], 'lng' => $dir[1]]);
$origin = new LatLng($location);
if(empty($location['lat']))
    $origin = $coord;

// setup just one waypoint (Google allows a max of 8)

$directionsRequest = new \dosamigos\google\maps\services\DirectionsRequest([
    'origin' => $origin,
    'destination' => $coord,
    //'waypoints' => $waypoints,
    'travelMode' => \dosamigos\google\maps\services\TravelMode::DRIVING
]);

// Lets configure the polyline that renders the direction
$polylineOptions = new \dosamigos\google\maps\overlays\PolygonOptions([
    'strokeColor' => '#FFAA00',
    'draggable' => true
]);

// Now the renderer
$directionsRenderer = new \dosamigos\google\maps\services\DirectionsRenderer([
    'map' => $map->getName(),
    'polylineOptions' => $polylineOptions
]);

// Finally the directions service
$directionsService = new \dosamigos\google\maps\services\DirectionsService([
    'directionsRenderer' => $directionsRenderer,
    'directionsRequest' => $directionsRequest
]);

// Thats it, append the resulting script to the map
$map->appendScript($directionsService->getJs());

// Lets add a marker now
$marker = new \dosamigos\google\maps\overlays\Marker([
    'position' => $coord,
    'title' => $configuracion['nombre_empresa']
]);
$marker->attachInfoWindow(
    new \dosamigos\google\maps\overlays\InfoWindow([
        'content' => "<p>{$configuracion['nombre_empresa']}</p>"
    ])
);

// Add marker to the map
$map->addOverlay($marker);


$relativeHomeUrl = Url::base(true).'/mapa?target=1';
$relativeHomeUrl2 = Url::base(true).'/mapa?target=2';
$relativeHomeUrl3 = Url::base(true).'/mapa?target=3';

?>



<div class="container" align="center">
    <br>
    <div class="bg_bienve">
        <div class="box_inter_bienve">
            <h2 class="txt-h2 text-center">Ubicación</h2>

         

            <img src="<?= Url::to('@web/web/images/texto4.png') ?>" class="img-responsive mov5">
        </div>
    </div>

</div>
<div class="bgnovedades2" style="height: 2px!important;"></div>
<?php echo $map->display();?>

