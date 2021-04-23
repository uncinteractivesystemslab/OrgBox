<?php

    include("./funcs/load_obi.php");
?>

<script type="text/javascript">
   
   function canceledit(obiid)
   {
      console.log(obiid);

      var post_info = {};
      post_info['obi_id'] = obiid;
      $.get("./funcs/cancel_obi.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
      })
      
      console.log("#obi_div_" + obiid);
   }

   function saveedit(obiid)
   {
      var obi_title_old = $("#hobititle").val();
      var obi_url_old = $("#hobiurl").val();
      var obi_text_old = $("#hobitext").val();
      var obi_note_old = $("#hobinote").val();
      var createType = $("#htype").val();
      var post_info = {};
      post_info['obi_id'] = obiid;
      obi_title = $("#d_obi_title_" + obiid).val();
      obi_url= $("#d_obi_url_" + obiid).val();
      obi_text = $("#d_obi_text_" + obiid).val();
      obi_note = $("#d_obi_note_" + obiid).val();
      post_info['obi_title'] = $("#d_obi_title_" + obiid).val();
      post_info['obi_url'] = $("#d_obi_url_" + obiid).val();
      post_info['obi_text'] = $("#d_obi_text_" + obiid).val();
      post_info['obi_note'] = $("#d_obi_note_" + obiid).val();
      $.post("./funcs/edit_obi2.php", post_info, function(data) {
         console.log("#obi_div_" + obiid, "state2");
         $("#obi_div_" + obiid).html(data);
      }).done(function(){
         //Save orgbox
         post_info['save_reason'] = "Edit Item";
         post_info['save_width'] =  $(window).width();
         post_info['save_height'] =  $(window).height();
         post_info['save_state'] = $("#orgboxdivs").html();
         $.post("./funcs/save_state.php", post_info, function(data) {
            recordobaction(obiid, "item", "record",  "Edit",  "", "", data);
            if(obi_title != obi_title_old){
               recordobaction(obiid, "item", "record",  "Title",  obi_title_old, obi_title, data);
            }
            if(obi_url != obi_url_old){
               recordobaction(obiid, "item", "record",  "URL",  obi_url_old, obi_url, data);
            }
            if(obi_text != obi_text_old){
               recordobaction(obiid, "item", "record",  "Text",  obi_text_old, obi_text, data);
            }
            if(obi_note != obi_note_old){
               recordobaction(obiid, "item", "record",  "Note",  obi_note_old, obi_note, data);
            }
            console.log("state saved!");
         })
         console.log("#orgboxitems" + obiid);
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
            saveedit($("#hobiid").val());
         }
      });
   });
</script>

<?php
    session_start();

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

        //$echoText .= "<div class='editobi'>";
        $echoText .= "<label class='textlabel'>Title:</label>";
        $echoText .= "<input class='input textentry' id='d_obi_title_".$obiid."' name='title' type='text' value='".$obititle."'><br/>";
        $echoText .= "<label class='textlabel'>URL:</label>";
        $echoText .= "<input class='input textentry' id='d_obi_url_".$obiid."' name='url' type='text' value='".$obiurl."'><br/>";
        $echoText .= "<label class='textlabel'>Text:</label>";
        $echoText .= "<textarea class='input textentryarea' id='d_obi_text_".$obiid."' name='name' type='text'>".$obitext."</textarea><br/>";
        $echoText .= "<label class='textlabel'>Note:</label>";
        $echoText .= "<textarea class='input textentryarea' id='d_obi_note_".$obiid."' name='name' type='text'>".$obinote."</textarea><br/>";
        $echoText .= "<div style='clear: both;'></div>";
        $echoText .= "<button id='cancel_obi_button' onclick='canceledit(".$obiid.")' class='cancelbutton'>Cancel</button>";
        $echoText .= "<button id='save_obi_button' onclick='saveedit(".$obiid.")' class='savebutton'>Save</button>";
      $echoText .= "<div style='clear: both;'></div>";
        //$echoText .= "</div>";

        $type = isset($_POST['type']) ? $_POST['type'] : "";
        $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";
        echo $echoText;
    

    

?>