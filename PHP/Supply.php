<?php
include_once 'Connection.php';
class Supply
{
    public $con = "";
    private $supID, $product, $type, $description, $quantity, $urgencyLevel;
    public function __construct()
    {
        //Call to Connection class
        $conObj = new Connection();
        $this->con = $conObj->connect();
    }
    // destructor  
    function __destruct()
    {
    }
    //Get and Set Methods  
    public function setSupID($supID)
    {
        $this->supID = $supID;
    }
    public function getSupID()
    {
        return $this->supID;
    }
    public function setProduct($product)
    {
        $this->product = $product;
    }
    public function getProduct()
    {
        return $this->product;
    }
    public function setType($type)
    {
        $this->type = $type;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function setUrgencyLevel($urgencyLevel)
    {
        $this->urgencyLevel = $urgencyLevel;
    }
    public function getUrgencyLevel()
    {
        return $this->urgencyLevel;
    }
    //Generate ID for each Supply
    public function generateID()
    {
        $supID = 'Sup-';
        $stmt = $this->con->query("SELECT COUNT(*) from `Supply`");
        while ($row = $stmt->fetch()) {
            $supID .= $row[0] + 1;
        }
        //Check if the supply ID exist in the system
        $stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `SupID` FROM `Supply` WHERE `SupID` = ?), 'Not Found')");
        $stmt->execute([$supID]);
        while ($row = $stmt->fetch()) {
            //If it doesnt exist then the ID is correct, it will be returned to the caller.
            if ($row[0] == "Not Found") {
                return $supID;
            } 
            //If it exist in the database then the generated ID would be a duplicate key so we will retrieve all the IDs to determine the missing ID.
            else {
                $stmt1 = $this->con->query("SELECT * from `Supply`");
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
                        $supID = 'Sup-' . $i;
                        return $supID;
                    }
                }
            }
        }
    }
    //Add supply to the database
    public function addSupply($empID)
    {
        //Inserted
        $sql = "INSERT INTO `Supply`(`SupID`, `Product`, `Type`, `Description`, `Quantity`, `UrgencyLevel`, `EmpID`) VALUES(?,?,?,?,?,?,?)";
        $result = $this->con->prepare($sql)->execute([$this->supID, $this->product, $this->type, $this->description, $this->quantity, $this->urgencyLevel, $empID]);
        if ($result) {
            echo "<script>alert('Added Succesfully!');
                document.location = '/Manage Supply List.php' </script>";
        } else {
            echo "<script>alert('Adding Failed! Please check your connection.');
                document.location = '/Manage Supply List.php' </script>";
        }
    }
    //View all the supplies in an organized format
    public function viewSupplies()
    {
        $accept = array(); $supID = array();  $product = array(); $type = array(); $description = array();
        $quantity = array(); $urgencyLevel = array(); $empID = array();
        $stmt = $this->con->query("SELECT `SupID`, `Product`, `Type`, `Description`, `Quantity`, `UrgencyLevel`, `EmpID` FROM `Supply`");
        while ($row = $stmt->fetch()) {
            array_push($supID, $row[0]);
            array_push($product, $row[1]);
            array_push($type, $row[2]);
            array_push($description, $row[3]);
            array_push($quantity, $row[4]);
            array_push($urgencyLevel, $row[5]);
            array_push($empID, $row[6]);
        }
        array_push($accept, $supID, $product, $type, $description, $quantity, $urgencyLevel, $empID);
        return $accept;
    }
    //Update supply list
    public function updateSupply($empID)
    {
        $sql = "UPDATE `supply` SET `Product`=?,`Type`=?,`Description`=?, `Quantity`=?,`UrgencyLevel`=?,`EmpID`=? WHERE `SupID`=?";
        $result = $this->con->prepare($sql)->execute([$this->product, $this->type, $this->description, $this->quantity, $this->urgencyLevel, $empID, $this->supID,]);
        if ($result) {
            echo "<script>alert('Updated Successfully!');
                document.location = '/Manage Supply List.php' </script>";
        } else {
            echo "<script>alert('Update Failed! Please check your connection.');
                document.location = '/Manage Supply List.php' </script>";
        }
    }
    //Remove a supply
    public function removeSupply()
    {
        $sql = "DELETE FROM `Supply` WHERE `SupID` = ?";
        $result = $this->con->prepare($sql)->execute([$this->supID]);
        if ($result) {
            echo "<script>alert('Removed Successfully!');
                document.location = '/Manage Supply List.php' </script>";
        } else {
            echo "<script>alert('Removing Failed! Please check your connection.');
                document.location = '/Manage Supply List.php' </script>";
        }
    }
}
