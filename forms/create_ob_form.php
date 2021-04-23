
<script type="text/javascript">

   
function cancelnew()
{
  $("#brandnewbox").remove();
  console.log("new box gone!");
}

function savenew()
{
    var ob_title = $("#d_ob_title").val();
    var obid = 0;
     var post_info = {};
     post_info['ob_title'] = ob_title;
    var createType = $("#htype").val();
    $.post("./funcs/create_ob2.php", post_info, function(data) {
        var returnarray = JSON.parse(data);
        obid = returnarray[1];
        $("#brandnewbox").remove();
        $("#orgboxdivs").append(returnarray[0]);
        console.log(data);
    }).done(function(){
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
                    minHeight: $(this).find(".orgboxdivcontent").outerHeight() + 40
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

        post_info['save_reason'] = "Create Box";
        post_info['save_width'] =  $(window).width();
        post_info['save_height'] =  $(window).height();
        post_info['save_state'] = $("#orgboxdivs").html();
            console.log("new ob state saved!");
        $.post("./funcs/save_state.php", post_info, function(data) {
            recordobaction(obid, "box", "record",  "Create",  "", "", data);
            recordobaction(obid, "box", "record",  "Active",  0, 1, data);
            recordobaction(obid, "box", "record",  "Title",  "", ob_title, data);
            console.log("new ob state saved!");
        });
    });
  
  //console.log("#orgboxitems" + "save");
}
    $(document).ready(function()
    {
       $("#d_ob_title").focus();

       $('.input').keydown( function(e) {
            if (e.which == 13) {
                savenew();
            }
        });
    });
</script>

<?php
    session_start();

    $obid = $_POST["ob_box_id"];
    $echoText = "<div class='orgboxdiv' id='brandnewbox'>";

    $echoText .= "<label class='textlabel'>Title:</label>";
    $echoText .= "<input class='input textentry' id='d_ob_title' name='title' type='text'><br/>";
    $echoText .= "<button id='cancel_obi_button' onclick='cancelnew()' class='cancelbutton'>Cancel</button>";
    $echoText .= "<button id='save_obi_button' onclick='savenew()' class='savebutton'>Save</button>";
    $echoText .= "<div style='clear: both;'></div>";


    $type = isset($_POST['type']) ? $_POST['type'] : "";
    $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";

    $echoText .= "</div>";

    
    echo $echoText;
?>