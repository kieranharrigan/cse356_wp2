var first = true;

function startDoc(json) {
    if(first) {
        var new_json = json.substring(0, json.length-1);
        json = new_json + ', "first":"1"}';
    }

    $.ajax({
        url: "DOCTOR/",
        type: "POST",
        data: json,
        dataType: "json",
        success: function(reply) {
            document.getElementById("chat").innerHTML += reply.eliza + "<br>";
        if(first) {
            document.getElementById("type").innerHTML += '<form id="input" autocomplete="off"><span id="you">You: </span><span id="sameline"><input id="box" type="text" name="human"></form>';
            var form = document.getElementById("input");
            $('#box').focus();
            form.addEventListener("submit", submitted);
            first = false;
        }
        
        $('#box').get(0).scrollIntoView();
        }
    });


}

function submitted(e) {
    e.preventDefault();
    var arr = $('#input').serializeArray();
    document.getElementById('input').reset();
    var new_arr = '{"human":"' + arr[0].value + '"}';
    startDoc(new_arr);
}
