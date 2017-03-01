function passToRecap() {
var serializedData = $("#input").serialize();

$.ajax({
    url: "recaptcha.php",
    type: "POST",
    data: serializedData,
    success: function(reply) {
        document.getElementById("result").innerHTML = reply;       
    }
});
}
