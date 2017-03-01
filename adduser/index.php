<html>
<head>
    <script type="text/javascript" src="recaptcha.js"></script>
</head>

<body>
    <form id="input" onsubmit="event.preventDefault(); passToRecap();" autocomplete="off">
        Username: <input type="text" name="username" autofocus><br>
        Password: <input type="text" name="password"><br>
        Email: <input type="text" name="email">
        <input type="submit" value="submit">
    </form>

    <div id="result"></div>
</body>
</html>
