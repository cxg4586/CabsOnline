<?php
	// Created and Developed by Vidyut Kodikal, 14881081
	// File for Assigning the Booking Number to the Taxi
	$bookingNum = $_POST["bookingNum"];
	require_once("settings.php");
	
	// The @ operator helps suppress any error messages that can possibly appear
	// mysqli_connect returns false if connection failed, otherwise a connection value
	$conn = @mysqli_connect($host,
		$user,
		$pswd,
		$dbnm);
	
	// To check whether the connection is successful				
	if (!$conn) {
		// To make an error message appear
		echo "<p>Database connection failure</p>";
	} else {
		// After connection is successful
		$queryCreateTable = "CREATE TABLE `bookings` (`bookingNumber` INT(11) NOT NULL AUTO_INCREMENT,`bookingDate` DATE NOT NULL,`bookingTime` TIME NOT NULL,`firstName` VARCHAR(255) NOT NULL,`lastName` VARCHAR(255) NOT NULL,`phoneNumber` INT(65) NOT NULL,`unitNumber` VARCHAR(255) NULL DEFAULT NULL,`streetNumber` INT(6) NOT NULL,`streetName` VARCHAR(255) NOT NULL,`suburb` VARCHAR(255) NOT NULL,`destinationSuburb` VARCHAR(255) NOT NULL,`pickupDate` DATE NOT NULL,`pickupTime` TIME NOT NULL,`genStatus` VARCHAR(255) NOT NULL,PRIMARY KEY (`bookingNumber`))COLLATE='utf8_general_ci' ENGINE=InnoDB";
		mysqli_query($conn, $queryCreateTable);
		// Selecting the booking number requested
		$query = "SELECT * FROM bookings WHERE bookingNumber = '$bookingNum'";
		
		// Executing the query and then store the result into the result pointer
		$result = mysqli_query($conn, $query);
		if(!$result) {
			// Display error message if database query does not execute
			echo "Error with database query 1";
		} else {
			// To check whether the number of rows are present within the database
			$rowCount = @mysqli_num_rows($result);
			
			// To check whether there are any inputs into the database if taxi already assigned
			if($rowCount > 0) {
				$query = "SELECT * FROM bookings WHERE bookingNumber = '$bookingNum' AND genStatus = 'assigned'";
				$result = mysqli_query($conn, $query);
				if(!$result) {
					// Error message appears if the MySQL query does not execute
					echo "Error with database query 2";
				} else {
					// Counts the number of rows
					$rowCount =@mysqli_num_rows($result);
					
					// Checks if there are inputs present and if the booking number has already been assigned
					if($rowCount > 0) {
						echo "This taxi has already been assigned.";
					} else {
						// Otherwise update the booking number to assigned
						$query = "UPDATE bookings SET genStatus = 'assigned' WHERE bookingNumber = $bookingNum";
						$result = mysqli_query($conn, $query);
						if(!$result) {
							// Error message appears if MySQL query does not execute
							echo "Error with database query 3";
						} else {
							// Otherwise state that the booking number has been assigned
							echo "The booking request $bookingNum has been properly assigned!";
						}
					} 
				}
			} else {
				// Otherwise the booking number was not found
				echo "Booking number not found";
			}
		}
	}
?>