<?php use yii\helpers\Url;
$paquetes = \app\models\Productos::find()->where(['estado' => '1'])->all();
 ?>

    <option disabled selected value >Paquete a Reservar</option>
<?php foreach ($paquetes as $paquete): ?>
    <option value="<?php echo $paquete['titulo']?>"><?php echo $paquete['titulo']?></option>
<?php endforeach; ?>