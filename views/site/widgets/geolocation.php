<?php
$scrip = <<<JS
    function showLocation(url,target) {
        /*var pos = {
            lat: '',
            lng: ''
        };*/
         if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                    //pos = {lat: position.coords.latitude, lng: position.coords.longitude};
                    window.location = url+'?target='+target+'&lat='+position.coords.latitude+'&lng='+position.coords.longitude;
                    /*$.ajax({
                        method:'GET',
                        url: url,
                        data: pos,
                        cache   : false,
                        success:function(data){
                            $('#modalContent').html(data);
                            $('#modal').modal('show');
                        },
                    });*/

                }, function (error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                       alert("User denied the request for Geolocation.");
                       break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }

            });

         } else { 
            alert('Geolocation is not supported by this browser');
        }

    }
JS;
$this->registerJs($scrip, \yii\web\View::POS_HEAD);
?>

<?php
/*yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'size' => 'modal-lg',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
]);
echo "";
yii\bootstrap\Modal::end();*/
?>