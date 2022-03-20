<?php

include_once 'Connection.php';
//Automatically get TreatmentID from memberID
if (isset($_REQUEST["memID"])) {
	$memID = $_REQUEST["memID"];
	$treObj = new TreatmentSchedule();
	$treid = $treObj->generateID($memID);
	echo $treid;
}

class TreatmentSchedule
{
	private $con = "";
	private $treSchID, $type, $treatmentName, $dose, $startDate, $endDate, $startTime, $hoursDif, $purpose, $inst, $empID;
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
	public function setTreSchID($treSchID)
	{
		$this->treSchID = $treSchID;
	}
	public function getTreSchID()
	{
		return $this->treSchID;
	}
	public function setType($type)
	{
		$this->type = $type;
	}
	public function getType()
	{
		return $this->type;
	}
	public function setTreName($treatmentName)
	{
		$this->treatmentName = $treatmentName;
	}
	public function getTreName()
	{
		return $this->treatmentName;
	}
	public function setDose($dose)
	{
		$this->dose = $dose;
	}
	public function getDose()
	{
		return $this->dose;
	}
	public function setSDate($startDate)
	{
		$this->startDate = $startDate;
		$dateTime = explode(" ", $startDate);
		$this->startTime = $dateTime[1];
	}
	public function getSDate()
	{
		return $this->startDate;
	}
	public function setEDate($endDate)
	{
		$this->endDate = $endDate;
	}
	public function getEDate()
	{
		return $this->endDate;
	}
	public function setHrDlf($hoursDif)
	{
		$this->hoursDif = $hoursDif;
	}
	public function getHrDlf()
	{
		return $this->hoursDif;
	}
	public function setPurpose($purpose)
	{
		$this->purpose = $purpose;
	}
	public function getPurpose()
	{
		return $this->purpose;
	}
	public function setSI($inst)
	{
		$this->inst = $inst;
	}
	public function getSI()
	{
		return $this->inst;
	}
	//Generate ID for each schedule based on member's ID
	public function generateID($memberID)
	{
		$treID = $memberID . '-Tre-';
		$stmt = $this->con->prepare("SELECT COUNT(*) from `Treatment Schedule` where MemberID = ?");
		$stmt->execute([$memberID]);
		while ($row = $stmt->fetch()) {
			$treID .= $row[0] + 1;
		}
		//Check if the treatment ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `TreID` FROM `Treatment Schedule` WHERE `TreID` = ?), 'Not Found')");
		$stmt->execute([$treID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $treID;
			} 
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt1 = $this->con->prepare("SELECT * FROM `Treatment Schedule` WHERE `TreID` = ?");
				$stmt1->execute([$treID]);
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
						$treID = $memberID . '-Sch-' . $i;
						return $treID;
					}
				}
			}
		}
	}
	//Check if the schedule was already added
	public function checkSchedule($memberID)
	{
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `TreID` FROM `Treatment Schedule` WHERE `MemberID` = ? AND `TreatmentName` = ? AND `StartDate` = ?), 'Empty')");
		$stmt->execute([$memberID, $this->treatmentName, $this->startDate]);
		while ($row = $stmt->fetch()) {
			return $row[0];
		}
	}
	//Add member's treatment schedule
	public function addTreSchedule($memberID, $empID)
	{
		$sql = "INSERT INTO `treatment schedule` (`TreID`, `MemberID`, `TreatmentType`, `TreatmentName`, `Dose`, `StartDate`, `EndDate`, `STime`, `HoursDifference`, `Purpose`, `Instruction`, `EmpID`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->treSchID, $memberID, $this->type, $this->treatmentName, $this->dose, $this->startDate, $this->endDate, $this->startTime, $this->hoursDif, $this->purpose, $this->inst, $empID]);
		if ($result) {
			echo "<script>alert('Added Successfully');
			document.location = '/Schedule Treatment.php' </script>";
		} else {
			echo "<script>alert('Adding Failed! Please check your connection.');
			document.location = '/Schedule Treatment.php' </script>";
		}
	}
	//Search treatment schedule based on member's ID
	public function searchTreSchedule($memberID)
	{
		$accept = array(); $treID = array(); $memID = array(); $treType = array(); $treName = array(); $dose = array();
		$sDate = array(); $eDate = array(); $sTime = array(); $hoursDif = array(); $purpose = array(); $sInst = array();
		$empID = array();
		$stmt = $this->con->prepare("SELECT `TreID`, `MemberID`, `TreatmentType`, `TreatmentName`, `Dose`, `StartDate`, `EndDate`, `STime`, `HoursDifference`, `Purpose`, `Instruction`, `EmpID` FROM `treatment schedule` where MemberID = ?");
		$stmt->execute([$memberID]);
		while ($row = $stmt->fetch()) {
			array_push($treID, $row[0]);
			array_push($memID, $row[1]);
			array_push($treType, $row[2]);
			array_push($treName, $row[3]);
			array_push($dose, $row[4]);
			array_push($sDate, $row[5]);
			array_push($eDate, $row[6]);
			array_push($sTime, $row[7]);
			array_push($hoursDif, $row[8]);
			array_push($purpose, $row[9]);
			array_push($sInst, $row[10]);
			array_push($empID, $row[11]);
		}
		array_push($accept, $treID, $memID, $treType, $treName, $dose, $sDate, $eDate, $sTime, $hoursDif, $purpose, $sInst, $empID);
		return $accept;
	}
	//Update treatment schedule of member
	public function updateTreSchedule($empID)
	{
		$sql = "UPDATE `treatment schedule` SET `TreatmentType` = ?, `TreatmentName` = ?, `Dose` = ?, StartDate = ?, EndDate = ?, HoursDifference = ?, Purpose = ?, Instruction = ?, `EmpID` = ? WHERE TreID = ?";
		$result = $this->con->prepare($sql)->execute([$this->type, $this->treatmentName, $this->dose, $this->startDate, $this->endDate, $this->hoursDif, $this->purpose, $this->inst, $empID, $this->treSchID]);
		if ($result) {
			echo "<script>alert('Updated Successfully!');
			document.location = '/Update Treatment Schedule.php' </script>";
		} else {
			echo "<script>alert('Update Failed! Please check your connection.');
			document.location = '/Update Treatment Schedule.php' </script>";
		}
	}
	//Remove treatment schedule of a member
	public function removeTreSchedule()
	{
		$sql = "DELETE FROM `treatment schedule` WHERE `TreID` = ?";
		$result = $this->con->prepare($sql)->execute([$this->treSchID]);
		if ($result) {
			echo "<script>alert('Removed Successfully!');
			document.location = '/Update Treatment Schedule.php' </script>";
		} else {
			echo "<script>alert('Removing Failed! Please check your connection.');
			document.location = '/Update Treatment Schedule.php' </script>";
		}
	}
	//Retrieve  daily treatment schedule for each day 
	public function getDailyTreSchedule()
	{
		$stmt = $this->con->query("SELECT member.FullName, `TreID`, `TreatmentType`, `TreatmentName`, `Dose`, date(`StartDate`), date(`EndDate`), time(`StartDate`), `HoursDifference`, `Purpose`, `Instruction` FROM `treatment schedule` INNER JOIN member ON `treatment schedule`.`MemberID`=Member.MemberID;");
		while ($row = $stmt->fetch()) {
			return $row;
		}
	}
	//To print treatment schedule for referral purposes
	public function exportTreSchedule($memberID)
	{
		function filterData(&$str)
		{
			$str = preg_replace("/\t/", "\\t", $str);
			$str = preg_replace("/\r?\n/", "\\n", $str);
			if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
		}
		$fields = array('TreID.', 'MemberID', 'Type', 'Name', 'Dose', 'Start Date', 'End Date', 'Purpose', 'Instruction');
		$excelData = implode("\t", array_values($fields)) . "\n";
		$query = $this->con->prepare("SELECT `FullName` FROM `Member` where `MemberID` = ?");
		$query->execute([$memberID]);
		// Excel file name for download 
		while ($row = $query->fetch()) {
			$fileName = $row['FullName'] . '-' . date('Y-m-d') . ".xls";
			$filePath = 'C:\Users\Mekdi\Documents\Referral/' . $fileName;
			// Get records from the database 
			$stmt = $this->con->prepare("SELECT `TreID`, `MemberID`, `TreatmentType`, `TreatmentName`, `Dose`, `StartDate`, `EndDate`, `Purpose`, `Instruction` FROM `Treatment Schedule` where `MemberID` = ?");
			$stmt->execute([$memberID]);
			$i = 0;
			while ($row = $stmt->fetch()) {
				$i++;
				$rowData = array($row['TreID'], $row['MemberID'], $row['TreatmentType'], $row['TreatmentName'], $row['Dose'], $row['StartDate'], $row['EndDate'], $row['Purpose'], $row['Instruction']);
				array_walk($rowData, __NAMESPACE__ . '\filterData');
				$excelData .= implode("\t", array_values($rowData)) . "\n";
			}
			// Headers for download 
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			header("Content-Type: application/vnd.ms-excel");
			if (file_put_contents($filePath, $excelData) !== false) {
				echo "File created (" . basename($excelData) . ").";
			} else {
				echo "Cannot create the file (" . basename($filePath) . ").";
			}
			// Render excel data 
			echo $excelData;
		}
		exit;
	}
}
