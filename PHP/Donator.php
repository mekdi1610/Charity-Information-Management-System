<?php
include_once 'Connection.php';
include_once 'Contributor.php';
class Donator extends Contributor
{
	public $con = "";
	public function __construct()
	{
		//Call the Connection class
		$conObj = new Connection();
		$this->con = $conObj->connect();
	}
	// destructor  
	function __destruct()
	{
	}
	//Generate ID for each Donator
	public function generateID()
	{
		$donID = 'Don-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Donator`");
		while ($row = $stmt->fetch()) {
			$donID .= $row[0] + 1;
		}
		//Check if the donator ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `DonID` FROM `Donator` WHERE `DonID` = ?), 'Not Found')");
		$stmt->execute([$donID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $donID;
			}
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt = $this->con->query("SELECT `DonID` from `Donator`");
				$i = 1;
				while ($row = $stmt->fetch()) {
					$noExp = explode("-", $row[0]);
					$no = $noExp[1];
					//Determine the missing ID
					if ($no == $i) {
						$i++;
					}
					//Make the missing ID the current ID to fill the gap
					else {
						$donID = 'Don-' . $i;
						return $donID;
					}
				}
			}
		}
	}
	//Register donator
	public function registerDonator($empID)
	{
		//Get information from the extended class Contributor
		$donID = $this->getConID();
		$fullName = $this->getFullName();
		$gender = $this->getGender();
		$phoneNo = $this->getPhoneNo();
		$email = $this->getEmail();
		$address = $this->getAddress();
		$occupation = $this->getOccupation();
		$workPlace = $this->getWorkPlace();
		$sql = "INSERT INTO `donator`(`DonID`, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `EmpID`) VALUES (?,?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$donID, $fullName, $gender, $phoneNo, $email, $address, $occupation, $workPlace, $empID]);
		if ($result) {
			echo "<script>alert('Registered Successfully! We will contact you for further events and projects.Thank you!');
				document.location = '/Register Contributor.php' </script>";
		} else {
			echo "<script>alert('Registration Failed!');
				document.location = '/Register Contributor.php' </script>";
		}
	}
}
