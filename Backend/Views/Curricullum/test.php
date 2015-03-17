<script src="../../js/jquery-1.9.1.min.js"></script>
<script src="testscript.js"></script>
<form id="appform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">
        
        <tr>
            <td width="100"><label class="control-label">Main Text</label></td>
            <td width="100"><textarea  name="maintext" rows="3"></textarea></td>

        </tr>
        

    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Save" />
       
    </div>
</form>

