//If "Daily" is checked the other check boxes are disabled
function changeState() {
    var daily = document.getElementById('Daily');
    var mon = document.getElementById('Mon');
    var tue = document.getElementById('Tue');
    var wed = document.getElementById('Wed');
    var thurs = document.getElementById('Thurs');
    var fri = document.getElementById('Fri');
    var sat = document.getElementById('Sat');
    var sun = document.getElementById('Sun');
    //If the daily checkbox is checked the others should be disabled
    if (daily.checked == true) {
        mon.disabled = true;
        tue.disabled = true;
        wed.disabled = true;
        thurs.disabled = true;
        fri.disabled = true;
        sat.disabled = true;
        sun.disabled = true;
    }
    //If the daily checkbox is unchecked the others should be enabled
    if (daily.checked == false) {
        mon.disabled = false;
        tue.disabled = false;
        wed.disabled = false;
        thurs.disabled = false;
        fri.disabled = false;
        sat.disabled = false;
        sun.disabled = false;
    }
}
//Clear all the checkboxes for the next values
function clearCheckBox() {
    var daily = document.getElementById('Daily');
    daily.checked = false;
    var mon = document.getElementById('Mon');
    mon.checked = false;
    var tue = document.getElementById('Tue');
    tue.checked = false;
    var wed = document.getElementById('Wed');
    wed.checked = false;
    var thurs = document.getElementById('Thrus');
    thurs.checked = false;
    var fri = document.getElementById('Fri');
    fri.checked = false;
    var sat = document.getElementById('Sat');
    sat.checked = false;
    var sun = document.getElementById('Sun');
    sun.checked = false;
}