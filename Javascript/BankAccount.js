//Add or Update Bank Account without reloading the page
function manageBankAccount() {
    var id = document.getElementById("id").value;
    var bankName = document.getElementById("bankName").value;
    var branchName = document.getElementById("branchName").value;
    var accName = document.getElementById("accName").value;
    var accNo = document.getElementById("accNo").value;
    var transferInfo = document.getElementById("transferInfo").value;
    var country = document.getElementById("country").value;
    var belongsTo = document.getElementById("belongsTo").value;
    var btn = document.getElementById("btn").value;
    var dataToSend = id + "/" + bankName + "/" + branchName + "/" + accName + "/" + accNo + "/" + transferInfo + "/" + country + "/" + belongsTo + "/" + btn;
    if (dataToSend.length == 0) {
        alert("Please fill in the forms");
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from Bank Account.php
                var msg = this.responseText;
                alert(msg);
            }
        };
        //Send value of the entire data entered to ManageBankAccount.php
        xmlhttp.open("GET", "ManageBankAccount.php?datasent=" + dataToSend, true);
        xmlhttp.send();
    }
}
//Clear the form of the Add/Update Bank Account
function clearForm() {
    var bankName = document.getElementById("bankName");
    var branchName = document.getElementById("branchName");
    var accName = document.getElementById("accname");
    var accNo = document.getElementById("accno");
    var transferInfo = document.getElementById("transferInfo");
    var country = document.getElementById("country");
    var belongsTo = document.getElementById("belongsto");
    bankName.value = "";
    branchName.value = "";
    accName.value = "";
    accNo.value = "";
    transferInfo.value = "";
    country.value = "";
    belongsTo.value = "";
}