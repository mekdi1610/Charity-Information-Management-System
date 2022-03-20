<?php

include_once 'Connection.php';
//To get all the tips that are not seen yet
if (isset($_REQUEST["seen"])) {
	$seen = $_REQUEST["seen"];
	$tipObj = new Tip();
	$tipObj->changeStatus();
}
class Tip
{
	private $tipID, $fullName, $phoneNo, $gender, $location, $behavior, $addiction, $otherInfo;
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
	//Get and Set Methods
	public function setTipID($tipID)
	{
		$this->tipID = $tipID;
	}
	public function getTipID()
	{
		return $this->tipID;
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
	public function setLocation($location)
	{
		$this->location = $location;
	}
	public function getLocation()
	{
		return $this->location;
	}
	public function setBehavior($behavior)
	{
		$this->behavior = $behavior;
	}
	public function getBehavior()
	{
		return $this->behavior;
	}
	public function setAddiction($addiction)
	{
		$this->addiction = $addiction;
	}
	public function getAddiction()
	{
		return $this->addiction;
	}
	public function setOtherInfo($otherInfo)
	{
		$this->otherInfo = $otherInfo;
	}
	public function getOtherInfo()
	{
		return $this->otherInfo;
	}
	//Generate ID for each tip the user sent
	public function generateID()
	{
		$tipID = 'Tip-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Tip`");
		while ($row = $stmt->fetch()) {
			$tipID .= $row[0] + 1;
		}
		//Check if the tip ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `TipID` FROM `Tip` WHERE `TipID` = ?), 'Not Found')");
		$stmt->execute([$tipID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $tipID;
			}
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt1 = $this->con->query("SELECT * from `Tip`");
				$i = 1;
				while ($row = $stmt1->fetch()) {
					$noExp = explode("-", $row[0]);
					$no = $noExp[1];
					//Determine the missing ID
					if ($no == $i) {
						$i++;
					} 
					//Make the missing ID the current ID to fill the gap
					else {
						$tipID = 'Tip-' . $i;
						return $tipID;
					}
				}
			}
		}
	}
	//Send tip about a potential member
	public function sendTip()
	{
		$sql = "INSERT INTO `tip`(`TipID`, `TipperName`, `TipperPhoneNo`, `Gender`, `Location`, `Behavior`, `Addicition`, `OtherInfo`) VALUES(?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->tipID, $this->fullName, $this->phoneNo, $this->gender, $this->location, $this->behavior, $this->addiction, $this->otherInfo]);
		if ($result) {
			echo "<script>alert('Sent Successfully! We will contact you for farther information');
				document.location = '/Home.php' </script>";
		} else {
			echo "<script>alert('Sending Failed! Please check your connection');
				document.location = '/Home.php' </script>";
		}
	}
	//View all the recurring and previous tips
	public function viewTips($status)
	{
		$accept = array(); $tipID = []; $tipperName = []; $tipperPhoneNo = []; $gender = [];
		$location = []; $behavior = []; $addiction = []; $otherInfo = [];
		$stmt = $this->con->prepare("SELECT `TipID` , `TipperName`, `TipperPhoneNo`, `Gender`, `Location`, `Behavior`, `Addicition`, `OtherInfo` FROM `tip` where `Seen`=?");
		$stmt->execute([$status]);
		while ($row = $stmt->fetch()) {
			array_push($tipID, $row[0]);
			array_push($tipperName, $row[1]);
			array_push($tipperPhoneNo, $row[2]);
			array_push($gender, $row[3]);
			array_push($location, $row[4]);
			array_push($behavior, $row[5]);
			array_push($addiction, $row[6]);
			array_push($otherInfo, $row[7]);
		}
		array_push($accept, $tipID, $tipperName, $tipperPhoneNo, $gender, $location, $behavior, $addiction, $otherInfo);
		return $accept;
	}
	//Check if the status of the tip is seen or not
	public function changeStatus()
	{
		$sql = "UPDATE `Tip` SET `Seen` = 'Yes' WHERE `Seen` = 'No'";
		$result = $this->con->prepare($sql)->execute();
	}
	//Check if the status of the tip is seen or not
	public function getCount()
	{
		$stmt = $this->con->query("SELECT COUNT(*) from `tip` where `Seen`='No'");
		while ($row = $stmt->fetch()) {
			return $row[0];
		}
	}
}
