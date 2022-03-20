//To display content for Volunteer role and hide those contents if the role selected is Donator or Visitor
function displayContent() {
    var Choice = document.getElementById("type").value;
    var btntoHide = document.getElementById("forVisDon");
    var toHide = document.getElementsByName('toHide');
    var photo = document.getElementById('photoHide');
    //For Volunteer
    if (Choice == "Volunteer") {
        for (var i = 0; i < toHide.length; i++) {
            toHide[i].style.display = "inline-block";
        }
        photo.style.display = "inline";
        btntoHide.style.display = "none";
    }
    //For Visitor and Donator
    if (Choice == "Visitor" || Choice == "Donator") {
        for (var i = 0; i < toHide.length; i++) {
            toHide[i].style.display = "none";
        }
        photo.style.display = "none";
    }
}
//Check if the contributor exist or not based on phone number and email
function checkValidation() {
    var phoneNo = document.getElementById("phone").value;
    var email = document.getElementById("email");
    var type = document.getElementById("type").value;
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
//Displayed only when users register themselves, the user should agree to certain terms of the organization to proceed
function approval() {
    var approve = document.getElementById("approve");
    var btnContinue = document.getElementById("btnContinue");
    //Button enabled if user checks on the crietria
    if (approve.checked == true) {
        btnContinue.disabled = false;
    }
    //Button disabled if user doesnt check on the crietria
    else {
        btnContinue.disabled = true;
    }
}