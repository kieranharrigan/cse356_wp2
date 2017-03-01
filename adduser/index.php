<html>
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="adduser.js"></script>
</head>

<body>
    <form id="input" onsubmit="event.preventDefault(); passToAdd();" autocomplete="off">
        Username: <input type="text" name="username" autofocus><br>
        Password: <input type="text" name="password"><br>
        Email: <input type="text" name="email">
        <input type="submit" value="submit">
    </form>

    <div id="result"></div>
</body>
</html>