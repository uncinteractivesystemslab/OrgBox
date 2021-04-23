
<?php

session_start();
require_once("../util/utils.php");

$obid = isset($_POST['ob_box_id']) ? $_POST['ob_box_id'] : "1";
$obititle = isset($_POST['obi_title']) ? $_POST['obi_title'] : "";
$obititle = htmlentities($obititle, ENT_QUOTES);    
$obiurl = isset($_POST['obi_url']) ? $_POST['obi_url'] : "";
$obitext = isset($_POST['obi_text']) ? $_POST['obi_text'] : "";

$echoText .= "<div class='orgboxdiv' id='orgboxdivnew'>";
$echoText .= "<span id='ob_title'>NEW BOX</span>";
$echoText .= "<hr/>";
$echoText .= "<li id='orgboxitemnew' class='orgboxitemblank'><div>";


$echoText .= "<label class='textlabel'>Title:</label>";
$echoText .= "<input class='input textentry' id='d_obi_title' name='title' type='text' value='".$obititle."'><br/>";
$echoText .= "<label class='textlabel'>URL:</label>";
$echoText .= "<input class='input textentry' id='d_obi_url' name='url' type='text' value='".$obiurl."'><br/>";
$echoText .= "<label class='textlabel'>Text:</label>";
$echoText .= "<textarea class='input textentryarea' id='d_obi_text' name='name' type='text'>".$obitext."</textarea><br/>";
$echoText .= "<label class='textlabel'>Note:</label>";
$echoText .= "<textarea class='input textentryarea' id='d_obi_note' name='name' type='text'></textarea><br/>";




$echoText .= "<button id='cancel_obi_button' onclick='cancelnew()' class='cancelbutton'>Cancel</button>";
$echoText .= "<button id='save_obi_button' onclick='checksave()' class='savebutton'>Save</button>";
$echoText .= "<div style='clear: both;'></div>";

$echoText .= "</div></li>";    

$echoText .= "</div>";
echo $echoText;
?>