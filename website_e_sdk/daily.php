<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

?>

<script type="text/javascript">

	
</script>


<?php
	putenv('HOME=/home/gustavo.assuncao');	//Necessario para indicar onde está a hidden directory com as credenciais AWS
	require 'vendor/autoload.php'; // provided by AWS


	use Aws\DynamoDb\DynamoDbClient;

	$client = DynamoDbClient::factory(array(
	    'profile' => 'default',
	    'region' => 'us-east-2',
	    'version' => '2012-08-10',
	    'credentials.cache' => true,
	    'validation' => false,
	    'scheme' => 'http'
	));






	/*
	for ($x = 1; $x <= 7; $x++) {
		$getobj = $client->query(array(
			'TableName' => 'Semanal',
			'KeyConditionExpression' => 'ID = :v_id',
			'ExpressionAttributeValues' =>  [
	    			':v_id' => ['N' => (string)$x],
			],
			'ScanIndexForward' => true,
			'Limit' => 3,             
		     )
		  );	
		
		$dia[$x-1] = $getobj['Items']['0']['Carros']['N'];
	}
	*/



	$getobj = $client->scan(array(
		'TableName' => 'Semanal',
		'ScanIndexForward' => true,       
	));

	for ($x = 0; $x < 7; $x++) {

		$id = $getobj['Items'][$x]['ID']['N'];
		$dia[$id-1] = $getobj['Items'][$x]['Carros']['N'];
	}



	//Definir tamanho maximo da barra:
	$max_width = 650;
	

	//Soma de todos os dias, para fazer grafico adaptavel
	$sum = 0;
	for($i = 0; $i < 7; $i++){

		$sum = $sum + $dia[$i];
	}


	if($sum == 0){
		$sum = 1;
	}
	
	$seg = ($dia[0]/$sum) * $max_width;
	$ter = ($dia[1]/$sum) * $max_width;
	$qua = ($dia[2]/$sum) * $max_width;
	$qui = ($dia[3]/$sum) * $max_width;
	$sex = ($dia[4]/$sum) * $max_width;
	$sab = ($dia[5]/$sum) * $max_width;
	$dom = ($dia[6]/$sum) * $max_width;


	echo "<h1 style='position: fixed; left:380px; top: 80px;'> 7-Day Week Stats </h1>";

	echo "<img src='line.png' width='820' height='5' style='position: absolute; top: 90px; left: 40px;'>";
	echo "<img src='line.png' width='820' height='5' style='position: absolute; top: 440px; left: 40px;'>";

	echo "<table>";
	
		echo "<tr style='position: absolute; left: 40px; top:100px;'>";
			echo "<td><i> Day 1 </i></td>";
			echo "<td><img src='bluebar.png' width='$seg' height='30' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($seg+130) . "px;'><b>" . $dia[0] . "</b></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($seg+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

		echo "<tr style='position: absolute; left: 40px; top:150px;'>";
			echo "<td><i> Day 2 </i></td>";
			echo "<td><img src='bluebar.png' width='$ter' height='30' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($ter+130) . "px;'><b>" . $dia[1] . "</b></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($ter+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:200px;'>";
			echo "<td><i> Day 3 </i></td>";
			echo "<td><img src='bluebar.png' width='$qua' height='30' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($qua+130) . "px;'><b>" . $dia[2] . "</b></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($qua+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:250px;'>";
			echo "<td><i> Day 4 </i></td>";
			echo "<td><img src='bluebar.png' width='$qui' height='30' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($qui+130) . "px;'><b>" . $dia[3] . "</b></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($qui+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:300px;'>";
			echo "<td><i> Day 5 </i></td>";
			echo "<td><img src='bluebar.png' width='$sex' height='30' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($sex+130) . "px;'><b>" . $dia[4] . "</b></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($sex+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:350px;'>";
			echo "<td><i> Day 6 </i></td>";
			echo "<td><img src='bluebar.png' width='$sab' height='30' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($sab+130) . "px;'><b>" . $dia[5] . "</b></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($sab+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:400px;'>";
			echo "<td><i> Day 7 </i></td>";
			echo "<td><img src='bluebar.png' width='$dom' height='30' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($dom+130) . "px;'><b>" . $dia[6] . "</b></td>";
			echo "<td style=' position: absolute; top: 0px; left:" . ($dom+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

	echo "</table>";



?>

