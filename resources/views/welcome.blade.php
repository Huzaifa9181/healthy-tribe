<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="resources/css/app.css">
</head>
<body>
    <p id="para"></p>
    <button id="ClickMe">click</button>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    @vite("resources/js/app.js" )
    <script>
$(document).on('click', '#ClickMe', function () {
    try {
        Echo.channel('chat-message').listen('chatMessage', (data) => {
            const message = data.message; // Access the message property directly
           $("#para").html(message.message);
        });
    } catch (error) {
        // Handle the error and display an error message
        alert('An error occurred: ' + error.message);
    }
});



    </script>
</body>
</html>
