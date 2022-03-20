<?php
include_once 'Connection.php';
//Accepts username to check if it exists or not
if (isset($_REQUEST["username"])) {
    $username = $_REQUEST["username"];
    $accObj = new Account();
    $accObj->setUserName($username);
    $accObj->checkUserName();
}

class Account
{
    protected $userName, $password, $role, $firstTime;
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
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }
    public function getUserName()
    {
        return $this->userName;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function setFirstTime($firstTime)
    {
        $this->firstTime = $firstTime;
    }
    public function getFirstTime()
    {
        return $this->firstTime;
    }
    //Add new account for users
    protected function signUp()
    {
        //Encrypt the password
        $hash = password_hash($this->password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO `account`(`UserName`, `Password`, `Role`) VALUES (?,?,?)";
        $result = $this->con->prepare($sql)->execute([$this->userName, $hash, $this->role]);
        if ($result) {
            echo "<script>alert('Signup Successfully!);
            document.location = '/Home.php' </script>";
        } else {
            echo "<script>alert('Signup Failed!, Please check your connection.);
            document.location = '/Signup.php' </script>";
        }
    }
    //Update user account
    protected function updateAccount()
    {
        //Encrypt the password
        $hash = password_hash($this->password, PASSWORD_BCRYPT);
        $sql = "UPDATE `account` SET `Password`=?, `Role`=? where `UserName`=?";
        $result = $this->con->prepare($sql)->execute([$hash, $this->role, $this->userName]);
        if ($result) {
            echo "<script>alert('Updated Successfully!);
            document.location = '/Home.php' </script>";
        } else {
            echo "<script>alert('Update Failed!, Please check your connection.);
            document.location = '/Signup.php' </script>";
        }
    }
    //Login with valid credentials to different part of the system
    public function login()
    {
        $passwords = $this->password;
        $stmt = $this->con->prepare("SELECT `UserName`, `Password`, `Role`, `FirstTime` FROM `account` WHERE UserName=?");
        $stmt->execute([$this->userName]);
        $row = $stmt->fetch();
        if (is_array($row)) {
            $pass = $row[1];
            //Since we cant decrypt a PASSWORD_BCRYPT, we fetch the password with a specfic username and compare the newly entered password with the retrieved
            if (password_verify($passwords, $pass)) {
                $_SESSION["UserName"] = $row[0];
                $UserName = $row[0];
                $this->role = $row[2];
                $this->firstTime = $row[3];
                $_SESSION["Role"] = $row[2];
            } else {
                echo "<script>alert('Invaild UserName or Password');
                document.location = '../Login.php' </script>";
            }
        }
        //If its user's first time logging in, he/she is asked to change their password
        if ($this->firstTime == 1) {
            $_SESSION["UserName"] = $this->userName;
            echo "<script>alert('First time logging in!, Please change your password.');
            document.location = '../ResetPassword.php' </script>";
        } else if (isset($_SESSION["UserName"])) {
            //If the role indicates the user is an employee then he/she is redirected to employee's home page with SESSION variables set
            if ($this->role == "Employee") {
                $stmt = $this->con->prepare("SELECT EmpID, FullName FROM `Employee` where UserName=?");
                $stmt->execute([$UserName]);
                while ($row = $stmt->fetch()) {
                    $_SESSION["EmpID"] = $row[0];
                    $_SESSION["FullName"] = $row[1];
                    $_SESSION["Role"] = "Employee";
                    $fullName = $row[1];
                }
                echo '<script language="javascript">';
                echo 'alert("Welcome! ' . $fullName . '"); document.location = "../Home.php"';
                echo '</script>';
            } //If the role indicates the user is an administrator then he/she is redirected to generate report page with SESSION variables set
            else if ($this->role == "Administrator") {
                $stmt = $this->con->prepare("SELECT EmpID, FullName FROM `Employee` where UserName=?");
                $stmt->execute([$UserName]);
                while ($row = $stmt->fetch()) {
                    $_SESSION["EmpID"] = $row[0];
                    $_SESSION["FullName"] = $row[1];
                    $_SESSION["Role"] = "Administrator";
                    $fullName = $row[1];
                }
                echo '<script language="javascript">';
                echo 'alert("Welcome! ' . $fullName . '"); document.location = "../Generate Report.php"';
                echo '</script>';
            }  //If the role indicates the user is a volunteer then he/she is redirected to volunteer's home page with SESSION variables set
            else if ($this->role == "Volunteer") {
                $stmt = $this->con->prepare("SELECT volunteer.VolID, volunteer.FullName, volunteer.email, volunteer.Photo, volunteer.Occupation FROM `Volunteer` INNER JOIN account ON `Volunteer`.`UserName`=`account`.`UserName` where `account`.`UserName`=?");
                $stmt->execute([$UserName]);
                while ($row = $stmt->fetch()) {
                    $_SESSION["VolID"] = $row[0];
                    $_SESSION["FullName"] = $row[1];
                    $_SESSION["Email"] = $row[2];
                    $_SESSION["Photo"] = $row[3];
                    $_SESSION["Occupation"] = $row[4];
                    $fullName = $row[1];
                }
                echo '<script language="javascript">';
                //Welcoming message
                echo 'alert("Welcome! ' . $fullName . '"); document.location = "../Home.php"';
                echo '</script>';
            }
        }
    }
    //Logout of the system
    public function logout()
    {
        session_start();
        unset($_SESSION["UserName"]);
        echo "<script>alert('GoodBye!');
        document.location = '../index.php' </script>";
    }
    //Generate UserName and Password when volunteer signup
    public function generateCredential($FullName)
    {
        //Get first, middle and last name of the volunteer
        $fullName = explode(" ", $FullName);
        $fName = $fullName[0];
        $mName = $fullName[1];
        $lName = $fullName[2];
        //Get the last 3 characters of each string
        $FN = substr($fName, -3);
        $MN = substr($mName, -3);
        $LN = substr($lName, -3);
        $charForUserName = $FN . $MN . $LN . "0123456789";
        $charLenUN = strlen($charForUserName);
        $userName = '';
        $charForPassword = $FN . $MN . $LN . "0123456789@#$^&*";
        $charLenPass = strlen($charForPassword);
        $password = $FN[0];
        //Genrate 8 character long username and password with random iteration
        for ($i = 0; $i < 8; $i++) {
            $userName .= $charForUserName[rand(0, $charLenUN - 1)];
            $password .= $charForPassword[rand(0, $charLenPass - 1)];
        }
        //To check if the random username exists in the system
        $stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `UserName` FROM `Volunteer` WHERE `userName` = ?), 'Not Found')");
        $stmt->execute([$userName]);
        while ($row = $stmt->fetch()) {
            //If the generated username is not found then the combination of username and password are returned
            if ($row[0] == "Not Found") {
                return $userName . " " . $password;
            }
            //If its found the whole process begins again
            else {
                $this->generateCredential($FullName);
            }
        }
    }
    //Search account information
    public function searchAccount()
    {
        $stmt = $this->con->prepare("SELECT `Password`, `Role` from `Account` where `UserName` = ?");
        $stmt->execute([$this->userName]);
        while ($row = $stmt->fetch()) {
            return $row[0] . "_" . $row[1];
        }
    }
    //Reset password with first login
    public function firstTimeLogin()
    {
        //Encrypt the password
        $hash = password_hash($this->password, PASSWORD_BCRYPT);
        $sql = "UPDATE `Account` SET `Password` = ?, `FirstTime`=0 WHERE UserName = ?";
        $result = $this->con->prepare($sql)->execute([$hash, $this->userName]);
        if ($result) {
            echo "<script>alert('Reset Successfully!');
            document.location = '/Login.php' </script>";
        } else {
            echo "<script>alert('Reset Failed! Please check your connection.');
            document.location = '/Login.php' </script>";
        }
    }
    //Check if the user name exists or not
    public function checkUserName()
    {
        $stmt = $this->con->prepare("SELECT IFNULL ( ( SELECT `UserName` FROM `Account` WHERE `UserName` = ?), 'Not Found')");
        $stmt->execute([$this->userName]);
        while ($row = $stmt->fetch()) {
            if ($row[0] == "Not Found") {
                //No message is displayed so that the user proceeds to the next field
            } else {
                echo "User name is already registered, try a different username";
            }
        }
    }
    //Send authorized email with an attached token if a user forgets his/her password
    public function forgotPassword()
    {
        //Retrieve email from database
        $stmt = $this->con->prepare("SELECT `Email` FROM `Volunteer` WHERE `UserName` = ?");
        $stmt->execute([$this->userName]);
        while ($row = $stmt->fetch()) {
            $email = $row[0];
        }
        //Prepare the email
        $headers = "From: beletebiniyam65@gmail.com";
        $subject = "Forgot Password";
        //Prepare token
        $token = md5($email) . rand(10, 9999);
        $expFormat = mktime(
            date("H"),
            date("i"),
            date("s"),
            date("m"),
            date("d") + 1,
            date("Y")
        );
        $expDate = date("Y-m-d H:i:s", $expFormat);
        $link = '<a href="mekedonia.com:8080/ResetPassword.php?key=' . $email . '&uname=' . $this->userName . '&token=' . $token . '&expdate=' . $expDate . '">Click To Reset password</a>';
        //Send email
        if (mail($email, $subject, $link, $headers)) {
            echo "<script>alert('Please check your email to reset your password');
            document.location = '/Login.php' </script>";
        } else {
            echo "<script>alert('Sending Failed!');
            document.location = '/Login.php' </script>";
        }
    }
}
