<script src="Backend/Views/Language/validatelanguage.js"></script>
<label class="error"><?php echo $errormessage;?></label><br>
<form id="appform" method="post" action="<?php echo $selfurl;?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">
        <tr>
            <td width="100"><label class="control-label">Code</label></td>
            <td><input name="code" type="text" id="code" class="span6 typeahead" value="<?php echo $code;?>">
       <span class="error"></span></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">Language</label></td>
            <td><input name="language" type="text" id="language" class="span6 typeahead" value="<?php echo $language;?>"></td>
        </tr>


    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Save" />
        <input onClick="window.location.href='<?php echo $listurl;?>'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
    </div>
</form>


