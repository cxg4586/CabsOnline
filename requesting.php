<?php
	// Created and Developed by Vidyut Kodikal, 14881081
	//File for Requesting the Booking of a Taxi
	// To Set the time zone
	date_default_timezone_set("Pacific/Auckland");
	
	// To Get the pickup date
	$pickupDate = $_POST["pickupDate"];
	
	// To Set up the pickup date, time and +2hour Info
	$pickupDate = date("Y-m-d");
	$pickupTime = date("H:i:s");
	$timeX2 = date("H:i:s", strtotime("+2 hours"));
	require_once("settings.php");
			
	// The @ operator helps suppress the appearance of any error messages
	// mysqli_connect returns false if connection failed, otherwise a connection value
	$conn = @mysqli_connect($host,
		$user,
		$pswd,
		$dbnm);
	
	// Checks whether the connection is successful				
	if (!$conn) {
		// Displays an error message
		echo "<p>Database connection failure</p>";
	} else {
		// After connection is successful
		$queryCreateTable = "CREATE TABLE `bookings` (`bookingNumber` INT(11) NOT NULL AUTO_INCREMENT,`bookingDate` DATE NOT NULL,`bookingTime` TIME NOT NULL,`firstName` VARCHAR(255) NOT NULL,`lastName` VARCHAR(255) NOT NULL,`phoneNumber` INT(65) NOT NULL,`unitNumber` VARCHAR(255) NULL DEFAULT NULL,`streetNumber` INT(6) NOT NULL,`streetName` VARCHAR(255) NOT NULL,`suburb` VARCHAR(255) NOT NULL,`destinationSuburb` VARCHAR(255) NOT NULL,`pickupDate` DATE NOT NULL,`pickupTime` TIME NOT NULL,`genStatus` VARCHAR(255) NOT NULL,PRIMARY KEY (`bookingNumber`))COLLATE='utf8_general_ci' ENGINE=InnoDB";
		mysqli_query($conn, $queryCreateTable);
		// To Select all the information from the table
		$query = "Select bookingNumber, firstName, lastName, phoneNumber, suburb, destinationSuburb, pickupDate, pickupTime From bookings Where genStatus = 'unassigned' AND pickupDate = '$pickupDate' AND pickupTime BETWEEN '$pickupTime' AND '$timeX2'";
	
		// To Execute the query and store the result into the result pointer
		$result = mysqli_query($conn, $query);
		if(!$result) {
			echo "Error with database query";
		}else {
			$rowCount = mysqli_num_rows($result);

			// This will create the table which will then display the output of the query
			if($rowCount === 0) {
				echo "No results to display";
			} else {
				echo "<table border='1' style='text-align:center;' class='center'>
					<thead>
						<tr style ='color: black;'>
							<th>
								Booking Number
							</th>
							<th>
								Customer First Name
							</th>
							<th>
								Customer Last Name
							</th>
							<th>
								Customer Phone
							</th>
							<th>
								Pick Up Suburb
							</th>
							<th>
								Destination Suburb
							</th>
							<th>
								Pick Up Date
							</th>
							<th>
								Pick Up Time
							</th>
						</tr>
					</thead>
					<tbody>";

					// If the query works, then it shall find the information relevant to the query and show that it exists
					if($result)	{
						while($row = mysqli_fetch_array($result))	{
								echo "<tr>";
									for($i = 0; $i < 8; $i++)	{
										echo "<td style='text-align:center; max-width: 350px; word-wrap: break-word;'>";
										echo "<p>".$row[$i]."</p>";
									}
								echo "</td></tr>";	
							}
							mysqli_free_result($result);
						}
							mysqli_close($conn);
					echo"</tbody></table>";
			}
		}
	}
?>