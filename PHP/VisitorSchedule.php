<?php
include_once 'Connection.php';

$visSchObj = new VisitorSchedule();
//Get available  time and return to the Ajax code
if (isset($_REQUEST["date"])) {
	$dateForSearch = $_REQUEST["date"];
	$time = $visSchObj->getScheduleTime($dateForSearch);
}
class VisitorSchedule
{
	private $visSchID, $noOfPeople, $date, $time;
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
	public function setVisSchID($visSchID)
	{
		$this->visSchID = $visSchID;
	}
	public function getVisSchID()
	{
		return $this->visSchID;
	}
	public function setNoOfPeople($noOfPeople)
	{
		$this->noOfPeople = $noOfPeople;
	}
	public function getNoOfPeople()
	{
		return $this->pepleToFeed;
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
	//Generate ID for each schedule
	public function generateID($visID)
	{
		$visSchID = $visID . '-Sch-';
		$stmt = $this->con->prepare("SELECT COUNT(*) from `Visitor Schedule` where `VisID` = ?");
		$stmt->execute([$visID]);
		while ($row = $stmt->fetch()) {
			$visSchID .= $row[0] + 1;
		}
		//Check if the schedule ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `VisSchID` FROM `Visitor Schedule` WHERE `VisSchID` = ?), 'Not Found')");
		$stmt->execute([$visSchID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $visSchID;
			} 
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt1 = $this->con->prepare("SELECT * from `Visitor Schedule` where `VisID`=?");
				$stmt1->execute([$visID]);
				$i = 1;
				while ($row = $stmt1->fetch()) {
					$noExp = explode("-", $row[0]);
					$no = $noExp[3];
					//Determine the missing ID
					if ($no == $i) {
						$i++;
					} 
					//Make the missing ID the current ID to fill the gap
					else {
						$volSchID = $visID . '-Sch-' . $i;
						return $volSchID;
					}
				}
			}
		}
	}
	//Add Visitor Schedule to the database
	public function addVisSchedule($visID)
	{
		$sql = "INSERT INTO `visitor schedule`(`VisSchID`, `VisID`, `NoOfPeople`, `Date`, `Time`) VALUES (?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->visSchID, $visID, $this->noOfPeople, $this->date, $this->time]);
		if ($result) {
			echo "<script>alert('We will be expecting you!');
			document.location = '/Schedule Visitors.php' </script>";
		} else {
			echo "<script>alert('Scheduling Failed! Please check your connection.');
			document.location = '/Schedule Visitors.php' </script>";
		}
	}
	//View all visitors that were scheduled on a specfic date
	public function viewVisSchedule()
	{
		$accept = array(); $visSchID = []; $type = []; $FN = []; $phoneNo = []; $email = [];
		$occ = []; $wp = []; $noOfPeople = []; $dates = []; $times = [];
		$stmt = $this->con->prepare("SELECT `visitor schedule`.`VisSchID`, `Type`, `FullName`, `PhoneNo`, `Email`, `Occupation`, `Workplace`, `visitor schedule`.`NoOfPeople`, `visitor schedule`.`Date`, `visitor schedule`.`Time` FROM `visitor` INNER JOIN `visitor schedule` ON visitor.VisID=`visitor schedule`.`VisID`");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			array_push($visSchID, $row[0]);
			array_push($type, $row[1]);
			array_push($FN, $row[2]);
			array_push($phoneNo, $row[3]);
			array_push($email, $row[4]);
			array_push($occ, $row[5]);
			array_push($wp, $row[6]);
			array_push($noOfPeople, $row[7]);
			array_push($dates, $row[8]);
			array_push($times, $row[9]);
		}
		array_push($accept, $visSchID, $type, $FN, $phoneNo, $email, $occ, $wp, $noOfPeople, $dates, $times);
		return $accept;
	}
		//View all visitors that were scheduled on a specfic date
		public function searchByDate($date)
		{
			$accept = array(); $visSchID = []; $type = []; $FN = []; $phoneNo = []; $email = [];
			$occ = []; $wp = []; $noOfPeople = []; $dates = []; $times = [];
			$stmt = $this->con->prepare("SELECT `visitor schedule`.`VisSchID`, `Type`, `FullName`, `PhoneNo`, `Email`, `Occupation`, `Workplace`, `visitor schedule`.`NoOfPeople`, `visitor schedule`.`Date`, `visitor schedule`.`Time` FROM `visitor` INNER JOIN `visitor schedule` ON visitor.VisID=`visitor schedule`.`VisID` where `visitor schedule`.`Date`=?");
			$stmt->execute([$date]);
			while ($row = $stmt->fetch()) {
				array_push($visSchID, $row[0]);
				array_push($type, $row[1]);
				array_push($FN, $row[2]);
				array_push($phoneNo, $row[3]);
				array_push($email, $row[4]);
				array_push($occ, $row[5]);
				array_push($wp, $row[6]);
				array_push($noOfPeople, $row[7]);
				array_push($dates, $row[8]);
				array_push($times, $row[9]);
			}
			array_push($accept, $visSchID, $type, $FN, $phoneNo, $email, $occ, $wp, $noOfPeople, $dates, $times);
			return $accept;
		}
	//Remove visitor schedule if they decided to cancel
	public function removeVisitorSch()
	{
		$sql = "DELETE FROM `Visitor Schedule` WHERE `VisSchID` = ?";
		$result = $this->con->prepare($sql)->execute([$this->visSchID]);
		if ($result) {
			echo "<script>alert('Removed Successfully!');
			document.location = '/View Scheduled Visitors.php' </script>";
		} else {
			echo "<script>alert('Removal Failed!');
			document.location = '/View Scheduled Visitors.php' </script>";
		}
	}
	//Get available time for a specific date user chooses
	public function getScheduleTime($dateForSearch)
	{
		$time = "";
		$stmt = $this->con->prepare("SELECT `Time` FROM `Visitor Schedule` where `date`=?");
		$stmt->execute([$dateForSearch]);
		while ($row = $stmt->fetch()) {
			$time .= $row[0] . "_";
		}
		echo $time;
		return $time;
	}
}
