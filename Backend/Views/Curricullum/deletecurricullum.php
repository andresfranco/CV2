<div><h3>Are you sure you want to delete this Curricullum?</h3></div>
<br>
<form id="appform" method="post" action="<?php echo $deleteurl;?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">
       
        <tr>
            <td width="100"><label class="control-label">Name</label></td>
            <td><input id ="name" name="name" type="text" id="language" class="span6 typeahead" value="<?php echo $name;?>" readonly></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">Main Text</label></td>
            <td width="100"><textarea  id="maintext" name="maintext" rows="3" readonly><?php echo $maintext?></textarea></td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">About Me</label></td>
            <td width="100"><textarea  name="aboutme" id="aboutme" rows="3" readonly><?php echo $aboutme?></textarea></td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Contact Details</label></td>
            <td width="100"><textarea  name="contactdetails" id="contactdetails" rows="3" readonly><?php echo $contactdetails?></textarea></td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Main Skills</label></td>
            <td width="100"><textarea name="mainskills" id="mainskills" rows="3" readonly><?php echo $mainskills?></textarea></td>

        </tr>


    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Delete" />
        <input onClick="window.location.href='<?php echo $listurl;?>'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
    </div>
    <input type="hidden" id="id" name="id"  value="<?php echo $id;?>">
</form>