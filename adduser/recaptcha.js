function passToRecap() {
	var json = JSON.stringify($("#input").serializeArray());

	$.ajax({
		url: "adduser.php",
		type: "POST",
		data: json,
		success: function(reply) {
			document.getElementById("result").innerHTML = reply;       
		}
	});
}