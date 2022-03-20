//To add activities to dropdown of volunteer schedule based on the selected date
function getActivityForAdd() {
    var dates = document.getElementById("date").value;
    var activity = document.getElementById("activity");
    if (dates.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from VolunteerSchedule.php
                var accept = this.responseText;
                var pop = accept;
                var res = pop.split("_");
                var option = document.createElement('option');
                //Populate the drop down menu
                for (var i = 0; i < ((res.length) - 1); i++) {
                    option.text = option.value = res[i];
                    activity.options[activity.options.length] = new Option(res[i], res[i]);
                }
            }
        };
        //Send selected date to VolunteerSchedule.php to get available daily activity
        xmlhttp.open("GET", "PHP/VolunteerSchedule.php?date=" + dates, true);
        xmlhttp.send();
        dates = "";
    }
}
//To add activities to select box of volunteer schedule
function getActivityForUpdate() {
    var dates = document.getElementById("dateu").value;
    var activity = document.getElementById("activityu");
    if (dates.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from VolunteerSchedule.php
                var accept = this.responseText;
                var pop = accept;
                var res = pop.split("_");
                var option = document.createElement('option');
                //Populate the drop down menu
                for (var i = 0; i < ((res.length) - 1); i++) {
                    option.text = option.value = res[i];
                    activity.options[activity.options.length] = new Option(res[i], res[i]);
                }
            }
        };
        //Send selected date to VolunteerSchedule.php to get available daily activity
        xmlhttp.open("GET", "PHP/VolunteerSchedule.php?date=" + dates, true);
        xmlhttp.send();
    }
}
//To add events to select box of volunteer schedule based on the selected date
function getEventForAdd() {
    var dates = document.getElementById("date").value;
    var activity = document.getElementById("activity");
    if (dates.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from VolunteerSchedule.php
                var accept = this.responseText;
                var pop = accept;
                var res = pop.split("_");
                var option = document.createElement('option');
                //Populate the drop down menu
                for (var i = 0; i < ((res.length) - 1); i++) {
                    option.text = option.value = res[i];
                    activity.options[activity.options.length] = new Option(res[i], res[i]);
                }
            }
        };
        //Send selected date to VolunteerSchedule.php to get available events
        xmlhttp.open("GET", "PHP/VolunteerSchedule.php?edate=" + dates, true);
        xmlhttp.send();
    }
}
//To add events to select box of volunteer schedule based on the selected date
function getEventForUpdate() {
    var dates = document.getElementById("dateu").value;
    var activity = document.getElementById("activityu");
    if (dates.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from VolunteerSchedule.php
                var accept = this.responseText;
                var pop = accept;
                var res = pop.split("_");
                var option = document.createElement('option');
                //Populate the drop down menu
                for (var i = 0; i < ((res.length) - 1); i++) {
                    option.text = option.value = res[i];
                    activity.options[activity.options.length] = new Option(res[i], res[i]);
                }
            }
        };
        //Send selected date to VolunteerSchedule.php to get available events
        xmlhttp.open("GET", "PHP/VolunteerSchedule.php?edate=" + dates, true);
        xmlhttp.send();
    }
}
//To get the time intervals of the selected activities on Add Volunteer Schedule
function getTimeForAdd() {
    var dates = document.getElementById("date").value;
    var activity = document.getElementById("activity").value;
    var dateActivity = "";
    var sTime = document.getElementById("stime");
    var eTime = document.getElementById("etime");
    dateActivity = activity + "/" + dates;
    if (activity.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from VolunteerSchedule.php
                var accept = this.responseText;
                var pop = accept;
                var res = pop.split("_");
                sTime.value = res[0];
                eTime.value = res[1];
            }
        };
        //Send selected date and activity to VolunteerSchedule.php to get start and end time
        xmlhttp.open("GET", "PHP/VolunteerSchedule.php?time=" + dateActivity, true);
        xmlhttp.send();
    }
}
//To get the time intervals of the selected activities on Update Volunteer Schedule
function getTimeForUpdate() {
    var dates = document.getElementById("dateu").value;
    var activity = document.getElementById("activityu").value;
    var dateActivity = "";
    var sTimeu = document.getElementById("stimeu");
    var eTimeu = document.getElementById("etimeu");
    dateActivity = activity + "/" + dates;
    if (activity.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from VolunteerSchedule.php
                var accept = this.responseText;
                var pop = accept;
                var res = pop.split("_");
                sTimeu.value = res[0];
                eTimeu.value = res[1];
            }
        };
        //Send selected date and activity to VolunteerSchedule.php to get start and end time
        xmlhttp.open("GET", "PHP/VolunteerSchedule.php?time=" + dateActivity, true);
        xmlhttp.send();
    }
}
//To clear drop down menu before adding new items
function clearDropDown() {
    var select = document.getElementById("activity");
    var select2 = document.getElementById("activityu");
    select.selectedIndex = 0;
    var length = select.options.length;
    for (i = length - 1; i >= 1; i--) {
        select.options[i] = null;
    }
    select2.selectedIndex = 0;
    var length = select2.options.length;
    for (i = length - 1; i >= 1; i--) {
        select2.options[i] = null;
    }
}
//Check if the selected time is in between the given time slots
function checkTime() {
    var sTime = document.getElementById("stime").value;
    var eTime = document.getElementById("etime").value;
    var nTime = document.getElementById("time").value;
    alert(sTime);
    if (eTime < nTime) {
        alert("This time slot is not in the range provided. Please Choose different time, or change the activity you want to participate in!");
    }
}