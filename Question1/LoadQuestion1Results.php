<?php
     header("Access-Control-Allow-Origin: *");
     session_start();

     require_once '../Dependencies/Core.php';
     header("Content-Type: application/json; charset=UTF-8");
     $ID = json_decode($_POST["x"], false); //Get data from client

	 //get results recorded by this polling unit passed from client
	 $Result = $Connection->query("SELECT * FROM announced_pu_results WHERE polling_unit_uniqueid = $ID->UniqueID");

	 $Data = array();
	 $FillIndex = 0;

	//Organising data to be passed back to client
	for($Count = 0; $Count < $Result->num_rows ; ++$Count)
	{
		    $Row = $Result->fetch_array(MYSQLI_ASSOC);
				$Data[$FillIndex] = array('Party'=>$Row['party_abbreviation'] , 'Score'=>$Row['party_score']);
            
		    $FillIndex++;
	}

	//pass data back to client
	echo json_encode($Data);
?>