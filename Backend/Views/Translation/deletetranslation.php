<script src="<?php echo $templatepath.'/Views/Translation/disabletranslationfields.js';?>"></script>
<div><h3>Are you sure you want to delete this Translation?</h3></div>
<br>
<form id="appform" method="post" action="<?php echo $deleteurl;?>">
<table width="400" border="0" cellspacing="1" cellpadding="2">
<tr>
<td width="100"><label class="control-label">Object Code</label></td>
<td><?php $globalobj->getmultiparambycode('objcode',$objectcode,'');?></td>
</tr>

<tr>
<td width="100"><label class="control-label">Parent</label></td>

<td width="100"><?php $db->getparent($globalobj,$objectcode,$parentid);?>
</td>

</tr>
<tr>
<td width="100"><label class="control-label">Object ID</label></td>

<td width="100"><?php $db->getobject($globalobj,$objectcode,$objectid);?>
</td>

</tr>
<tr>
<td width="100"><label class="control-label">Language</label></td>
<td width="100"><?php $globalobj->getlanguageselect('',$languagecode);?></td>

</tr>
<tr>
<td width="100"><label class="control-label">Field</label></td>
<td width="100"><?php $db->getfields($globalobj,$objectcode,$field);?>
</td>

</tr>
<tr>
<td width="100"><label class="control-label">Content</label></td>
<td width="100"><textarea  rows="3" disabled><?php echo $content;?></textarea></td>

</tr>

</table>
<br>
<div class="options btn-group">
<input  id ="deletebutton" class="btn btn-primary" type="submit" value="Delete" />
<input onClick="window.location.href='<?php echo $listurl;?>'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
</div>
<input type="hidden" id="id" name="id"  value="<?php echo $id;?>">
</form>