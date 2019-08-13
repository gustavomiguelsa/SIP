<?php
	putenv('HOME=/home/gustavo.assuncao');	//Necessario para indicar onde está a hidden directory com as credenciais AWS
	require 'vendor/autoload.php'; // provided by AWS


	use Aws\DynamoDb\DynamoDbClient;
	use Aws\DynamoDb\Enum\Type;
	use Aws\DynamoDb\Enum\KEYType;
	use Aws\DynamoDb\Enum\AttributeAction;


	$client = DynamoDbClient::factory(array(
	    'profile' => 'default',
	    'region' => 'us-east-2',
	    'version' => '2012-08-10',
	    'credentials.cache' => true,
	    'validation' => false,
	    'scheme' => 'http'
	));



	try {
		/*
		// GET 1 ITEM EXAMPLE
		$response = $client->getItem(array(
		    'TableName' => 'ParkingZolTable',
		    'Key' => array( 'ID' => array( 'N' => '1950' ),
				'Timestamp' => array( 'N' => '22000' ),
				)
		));
		$value = $response['Item']['payload']['M']['Spot1']['S'];
		print_r($value);
		*/

 

		/*
		// GET SEVERAL ITEMS FROM TABLE - QUERY EXAMPLE
		$getobj = $client->query(array(
			'TableName' => 'ParkingZolTable',
			'KeyConditionExpression' => 'ID = :v_id',
			'ExpressionAttributeValues' =>  [
            			':v_id' => ['N' => '1950'],
			],
			'ScanIndexForward' => true,
			'Limit' => 3,             
		     )
		  );
		


		
		for ($x = 0; $x <= 2; $x++) {
			$timestamp = $getobj['Items'][$x]['Timestamp']['N'];
			echo "The Timestamp is: $timestamp <br>";
		} 
		*/



		/*
		// GET SEVERAL ITEMS FROM TABLE - SCAN EXAMPLE
		$getobj = $client->scan(array(
			'TableName' => 'ParkingZolTable',
			'ScanIndexForward' => true,           
		     )
		  );		



		//Aqui mete-se como maximo o total de elementos na tabela porque o scan vai buscar todos
		for ($x = 0; $x <= 9; $x++) {
			$timestamp = $getobj['Items'][$x]['Timestamp']['N'];
			echo "The Timestamp is: $timestamp <br>";
		} 
		*/		


		//UPDATE ITEM EXAMPLE
		/*$response = $client->updateItem(array(
			'TableName' => 'ParkingZolTable',
			'Key' => array(
				'ID' => array( 'N' => '5' ),
				'Timestamp' => array( 'N' => '34' )
			),
			'UpdateExpression' => 'set Humidity = :val',
			'ExpressionAttributeValues'=> [
            			':val' => ['N' => '23'],
			],
			'ReturnValues' => 'UPDATED_NEW'
		));
		print_r($response);
		*/





		$getobj = $client->scan(array(
			'TableName' => 'State',
			'ScanIndexForward' => true,       
		));

		for ($x = 0; $x < 6; $x++) {

			$id = $getobj['Items'][$x]['ID']['N'];
			$state_value[$id-1] = $getobj['Items'][$x]['Value']['N'];
		}


	} catch (Exception $e) {
		echo '<p>Exception received : ',  $e->getMessage(), "\n</p>";
	}



	echo "
		<h1 style='position:absolute; top:20px; left: 40px;'><i> Temperature: </i> &nbsp&nbsp " . $state_value[4] . "ºC</h1>
		<h1 style='position:absolute; top:20px; left: 490px;'><i> Humidity: </i> &nbsp&nbsp " . $state_value[5] . "% RH</h1>
		<img class='lines-pic' src='lines.png' alt='lines'>";
	

	if($state_value[0] == 0){

		$color1 = "green";
		
	} else {
		$color1 = "red";
		echo "<img class='car-1' src='car_right.png' alt='car1' width='220' height='110'>";
	}	
	
	if($state_value[1] == 0){

		$color2 = "green";
		
	} else {
		$color2 = "red";
		echo "<img class='car-2' src='car_right.png' alt='car2' width='220' height='110'>";
	}

	if($state_value[2] == 0){

		$color3 = "green";
		
	} else {
		$color3 = "red";
		echo "<img class='car-3' src='car_left.png' alt='car3' width='220' height='110'>";
	}

	if($state_value[3] == 0){

		$color4 = "green";
		
	} else {
		$color4 = "red";
		echo "<img class='car-4' src='car_left.png' alt='car4' width='220' height='110'>";
	}
	

	echo "
		<svg class='dots' width='9000' height='500'>
			<circle id='dot1' cx='43' cy='170' r='10' style='fill: " . $color1 . "; stroke: blue; stroke-width: 2' />
			<circle id='dot2' cx='43' cy='320' r='10' style='fill: " . $color2 . "; stroke: blue; stroke-width: 2' />
			<circle id='dot3' cx='847' cy='170' r='10' style='fill: " . $color3 . "; stroke: blue; stroke-width: 2' />
			<circle id='dot4' cx='847' cy='320' r='10' style='fill: " . $color4 . "; stroke: blue; stroke-width: 2' />
		</svg>
	";
?>

















