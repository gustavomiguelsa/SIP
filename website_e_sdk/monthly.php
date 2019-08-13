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
		'TableName' => 'Anual',
		'ScanIndexForward' => true,       
	));

	for ($x = 0; $x < 12; $x++) {

		$id = $getobj['Items'][$x]['ID']['N'];
		$month[$id-1] = $getobj['Items'][$x]['Carros']['N'];
	}




	//Definir tamanho maximo da barra:
	$max_width = 650;
	

	//Soma de todos os dias, para fazer grafico adaptavel
	$sum = 0;
	for($i = 0; $i < 12; $i++){

		$sum = $sum + $month[$i];
	}

	if($sum == 0){
		$sum = 1;
	}

	$month1 = ($month[0]/$sum) * $max_width;
	$month2 = ($month[1]/$sum) * $max_width;
	$month3 = ($month[2]/$sum) * $max_width;
	$month4 = ($month[3]/$sum) * $max_width;
	$month5 = ($month[4]/$sum) * $max_width;
	$month6 = ($month[5]/$sum) * $max_width;
	$month7 = ($month[6]/$sum) * $max_width;
	$month8 = ($month[7]/$sum) * $max_width;
	$month9 = ($month[8]/$sum) * $max_width;
	$month10 = ($month[9]/$sum) * $max_width;
	$month11 = ($month[10]/$sum) * $max_width;
	$month12 = ($month[11]/$sum) * $max_width;




	echo "<h1 style='position: fixed; left:380px; top: 80px;'> 12-Month Year Stats </h1>";

	echo "<img src='line.png' width='820' height='5' style='position: absolute; top: 80px; left: 40px;'>";
	echo "<img src='line.png' width='820' height='5' style='position: absolute; top: 447px; left: 40px;'>";

	echo "<table>";
	
		echo "<tr style='position: absolute; left: 40px; top:90px;'>";
			echo "<td><i> Month 1 </i></td>";
			echo "<td><img src='bluebar.png' width='$month1' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month1+130) . "px;'><b>" . $month[0] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month1+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

		echo "<tr style='position: absolute; left: 40px; top:120px;'>";
			echo "<td><i> Month 2 </i></td>";
			echo "<td><img src='bluebar.png' width='$month2' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month2+130) . "px;'><b>" . $month[1] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month2+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:150px;'>";
			echo "<td><i> Month 3 </i></td>";
			echo "<td><img src='bluebar.png' width='$month3' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month3+130) . "px;'><b>" . $month[2] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month3+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:180px;'>";
			echo "<td><i> Month 4 </i></td>";
			echo "<td><img src='bluebar.png' width='$month4' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month4+130) . "px;'><b>" . $month[3] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month4+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

		echo "<tr style='position: absolute; left: 40px; top:210px;'>";
			echo "<td><i> Month 5 </i></td>";
			echo "<td><img src='bluebar.png' width='$month5' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month5+130) . "px;'><b>" . $month[4] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month5+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

		echo "<tr style='position: absolute; left: 40px; top:240px;'>";
			echo "<td><i> Month 6 </i></td>";
			echo "<td><img src='bluebar.png' width='$month6' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month6+130) . "px;'><b>" . $month[5] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month6+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:270px;'>";
			echo "<td><i> Month 7 </i></td>";
			echo "<td><img src='bluebar.png' width='$month7' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month7+130) . "px;'><b>" . $month[6] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month7+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:300px;'>";
			echo "<td><i> Month 8 </i></td>";
			echo "<td><img src='bluebar.png' width='$month8' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month8+130) . "px;'><b>" . $month[7] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month8+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

		echo "<tr style='position: absolute; left: 40px; top:330px;'>";
			echo "<td><i> Month 9 </i></td>";
			echo "<td><img src='bluebar.png' width='$month9' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month9+130) . "px;'><b>" . $month[8] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month9+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

		echo "<tr style='position: absolute; left: 40px; top:360px;'>";
			echo "<td><i> Month 10 </i></td>";
			echo "<td><img src='bluebar.png' width='$month10' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month10+130) . "px;'><b>" . $month[9] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month10+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:390px;'>";
			echo "<td><i> Month 11 </i></td>";
			echo "<td><img src='bluebar.png' width='$month11' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month11+130) . "px;'><b>" . $month[10] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month11+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";
		
		echo "<tr style='position: absolute; left: 40px; top:420px;'>";
			echo "<td><i> Month 12 </i></td>";
			echo "<td><img src='bluebar.png' width='$month12' height='15' border='5' style='border-radius:8px; position: absolute; top: 0px; left: 120px;'></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month12+130) . "px;'><b>" . $month[11] . "</b></td>";
			echo "<td style=' position: absolute; top: -5px; left:" . ($month12+170) . "px;'><img src='car_right.png' width='40' height='20'></td>";
		echo "</tr>";

	echo "</table>";





?>

