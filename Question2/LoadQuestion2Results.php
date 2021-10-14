<?php
     header("Access-Control-Allow-Origin: *");
     session_start(); 
     require_once '../Dependencies/Core.php';
     header("Content-Type: application/json; charset=UTF-8");
	 error_reporting(E_ERROR | E_PARSE);
     $ID = json_decode($_POST["x"], false);

	 //Fetch polling units that are under one local government
	 $Result = $Connection->query("SELECT * FROM polling_unit WHERE lga_id = $ID->lga_id");

	 //Insert them in an array
	 $Collect_Polling_Units = array();
	 for($i = 0; $i < $Result->num_rows; ++$i)
	 {
	     $Row = $Result->fetch_array(MYSQLI_ASSOC);
	     $Collect_Polling_Units[$i] = $Row['uniqueid'];
	 }

	 //Section Party and VoteCount for each polling unit
	 $Party = array(array());
	 $VoteCount = array(array()); 
	 for($j = 0; $j < $Result->num_rows; ++$j)
	 {
	    $unique_id = $Collect_Polling_Units[$j];
	    $res = $Connection->query("SELECT * FROM announced_pu_results WHERE polling_unit_uniqueid = $unique_id");

		for($a = 0; $a < $res->num_rows; ++$a)
		{
		   $row = $res->fetch_array(MYSQLI_ASSOC);
		   $Party[$j][$a] = $row['party_abbreviation'];
		   $VoteCount[$j][$a] = $row['party_score'];
		}
	 }
	
	 //Fetch list of Parties
	 $AllParty = array();
	 $TrackCount = array();
	 $res = $Connection->query("SELECT * FROM party");
	 for($p = 0; $p < $res->num_rows; ++$p)
	 {
	    $row = $res->fetch_array(MYSQLI_ASSOC);
		$AllParty[$p] = $row['partyid'];
		$TrackCount[$p] = 0;
	 }

	 //Add all votes from each polling unit for each party
	 for($k = 0; $k < sizeof($AllParty); $k++)
	 {
	      if($Party[$k] == null)
		  continue;

		  for($w = 0; $w < sizeof($Party[$k]); $w++)
		  {
				if($AllParty[$w] == $Party[$k][$w])
				{
					$TrackCount[$w] += (int)$VoteCount[$k][$w];
				}
		  }
	 }

	 $Data = array();
	 $FillIndex = 0;
	 //Organise data for client
	 $Data = array('Parties'=>$AllParty , 'Scores'=>$TrackCount);

    //pass data to client
	echo json_encode($Data);
?>
