<?php
include_once 'Connection.php';
include_once 'Account.php';

class Employee extends Account
{
	private $empID, $fullName;
	public $con = "";
	public function __construct()
	{
		//Call the Connection class
		$conObj = new Connection();
		$this->con = $conObj->connect();
	}
	// destructor  
	function __destruct() {}
	//Get and Set Methods
	public function setEmpID($empID)
	{
		$this->empID = $empID;
	}
	public function getEmpID()
	{
		return $this->empID;
	}
	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}
	public function getFullName()
	{
		return $this->fullName;
	}
	//Generate ID for each Employee
	public function generateID()
	{
		$empID = 'Emp-';
		$stmt = $this->con->query("SELECT COUNT(*) from `Employee`");
		while ($row = $stmt->fetch()) {
			$empID .= $row[0] + 1;
		}
		//Check if the employee ID exist in the system
		$stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `EmpID` FROM `Employee` WHERE `EmpID` = ?), 'Not Found')");
		$stmt->execute([$empID]);
		while ($row = $stmt->fetch()) {
			//If it doesnt exist then the ID is correct, it will be returned to the caller.
			if ($row[0] == "Not Found") {
				return $empID;
			} 
			//If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
			else {
				$stmt = $this->con->query("SELECT `EmpID` from `Employee`");
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
						$empID = 'Emp-' . $i;
						return $empID;
					}
				}
			}
		}
	}
	//Add an employee and create an account
	public function addEmployee()
	{
		//Create an account for employee while registering his/her's personal information
		$this->signUp();
		$sql = "INSERT INTO `employee`(`EmpID`, `FullName`, `Role`, `UserName`) VALUES (?,?,?,?)";
		$result = $this->con->prepare($sql)->execute([$this->empID, $this->fullName, $this->role, $this->userName]);
		if ($result) {
			echo "<script>alert('Signup Successfully!);
				document.location = '/Signup.php' </script>";
		} else {
			echo "<script>alert('Signup Failed! Please check your connection.);
				document.location = '/Signup.php' </script>";
		}
	}
	//Update employee and update his/her account
	public function updateEmployee()
	{
		//Update an account for employee while updating his/her's personal information
		$this->updateAccount();
		$sql = "UPDATE Employee SET `FullName`=?, `Role`=? where `UserName`=?";
		$result = $this->con->prepare($sql)->execute([$this->fullName, $this->role, $this->userName]);
		if ($result) {
			echo "<script>alert('Updated Successfully!);
				document.location = '/Signup.php' </script>";
		} else {
			echo "<script>alert('Updated Failed! Please check your connection.);
				document.location = '/Signup.php' </script>";
		}
	}
	//Search for employees personal information
	public function searchEmployee()
	{
		$userName = $this->getUserName();
		$stmt = $this->con->prepare("SELECT `FullName`, `Role` from `Employee` where `UserName` = ?");
		$stmt->execute([$userName]);
		while ($row = $stmt->fetch()) {
			return $row[0] . "_" . $row[1];
		}
	}
}
//Get username to search for an employee
if (isset($_REQUEST["unforsearch"])) {
	$userName = $_REQUEST["unforsearch"];
	$empObj = new Employee();
	$empObj->setUserName($userName);
	$fetchData = $empObj->searchEmployee();
	echo trim($fetchData);
}
