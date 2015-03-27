<?php
require_once '../../Controller/CurricullumController.php';
$db=new CurricullumController();
$result =$db->getall();
?>
<div><a href="curriculluminsertcontent.php"><img src="../../img/addrow.png"></a></div><br>
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
<th>Name</th>
<th>Actions</th>
</tr> 
</thead>   
<tbody>
 <?php
  foreach ($result as $row) 
  {
   echo '<tr>';
   echo '<td>'. $row['name'] . '</td>';
   echo '<td class="center">
         <a class="btn btn-info" href="curricullumeditcontent.php?id='.$row['id'].'">
	 <i class="halflings-icon white edit"></i>  
	 </a>
	 <a href ="deletecurricullumcontent.php?id='.$row['id'].'" class="btn btn-danger">
	 <i class="halflings-icon white trash"></i> 
	 </a>
	</td>';
        echo '</tr>';
   }
  ?>
 </tbody>
 </table>            
 </div>
 </div>
 </div>