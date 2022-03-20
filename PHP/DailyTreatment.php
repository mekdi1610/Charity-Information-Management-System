<?php
include_once 'Connection.php';
$dailyTreObj = new DailyTreatment();
session_start();
error_reporting(0);

if (isset($_SESSION["EmpID"])) {
    $empID = $_SESSION["EmpID"];
}
if (isset($_REQUEST["shift"])) {
    $shift = $_REQUEST["shift"];
    $dataToUse = explode("_", $shift);
    $dailyTreObj->getDailyTreatment($dataToUse[0], $dataToUse[1]);
}
if (isset($_REQUEST["check"])) {
    $dailyTreObj->checkExpire();
}
if (isset($_REQUEST["timestamp"])) {
    $timestamp = $_REQUEST["timestamp"];
    $accept = explode("/", $timestamp);
    $treID = $accept[0];
    $dailyID = $dailyTreObj->generateDailyID($treID);
    $dateTime = date("Y-m-d H:i");
    $dailyTreObj->addDailyTreatment($treID, $dateTime, $empID);
    exit();
}
class DailyTreatment
{
    public $con = "";
    private $dailyTreID;
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
    public function setDailyTreID($dailyTreID)
    {
        $this->dailyTreID = $dailyTreID;
    }
    public function getDailyTreID()
    {
        return $this->dailyTreID;
    }
    //Generate ID for each daily treatment adminstered 
    public function generateDailyID($treID)
    {
        $dailyTreID = $treID . '-D-';
        $stmt = $this->con->prepare("SELECT COUNT(*) from `Daily Treatment` where treID = ?");
        $stmt->execute([$treID]);
        while ($row = $stmt->fetch()) {
            $dailyTreID .= $row[0] + 1;
            $this->setDailyTreID($dailyTreID);
        }
        //Check if the daily treatment ID exist in the system
        $stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `DailyTreID` FROM `Daily Treatment` WHERE `DailyTreID` = ?), 'Not Found')");
        $stmt->execute([$dailyTreID]);
        while ($row = $stmt->fetch()) {
            //If it doesnt exist then the ID is correct, it will be returned to the caller.
            if ($row[0] == "Not Found") {
                return $dailyTreID;
            }
            //If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
            else {
                $stmt = $this->con->query("SELECT `DailyTreID` from `Daily Treatment`");
                $i = 1;
                while ($row = $stmt->fetch()) {
                    $noExp = explode("-", $row[5]);
                    $no = $noExp[1];
                    //Determine the missing ID
                    if ($no == $i) {
                        $i++;
                    }
                    //Make the missing ID the current ID to fill the gap
                    else {
                        $dailyTreID = $treID . '-D-' . $i;
                        return $dailyTreID;
                    }
                }
            }
        }
    }
    //Check if treatement is already adminstered on previous shifts to add hours difference
    public function checkDuplicate($treID)
    {
        $date = date("Y-m-d");
        $count = 0;
        $arrayAdd = array();
        //Check for duplicates with the same TreID and Date
        $stmt = $this->con->prepare("SELECT COUNT(*), max(time(`Date`)) FROM `daily treatment` WHERE `TreID` = ? AND date(Date)= ?");
        $stmt->execute([$treID, $date]);
        while ($row = $stmt->fetch()) {
            $count = $row[0];
            //Not yet administered today
            if ($count == 0) {
                $time = 0;
            }
            //Administered today
            else {
                $time = $row[1];
            }
        }
        array_push($arrayAdd, $count, $time);
        return $arrayAdd;
    }
    //Add new adminstered treatment
    public function addDailyTreatment($TreID, $time, $empID)
    {
        $sql = "INSERT INTO `daily treatment`(`dailyTreID`, `treID`, `Date`, `EmpID`) VALUES(?,?,?,?)";
        $result = $this->con->prepare($sql)->execute([$this->dailyTreID, $TreID, $time, $empID]);
        if ($result) {
            echo "Added Successfully!";
        } else {
            echo "Adding Failed! Please check your connection.";
        }
    }
    //Get all the daily treatments with specific shift and type
    public function getDailyTreatment($shift, $type)
    {
        $accept = array();
        $memID = array();
        $treType = array();
        $treName = array();
        $dose = array();
        $sTime = array();
        $purpose = array();
        $sInst = array();
        $ShiftS1 = 0;
        $ShiftE1 = 0;
        //If the administrator is on the first shift, time is set from 8:00 to 15:59
        if ($shift == "Shift 1") {
            $ShiftS1 = date("H:i", strtotime("08:00")) . " ";
            $ShiftE1 = date("H:i", strtotime("15:59")) . " ";
        }
        //If the administrator is on the second shift, time is set from 16:00 to 23:59
        if ($shift == "Shift 2") {
            $ShiftS1 = date("H:i", strtotime("16:00")) . " ";
            $ShiftE1 = date("H:i", strtotime("23:59")) . " ";
        }
        //If the administrator is on the third shift, time is set from 00:00 to 7:59
        if ($shift == "Shift 3") {
            $ShiftS1 = date("H:i", strtotime("00:00")) . " ";
            $ShiftE1 = date("H:i", strtotime("7:59")) . " ";
        }
        //Prepare the table
        echo "<thead>";
        echo "<tr>";
        echo "<th>Tre-Type</th>";
        echo "<th>Name</th>";
        echo "<th>TreID</th>";
        echo "<th>Tre-Name</th>";
        echo "<th>Dose</th>";
        echo "<th>STime</th>";
        echo "<th>Purpose</th>";
        echo "<th>Instruction</th>";
        echo "<th>Check</th>";
        echo "</tr>";
        echo "</thead>";
        //Get all the treatments to display in the table
        $stmt = $this->con->prepare("SELECT member.FullName, `TreID`, `TreatmentType`, `TreatmentName`, `Dose`, date(`StartDate`), date(`EndDate`), time(`StartDate`), `HoursDifference`, `Purpose`, `Instruction` FROM `treatment schedule` INNER JOIN member ON `treatment schedule`.`MemberID`=Member.MemberID where `TreatmentType`=?");
        $stmt->execute([$type]);
        $times = 0;
        while ($row = $stmt->fetch()) {
            $today = date("Y-m-d");
            $startDate = $row[5];
            $endDate = $row[6];
            $dailyTreObj = new DailyTreatment();
            //Check if the treatment is already adminstered that day
            $accept = $dailyTreObj->checkDuplicate($row[1]);
            $count = $accept[0];
            $timeAccepted = $accept[1];
            $HrDif = $row[8];
            // The first treatment that day
            if ($count == 0) {
                $time = strtotime($row[7]);
                $times = date('H:i', $time) . " ";
            }
            //Treatment already adminstered that day so look for the time or date to adminster next
            if ($count > 0) {
                $time = strtotime(+$HrDif . 'hour', strtotime($timeAccepted));
                $times = date('H:i', $time) . " ";
            }
            /*Make sure the date of the treatment is equal to or more than today's date.
            Make sure you are on the right shift by comparing the time gap.
            Make sure its less than 24 hours to increment within the shifts.*/
            if ($startDate <= $today && $today < $endDate && $times >= $ShiftS1 && $times <= $ShiftE1 && $HrDif < 24) {
                echo "<tr>";
                //Set up different icons but open the same page
                if ($row[2] == "Medication") {
                    echo '<td> <a href="#modal2" data-toggle="modal" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-medkit"></i></a></td>';
                } else {
                    echo '<td> <a href="#modal2" data-toggle="modal" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-wheelchair" aria-hidden="true"></i></a></td>';
                }
                echo "<td>$row[0]</td>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[3]</td>";
                echo "<td>$row[4]</td>";
                echo "<td>$times</td>";
                echo "<td>$row[8]</td>";
                echo "<td>$row[10]</td>";
                if ($HrDif >= 8) {
                    $shift = 0;
                }
                //Checkbox
                echo '<td> <input type="checkbox" class="check" name="check[]" value="' . $row[1] . '/' . $shift . '" onclick="addTimeStamp(this); adjustTable(this);"></td>';
                echo "</tr>";
            }
        }
        array_push($accept, $memID, $treType, $treName, $dose, $sTime, $purpose, $sInst);
        return $accept;
    }
    //Check if the data in the table "Daily Treatment" has expired (older than 2month)
    public function checkExpire()
    {
        $stmt = $this->con->query("SELECT count(`dailyTreID`), MIN(`Date`) FROM `daily treatment`");
        while ($row = $stmt->fetch()) {
            $noRows = $row[0];
            $minDate = $row[1];
        }
        if ($noRows != 0) {
            //To export after two month
            $today = date("Y-m-d");
            $dateFromDB = explode("-", $minDate); //Date from database
            $newMonth = $dateFromDB[1] + 2;
            $newDate = $dateFromDB[0] . "-" . $newMonth . "-" . $dateFromDB[2];
            //If the data is expired, a button is displayed to export the table information to an excel document
            if ($newDate < $today) {
                echo '<form class="form-horizontal row-fluid" action="../DailyTreatment.php" method="post">';
                echo '<button type="submit" class="print" id="export" name="Export"><i class="fa fa-share" aria-hidden="true"></i>Export</button>';
                echo "</form>";
            }
        }
    }
    //Export to excel if the data expired and clear the table "Daily Treatment"
    public function exportOnExpire()
    {
        function filterData(&$str)
        {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }
        $fields = array('DailyTreID.', 'TreID', 'TimeStamp', 'EmpID');
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n";
        // Excel file name for download 
        $fileName = date('Y-m-d') . ".xls";
        $filePath = 'C:\Users\Mekdi\Documents\DailySchedule/' . $fileName;
        // Get records from the database 
        $query2 = $this->con->prepare("SELECT `dailyTreID`, `treID`, `Date`, `EmpID` FROM `Daily Treatment`");
        $query2->execute();
        // Output each row of the data 
        $i = 0;
        while ($row = $query2->fetch()) {
            $i++;
            $rowData = array($row['dailyTreID'], $row['treID'], $row['Date'], $row['EmpID']);
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
        // Render excel data 
        ///Remove data from database after its exported
        $sql = "DELETE FROM `daily treatment`";
        $result = $this->con->prepare($sql)->execute();
        if ($result) {
            echo "<script>alert('Schedule BackedUp!');
            document.location = '/Add Daily Schedule.php' </script>";
        } else {
            echo "<script>alert('Backup Failed!');
            document.location = '/Add Daily Schedule.php' </script>";
        }

        exit;
    }
}

?>