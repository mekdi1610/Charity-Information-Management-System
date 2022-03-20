//Generate treatment schedule ID from member ID without reload
function generateTreID() {
    var memID = document.getElementById("MemberID").value;
    var treID = document.getElementById("TreID");
    if (memID.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Accept treatment ID and display it
                treID.value = $.trim(this.responseText);
            }
        };
        //Send member ID to TreatmentSchedule to generate treatment schedule ID
        xmlhttp.open("GET", "PHP/TreatmentSchedule.php?memID=" + memID, true);
        xmlhttp.send();
    }
}
//Display medication type and dose for schedule treatment
function displayForMedication() {
    var treType = document.getElementById("type").value;
    var medType = document.getElementById("medType");
    var dose = document.getElementById("dose");
    if (treType == "Medication") {
        medType.style.display = "block";
        dose.style.display = "block";
    } else {
        medType.style.display = "none";
        dose.style.display = "none";
    }
}