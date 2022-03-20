<?php
include_once 'Connection.php';
class News
{
    public $con = "";
    private $newsID, $poster, $description, $date;
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
    public function setNewsID($newsID)
    {
        $this->newsID = $newsID;
    }
    public function getNewsID()
    {
        return $this->newsID;
    }
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }
    public function getPoster()
    {
        return $this->poster;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setDate($date)
    {
        $this->date = $date;
    }
    public function getDate()
    {
        return $this->date;
    }
    //View all the current and previous news
    public function viewFeed()
    {
        $accept = array(); $newsID = array(); $poster = array(); $description = array(); $date = array();
        $stmt = $this->con->query("SELECT `NewsID`, `Poster`, `Description`, `Date`, `EmpID` FROM `news`");
        while ($row = $stmt->fetch()) {
            array_push($newsID, $row[0]);
            array_push($poster, $row[1]);
            array_push($description, $row[2]);
            array_push($date, $row[3]);
        }
        array_push($accept, $newsID, $poster, $description, $date);
        return $accept;
    }
    //Update old news with current feeds
    public function publishNews($empID)
    {
        $sql = "UPDATE `News` SET `Poster`='$this->poster',`Description`=?, `Date`=?,`EmpID`=? WHERE `NewsID`=?";
        $result = $this->con->prepare($sql)->execute([$this->description, $this->date, $empID, $this->newsID]);
        if ($result) {
            echo "<script>alert('Saved Successfully!');
                    document.location = '/Publish News.php' </script>";
        } else {
            echo "<script>alert('Saving Failed! Please check your connection.');
                document.location = '/Publish News.php' </script>";
        }
    }
}
