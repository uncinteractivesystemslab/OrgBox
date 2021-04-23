<!--- Copyright (C) 2020 UNC Interactive Information Systems Lab, School of Information and Library Science --->
<?php
    function printitem($keyID, $valueID, $levelOneID, $levelTwoID, $currentLevel, $previousLevel) 
    {
       echo "<li class='orgboxitem' id='obi_".$keyID."' sid='".$keyID."'><div id='obi_div_".$keyID."'>";
       load_obi($keyID);
       echo "</div>";
    }

    function printbookmark($keyID) 
    {
       echo "<li class='orgboxitem' id='obi_".$keyID."' sid='".$keyID."'><div id='obi_div_".$keyID."'>";
       load_bookmark($keyID);
       echo "</div>";
    }

    function startol($keyID, $currentLevel, $previousLevel) 
    {
       if($currentLevel > $previousLevel)
       {
          for($x = 0; $x < ($currentLevel - $previousLevel); $x++){
             echo "<ol>";
          }
       }
       else if( ($previousLevel > $currentLevel))
       {
          for($x = 0; $x < ($previousLevel - $currentLevel); $x++){
             echo "</ol></li>";
          }
       }
       else if($previousLevel == $currentLevel){
         echo "</li>";
       }
       
       
    }
    function endol($keyID, $currentLevel, $previousLevel) 
    {

       if($previousLevel > $currentLevel)
       {
          for($x = 0; $x < ($previousLevel - $currentLevel); $x++){
             echo "</li></ol>";
          }
       }
    }

?>