<?php
include_once 'Connection.php';
class BankAccount
{
    public $con = "";
    private $accID, $bankName, $branchName, $accName, $accNo, $transferInfo, $country, $belongsTo;
    public function __construct()
    {
        //Call the Connection class
        $conObj = new Connection();
        $this->con  = $conObj->connect();
    }
    // destructor  
    function __destruct()
    {
    }
    //Get and Set Methods
    public function setAccID($accID) 
    {
        $this->accID = $accID;
    }
    public function getAccID()
    {
        return $this->accID;
    }
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
    }
    public function getBankName()
    {
        return $this->bankName;
    }
    public function setBranchName($branchName)
    {
        $this->branchName = $branchName;
    }
    public function getBranchName()
    {
        return $this->branchName;
    }
    public function setAccName($accName)
    {
        $this->accName = $accName;
    }
    public function getAccName()
    {
        return $this->accName;
    }
    public function setAccNo($accNo)
    {
        $this->accNo = $accNo;
    }
    public function getAccNo()
    {
        return $this->accNo;
    }
    public function setTransferInfo($transferInfo)
    {
        $this->transferInfo = $transferInfo;
    }
    public function getTransferInfo()
    {
        return $this->transferInfo;
    }
    public function setCountry($country)
    {
        $this->country = $country;
    }
    public function getCountry()
    {
        return $this->country;
    }
    public function setBelongsTo($belongsTo)
    {
        $this->belongsTo = $belongsTo;
    }
    public function getBelongsTo()
    {
        return $this->belongsTo;
    }
    //Generate ID for each bank accounts
    public function generateID()
    {
        $accNo = 'Acc-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Bank Account`");
		while ($row = $stmt->fetch()) {
			$accNo .= $row[0] + 1;
        }
        //Check if the bank account ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `AccID` FROM `Bank Account` WHERE `AccID` = ?), 'Not Found')");
		$stmt->execute([$accNo]);
		while ($row = $stmt->fetch()) {
            //If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $accNo;
            } 
            //If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
            else {
				$stmt = $this->con->query("SELECT `AccID` from `Bank Account`");
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
						$accNo = 'Acc-' . $i;
						return $accNo;
					}
				}
			}
		}
    }
    //Add bank account
    public function addBankAccount($empID)
    {

        $sql = "INSERT INTO `bank account`(`AccID`, `BankName`, `BranchName`, `AccountName`, `AccountNumber`, `TransferInfo`, `Country`,`BelongsTo`, `EmpID`) VALUES(?,?,?,?,?,?,?,?,?)";
        $result = $this->con->prepare($sql)->execute([$this->accID, $this->bankName, $this->branchName, $this->accName, $this->accNo, $this->transferInfo, $this->country, $this->belongsTo, $empID]);
        if ($result) {
            echo "Added Successfully!";
        } else {
            echo "Adding Failed!, Please check your connection.";
        }
    }
    //View all bank accounts in an organized format
    public function viewBankAccount()
    {
        $accept = array(); $accID = array(); $bankName = array(); $branchName = array(); $accName = array(); $accNo = array();
        $transferInfo = array(); $empID = array(); $country = array(); $belongsTo = array();
        $stmt = $this->con->query("SELECT `AccID`, `BankName`, `BranchName`, `AccountName`, `AccountNumber`, `TransferInfo`, `EmpID`, `Country`, `BelongsTo` FROM `Bank Account` ORDER BY AccID LIMIT 3");
        while ($row = $stmt->fetch()) {
            array_push($accID, $row[0]);
            array_push($bankName, $row[1]);
            array_push($branchName, $row[2]);
            array_push($accName, $row[3]);
            array_push($accNo, $row[4]);
            array_push($transferInfo, $row[5]);
            array_push($empID, $row[6]);
            array_push($country, $row[7]);
            array_push($belongsTo, $row[8]);
        }
        array_push($accept, $accID, $bankName, $branchName, $accName, $accNo, $transferInfo, $empID, $country, $belongsTo);
        return $accept;
    }
    //Update bank account information
    public function updateBankAccount($empID)
    {
        $sql = "UPDATE `Bank Account` SET `BankName`=?, `BranchName`=?,`AccountName`=?,`AccountNumber`=?, `TransferInfo`=?,`EmpID`=? WHERE `AccID`=?";
        $result = $this->con->prepare($sql)->execute([$this->bankName, $this->branchName, $this->accName, $this->accNo, $this->transferInfo, $empID, $this->accID]);
        if ($result) {
            echo "Updated Successfully!";
        } else {
            echo "Update Failed!, Please check your connection.";
        } 
    }
    //Remove bank account
    public function removeBankAccount()
    {
        $sql = "DELETE FROM `Bank Account` WHERE `AccID` =?";
        $result = $this->con->prepare($sql)->execute([$this->accID]);
        if ($result) {
            echo "<script>alert('Removed Successfully!');
                document.location = '/ManageBankAccount.php' </script>";
        } else {
            echo "<script>alert('Remove Failed!, Please check your connection.);
				document.location = '/ManageBankAccount.php' </script>";
        }
    }
}
