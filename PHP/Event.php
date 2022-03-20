<?php
include_once 'Connection.php';
session_start();
$eveObj = new Event();
//Get avaiable time for an event based on selected date, results return to ajax code
if (isset($_REQUEST["date"])) {
	$dateForSearch = $_REQUEST["date"];
	$time = $eveObj->getEventTime($dateForSearch);
}
class Event
{
	private $eveID, $type, $peopleToFeed, $peopleToInvite, $peopleNeeded, $date, $time, $fullName, $phoneNo, $email, $hall, $empID;
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
	public function setEveID($eveID)
	{
		$this->eveID = $eveID;
	}
	public function getEveID()
	{
		return $this->eveID;
	}
	public function setType($type)
	{
		$this->type = $type;
	}
	public function getType()
	{
		return $this->type;
	}
	public function setPepleToFeed($peopleToFeed)
	{
		$this->peopleToFeed = $peopleToFeed;
	}
	public function getPepleToFeed()
	{
		return $this->pepleToFeed;
	}
	public function setPeopleToInvite($peopleToInvite)
	{
		$this->peopleToInvite = $peopleToInvite;
	}
	public function getPeopleToInvite()
	{
		return $this->peopleToInvite;
	}
	public function setPeopleNeeded($peopleNeeded)
	{
		$this->peopleNeeded = $peopleNeeded;
	}
	public function getPeopleNeeded()
	{
		return $this->peopleNeeded;
	}
	public function setDate($date)
	{
		$this->date = $date;
	}
	public function getDate()
	{
		return $this->date;
	}
	public function setTime($time)
	{
		$this->time = $time;
	}
	public function getTime()
	{
		return $this->time;
	}
	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}
	public function getFullName()
	{
		return $this->fullName;
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
	public function setHall($hall)
	{
		$this->hall = $hall;
	}
	public function getHall()
	{
		return $this->hall;
	}
	//Generate ID for each event
	public function generateID()
	{
		$eveID = 'Eve-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Event`");
		while ($row = $stmt->fetch()) {
			$eveID .= $row[0] + 1;
		}
		//Check if the event ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `EventID` FROM `Event` WHERE `EventID` = ?), 'Not Found')");
		$stmt->execute([$eveID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $eveID;
			}
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt = $this->con->query("SELECT `EventID` from `Event`");
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
						$eveID = 'Eve-' . $i;
						return $eveID;
					}
				}
			}
		}
	}
	//Send application to celebarte occassion | personal event in the organization
	public function applyForEvent()
	{
		$sql = "INSERT INTO `event`(`EventID`, `EventType`, `PeopleToFeed`, `PeopleInvited`, `Date`, `Time`, `FullName`, `PhoneNo`, `Email`) VALUES(?,?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->eveID, $this->type, $this->peopleToFeed, $this->peopleToInvite, $this->date, $this->time, $this->fullName, $this->phoneNo, $this->email]);
		if ($result) {
			echo "<script>alert('Applied Succesfully! We will contact you via email for the approval process.');
			document.location = '../index.php' </script>";
		} else {
			echo "<script>alert('Application Failed! Please check your connection.');
			document.location = '../index.php' </script>";
		}
	}
	//View all events based on status
	public function viewEvent($status)
	{
		$accept = array(); $eveID = []; $eventType = []; $NPF = []; $NPI = []; $date = [];
		$time = []; $FN = []; $Phone = []; $Email = [];
		$stmt = $this->con->prepare("SELECT `EventID`, `EventType`, `PeopleToFeed`, `PeopleInvited`, `Date`, `Time`, `FullName`, `PhoneNo`, `Email` FROM `event` where `Approval`= ?");
		$stmt->execute([$status]);
		while ($row = $stmt->fetch()) {
			array_push($eveID, $row[0]);
			array_push($eventType, $row[1]);
			array_push($NPF, $row[2]);
			array_push($NPI, $row[3]);
			array_push($date, $row[4]);
			array_push($time, $row[5]);
			array_push($FN, $row[6]);
			array_push($Phone, $row[7]);
			array_push($Email, $row[8]);
			$count = count($eveID);
		}
		array_push($accept, $eveID, $eventType, $NPF, $NPI, $date, $time, $FN, $Phone, $Email, $count);
		return $accept;
	}
	//Get avaiable time to apply for an event
	public function getEventTime($dateForSearch)
	{
		$time = "";
		$stmt = $this->con->prepare("SELECT `Time` FROM `Event` where `date`=? and `Approval`='Approved'");
		$stmt->execute([$dateForSearch]);
		while ($row = $stmt->fetch()) {
			$time .= $row[0] . "_";
		}
		echo $time;
		return $time;
	}
	//Approve status of an event
	public function approveEvent($empID)
	{
		$sql = "UPDATE `Event` SET `PeopleNeeded` = ?, `Hall` = ?, `Approval` = 'Approved', `EmpID` = ? WHERE EventID = ?";
		$result = $this->con->prepare($sql)->execute([$this->peopleNeeded, $this->hall, $empID, $this->eveID]);
		if ($result) {
			echo "<script>alert('Approved Successfully!');
			document.location = '/View Event.php' </script>";
		} else {
			echo "<script>alert('Approval Failed!');
			document.location = '/View Event.php' </script>";
		}
	}
	//Deny status of an event
	public function denyEvent($empID)
	{
		$sql = "UPDATE `Event` SET `Approval` = 'Denied', `EmpID` = ? WHERE EventID = ?";
		$result = $this->con->prepare($sql)->execute([$empID, $this->eveID]);
		if ($result) {
			echo "<script>alert('Denied Successfully!');
			document.location = '/View Event.php' </script>";
		}
	}
	//Send both approval and denial email to applier
	public function sendEmail($subject, $message)
	{
		$headers = "From: beletebiniyam65@gmail.com";
		if (mail($this->email, $subject, $message, $headers)) {
			echo "<script>alert('Sent Successfully! We will reply soon.');
			document.location = '/View Event.php' </script>";
		} else {
			echo "<script>alert('Sending Failed!');
			document.location = '/View Event.php' </script>";
		}
	}
}
