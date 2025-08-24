<?php include "../pages/dbconnect.php"; 
        

$userid = $_POST['u'];
        $data = $db->query("SELECT * FROM users WHERE userid=$userid");
        $ir = $db->fetch_row($data);
        ?>

        <a href="userlist.php" class="btn btn-info" target="userlist">Back</a>
        	<table border='0' class='table'>
        	   <tr>
        	       <td colspan="2" align="center"><img src="<?php echo $ir['profileimage']; ?>" height="250px"></td>
        	   </tr>
        	   <tr>
        	        <td><b>Bucks:</b></td>
        	        <td><span class="bucks"><?php echo money_formatter($ir['bucks']); ?></span></td>
        	   <tr>
        	   <tr>
        	        <td><b>Airline Image:</b></td>
        	        <td><img src="<?php echo $ir['airlineimage']; ?>" height="100px"></td>
        	   <tr>
        	       <tr>
        	        <td><b>Airline Name:</b></td>
        	        <td><font color="<?php echo $ir['airlinecolour']; ?>"><b><?php echo $ir['airlinename']; ?></b></font></td>
        	   <tr>

        	       <?php
        	       if($ir['airlinehq'] == "1") {
        	           ?>
        	               <tr>
        	                   <td><b>HQ Location:</b></td>
        	                   <td><b>Latitude:</b> <?php echo $ir['latitude'];?> | <b>Longitude:</b> <?php echo $ir['longitude'];?></td>
        	               </tr>

        	           <?php
        	       } else {
        	           ?>
        	               <tr>
        	                   <td><b>HQ Location</b></td>
        	                   <td><b>NO HQ</b></td>
        	               </tr>
        	           <?php
        	       }
        	       ?>
        	   <tr>
        	        <td><b>Planes Count:</b></td>
        	        <td><b><?php
                        $user = $ir['userid'];
                        $air = $db->query("SELECT planeOWNER FROM userairplanes WHERE planeOWNER=$user");
                        $count=$db->num_rows($air);
                        echo $count;
                        ?></b></td>
        	   <tr>
        	   <tr>
        	       <td><b>Signed up</b></td>
        	       <td><b><?php 
        	            $joined = $ir['joineddate'];
        	            echo(date("l jS \of F Y h:i:s A",$joined));
        	            ?></b></td>
        	   </tr>
        	   </table>
