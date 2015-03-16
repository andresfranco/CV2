<script src="../../js/jquery-1.9.1.min.js"></script>
<script src="deletelanguage.js"></script>
<?php
require_once '../../Controller/LanguageController.php';
require_once '../../libraries/medoo.php';

$language =new LanguageController();

$result =$language->getall();

?>
<div><a href="languageinsertcontent.php"><img src="../../img/addrow.png"></a></div><br>
<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						
						<div class="box-icon">
							
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							
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
                                                      <?php
            foreach ($result as $row) {
                echo '<tr>';
                echo '<td>'. $row['code'] . '</td>';
                echo '<td>'. $row['language'] . '</td>';
                echo '<td class="center">

		     <a class="btn btn-info" href="languageeditcontent.php?id='.$row['code'].'">
		     <i class="halflings-icon white edit"></i>  
		     </a>
		     <a href ="deletelanguagecontent.php?code='.$row['code'].'" class="btn btn-danger" ">
		     <i class="halflings-icon white trash"></i> 
		     </a>
		    </td>';

                echo '</tr>';
            }
            ?>
			
							
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

			
			</div><!--/row-->



