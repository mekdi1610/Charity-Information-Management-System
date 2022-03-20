//If member is not initated from a tip then additional information should be entered hence it unhides the fields
function displayAdditionalInfo() {
    var tipID = document.getElementById("tipID").value;
    //Tip is none so it unhides the fields
    if (tipID == "None") {
        var additionalInfo = document.getElementsByName("additionalInfo");
        for (var i = 0; i < additionalInfo.length; i++) {
            additionalInfo[i].style.display = "block";
        }
    }
    //If tip has a value then these fields are hidden
    if (tipID != "None") {
        alert("Not None");
        var additionalInfo = document.getElementsByName("additionalInfo");
        for (var i = 0; i < additionalInfo.length; i++) {
            additionalInfo[i].style.display = "none";
        }
    }
}
//Check if the member exist or not based on fullname and date of birth
function checkValidation() {
    var FName = document.getElementById("FName").value;
    var MName = document.getElementById("MName").value;
    var LName = document.getElementById("LName").value;
    var dob = document.getElementById("dob").value;
    var fullName = FName + ' ' + MName + ' ' + LName;
    var fullNameDOB = fullName + "_" + dob;
    if (fullNameDOB.length == 0) {
        document.getElementById("validator").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from Member.php
                document.getElementById("validator").innerHTML = this.responseText;
            }
        };
        //Send phoneNo and type to Contributor.php
        xmlhttp.open("GET", "PHP/Member.php?fullNameDOB=" + fullNameDOB, true);
        xmlhttp.send();
    }
}