<?php

	// Expects input as JSON in the format {"userID":"", "Name":""}
	
	$inData = getRequestInfo();
	
	$searchResult = 0;
	$searchCount = 0;

	$conn = new mysqli("localhost", "copcom_admin", "Paul4Change!", "copcom_Contacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "SELECT * FROM Contacts WHERE userID=" . $inData["userID"] . " AND Name='" . $inData["Name"] . "'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				if( $searchCount > 0 )
				{
					$searchResults .= ",";
				}
				$searchCount++;
				$searchResults .= '"' . $row["Name"] . '"' . 'Phone:' . $row["phone"];
			}
		}
		else
		{
			returnWithError( "No Records Found" );
		}
		$conn->close();
	}
	
	returnWithInfo( $searchResults );
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		//$retValue = '{"id":0,"Name:":"","phone":"","email":", "userID":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>