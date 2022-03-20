//Search for employees information for update
function searchAccount() {
    var userName = document.getElementById("userName").value;
    var fullName = document.getElementById("fullName");
    var role = document.getElementById("role");
    alert(userName);
    if (userName.length == 0) {
        document.getElementById("validator").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var accept = this.responseText;
                var pop = accept;
                var res = pop.split("_");
                //Recieve fullname and role, and make the UserName textbox ready only
                fullName.value = res[0].trim();
                role.value = res[1].trim();
                document.getElementById("UserName").readOnly = true;
            }
        };
        //Send value of userName to search an employee
        xmlhttp.open("GET", "PHP/Employee.php?unforsearch=" + userName, true);
        xmlhttp.send();
    }
}