<?php
include_once 'Connection.php';
include_once 'Contributor.php';

class Visitor extends Contributor
{
	public $con = "";
	private $typeOfVis;
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
	//Get and Set Methods 
	public function setTypeOfVis($typeOfVis)
	{
		$this->typeOfVis = $typeOfVis;
	}
	public function getTypeOfVis()
	{
		return $this->typeOfVis;
	}
	//Generate unique ID for each visitor
	public function generateID()
	{
		$fn = $this->getFullName();
		$fullName = explode(" ", $fn);
		//Seprate FullName into First, Middle and Last Name.
		$fName = $fullName[0];
		$mName = $fullName[1];
		$lName = $fullName[2];
		//Get the last 3 characters of the names.
		$FN = substr($fName, -3);
		$MN = substr($mName, -3);
		$LN = substr($lName, -3);
		$charForvisID = $FN . $MN . $LN . "12345";
		$charLenVisID = strlen($charForvisID);
		$visID = "Vis-";
		for ($i = 0; $i < 3; $i++) {
			$visID .= $charForvisID[rand(0, $charLenVisID - 1)];
		}
		//To check if the random username exists in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `VisID` FROM `Visitor` WHERE `VisID` = ?), 'Not Found')");
		$stmt->execute([$visID]);
		while ($row = $stmt->fetch()) {
			//If the VisID is not found then its a valid ID
			if ($row[0] == "Not Found") {
				return $visID;
			}
			//If it is found then the whole process begin again
			else {
				$this->generateID();
			}
		}
	}
	//Register visitor to the system
	public function registerVisitor($empID)
	{
		//Get all the information from the extended class of Contributor
		$visID = $this->getConID();
		$fullName = $this->getFullName();
		$gender = $this->getGender();
		$phoneNo = $this->getPhoneNo();
		$email = $this->getEmail();
		$address = $this->getAddress();
		$occupation = $this->getOccupation();
		$workPlace = $this->getWorkPlace();
		$sql = "INSERT INTO `visitor`(`VisID`, `Type`, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `EmpID`) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$visID, $this->typeOfVis, $fullName, $gender, $phoneNo, $email, $address, $occupation, $workPlace, $empID]);
		if ($result) {
			echo '<script language="javascript">';
			echo 'alert("Registered Successfully!\r\nYour ID is:-' . $visID . '\r\nWe will contact you for further events and projects.Thank you!")';
			echo '</script>';
		} else {
			echo "<script>alert('Registration Failed! Please check your connection.');
				document.location = '../Home.php' </script>";
		}
	}
	//Check if the visitor already exists
	public function checkID()
	{
		$visID = $this->getConID();
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `VisID` FROM `visitor` WHERE `VisID` = ?), 'Empty')");
		$stmt->execute([$visID]);
		while ($row = $stmt->fetch()) {
			if ($row[0] == "Empty") {
				echo "<script>alert('Your ID doesnt exist in the system, Please fill in the Rep Information to continue.');
					document.location = '/Register Contributor.php' </script>";
				echo "Your ID doesnt exist in the system, Please fill in the Personal Information to continue.";
			} else {
				echo "Proceed to the schedule information";
				return $row[0];
			}
		}
	}
}

if (isset($_REQUEST["id"])) {
	$visID = $_REQUEST["id"];
	$visObj = new Visitor();
	$visObj->setConID($visID);
	$check = $visObj->checkID();
}
