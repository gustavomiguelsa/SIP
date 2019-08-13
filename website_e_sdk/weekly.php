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






	$getobj = $client->scan(array(
		'TableName' => 'Mensal',
		'ScanIndexForward' => true,       
	));

	for ($x = 0; $x < 4; $x++) {

		$id = $getobj['Items'][$x]['ID']['N'];
		$week[$id-1] = $getobj['Items'][$x]['Carros']['N'];
	}





	//Definir tamanho maximo da barra:
	$max_width = 650;
	

	//Soma de todos os dias, para fazer grafico adaptavel
	$sum = 0;
	for($i = 0; $i < 4; $i++){

		$sum = $sum + $week[$i];
	}

	if($sum == 0){
		$sum = 1;
	}

	$week1 = ($week[0]/$sum) * $max_width;
	$week2 = ($week[1]/$sum) * $max_width;
	$week3 = ($week[2]/$sum) * $max_width;
	$week4 = ($week[3]/$sum) * $max_width;




	echo "<h1 style='position: fixed; left:380px; top: 80px;'> 4-Week Month Stats </h1>";

	echo "<img src='line.png' width='820' height='5' style='position: absolute; top: 90px; left: 40px;'>";
	echo "<img src='line.png' width='820' height='5' style='position: absolute; top: 430px; left: 40px;'>";

	echo "<table>";
	
		echo "<tr style='position: absolute; left: 40px; top:100px;'>";
			echo "<td><i> Week 1 </i></td>";
			echo "<td><img src='bluebar.png' width='$week1' height='50' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week1+130) . "px;'><b>" . $week[0] . "</b></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week1+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

		echo "<tr style='position: absolute; left: 40px; top:190px;'>";
			echo "<td><i> Week 2 </i></td>";
			echo "<td><img src='bluebar.png' width='$week2' height='50' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week2+130) . "px;'><b>" . $week[1] . "</b></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week2+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:280px;'>";
			echo "<td><i> Week 3 </i></td>";
			echo "<td><img src='bluebar.png' width='$week3' height='50' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week3+130) . "px;'><b>" . $week[2] . "</b></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week3+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:370px;'>";
			echo "<td><i> Week 4 </i></td>";
			echo "<td><img src='bluebar.png' width='$week4' height='50' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week4+130) . "px;'><b>" . $week[3] . "</b></td>";
			echo "<td style=' position: absolute; top: 10px; left:" . ($week4+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

	echo "</table>";








?>

