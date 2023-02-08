<?php
session_start();
if(isset($_GET['logout'])){    
	
    if ($_SESSION['name'] == 'aintnobodyevagonguessthis'){
        $logout_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")." </span><span class='left-info'><b class='user-name-left'> System</b>has stopped viewing the chat session.</span><br></div>";
    }
    else{
        $logout_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")." </span><span class='left-info'> User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br></div>";
    }
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
	
	session_destroy();
	header("Location: index.php"); //Redirect the user 
}
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
        if ($_SESSION['name'] == 'aintnobodyevagonguessthis'){
            $login_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")." </span><span class='join-info'><b class='user-name-joined'> System</b>is now in the chat session.</span><br></div>";
        }
        else{
            $login_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")." </span><span class='join-info'> User <b class='user-name-joined'>". $_SESSION['name'] ."</b> has joined the chat session.</span><br></div>";
        }
        file_put_contents("log.html", $login_message, FILE_APPEND | LOCK_EX);
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}
function loginForm(){
    echo 
    '<div class="sticky">
    <h1 style="padding-left: 25px;color: white;text-decoration: underline;">YACS: YetAnotherChatService</h1>
    <a href="bypass.html" style="padding-left:25px;color:white;text-decoration: underline;">School-wide Chat Service & Site Unblocker</a>
    </div>
<div id="loginform" class="center"> 
<h1 style="padding-left: 30px;padding-top: 10px;text-decoration:underline;text-align: left;">YACS Login<br></h1>
<p>Type in your name here, this is what people\'ll see<br>when you send a message.</p> 
<form action="index.php" method="post"> 
<label for="name">Name &mdash;</label> 
<input type="text" name="name" id="name" /> 
<input type="submit" name="enter" id="enter" value="Enter" /> 
</form> 
</div>
<div class="navbar">
<p><span style="color:yellow;">YACS <i>(YetAnotherChatService)</i> is hosted locally.</span><br><a href="local.html" style="color:yellow;text-decoration:underline;">Learn more about what this means here.</a><br><br>Jack Murphy 2023</p>
</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title id="titled">
            YACS | <?php if (!isset($_SESSION['name'])) echo "Not Logged In"; else if ($_SESSION['name'] == "aintnobodyevagonguessthis") echo "Logged in as: System"; else echo "Logged in as: ".$_SESSION['name'].""; ?>
        </title>
        <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
        <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
        <meta name="description" content="This is Yet Another Chat Service. Idk what else to say?" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <div class="sticky">
        <h1 style="padding-left: 25px;color: white;text-decoration: underline;">YACS: YetAnotherChatService</h1>
        <a href="bypass.html" style="padding-left:25px;color:white;text-decoration: underline;">School-wide Chat Service & Site Unblocker</a>
    </div>
    <body>
    <?php
    if(!isset($_SESSION['name'])){
        loginForm();
    }
    else {
    ?>
        <div id="wrapper" class="center">
        <h1 style="padding-left: 25px;"><br>YACS Chat<br></h1>
            <div id="menu">
                <p class="welcome">Welcome, <b><?php if ($_SESSION['name'] == "aintnobodyevagonguessthis") echo "System"; else echo $_SESSION['name']; ?></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
            </div>
            <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
            </div>
            <form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // jQuery Document 
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request 
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div 
                            //Auto-scroll 
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request 
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div 
                            }	
                        }
                    });
                }
                setInterval (loadLog, 2500);
                $("#exit").click(function () {
                    var exit = confirm("Are you sure you want to leave the Chat?");
                    if (exit == true) {
                    window.location = "index.php?logout=true";
                    }
                });
            });
        </script>
        <div class="navbar">
            <p><span style="color:yellow;">YACS <i>(YetAnotherChatService)</i> is hosted locally.</span><br><a href="local.html" style="color:yellow;text-decoration:underline;">Learn more about what this means here.</a><br><br>Jack Murphy 2023</p>
        </div>
    </body>
</html>
<?php
}
?>