<?php
include_once 'Connection.php';
class Report
{
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
    //Generate report with any selected member data
    public function generateMemReport($gen, $rel, $ms, $iL, $sta)
    {
        //Customized queries
        $report = array(); $memID = []; $FN = []; $AD = [];
        if ($gen == null) {
            $gender = "`Gender` is not null";
        } else {
            $gender = "`Gender`='$gen'";
        }
        if ($rel == null) {
            $Religion = "`Religion` is not null";
        } else {
            $Religion = "`Religion`='$rel'";
        }
        if ($ms == null) {
            $martialStatus = "`MaritalStatus` is not null";
        } else {
            $martialStatus = "`MaritalStatus`='$ms'";
        }
        if ($iL == null) {
            $initalLocation = "`InitalLocation` is not null";
        } else {
            $initalLocation = "`InitalLocation`='$iL'";
        }
        if ($iL == null) {
            $status = "`Status` is not null";
        } else {
            $status = "`Status`='$sta'";
        }
        $stmt = $this->con->query("SELECT `MemberID`, `FullName`, `AdmittedDate` FROM `member` where $gender AND $Religion AND $martialStatus AND $initalLocation AND $status order by FullName");
        while ($row = $stmt->fetch()) {
            array_push($memID, $row[0]);
            array_push($FN, $row[1]);
            array_push($AD, $row[2]);
        }
        array_push($report, $memID, $FN, $AD);
        return $report;
    }
    //Print report that was generated from member's information
    public function printMemReport($gen, $rel, $ms, $iL, $sta)
    {
        //Call generateMemReport to get all the information of the current report
        $report = $this->generateMemReport($gen, $rel, $ms, $iL, $sta);
        $memberID = $report[0];
        $fullName = $report[1];
        $admittedDate = $report[2];
        function filterDatas(&$str)
        {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }
        $fields = array('No.', 'Full Name', 'Admitted Date');
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n";
        $fileName = "Member's Report" . '-' . date('Y-m-d') . ".xls";
        $filePath = 'C:\Users\Mekdi\Documents\Reports/' . $fileName;
        if (sizeof($memberID) > 0) {
            // Output each row of the data 
            $no = 1;
            for ($i = 0; $i < sizeof($memberID); $i++) {
                $rowData = array($no, $fullName[$i], $admittedDate[$i]);
                array_walk($rowData, __NAMESPACE__ . '\filterDatas');
                $excelData .= implode("\t", array_values($rowData)) . "\n";
                $no++;
            }
        } else {
            $excelData .= 'No records found...' . "\n";
        }
        // Headers for download 
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Type: application/vnd.ms-excel");
        if (file_put_contents($filePath, $excelData) !== false) {
            echo "File created (" . basename($excelData) . ").";
        } else {
            echo "Cannot create the file (" . basename($filePath) . ").";
        }
    }
    //To auto generate occupation on the drop down menu
    public function getOccupation()
    {
        $occ = [];
        $stmt = $this->con->query("SELECT DISTINCT Occupation from Volunteer UNION SELECT DISTINCT Occupation from donator UNION SELECT DISTINCT Occupation from visitor");
        while ($row = $stmt->fetch()) {
            array_push($occ, $row[0]);
        }
        return $occ;
    }
    //Generate report with any contributor data
    public function generateConReport($type, $gen, $occ)
    {
        $report = array(); $FN = []; $phoneNo = []; $email = []; $wp = [];
        //Customized queries
        if ($gen == null) {
            $gender = "`Gender` is not null";
        } else {
            $gender = "`Gender`='$gen'";
        }
        if ($occ == null) {
            $Occupation = "`Occupation` is not null";
        } else {
            $Occupation = "`Occupation`='$occ'";
        }
        if ($type == null) {
            $stmt = $this->con->query("SELECT `FullName`, `PhoneNo`, `Email`, `WorkPlace` FROM Volunteer UNION SELECT `FullName`, `PhoneNo`, `Email`, `WorkPlace` FROM Donator UNION SELECT `FullName`, `PhoneNo`, `Email`, `WorkPlace` FROM Visitor where $gender AND $Occupation order by FullName");
            while ($row = $stmt->fetch()) {
                array_push($FN, $row[0]);
                array_push($phoneNo, $row[1]);
                array_push($email, $row[2]);
                array_push($wp, $row[3]);
            }
        } else {
            $stmt = $this->con->query("SELECT `FullName`, `PhoneNo`, `Email`, `WorkPlace` FROM $type where $gender AND $Occupation order by FullName");
            while ($row = $stmt->fetch()) {
                array_push($FN, $row[0]);
                array_push($phoneNo, $row[1]);
                array_push($email, $row[2]);
                array_push($wp, $row[3]);
            }
        }
        array_push($report, $FN, $phoneNo, $email, $wp);
        return $report;
    }
    //Print report that was generated from contributor's information
    public function printConReport($type, $gen, $occ)
    {
        //Call generateMemReport to get all the information of the current report
        $reportForCon = $this->generateConReport($type, $gen, $occ);
        $fullName = $reportForCon[0];
        $phoneNo = $reportForCon[1];
        $email = $reportForCon[2];
        $wp = $reportForCon[3];
        function filterData(&$str)
        {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }
        $fields = array('No.', 'Full Name', 'PhoneNo', 'Email', 'Work Place');
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n";
        $fileName = "Contributor's Report" . '-' . date('Y-m-d') . ".xls";
        $filePath = 'C:\Users\Mekdi\Documents\Reports/' . $fileName;

        if (sizeof($fullName) > 0) {
            // Output each row of the data 
            $no = 1;
            for ($i = 0; $i < sizeof($fullName); $i++) {
                $rowData = array($no, $fullName[$i], $phoneNo[$i], $email[$i], $wp[$i]);
                array_walk($rowData, __NAMESPACE__ . '\filterData');
                $excelData .= implode("\t", array_values($rowData)) . "\n";
                $no++;
            }
        } else {
            $excelData .= 'No records found...' . "\n";
        }
        // Headers for download 
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Type: application/vnd.ms-excel");
        if (file_put_contents($filePath, $excelData) !== false) {
            echo "File created (" . basename($excelData) . ").";
        } else {
            echo "Cannot create the file (" . basename($filePath) . ").";
        }
    }
}
