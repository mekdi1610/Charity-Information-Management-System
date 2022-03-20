//Check if user name exists or not
function checkUserName() {

    var userName = document.getElementById("userName").value;
    if (userName.length == 0) {
        document.getElementById("validatorID").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from Account.php
                document.getElementById("validatorID").innerHTML = this.responseText;
            }
        };
        //Send value of userName to Account.php
        xmlhttp.open("GET", "PHP/Account.php?username=" + userName, true);
        xmlhttp.send();
    }
}
//Check if password length is greater than 8 characters and if the password contains upper case, lower case, number and special characters
function checkPassword() {
    var regex = /^[A-Za-z0-9 ]+$/
    var newPassword = document.getElementById("password").value;
    document.getElementById("validator2").innerHTML = "";
    var upper = [];
    var lower = [];
    var no = [];
    //If a character in the password is number, lower case or uppercase its pushed to their respective arrays
    for (var i = 0; i < newPassword.length; i++) {
        var res = newPassword.charAt(i);
        //Number
        if (!isNaN(res * 1)) {
            no.push(res);
        } else {
            //Uppercase
            if (res == res.toUpperCase()) {
                upper.push(res);
            }
            //Lowercase
            if (res == res.toLowerCase()) {
                lower.push(res);
            }
        }
    }
    //Test the password with special characters
    var isValid = regex.test(newPassword);
    if (!isValid) {
        document.getElementById("validator2").innerHTML = "";
    } else {
        document.getElementById("validator2").innerHTML = "Password must contain special charcters";
    }
    //If upper array is empty then no Uppercase letter in the password
    if (upper.length == 0) {
        document.getElementById("validator2").innerHTML = "Password must contain uppercase letters";
    }
    //If lower array is empty then no Lowercase letter in the password
    if (lower.length == 0) {
        document.getElementById("validator2").innerHTML = "Password must contain lowercase letters";
    }
    //If no array is empty then no numbers in the password
    if (no.length == 0) {
        document.getElementById("validator2").innerHTML = "Password must contain numbers";
    }
    //To check if the length of the password is greater than or equal to 8
    if (newPassword.length < 8) {
        document.getElementById("validator2").innerHTML = "Password must contain atleast 8 characters";
    }
}
//To make password visible by changing the type of the input field
var flag = 1; //Its clicked
function showPassword() {
    var password = document.getElementById("password");
    if (flag == 0) {
        password.type = "password";
        flag = 2;
    }
    if (flag == 1) {
        password.type = "text";
        flag = 0;
    }
    if (flag == 2) {
        flag = 1;
    }
}