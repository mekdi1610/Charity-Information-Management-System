//Check if the contributor exist or not based on phone number and email
function checkValidation() {
    var phoneNo = document.getElementById("phone").value;
    var email = document.getElementById("email");
    var type = "Visitor";
    var phoneType = phoneNo + " " + type;
    if (phoneNo.length == 0) {
        document.getElementById("validator").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from Contributor.php
                document.getElementById("validator").innerHTML = this.responseText;
                var msg = document.getElementById("validator").innerHTML;
                //If the response text is accordingly, it clears out the value of email
                if (msg == "Please choose your role to proceed") {
                    email.value = "";
                }
            }
        };
        //Send phoneNo and type to Contributor.php
        xmlhttp.open("GET", "PHP/Contributor.php?phoneType=" + phoneType, true);
        xmlhttp.send();
    }
}
//Check visitor ID if it exists then the textboxs are disabled for user to proceed to schedule information
function checkID() {
    var IDs = document.getElementById("id").value;
    var fName = document.getElementById("fName");
    var mName = document.getElementById("mName");
    var lName = document.getElementById("lName");
    var gender = document.getElementById("gender")
    var phone = document.getElementById("phone");
    var email = document.getElementById("email");
    var address = document.getElementById("address");
    var occ = document.getElementById("occupation");
    var wp = document.getElementById("workPlace");
    var msg = "";
    if (IDs.length == 0) {
        document.getElementById("validatorID").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("validatorID").innerHTML = this.responseText;
                msg = this.responseText;
                //If the response text contains the following message then all the fields in personal and address parts are disabled
                if (msg.trim() == "Proceed to the schedule information") {
                    fName.disabled = true;
                    mName.disabled = true;
                    lName.disabled = true;
                    gender.disabled = true;
                    phone.disabled = true;
                    email.disabled = true;
                    address.disabled = true;
                    occ.disabled = true;
                    wp.disabled = true;
                }
            }
        };
        //Send the ID that the user entered to visitor.php to be validated
        xmlhttp.open("GET", "PHP/Visitor.php?id=" + IDs, true);
        xmlhttp.send();
    }
}