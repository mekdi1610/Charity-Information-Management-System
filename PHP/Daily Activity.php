<?php
include_once 'Connection.php';

class DailyActivity
{
	private $actID, $type, $duration, $peopleNeeded, $startTime, $endTime, $occNeeded, $empID;
	public $con = "";
	public function __construct()
	{
		//Call the Connection class
		$conObj = new Connection();
		$this->con  = $conObj->connect();
	}
	// destructor  
	function __destruct(){}
	//Get and Set Methods
	public function setActID($actID)
	{
		$this->actID = $actID;
	}
	public function getActID()
	{
		return $this->actID;
	}
	public function setType($type)
	{
		$this->type = $type;
	}
	public function getType()
	{
		return $this->type;
	}
	public function setDuration($duration)
	{
		$this->duration = $duration;
	}
	public function getDuration()
	{
		return $this->duration;
	}
	public function setPeopleNeeded($peopleNeeded)
	{
		$this->peopleNeeded = $peopleNeeded;
	}
	public function getPeopleNeeded()
	{
		return $this->peopleNeeded;
	}
	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}
	public function getStartTime()
	{
		return $this->startTime;
	}
	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}
	public function getEndTime()
	{
		return $this->endTime;
	}
	public function setOccNeeded($occNeeded)
	{
		$this->occNeeded = $occNeeded;
	}
	public function getOccNeeded()
	{
		return $this->occNeeded;
	}
	//Generate ID for each activity
	public function generateID()
	{
		$actID = 'Act-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Activity`");
		while ($row = $stmt->fetch()) {
			$actID .= $row[0] + 1;
		}
		//Check if the activity ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `ActID` FROM `Activity` WHERE `ActID` = ?), 'Not Found')");
		$stmt->execute([$actID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $actID;
			} 
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt = $this->con->query("SELECT `ActID` from `Activity`");
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
						$actID = 'Act-' . $i;
						return $actID;
					}
				}
			}
		}
	}
	//Add daily activity
	public function addActivity($empID)
	{
		$sql = "INSERT INTO `activity`(`ActID`, `Type`, `Duration`, `PeopleNeeded`, `StartTime`, `EndTime`, `OccNeeded`, `EmpID`) VALUES (?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->actID, $this->type, $this->duration, $this->peopleNeeded, $this->startTime, $this->endTime, $this->occNeeded, $empID]);
		if ($result) {
			echo "<script>alert('Added Sucessfully!');
				document.location = '/Manage Daily Activity.php' </script>";
		} else {
			echo "<script>alert('Adding Failed!, Please check your connection.);
				document.location = '/Manage Daily Activity.php' </script>";
		}
	}
	//View all the activites in a an organized format
	public function viewActivities()
	{
		$accept = array(); $actID = array(); $type = []; $duration = []; $peopleNeeded = []; $startTime = []; $endTime = []; $occNeeded = []; $empID = [];
		$stmt = $this->con->query("SELECT `ActID`, `Type`, `Duration`, `PeopleNeeded`, `StartTime`, `EndTime`, `OccNeeded`, `EmpID` FROM `activity`");
		while ($row = $stmt->fetch()) {
			array_push($actID, $row[0]);
			array_push($type, $row[1]);
			array_push($duration, $row[2]);
			array_push($peopleNeeded, $row[3]);
			array_push($startTime, $row[4]);
			array_push($endTime, $row[5]);
			array_push($occNeeded, $row[6]);
			array_push($empID, $row[7]);
		}
		array_push($accept, $actID, $type, $duration, $peopleNeeded, $startTime, $endTime, $occNeeded, $empID);

		return $accept;
	}
	//Update an activity
	public function updateActivity($empID)
	{
		$sql = "UPDATE `activity` SET `Type`=?,`Duration`=?,`PeopleNeeded`=?,`StartTime`=?,`EndTime`=?,`OccNeeded`=?,`EmpID`=? WHERE ActID = ?";
		$result = $this->con->prepare($sql)->execute([$this->type, $this->duration, $this->peopleNeeded, $this->startTime, $this->endTime, $this->occNeeded, $empID, $this->actID]);
		if ($result) {
			echo "<script>alert('Updated Successfully!');
				document.location = '/Manage Daily Activity.php' </script>";
		} else {
			echo "<script>alert('Update Failed!, Please check your connection.);
				document.location = '/Manage Daily Activity.php' </script>";
		}
	}
	//Remove activity from database
	public function removeActivity()
	{
		$sql = "DELETE FROM `activity` WHERE `ActID` = ?";
		$result = $this->con->prepare($sql)->execute([$this->actID]);
		if ($result) {
			echo "<script>alert('Removed Successfully!');
				document.location = '/Manage Daily Activity.php' </script>";
		} else {
			echo "<script>alert('Remove Failed!, Please check your connection.);
				document.location = '/Manage Daily Activity.php' </script>";
		}
	}
}
