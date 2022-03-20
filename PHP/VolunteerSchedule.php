<?php
include_once 'Connection.php';
//Get activity and return to the ajax code
if (isset($_REQUEST["date"])) {
    $date = $_REQUEST["date"];
    $volSch = new VolunteerSchedule();
    $volSch->setDate($date);
    $type = $volSch->getActivity();
    $activity = explode("-", $type);
    for ($i = 0; $i < sizeof($activity); $i++) {
        $count = $volSch->getCount($date, $activity[$i]); //Volunteer Schedule
        $NP = $volSch->getNP($activity[$i]); //Activity
        if ($count < $NP) {
            echo $activity[$i] . "_";
        }
    }
}
//Get events and return to the ajax code
if (isset($_REQUEST["edate"])) {
    $date = $_REQUEST["edate"];
    $volSch = new VolunteerSchedule();
    $eveType = $volSch->getEvent($date);
    $events = explode("-", $eveType);
    for ($i = 0; $i < sizeof($events); $i++) {
        $count = $volSch->getCount($date, $events[$i]); //Volunteer Schedule
        $NP = $volSch->getNPForEvent($events[$i]); //Activity
        if ($count < $NP) {
            echo $events[$i] . "_";
        }
    }
}
//Get time and return to the ajax code
if (isset($_REQUEST["time"])) {
    $act = $_REQUEST["time"];
    $volSch = new VolunteerSchedule();
    $accept = explode("/", $act);
    $startTime = $volSch->getST($act);
    echo $startTime;
    $eveTime = $volSch->getSTForEvent($act);
    echo $eveTime;
}

class VolunteerSchedule
{
    public $con = "";
    private $volSchID, $date;
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
    public function setVolSchID($volSchID)
    {
        $this->volSchID = $volSchID;
    }
    public function getVolSchID()
    {
        return $this->volSchID;
    }
    public function setDate($date)
    {
        $this->date = $date;
    }
    public function getDate()
    {
        return $this->date;
    }
    //Generate ID for each schedule based on volunteer ID
    public function generateID($volID)
    {
        $volSchID = $volID . '-Sch-';
        $sql = $stmt = $this->con->prepare("SELECT COUNT(*) from `Volunteer Schedule` where `VolID` = '$volID'");
        $stmt->execute([$volID]);
        while ($row = $stmt->fetch()) {
            $volSchID .= $row[0] + 1;
        }
        //Check if the schedule ID exist in the system
        $stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `VolSchID` FROM `Volunteer Schedule` WHERE `VolSchID` = ?), 'Not Found')");
        $stmt->execute([$volSchID]);
        while ($row = $stmt->fetch()) {
            //If it doesnt exist then the ID is correct, it will be returned to the caller.
            if ($row[0] == "Not Found") {
                return $volSchID;
            }
            //If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID. 
            else {
                $stmt1 = $this->con->prepare("SELECT * from `Volunteer Schedule` where VolID=?");
                $stmt1->execute([$volID]);
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
                        $volSchID = $volID . '-Sch-' . $i;
                        return $volSchID;
                    }
                }
            }
        }
    }
    //Get activity for each date the user selected
    public function getActivity()
    {
        $dates = $this->date;
        //Convert the date string into a timestamp.
        $timestamp = strtotime($dates);
        //Get the day of the week using PHP's date function.
        $dayOfWeek = date("l", $timestamp);
        $type = "";
        $daily = "daily,";
        $stmt = $this->con->prepare("SELECT `Type` FROM `activity` where duration like ? or duration=?");
        $stmt->execute(["%$dayOfWeek%", $daily]);
        while ($row = $stmt->fetch()) {
            $type .= $row[0] . "-";
        }
        return $type;
    }
    //Get events for specific date the user selected
    public function getEvent($dates)
    {
        $eveType = "";
        $stmt = $this->con->prepare("SELECT `EventID`, `EventType`, `Date`, `Time` FROM `Event` where `date` = ?");
        $stmt->execute([$dates]);
        while ($row = $stmt->fetch()) {

            $eveType .= $row[1] . "-";
        }
        return $eveType;
    }
    //Adds volunteer schedule
    public function addVolSchedule($volID, $activity)
    {
        $sql = "INSERT INTO `volunteer schedule`(`VolSchID`, `VolID`, `Date`, `Activity`) VALUES(?,?,?,?)";
        $result = $this->con->prepare($sql)->execute([$this->volSchID, $volID, $this->date, $activity]);
        if ($result) {
            echo "<script>alert('Scheduled Succesfully!');
            document.location = '/Schedule Volunteer.php' </script>";
        } else {
            echo "<script>alert('Scheduling Failed! Please check your connection.');
            document.location = '/Schedule Volunteer.php' </script>";
        }
    }
    //View all the volunteers' schedules 
    public function viewVolSchedule()
    {
        $accept = array(); $volSchIDArray = array(); $volIDArray = array();
        $fullNameArray = array(); $dateArray = array(); $activityArray = array();
        $stmt = $this->con->query("SELECT `volunteer schedule`.`VolSchID`, volunteer.VolID, volunteer.FullName, `volunteer schedule`.`Date`, `volunteer schedule`.`Activity` FROM `Volunteer` INNER JOIN `Volunteer Schedule` ON `Volunteer`.`VolID`=`Volunteer Schedule`.`VolID`");
        while ($row = $stmt->fetch()) {
            array_push($volSchIDArray, $row[0]);
            array_push($volIDArray, $row[1]);
            array_push($fullNameArray, $row[2]);
            array_push($dateArray, $row[3]);
            array_push($activityArray, $row[4]);
        }
        array_push($accept, $volSchIDArray, $volIDArray, $fullNameArray, $dateArray, $activityArray);
        return $accept;
    }
    //Search volunteer schedule by date
    public function searchByDate()
    {
        $accept = array(); $volSchIDArray = array(); $volIDArray = array(); $dateArray = array(); $activityArray = array();;
        $stmt = $this->con->prepare("SELECT `VolSchID`, `VolID`, `Date`, `Activity` FROM `volunteer schedule` where date(`Date`)=?");
        $stmt->execute([$this->date]);
        while ($row = $stmt->fetch()) {
            array_push($volSchIDArray, $row[0]);
            array_push($volIDArray, $row[1]);
            array_push($dateArray, $row[2]);
            array_push($activityArray, $row[3]);
        }
        array_push($accept, $volSchIDArray, $volIDArray, $dateArray, $activityArray);
        return $accept;
    }
    //Search the schedules of a specfic volunteer
    public function searchVolSchedule($volID)
    {
        $today = date("y-m-d H:i");
        $accept = array(); $volSchIDArray = array(); $dateArray = array(); $activityArray = array();
        $stmt = $this->con->prepare("SELECT `VolSchID`, `Date`, `Activity` FROM `volunteer schedule` where `VolID`=? and date(`Date`)>?");
        $stmt->execute([$volID, $today]);
        while ($row = $stmt->fetch()) {
            array_push($volSchIDArray, $row[0]);
            array_push($dateArray, $row[1]);
            array_push($activityArray, $row[2]);
        }
        array_push($accept, $volSchIDArray, $dateArray, $activityArray);
        return $accept;
    }
    //Update volunteer schedule
    public function updateVolSchedule($activity)
    {
        $sql = "UPDATE `volunteer schedule` SET `Date`=?,`Activity`=? WHERE VolSchID = ?";
        $result = $this->con->prepare($sql)->execute([$this->date, $activity, $this->volSchID]);
        if ($result) {
            echo "<script>alert('Updated Successfully!');
                document.location = '/Schedule Volunteer.php' </script>";
        } else {
            echo "<script>alert('Update Failed! Please check your connection');
            document.location = '/Schedule Volunteer.php' </script>";
        }
    }
    //Remove a volunteer schedule using the schedule ID
    public function removeVolSchedule()
    {
        $sql = "DELETE FROM `volunteer schedule` WHERE `VolSchID` = ?";
        $result = $this->con->prepare($sql)->execute([$this->volSchID]);
        if ($result) {
            echo "<script>alert('Removed Successfully!');
            document.location = '/Schedule Volunteer.php' </script>";
        } else {
            echo "<script>alert('Remaoval Failed! Please check your connection');
            document.location = '/Schedule Volunteer.php' </script>";
        }
    }
    //Count how many people scheduled a particular activity on a specfic date
    public function getCount($date, $activity)
    {
        $stmt = $this->con->prepare("SELECT COUNT(*) from `Volunteer Schedule` where date(`Date`) = ? AND `activity` = ?");
        $stmt->execute([$date, $activity]);
        while ($row = $stmt->fetch()) {
            return $row[0];
        }
    }
    //Get number of volunteer needed to execute an activity
    public function getNP($activity)
    {
        $stmt = $this->con->prepare("SELECT `PeopleNeeded` from `Activity` where `Type` = ?");
        $stmt->execute([$activity]);
        while ($row = $stmt->fetch()) {
            return $row[0];
        }
    }
    //Get the number of volunteers that are needed to execute an event
    public function getNPForEvent($events)
    {
        $stmt = $this->con->prepare("SELECT `PeopleNeeded` from `Event` where `EventType` = ?");
        $stmt->execute([$events]);
        while ($row = $stmt->fetch()) {
            return $row[0];
        }
    }
    //Get start time of an activiy
    public function getST($activity)
    {
        $accept = explode("/", $activity);
        $act = trim($accept[0]);
        $stmt = $this->con->prepare("SELECT `StartTime`, `EndTime` from `Activity` where `Type` = ?");
        $stmt->execute([$act]);
        while ($row = $stmt->fetch()) {
            return $row[0] . "_" . $row[1];
        }
    }
    //Get start time of an event
    public function getSTForEvent($eveType)
    {
        $array = "";
        $accept = explode("/", $eveType);
        $eve = trim($accept[0]);
        $date = trim($accept[1]);
        $stmt = $this->con->prepare("SELECT `Time` from `Event` where `EventType` = ? and `Date`= ?");
        $stmt->execute([$eve, $date]);
        while ($row = $stmt->fetch()) {
            $array .= $row[0] . "_";
        }
        return $array;
    }
}
