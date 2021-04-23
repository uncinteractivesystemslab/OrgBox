<script type="text/javascript">
   
   function cancelremove(obid, obtitle)
   {
        var post_info = {};
        post_info['ob_id'] = obid;
        post_info['ob_title'] = obtitle;
        $.get("./funcs/cancel_ob.php", post_info, function(data) {         
            $("#ob_div_" + obid).html(data);
        });
   }

   function saveremove(obid, obstructure)
   {
      console.log(obid);

      var createType = $("#htype").val();
      var post_info = {};
      post_info['ob_id'] = obid;
      post_info['ob_items'] = obstructure;
      $.post("./funcs/remove_ob2.php", post_info, function(data) {
         console.log(data);
         $("#ob_" + obid).remove();
      }).done(function(){
         //Save orgbox
         post_info['save_reason'] = "Remove Box";         
         post_info['save_width'] =  $(window).width();
         post_info['save_height'] =  $(window).height();
         post_info['save_state'] = $("#orgboxdivs").html();
         $.post("./funcs/save_state.php", post_info, function(data) {
            recordobaction(obid, "box", "record",  "Remove",  "", "", data);
            recordobaction(obid, "box", "record",  "Active",  1, 0, data);
            console.log("state saved!");
         })
      });
      
   }
</script>

<?php
   session_start();

   $obid = isset($_POST['ob_id']) ? $_POST['ob_id'] : "1";
   $obtitle = isset($_POST['ob_title']) ? $_POST['ob_title'] : "Blank Title";
   $obitems = isset($_POST['ob_items']) ? $_POST['ob_items'] : "Blank Title";

   $echoText = "";

   $echoText .= "<div class='warning'>Do you want to delete this box and all its contents?<br/>";
   $echoText .= "<button id='cancel_ob_button' onclick='cancelremove(".$obid.", \"$obtitle\")' class='cancelbutton'>Cancel</button>";
   $echoText .= "<button id='save_ob_button' onclick='saveremove(".$obid.", \"$obitems\")' class='savebutton'>Delete</button>";
   $echoText .= "<div style='clear: both;'></div>";
   $echoText .= "</div>";

   $type = isset($_POST['type']) ? $_POST['type'] : "";
   $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";
   echo $echoText;
?>