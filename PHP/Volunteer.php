<?php
include_once 'Connection.php';
include_once 'Contributor.php';

class Volunteer extends Contributor
{
	public $con = "";
	private $imgContent, $skill, $days, $inRes, $eFullName, $ePhoneNo;
	public function __construct()
	{
		//Call the Connection class
		$conObj = new Connection();
		$this->con = $conObj->connect();
	}
	// destructor  
	function __destruct() {}
	//Get and Set Methods
	public function setPhoto($imgContent)
	{
		$this->imgContent = $imgContent;
	}
	public function getPhoto()
	{
		return $this->imgContent;
	}
	public function setSkill($skill)
	{
		$this->skill = $skill;
	}
	public function getSkill()
	{
		return $this->skill;
	}
	public function setDays($days)
	{
		$this->days = $days;
	}
	public function getDays()
	{
		return $this->days;
	}
	public function setInRes($inRes)
	{
		$this->inRes = $inRes;
	}
	public function getInRes()
	{
		return $this->inRes;
	}
	public function setEFullName($eFullName)
	{
		$this->eFullName = $eFullName;
	}
	public function getEFullName()
	{
		return $this->eFullName;
	}
	public function setEPhoneNo($ePhoneNo)
	{
		$this->ePhoneNo = $ePhoneNo;
	}
	public function getEPhoneNo()
	{
		return $this->ePhoneNo;
	}
	//Generate ID for each volunteer
	public function generateID()
	{
		$volID = 'Vol-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Volunteer`");
		while ($row = $stmt->fetch()) {
			$volID .= $row[0] + 1;
		}
		//Check if the volunteer ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `VolID` FROM `Volunteer` WHERE `VolID` = ?), 'Not Found')");
		$stmt->execute([$volID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $volID;
			}
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID. 
			else {
				$stmt1 = $this->con->query("SELECT * from `Volunteer`");
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
						$volID = 'Vol-' . $i;
						return $volID;
					}
				}
			}
		}
	}
	//Register volunteer
	public function registerVolunteer($empID)
	{
		//Generate credential for the volunteer for next login
		$credential = explode(" ", $this->generateCredential($this->fullName));
		$userName = $credential[0];
		$password = $credential[1];
		$this->userName = $credential[0];
		$this->password = $credential[1];
		$this->role = "Volunteer";
		//Can set Account variables directly because access modifiers are protected.
		$this->signUp();
		$sql = "INSERT INTO `Volunteer`(`VolID`, `Photo`, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `Skill`, `PreferedDays`, `CurrentlyInResident`, `emeContactName`, `emeContactPhone`, `userName`, `EmpID`) VALUES (?,'$this->imgContent',?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->conID, $this->fullName, $this->gender, $this->phoneNo, $this->email, $this->address, $this->occupation, $this->workPlace, $this->skill, $this->days, $this->inRes, $this->eFullName, $this->ePhoneNo, $userName, $empID]);
		if ($result) {
			echo '<script language="javascript">';
			echo 'alert("Registered Successfully!\r\nYour Credentials are:- UserName: ' . $userName . ', Password:' . $password . ' \r\nWe will contact you for further events and projects.Thank you!")';
			echo '</script>';
		} else {
			echo "<script>alert('Registration Failed! Please check your connection.');
			document.location = '/Register Contributor.php' </script>";
		}
	}
	//Search volunteer based on the volunteer's username
	public function searchVolunteer($userName)
	{
		$stmt = $this->con->prepare("SELECT `VolID`, `Photo`, `FullName`, `Gender`, `PhoneNo`, `Email`, `Address`, `Occupation`, `Workplace`, `Skill`, `PreferedDays`, `CurrentlyInResident`, `emeContactName`, `emeContactPhone`, volunteer.`userName`, account.Password from `volunteer` INNER JOIN account ON `Volunteer`.`userName`=account.UserName where volunteer.userName=?");
		$stmt->execute([$userName]);
		while ($row = $stmt->fetch()) {
			$this->conID = $row[0];
			$this->imgContent = $row[1];
			$this->fullName = $row[2];
			$this->gender = $row[3];
			$this->phoneNo = $row[4];
			$this->email = $row[5];
			$this->address = $row[6];
			$this->occupation = $row[7];
			$this->workPlace = $row[8];
			$this->skill = $row[9];
			$this->days = $row[10];
			$this->inRes = $row[11];
			$this->eFullName = $row[12];
			$this->ePhoneNo = $row[13];
			$this->password = $row[15];
		}
	}
	//Get major volunteer information for the index page based on the number of time they volunteered
	public function getMainVolunteers()
	{
		$accept = array(); $volID = array(); $photo = array(); $FN = array(); $occ = array();
		$stmt = $this->con->query("SELECT `volunteer schedule`.VolID, COUNT(*) AS magnitude, `volunteer`.`Photo`, `volunteer`.`FullName`, `volunteer`.`Occupation`
		FROM `volunteer schedule` INNER JOIN volunteer ON `volunteer schedule`.`VolID`=`volunteer`.`VolID`
		GROUP BY VolID
		ORDER BY magnitude DESC
		LIMIT 6");
		while ($row = $stmt->fetch()) {
			array_push($volID, $row[0]);
			array_push($photo, $row[2]);
			array_push($FN, $row[3]);
			array_push($occ, $row[4]);
		}
		array_push($accept, $volID, $photo, $FN, $occ);
		return $accept;
	}
	//Update volunteer's profile
	public function updateProfile()
	{
		//Can get Account variables directly because access modifiers are protected.
		$sql = "UPDATE Volunteer SET `FullName` =?, `Gender` =?, `PhoneNo` = ?, `Email` =?, `Address` =?, `Occupation` =?, `Workplace` =?, `Skill` = ?, `PreferedDays` = ?, `CurrentlyInResident` = ?, `emeContactName` = ?, `emeContactPhone` = ? WHERE `VolID` =?";
		$result = $this->con->prepare($sql)->execute([$this->fullName, $this->gender, $this->phoneNo, $this->email, $this->address, $this->occupation, $this->workPlace, $this->skill, $this->days, $this->inRes, $this->eFullName, $this->ePhoneNo, $this->conID]);
		if ($result) {
			//Update Account while updating personal information of volunteer.
			$this->updateAccount();
			if ($result) {
				echo "<script>alert('Updated Successfully!');
				document.location = '/Home.php' </script>";
			} else {
				echo "<script>alert('Update Failed! Please check your coonection.');
				document.location = '/Home.php' </script>";
			}
		}
	}
	//For each volunteers to view requests made by the organization
	public function getRequest()
	{
		$accept = array(); $actID = array(); $type = []; $duration = []; $PeopleNeeded = []; $startTime = []; $endTime = []; $skill = [];
		$stmt = $this->con->prepare("SELECT `ActID`, `Type`, `Duration`, `PeopleNeeded`, `StartTime`, `EndTime`, `OccNeeded`, `EmpID` FROM `activity` where `OccNeeded`=?");
		$stmt->execute([$this->occupation]);
		while ($row = $stmt->fetch()) {
			array_push($actID, $row[0]);
			array_push($type, $row[1]);
			array_push($duration, $row[2]);
			array_push($PeopleNeeded, $row[3]);
			array_push($startTime, $row[4]);
			array_push($endTime, $row[5]);
			array_push($skill, $row[6]);
		}
		array_push($accept, $actID, $type, $duration, $PeopleNeeded, $startTime, $endTime, $skill);
		return $accept;
	}
}
