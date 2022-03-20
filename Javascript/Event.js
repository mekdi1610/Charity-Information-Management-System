//Get available time slots for selected date.
function getTime() {
    var date = document.getElementById("date").value;
    if (date.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from Event.php
                var accept = this.responseText;
                var pop = accept;
                var slots = ["10:00 AM-12:00 PM", "12:00 PM-2:00 PM", "2:00 PM-4:00 PM"];
                var newslot;
                var select = document.getElementById("startT");
                var option = document.createElement('option');
                //If all the slots are booked
                if (pop.includes("10:00 AM-12:00 PM") && pop.includes("12:00 PM-2:00 PM") && pop.includes("2:00 PM-4:00 PM")) {
                    newslot = slots.splice(0, 3);
                    alert("Please pick another date! This date is fully booked.")
                } else if (pop.includes("10:00 AM-12:00 PM") && pop.includes("12:00 PM-2:00 PM")) {
                    newslot = slots.splice(0, 2);
                } else if (pop.includes("10:00 AM-12:00 PM") && pop.includes("12:00 PM-2:00 PM")) {
                    newslot = slots.splice(1, 3);
                } else if (pop.includes("10:00 AM-12:00 PM") && pop.includes("2:00 PM-4:00 PM")) {
                    newslot = slots.splice(0, 1)
                    newslot = slots.splice(1, 2);
                } else if (pop.includes("10:00 AM-12:00 PM")) {
                    newslot = slots.splice(0, 1);
                } else if (pop.includes("12:00 PM-2:00 PM")) {
                    newslot = slots.splice(1, 2);
                } else if (pop.includes("2:00 PM-4:00 PM")) {
                    newslot = slots.splice(2, 3);
                }
                //Populate the drop down menu
                for (var i = 0; i < (slots.length); i++) {
                    option.text = option.value = slots[i];
                    select.options[select.options.length] = new Option(slots[i], slots[i]);
                }
            }
        };
        //Send selected date to Event.php to get available time slots
        xmlhttp.open("GET", "PHP/Event.php?date=" + date, true);
        xmlhttp.send();
    }
}
//To clear drop down menu before adding new items
function clearDropDown() {
    var select = document.getElementById("startT");
    select.selectedIndex = 0;
    var length = select.options.length;
    for (i = length - 1; i >= 1; i--) {
        select.options[i] = null;
    }
}