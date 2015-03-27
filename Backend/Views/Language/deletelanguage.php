<div><h3>Are you sure you want to delete this Language?</h3></div>
<br>
<form id="appform" method="post" action="<?php echo $deleteurl;?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">
        <tr>
            <td width="100"><label class="control-label">Code</label></td>
            <td><input name="code" type="text" id="code"  value="<?php echo $code;?>" readonly>
                <span class="error"></span></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">Language</label></td>
            <td><input name="language" type="text" id="language"  value="<?php echo $language;?>" readonly></td>
        </tr>

        </tr>
    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Delete" />
        <input onClick="window.location.href='<?php echo $listurl;?>'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
    </div>
</form>