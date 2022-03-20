//Get time slots for visistors schedule       
function getTime() {
    var date = document.getElementById("date").value;
    if (date.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var accept = this.responseText;
                var pop = accept;
                var slots = ["9:00 AM-11:00 AM", "11:00 AM-1:00 PM", "2:00 PM-4:00 PM"];
                var newslot;
                var select = document.getElementById("startT");
                var option = document.createElement('option');
                if (pop.includes("9:00 AM-11:00 AM") && pop.includes("11:00 AM-1:00 PM") && pop.includes("2:00 PM-4:00 PM")) {
                    newslot = slots.splice(0, 3);
                    alert("Please pick another date! This date is fully booked.")
                } else if (pop.includes("9:00 AM-11:00 AM") && pop.includes("11:00 AM-1:00 PM")) {
                    newslot = slots.splice(0, 2);
                } else if (pop.includes("11:00 AM-1:00 PM") && pop.includes("2:00 PM-4:00 PM")) {
                    newslot = slots.splice(1, 3);
                } else if (pop.includes("9:00 AM-11:00 AM") && pop.includes("2:00 PM-4:00 PM")) {
                    newslot = slots.splice(0, 1)
                    newslot = slots.splice(1, 2);
                } else if (pop.includes("9:00 AM-11:00 AM")) {
                    newslot = slots.splice(0, 1);
                } else if (pop.includes("11:00 AM-1:00 PM")) {
                    newslot = slots.splice(1, 2);
                } else if (pop.includes("2:00 PM-4:00 PM")) {
                    newslot = slots.splice(2, 3);
                }
                for (var i = 0; i < (slots.length); i++) {
                    option.text = option.value = slots[i];
                    select.options[select.options.length] = new Option(slots[i], slots[i]);
                }
            }
        };
        xmlhttp.open("GET", "PHP/VisitorSchedule.php?date=" + date, true);
        xmlhttp.send();
    }
}
//To clear drop down menu before add
function clearDropDown() {
    var select = document.getElementById("startT");
    select.selectedIndex = 0;
    var length = select.options.length;
    for (i = length - 1; i >= 1; i--) {
        select.options[i] = null;
    }
}