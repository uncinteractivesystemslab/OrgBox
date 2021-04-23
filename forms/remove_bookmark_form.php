<script type="text/javascript">
   
   function cancelobiremove(obiid)
   {
      var post_info = {};
      post_info['obi_id'] = obiid;
      $.get("./funcs/cancel_bookmark.php", post_info, function(data) {
          console.log(obiid, data);
         $("#obi_div_" + obiid).html(data);
      });
   }

   function saveobiremove(obiid, obid)
   {

      var createType = $("#htype").val();
      var post_info = {};
      post_info['obi_id'] = obiid;
      $.post("./funcs/remove_obi2.php", post_info, function(data) {
         console.log(data);
         $("#obi_" + obiid).remove();
      }).done(function(){
         $("#orgboxitems" + obid).nestedSortable();
         var new_index = $("#orgboxitems" + obid).nestedSortable('serialize');
         var post_info = {};
         post_info['ob_structure'] = JSON.stringify(new_index);
         post_info['ob_id'] = obid;
         $.post("./funcs/order_sb.php", post_info, function(data) {
            console.log("update order got back " + data);
         }).done(function(){
            post_info['save_reason'] = "Remove Item";            
            post_info['save_width'] =  $(window).width();
            post_info['save_height'] =  $(window).height();
            post_info['save_state'] = $("#orgboxdivs").html();
            $.post("./funcs/save_state.php", post_info, function(data) {
               recordobaction(obiid, "item", "record",  "Active",  1, 0, data);
               console.log("state saved!");
            })
         })
      });
      
   }
</script>

<?php
   session_start();

   $obiid = isset($_POST['obi_id']) ? $_POST['obi_id'] : "1";
   $obid = isset($_POST['ob_id']) ? $_POST['ob_id'] : "1";
   $obisubitems = isset($_POST['obi_subitems']) ? $_POST['obi_subitems'] : "No Items";

   $echoText = "";

   $echoText .= "<div class='warning'>Do you want to delete this bookmark?<br/>";
   $echoText .= "<button id='cancel_obi_button' onclick='cancelobiremove(".$obiid.")' class='cancelbutton'>Cancel</button>";
   $echoText .= "<button id='save_obi_button' onclick='saveobiremove(".$obiid.", ".$obid.")' class='savebutton'>Delete</button>";
   $echoText .= "<div style='clear: both;'></div>";
   $echoText .= "</div>";

   $type = isset($_POST['type']) ? $_POST['type'] : "";
   $echoText .= "<input type='hidden' id='htype' name='htype' value='".$type."'>";
   echo $echoText;
?>