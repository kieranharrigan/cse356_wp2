function passToAdd() {
	var arr = $("#input").serializeArray();
	var json = {};

	$.each(arr, function() {
		json[this.name] = this.value;
	});

	$.ajax({
		url: "adduser.php/",
		type: "POST",
		data: JSON.stringify(json),
		success: function(reply) {
			document.getElementById("result").innerHTML = reply;       
		}
	});
}