<div><a href="<?php echo $newurl; ?>"><img src="<?php echo $templatepath.'/img/addrow.png';?>"></a></div><br>
<div class="row-fluid sortable">		
<div class="box span12">
<div class="box-header" data-original-title>
<div class="box-icon">
<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
</div>
</div>
<div class="box-content">
<?php echo $translationobj->buildgrid($editurl,$deleteurl)?>          
 </div>
 </div>
 </div>

