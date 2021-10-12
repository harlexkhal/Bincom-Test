<?php
     header("Access-Control-Allow-Origin: *");
     session_start();

     require_once '../Dependencies/Core.php';
     header("Content-Type: application/json; charset=UTF-8");
   
	  //Fetches lists of LGAs and passed to client to enable selection of any one of the LGAs available
	 $Result = $Connection->query("SELECT * FROM lga");
	 $Data = array();
	 $FillIndex = 0;
	  
	for($Count = 0; $Count < $Result->num_rows ; ++$Count)
	{
		    $Row = $Result->fetch_array(MYSQLI_ASSOC);
				$Data[$FillIndex] = array('Name'=>$Row['lga_name'] , 'UniqueID'=>$Row['lga_id']);
            
		    $FillIndex++;
	}
	echo json_encode($Data);
?>