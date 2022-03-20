<?php
include_once 'Connection.php';
include_once 'Account.php';

class Contributor extends Account
{
	public $con = "";
	protected $conID, $fullName, $gender, $phoneNo, $email, $address, $occupation, $workPlace, $type;
	private $colName, $tableName;
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
	public function setConID($conID)
	{
		$this->conID = $conID;
	}
	public function getConID()
	{
		return $this->conID;
	}
	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}
	public function getFullName()
	{
		return $this->fullName;
	}
	public function setGender($gender)
	{
		$this->gender = $gender;
	}
	public function getGender()
	{
		return $this->gender;
	}
	public function setPhoneNo($phoneNo)
	{
		$this->phoneNo = $phoneNo;
	}
	public function getPhoneNo()
	{
		return $this->phoneNo;
	}
	public function setEmail($email)
	{
		$this->email = $email;
	}
	public function getEmail()
	{
		return $this->email;
	}
	public function setAddress($address)
	{
		$this->address = $address;
	}
	public function getAddress()
	{
		return $this->address;
	}
	public function setOccupation($occupation)
	{
		$this->occupation = $occupation;
	}
	public function getOccupation()
	{
		return $this->occupation;
	}
	public function setWorkPlace($workPlace)
	{
		$this->workPlace = $workPlace;
	}
	public function getWorkPlace()
	{
		return $this->workPlace;
	}
	public function setType($type)
	{
		$this->type = $type;
	}
	public function getType()
	{
		return $this->type;
	}
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}
	public function getTableName()
	{
		return $this->tableName;
	}
	public function setColName($colName)
	{
		$this->colName = $colName;
	}
	public function getColName()
	{
		return $this->colName;
	}
	//Search contributor's information
	public function searchContributor()
	{
		//Two forms
		$IDForSearch = $this->conID;
		$fullName = $this->fullName;
		//Search by ID
		if ($IDForSearch != null) {
			$stmt = $this->con->prepare("SELECT count($this->colName), $this->colName, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `EmpID` FROM $this->tableName WHERE $this->colName = ?");
			$stmt->execute([$IDForSearch]);
			while ($row = $stmt->fetch()) {
				//If ID doesnt exist in the system it will return the following message
				if ($row[0] == 0 && $IDForSearch != "") {
					echo "<script>alert('No result found!');
						document.location = '/Update Contributor.php' </script>";
				}
				//If it exists then variables are set
				else {
					$this->fullName = $row['FullName'];
					$this->gender = $row['Gender'];
					$this->phoneNo = $row['PhoneNo'];
					$this->email = $row['Email'];
					$this->address = $row['Address'];
					$this->occupation = $row['Occupation'];
					$this->workPlace = $row['Workplace'];
					$this->empID = $row['EmpID'];
				}
			}
		}
		//Search by Full Name
		if ($fullName != "") {
			$stmt1 = $this->con->prepare("SELECT `VisID`, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `EmpID` FROM `Visitor` WHERE `FullName` = ?");
			$stmt2 = $this->con->prepare("SELECT `DonID`, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `EmpID` FROM `Donator` WHERE `FullName` = ?");
			$stmt3 = $this->con->prepare("SELECT `VolID`, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `EmpID` FROM `Volunteer` WHERE `FullName` = ?");
			$stmt1->execute([$fullName]);
			$stmt2->execute([$fullName]);
			$stmt3->execute([$fullName]);
			$accept = array();
			//If FullName is found in Visitor table information is pushed to an array
			while ($row = $stmt1->fetch()) {
				$tableName = "Visitor";
				array_push($accept, $row, $tableName);
			}
			//If FullName is found in Donator table information is pushed to an array
			while ($row = $stmt2->fetch()) {
				$tableName = "Donator";
				array_push($accept, $row, $tableName);
			}
			//If FullName is found in Volunteer table information is pushed to an array
			while ($row = $stmt3->fetch()) {

				$tableName = "Volunteer";
				array_push($accept, $row, $tableName);
			}
			return $accept;
		}
	}
	//Update contributor contact information
	public function updateContributor()
	{
		$sql = "UPDATE $this->tableName SET `FullName` = ?, `Gender` = ?, `PhoneNo` = ?, `Email` = ?, `Address` = ?, `Occupation` = ?, `Workplace` = ? WHERE $this->colName = ?";
		$result = $this->con->prepare($sql)->execute([$this->fullName, $this->gender, $this->phoneNo, $this->email, $this->address, $this->occupation, $this->workPlace, $this->conID]);
		if ($result) {
			echo "<script>alert('Updated Successfully!');
			document.location = '/Update Contributor.php' </script>";
		} else {
			echo "<script>alert('Update Failed! Please check your connection.');
			document.location = '/Update Contributor.php' </script>";
		}
	}
	//Remove contributor information
	public function removeContributor()
	{
		$sql = "DELETE FROM $this->tableName WHERE $this->colName= ?";
		$result = $this->con->prepare($sql)->execute([$this->conID]);
		if ($result) {
			echo "<script>alert('Removed Successfully!');
			document.location = '/Update Contributor.php' </script>";
		} else {
			echo "<script>alert('Remove Failed! Please check your connection.');
			document.location = '/Update Contributor.php' </script>";
		}
	}
	//Check if a contributor exists before registering infromation
	public function checkContributor()
	{
		//To check in the system type should be selected
		if ($this->tableName == "Type") {
			echo "Please choose your role to proceed";
		}
		//Check using PhoneNo
		else {
			$stmt1 = $this->con->prepare("SELECT IFNULL ( ( SELECT `PhoneNo` FROM $this->tableName WHERE `PhoneNo` = ?), 'Empty')");
			$stmt1->execute([$this->phoneNo]);
			while ($row = $stmt1->fetch()) {
				//If the contributor information doesnt exist then its valid to register
				if ($row[0] == "Empty") {
					return $row[0];
				}
				//If PhoneNo exist in the system then the system will display the following message
				else {
					echo "<script>alert('This user is already registered!');
				document.location = '/Register Contributor.php' </script>";
					echo "This user is already registered!";
				}
			}
		}
	}
	//Get all the contact information of the contributor
	public function viewContactInfo()
	{
		$accept = array();
		$acceptVol = array();
		$acceptDon = array();
		$acceptVis = array();
		$stmt1 = $this->con->query("SELECT `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace` FROM Volunteer");
		//Push Volunteer's information to acceptVol array
		while ($row = $stmt1->fetch()) {
			array_push($acceptVol, $row);
		}
		$stmt2 = $this->con->query("SELECT `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace` FROM Donator");
		//Push Donator's information to acceptDon array
		while ($row = $stmt2->fetch()) {
			array_push($acceptDon, $row);
		}
		$stmt3 = $this->con->query("SELECT `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace` FROM Visitor");
		//Push Visitor's information to acceptVis array
		while ($row = $stmt3->fetch()) {
			array_push($acceptVis, $row);
		}
		array_push($accept, $acceptVol, $acceptDon, $acceptVis);
		return $accept;
	}
	//Search contributors by occupation
	public function searchByOccupation($OccSearch)
	{
		$accept = array();
		$acceptVol = array();
		$acceptDon = array();
		$acceptVis = array();
		$stmt1 = $this->con->prepare("SELECT `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace` FROM Volunteer where `Occupation`=?");
		$stmt1->execute([$OccSearch]);
		//Push Volunteer's information to acceptVol array
		while ($row = $stmt1->fetch()) {
			array_push($acceptVol, $row);
		}
		$stmt2 = $this->con->prepare("SELECT `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace` FROM Donator where `Occupation`=?");
		$stmt2->execute([$OccSearch]);
		//Push Visitor's information to acceptVis array
		while ($row = $stmt2->fetch()) {
			array_push($acceptDon, $row);
		}
		$stmt3 = $this->con->prepare("SELECT `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace` FROM Visitor where `Occupation`=?");
		$stmt3->execute([$OccSearch]);
		//Push Visitor's information to acceptVis array
		while ($row = $stmt3->fetch()) {
			array_push($acceptVis, $row);
		}
		array_push($accept, $acceptVol, $acceptDon, $acceptVis);
		return $accept;
	}
}
//Check if contributor exist and return result to ajax code
if (isset($_REQUEST["phoneType"])) {
	$datasent = $_REQUEST["phoneType"];
	$conObj = new Contributor();
	$accept = explode(" ", $datasent);
	$conObj->setPhoneNo($accept[0]);
	$table = $accept[1];
	$conObj->setTableName($table);
	$conObj->checkContributor();
}
