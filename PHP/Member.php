<?php
include_once 'Connection.php';
//Check if contributor exist and return result to ajax code
if (isset($_REQUEST["fullNameDOB"])) {
	$datasent = $_REQUEST["fullNameDOB"];
	$memObj = new Member();
	$accept = explode("_", $datasent);
	$memObj->setFullName($accept[0]);
	$memObj->setDateOfBirth($accept[1]);
	$memObj->checkMember();
}
class Member
{
	private $memberID, $imgContent, $fullName, $dateOfBirth, $gender, $motherName, $religion, $placeOfBirth, $maritalStatus, $initalLocation, $admittedDate, $behavior, $addiction, $belongings, $height, $weight, $healthStatus, $medDes, $status;
	private $con = "";
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
	public function setMemberID($memberID)
	{
		$this->memberID = $memberID;
	}
	public function getMemberID()
	{
		return $this->memberID;
	}
	public function setPhoto($imgContent)
	{
		$this->imgContent = $imgContent;
	}
	public function getPhoto()
	{
		return $this->imgContent;
	}
	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}
	public function getFullName()
	{
		return $this->fullName;
	}
	public function setDateOfBirth($dateOfBirth)
	{
		$this->dateOfBirth = $dateOfBirth;
	}
	public function getDateOfBirth()
	{
		return $this->dateOfBirth;
	}
	public function setGender($gender)
	{
		$this->gender = $gender;
	}
	public function getGender()
	{
		return $this->gender;
	}
	public function setMotherName($motherName)
	{
		$this->motherName = $motherName;
	}
	public function getMotherName()
	{
		return $this->motherName;
	}
	public function setReligion($religion)
	{
		$this->religion = $religion;
	}
	public function getReligion()
	{
		return $this->religion;
	}
	public function setPlaceOfBirth($placeOfBirth)
	{
		$this->placeOfBirth = $placeOfBirth;
	}
	public function getPlaceOfBirth()
	{
		return $this->placeOfBirth;
	}
	public function setMaritalStatus($maritalStatus)
	{
		$this->maritalStatus = $maritalStatus;
	}
	public function getMaritalStatus()
	{
		return $this->maritalStatus;
	}
	public function setInitalLocation($initalLocation)
	{
		$this->initalLocation = $initalLocation;
	}
	public function getInitalLocation()
	{
		return $this->initalLocation;
	}
	public function setAdmittedDate($admittedDate)
	{
		$this->admittedDate = $admittedDate;
	}
	public function getAdmittedDate()
	{
		return $this->admittedDate;
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
	public function setBelongings($belongings)
	{
		$this->belongings = $belongings;
	}
	public function getBeongings()
	{
		return $this->belongings;
	}
	public function setHeight($height)
	{
		$this->height = $height;
	}
	public function getHeight()
	{
		return $this->height;
	}
	public function setWeight($weight)
	{
		$this->weight = $weight;
	}
	public function getWeight()
	{
		return $this->weight;
	}
	public function setHealthStatus($healthStatus)
	{
		$this->healthStatus = $healthStatus;
	}
	public function getHealthStatus()
	{
		return $this->healthStatus;
	}
	public function setMedDes($medDes)
	{
		$this->medDes = $medDes;
	}
	public function getMedDes()
	{
		return $this->medDes;
	}
	public function setStatus($status)
	{
		$this->status = $status;
	}
	public function getStatus()
	{
		return $this->status;
	}
	//Generate ID for each member
	public function generateID()
	{
		$memberID = 'M-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Member`");
		while ($row = $stmt->fetch()) {
			$memberID .= $row[0] + 1;
		}
		//Check if the member ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `MemberID` FROM `Member` WHERE `MemberID` = ?), 'Not Found')");
		$stmt->execute([$memberID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $memberID;
			}
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt1 = $this->con->query("SELECT * from `Member`");
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
						$memberID = 'M-' . $i;
						return $memberID;
					}
				}
			}
		}
	}
	//Check if member already exists in the system
	public function checkMember()
	{
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `MemberID` FROM `Member` WHERE `FullName` = ? and `DateOfBirth` = ?), 'Empty')");
		$stmt->execute([$this->fullName, $this->dateOfBirth]);
		while ($row = $stmt->fetch()) {
			if($row[0]=="Empty"){
				return $row[0];
			}
			else{
				echo "<script>alert('This member is already registered!');
				document.location = '/Register Contributor.php' </script>";
				echo "This member is already registered!";
			}
			
		}
	}
	//Register member's information and set session information to display the Treatment Schedule Dialog Box
	public function registerMember($tipID, $empPresent, $empID)
	{
		$sql = "INSERT INTO Member(`MemberID`, `Photo`, `FullName`, `DateOfBirth`, `Gender`, `MotherName`, `Religion`, `PlaceOfBirth`, `MaritalStatus`, `InitalLocation`, `AdmittedDate`, `TipID`, `Behavior`, `Addiction`, `Belongings`, `Height`, `Weight`, `HealthStatus`, `MedDescription`, `Status`, `EmpPresent`, `EmpID`) VALUES (?,'$this->imgContent',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->memberID, $this->fullName, $this->dateOfBirth, $this->gender, $this->motherName, $this->religion, $this->placeOfBirth, $this->maritalStatus, $this->initalLocation, $this->admittedDate, $tipID, $this->behavior, $this->addiction, $this->belongings, $this->height, $this->weight, $this->healthStatus, $this->medDes, $this->status, $empPresent, $empID]);
		if ($result) {
			$_SESSION["fullName"] = $this->fullName;
			$_SESSION["memID"] = $this->memberID;
			echo "<script>alert('Registered Successfully!');
			document.location = '/Register Member.php' </script>";
		} else {
			echo "<script>alert('Registration Failed!');
			document.location = '/Register Member.php' </script>";
		}
	}
	//Search member using its ID or Full Name
	public function searchMember()
	{
		$arrayReturn = array();
		$stmt = $this->con->prepare("SELECT count(`MemberID`), `MemberID`, `Photo`, `FullName`, `DateOfBirth`, `Gender`, `MotherName`, `Religion`, `PlaceOfBirth`, `MaritalStatus`, `InitalLocation`, `AdmittedDate`, `TipID`, `Behavior`, `Addiction`, `Belongings`, `Height`, `Weight`,`HealthStatus`, `MedDescription`, `Status`, `EmpPresent`, `EmpID` FROM `Member` WHERE MemberID = ? or FullName = ?");
		$stmt->execute([$this->memberID, $this->fullName]);
		while ($row = $stmt->fetch()) {
			//If the ID is not found in the system, it will display the following information
			if ($row[0] == 0 && ($this->memberID != "" || $this->fullName != "")) {
				echo "<script>alert('No result found!');
					document.location = '/Home.php' </script>";
			}
			//If the ID exist in the system, it will set result to the respective variables
			else {
				$this->memberID = $row['MemberID'];
				$this->imgContent = $row['Photo'];
				$this->fullName = $row['FullName'];
				$this->dateOfBirth = $row['DateOfBirth'];
				$this->gender = $row['Gender'];
				$this->motherName = $row['MotherName'];
				$this->religion = $row['Religion'];
				$this->placeOfBirth = $row['PlaceOfBirth'];
				$this->maritalStatus = $row['MaritalStatus'];
				$this->initalLocation = $row['InitalLocation'];
				$this->admittedDate = $row['AdmittedDate'];
				$this->behavior = $row['Behavior'];
				$this->addiction = $row['Addiction'];
				$this->belongings = $row['Belongings'];
				$this->height = $row['Height'];
				$this->weight = $row['Weight'];
				$this->healthStatus = $row['HealthStatus'];
				$this->medDes = $row['MedDescription'];
				$this->status = $row['Status'];
				$tipID = $row['TipID'];
				$empPresent = $row['EmpPresent'];
				$empID = $row["EmpID"];
				array_push($arrayReturn, $tipID, $empPresent, $empID);
				return $arrayReturn;
			}
		}
	}
	//Update member information
	public function updateMember($tipID, $empPresent, $empID)
	{
		$sql = "UPDATE `Member` SET `FullName` = ?, `DateOfBirth` =?, `Gender` = ?, `MotherName` = ?, `Religion` = ?,	`PlaceOfBirth` = ?, `MaritalStatus` = ?, `InitalLocation` = ?, `AdmittedDate` = ?, `TipID` = ?, `Behavior` = ?, `Addiction` = ?, `Belongings` = ?, `Height` = ?,
		`Weight` = ?, `HealthStatus` = ?, `MedDescription` = ?, `Status` = ?, `EmpPresent` = ?, `EmpID` = ? WHERE MemberID = ?";
		$result = $this->con->prepare($sql)->execute([$this->fullName, $this->dateOfBirth, $this->gender, $this->motherName, $this->religion, $this->placeOfBirth, $this->maritalStatus, $this->initalLocation, $this->admittedDate, $tipID, $this->behavior, $this->addiction, $this->belongings, $this->height, $this->weight, $this->healthStatus, $this->medDes, $this->status, $empPresent, $empID, $this->memberID]);
		if ($result) {
			echo "<script>alert('Updated Successfully!');
			document.location = '/Update Member.php' </script>";
		} else {
			echo "<script>alert('Update Failed! Please check your connection');
			document.location = '/Update Member.php' </script>";
		}
	}
	//View all members' information
	public function viewMember()
	{
		$accept = array(); $arrayMemberID = []; $arrayPhoto = []; $arrayFullName = []; $arrayDOB = []; $arrayStatus = [];
		$stmt = $this->con->query("SELECT `MemberID`, `Photo`, `FullName`, `DateofBirth`, `Status` from `Member`");
		while ($row = $stmt->fetch()) {
			array_push($arrayMemberID, $row[0]);
			array_push($arrayPhoto, $row[1]);
			array_push($arrayFullName, $row[2]);
			array_push($arrayDOB, $row[3]);
			array_push($arrayStatus, $row[4]);
		}
		array_push($accept, $arrayMemberID, $arrayPhoto, $arrayFullName, $arrayDOB, $arrayStatus);
		return $accept;
	}
	//Search memberID using FullName
	public function searchForID()
	{
		$stmt = $this->con->prepare("SELECT `MemberID` FROM `Member` WHERE FullName = ?");
		$stmt->execute([$this->fullName]);
		while ($row = $stmt->fetch()) {
			$this->memberID = $row['MemberID'];
		}
	}
	//Get information of critical members for the donation page
	public function getCriticalMembers()
	{
		$accept = []; $arrayPhoto = []; $arrayFullName = []; $arrayCD = []; $bankName = array(); 
		$branchName = array(); $accName = array(); $accNo = array(); $transferInfo = array(); $country = array();
		$stmt = $this->con->prepare("SELECT member.Photo, member.FullName, member.MedDescription, `BankName`, `BranchName`, `AccountName`, `AccountNumber`, `TransferInfo`, `Country`  FROM member INNER JOIN `bank account` ON `member`.`MemberID`=`bank account`.`BelongsTo` WHERE member.HealthStatus=? ORDER BY MemberID DESC LIMIT 3");
		$stmt->execute(["Critical"]);
		while ($row = $stmt->fetch()) {
			array_push($arrayPhoto, $row[0]);
			array_push($arrayFullName, $row[1]);
			array_push($arrayCD, $row[2]);
			array_push($bankName, $row[3]);
			array_push($branchName, $row[4]);
			array_push($accName, $row[5]);
			array_push($accNo, $row[6]);
			array_push($transferInfo, $row[7]);
			array_push($country, $row[8]);
		}
		array_push($accept, $arrayPhoto, $arrayFullName, $arrayCD, $bankName, $branchName, $accName, $accNo, $transferInfo, $country);
		return $accept;
	}
}
