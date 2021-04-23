<script type="text/javascript">
   
   function canceledit(obid, obtitle)
   {
      var post_info = {};
      post_info['ob_id'] = obid;
      post_info['ob_title'] = obtitle;
      $.get("./funcs/cancel_ob.php", post_info, function(data) {         
        $("#ob_div_" + obid).html(data);
      });
   }

   function saveedit(obid, oldobtitle)
   {
      console.log(obid);

      var createType = $("#htype").val();
      var post_info = {};
      post_info['ob_id'] = obid;
      var obtitle =$("#d_ob_title_" + obid).val();
      post_info['ob_title'] = obtitle;
      $.post("./funcs/edit_ob2.php", post_info, function(data) {
         console.log("#ob_div_" + obid, "state2");
         $("#ob_div_" + obid).html(data);
      }).done(function(){
         //Save orgbox
         post_info['save_reason'] = "Edit Box";
         post_info['save_width'] =  $(window).width();
         post_info['save_height'] =  $(window).height();
         post_info['save_state'] = $("#orgboxdivs").html();
         $.post("./funcs/save_state.php", post_info, function(data) {
            if(obtitle != oldobtitle){
               recordobaction(obid, "box", "record",  "Edit",  "", "", data);
               recordobaction(obid, "box", "record",  "Title",  oldobtitle, obtitle, data);
            }
            console.log("state saved!");
         }) 
         console.log("#orgboxitems" + obid);
      });
      
   }
   $(document).ready(function()
   { 
      //Set focus to end of the first input field
      var inputField = $(".input").first();
      var inputLength = inputField.val().length;
      inputField.focus();
      inputField[0].setSelectionRange(inputLength, inputLength);

      $('.input').keydown( function(e) {
         if (e.which == 13) {
            saveedit($("#hobid").val());
         }
      });
   });
</script>

<?php
   session_start();

   $obid = isset($_POST['ob_id']) ? $_POST['ob_id'] : "1";
   $obtitle = isset($_POST['ob_title']) ? $_POST['ob_title'] : "Blank Title";

   $echoText = "<input type='hidden' id='hobid' name='hobid' value='".$obid."'>";

   $echoText .= "<label class='textlabel'>Title:</label>";
   $echoText .= "<input class='input textentry' id='d_ob_title_".$obid."' name='title' type='text' value='".$obtitle."'><br/>";
   $echoText .= "<div style='clear: both;'></div>";
   $echoText .= "<button id='cancel_ob_button' onclick='canceledit(".$obid.", \"$obtitle\")' class='cancelbutton'>Cancel</button>";
   $echoText .= "<button id='save_ob_button' onclick='saveedit(".$obid.", \"$obtitle\")' class='savebutton'>Save</button>";
   $echoText .= "<div style='clear: both;'></div>";

   $type = isset($_POST['type']) ? $_POST['type'] : "";
   $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";
   echo $echoText;
?>