<?php

        $obiid = isset($_POST['obi_id']) ? $_POST['obi_id'] : "1";
        $obititle = isset($_POST['obi_title']) ? $_POST['obi_title'] : "Blank Title";
        $obiurl = isset($_POST['obi_url']) ? $_POST['obi_url'] : "www.google.com";
        $obitext = isset($_POST['obi_text']) ? $_POST['obi_text'] : "Blank Text";
        $obinote = isset($_POST['obi_note']) ? $_POST['obi_note'] : "Blank Note";

        $echoText = "<input type='hidden' id='hobiid' name='hobiid' value='".$obiid."'>";
        $echoText = "<input type='hidden' id='hobititle' name='hobititle' value='".$obititle."'>";
        $echoText = "<input type='hidden' id='hobiurl' name='hobiurl' value='".$obiurl."'>";
        $echoText = "<input type='hidden' id='hobitext' name='hobitext' value='".$obitext."'>";
        $echoText = "<input type='hidden' id='hobinote' name='hobinote' value='".$obinote."'>";

        $echoText .= "<label class='textlabel'>Title:</label>";
        $echoText .= "<input class='input textentry' id='d_obi_title_".$obiid."' name='title' type='text' value='".$obititle."'><br/>";
        $echoText .= "<label class='textlabel'>URL:</label>";
        $echoText .= "<input class='input textentry' id='d_obi_url_".$obiid."' name='url' type='text' value='".$obiurl."'><br/>";
        $echoText .= "<label class='textlabel'>Text:</label>";
        $echoText .= "<textarea class='input textentryarea' id='d_obi_text_".$obiid."' name='name' type='text'>".$obitext."</textarea><br/>";
        $echoText .= "<label class='textlabel'>Note:</label>";
        $echoText .= "<textarea class='input textentryarea' id='d_obi_note_".$obiid."' name='name' type='text'>".$obinote."</textarea><br/>";
        $echoText .= "<div style='clear: both;'></div>";
        $echoText .= "<button id='cancel_obi_button' onclick='editobicancel(".$obiid.")' class='cancelbutton'>Cancel</button>";
        $echoText .= "<button id='save_obi_button' onclick='editobiconfirm(".$obiid.")' class='savebutton'>Save</button>";
        $echoText .= "<div style='clear: both;'></div>";

        $type = isset($_POST['type']) ? $_POST['type'] : "";
        $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";
        echo $echoText;
?>