<?php
session_start();
if(isset($_SESSION['name'])){
    $text = $_POST['text'];
	
    if($_SESSION['name'] == 'aintnobodyevagonguessthis'){
        $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")." </span> <b class='user-name-left'>System</b> ".$text."<br></div>";
    }
    else{
        $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")." </span> <b class='user-name'>".$_SESSION['name']."</b> ".stripslashes(htmlspecialchars($text))."<br></div>";
    }
    file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
}
?>