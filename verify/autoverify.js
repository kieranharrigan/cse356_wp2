function verify() {
var urlParams = new URLSearchParams(window.location.search);

var email = urlParams.get('email');
var key = urlParams.get('key');

var json = '{"email":"' + email + '", "key":"' + key + '"}';

    $.ajax({
        url: "verify.php/",
        type: "POST",
        data: json,
        dataType: "json",
        success: function(reply) {
            document.getElementById("result").innerHTML += reply.phrase + "<br>";
        }  
    });
}
