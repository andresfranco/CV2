<div><a href="<?php echo $newurl; ?>"><img src="Backend/img/addrow.png"></a></div><br>
<div class="row-fluid sortable">		
<div class="box span12">
<div class="box-header" data-original-title>
<div class="box-icon">
<a href="#" class="btn-minimize">
<i class="halflings-icon chevron-up"></i>
</a>
</div>
</div>
<div class="box-content">
<table class="table table-striped table-bordered bootstrap-datatable datatable">
<thead>
<tr>
<th>Code</th>
<th>Language</th>
<th>Actions</th>
</tr>
</thead>   
<tbody>
  <?php $languageobj->buildgrid($editurl,$deleteurl);?>
    </tbody>
    </table>            
   </div>
   </div>
   </div>