
<script type="text/javascript">
  
    function cancelnew(obid)
    {
        $("#orgboxitemnew_" + obid).remove();
        
        $("#orgboxdivs").packery('layout');
        //console.log("new item gone!");
    }

    function checksave(obid)
    {
        
            savenew(obid, 0, '');
    }

    function savenew(obid, pid, pr)
    {
        var obi_title = $("#d_obi_title").val();
        var obi_url = $("#d_obi_url").val();
        var obi_text = $("#d_obi_text").val();
        var obi_note = $("#d_obi_note").val();
        var obiid = 0;
        var createType = $("#htype").val();
        console.log("working");
        var post_info = {};
        post_info['obi_title'] = obi_title;
        post_info['obi_url'] = obi_url;
        post_info['obi_text'] = obi_text;
        post_info['obi_note'] = obi_note;
        post_info['obi_box_id'] = obid;
        console.log(post_info);
        
        $.post("./funcs/create_obi2.php", post_info, function(data) {
            var returnarray = JSON.parse(data);
            $("#orgboxitemnew_" + obid).remove();
            $("#orgboxitems" + obid).append(returnarray[0]);
            obiid = returnarray[1];
            
            $("#orgboxdivs").packery('layout');
        }).done(function(){
            $("#orgboxitems" + obid).nestedSortable();
            var new_index = $("#orgboxitems" + obid).nestedSortable('serialize');
            var post_info = {};
            post_info['ob_structure'] = JSON.stringify(new_index);
            post_info['ob_id'] = obid;
            $.post("./funcs/order_sb.php", post_info, function(data) {
                console.log("update order got back " + data);
                
            }).done(function(){
                post_info['save_reason'] = "Create Item";
                post_info['save_width'] =  $(window).width();
                post_info['save_height'] =  $(window).height();
                post_info['save_state'] = $("#orgboxdivs").html();
                $.post("./funcs/save_state.php", post_info, function(data) {
                    recordobaction(obiid, "item", "record",  "Create",  "", "", data);
                    recordobaction(obiid, "item", "record",  "Active",  0, 1, data);
                    recordobaction(obiid, "item", "record",  "Title",  "", obi_title, data);
                    recordobaction(obiid, "item", "record",  "URL",  "", obi_url, data);
                    recordobaction(obiid, "item", "record",  "Text",  "", obi_text, data);
                    recordobaction(obiid, "item", "record",  "Note",  "", obi_note, data);
                    console.log("state saved!");
                });                
            });

        });
        
      
        console.log("#orgboxitems" + obid);
    }
    $(document).ready(function()
    {
       $("#d_obi_title").focus();
       
        $('.input').keydown( function(e) {
            if (e.which == 13) {
                checksave($("#hobid").val());
            }
        });
    });
</script>

<?php

    session_start();
    require_once("../util/utils.php");

    $obid = isset($_POST['ob_box_id']) ? $_POST['ob_box_id'] : "1";
    $obititle = isset($_POST['obi_title']) ? $_POST['obi_title'] : "";
    $obititle = htmlentities($obititle, ENT_QUOTES);    
    $obiurl = isset($_POST['obi_url']) ? $_POST['obi_url'] : "";
    $obitext = isset($_POST['obi_text']) ? $_POST['obi_text'] : "";
    $echoText = "<li id='orgboxitemnew_".$obid."' class='orgboxitemblank'><div>";

    $type = isset($_POST['type']) ? $_POST['type'] : "";
    $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";
    $echoText .= "<input type='hidden' id='hobid' name='hobid' value='".$obid."'>";

    $echoText .= "<label class='textlabel'>Title:</label>";
    $echoText .= "<input class='input textentry' id='d_obi_title' name='title' type='text' value='".$obititle."'><br/>";
    $echoText .= "<label class='textlabel'>URL:</label>";
    $echoText .= "<input class='input textentry' id='d_obi_url' name='url' type='text' value='".$obiurl."'><br/>";
    $echoText .= "<label class='textlabel'>Text:</label>";
    $echoText .= "<textarea class='input textentryarea' id='d_obi_text' name='text' type='text'>".$obitext."</textarea><br/>";
    $echoText .= "<label class='textlabel'>Note:</label>";
    $echoText .= "<textarea class='input textentryarea' id='d_obi_note' name='note' type='text'></textarea><br/>";

    
    $echoText .= "<button id='cancel_obi_button' onclick='cancelnew(".$obid.")' class='cancelbutton'>Cancel</button>";
    $echoText .= "<button id='save_obi_button' onclick='checksave(".$obid.")' class='savebutton'>Save</button>";
    $echoText .= "<div style='clear: both;'></div>";

    $echoText .= "</div></li>";    

    echo $echoText;
?>