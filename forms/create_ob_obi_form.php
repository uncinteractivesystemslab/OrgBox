
<script type="text/javascript">

   
function cancelnew()
{
  $("#orgboxdivnew").remove();
  console.log("new item gone!");
}

function checksave()
{
        savenew(0, null);
}

function savenew(pid, pr)
{
    var obi_title = $("#d_obi_title").val();
     var obi_url = $("#d_obi_url").val();
     var obi_text = $("#d_obi_text").val();
     var obi_note = $("#d_obi_note").val();
     var obiid = 0;
     var obid = 0;
     //console.log("ob_id " + ob_id, obi_id);
     var post_info = {};
     post_info['obi_title'] = obi_title;
     post_info['obi_url'] = obi_url;
     post_info['obi_text'] = obi_text;
     post_info['obi_note'] = obi_note;
    var createType = $("#htype").val();
    $.post("./funcs/create_ob_obi.php", post_info, function(data) {
        //console.log(data);
        var returnarray = JSON.parse(data);
        $("#orgboxdivnew").remove();
        $("#orgboxdivs").append(returnarray[0]);

        obiid = returnarray[1];
        obid = returnarray[2];
        //console.log(returnarray);
    }).done(function(){
        //$("#orgboxitems" + obid).nestedSortable();
        var old_s_index, old_r_index;
        $( ".orgboxitems" ).nestedSortable(
        {
            connectWith: ".orgboxitems",
            handle: 'div',
            items: '.orgboxitem',
            disabledClass: '.orgboxitemnew, .orgboxitemblank', 
            forcePlaceholderSize: true, 
            tolerance: 'pointer',       
            toleranceElement : '> div',
            maxLevels: 3,
            scroll: true,
            revert: true,
            placeholder: "ui-state-highlight",
            opacity: 0.8,
            start: function(event, ui){
                ui.placeholder.height(ui.item.height());
                old_s_index = $(this).nestedSortable('serialize');
            },
            over: function(event,ui){
                old_r_index = $(this).nestedSortable('serialize');
            },
            update: function(event,ui){
                var widget = $(this).closest(".orgboxdiv").resizable( "widget" );
                var content = widget.children(".orgboxdivcontent");
                widget.css({
                    minHeight: content.outerHeight() + 50,
                    minWidth: content.outerWidth()
                })

                var obid = $(this).closest(".orgboxdiv").attr('box');
                var new_index = $(this).nestedSortable('serialize');
                var post_info = {};
                post_info['ob_structure'] = JSON.stringify(new_index);
                post_info['ob_id'] = obid;
                post_info['save_reason'] = "Ordering Items";
                post_info['save_width'] =  $(window).width();
                post_info['save_height'] =  $(window).height();
                post_info['save_state'] = $("#orgboxdivs").html();
                $.post("./funcs/order_sb.php", post_info, function(data) {
                    if(ui.sender == null){
                        recordobaction(obid, "box", "record",  "item order",  JSON.stringify(old_s_index), JSON.stringify(new_index), data);
                    }
                    else{
                        recordobaction(obid, "box", "record",  "item order",  JSON.stringify(old_r_index), JSON.stringify(new_index), data);
                    }
                console.log("item order saved " + obid);
                });
            }
        }).disableSelection();

        $( ".orgboxdiv" ).resizable({
            minWidth: 400,
            minHeight: 60,
            start: function(event,ui) {
                $(this).css({
                    minHeight: $(this).find(".orgboxdivcontent").outerHeight() + 50
                });
            }, 
            stop: function(event,ui) {            
                var content = $(this).children(".orgboxdivcontent");
                $(this).css({
                    minHeight: content.outerHeight() + 50,
                    minWidth: content.outerWidth()
                });
            } 
        });

        var oldposition, dragposition = '';
        $( ".orgboxdiv" ).draggable({  
            containment: "window",
            start: function(event, ui){
                temp = ui.position;
                oldposition = temp["top"]+","+temp["left"];
            },
            stop: function(event,ui){
                var obid = $(this).closest(".orgboxdiv").attr('box');
                dragposition = ui.position;
                var post_info = {};
                post_info['ob_position'] = dragposition["top"]+","+dragposition["left"];//"{\"top\":"+dragposition["top"]+", \"left\":"+dragposition["left"]+"}";
                post_info['ob_id'] = obid;
                post_info['save_reason'] = "Box Drag";
                post_info['save_width'] =  $(window).width();
                post_info['save_height'] =  $(window).height();
                post_info['save_state'] = $("#orgboxdivs").html();

                $.post("./funcs/drag_box.php", post_info, function(data) {
                recordobaction(obid, "box", "record",  "box position",  oldposition, post_info['ob_position'] , data);
                console.log("box position saved " + obid);
                });
            }
        });

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
                recordobaction(obid, "box", "record",  "Create",  "", "", data);
                recordobaction(obid, "box", "record",  "Active",  0, 1, data);
                recordobaction(obid, "box", "record",  "Title",  "", "NEW BOX", data);
                recordobaction(obiid, "item", "record",  "Create",  "", "", data);
                recordobaction(obiid, "item", "record",  "Active",  0, 1, data);
                recordobaction(obiid, "item", "record",  "Title",  "", obi_title, data);
                recordobaction(obiid, "item", "record",  "URL",  "", obi_url, data);
                recordobaction(obiid, "item", "record",  "Text",  "", obi_text, data);
                recordobaction(obiid, "item", "record",  "Note",  "", obi_note, data);
                console.log("state saved!");
            })
        });
    });
  
  console.log("#orgboxitems");
}
$(document).ready(function()
{
   $("#d_obi_title").focus();
   
    $('.input').keydown( function(e) {
        if (e.which == 13) {
            checksave();
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