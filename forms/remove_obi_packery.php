<?php
   session_start();

   $obiid = isset($_POST['obi_id']) ? $_POST['obi_id'] : "1";
   $obid = isset($_POST['ob_id']) ? $_POST['ob_id'] : "1";
   $obisubitems = isset($_POST['obi_subitems']) ? $_POST['obi_subitems'] : "No Items";

   $echoText = "";

   $echoText .= "<div class='warning'>Do you want to delete this item and all its sub-items if any?<br/>";
   $echoText .= "<button id='cancel_obi_button' onclick='removeobicancel(".$obiid.")' class='cancelbutton'>Cancel</button>";
   $echoText .= "<button id='save_obi_button' onclick='removeobiconfirm(".$obiid.", ".$obid.", \"$obisubitems\")' class='savebutton'>Delete</button>";
   $echoText .= "<div style='clear: both;'></div>";
   $echoText .= "</div>";

   $type = isset($_POST['type']) ? $_POST['type'] : "";
   $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";
   echo $echoText;
?>