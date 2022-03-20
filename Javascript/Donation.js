//Display modal for critical member to add a bank account
function display() {
    var modal = document.getElementById("modal");
    modal.style.display = "block";
}

function display2() {
    var modal = document.getElementById("modal2");
    modal.style.display = "block";
}
//Hides modal for normal member bank account
function Tohide() {
    var modal = document.getElementById("modal");
    var modal2 = document.getElementById("modal2");
    modal.style.display = "none";
    modal2.style.display = "none";
}