<?php
require_once '../../Controller/LanguageController.php';
require_once '../../libraries/medoo.php';

$language =new LanguageController();

$result =$language->getall();
?>
<html>
<head>
<link   href="../../css/bootstrap.min.css" rel="stylesheet">
<script src="../../js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <h3>Languages</h3>
    </div>
    <br>
    <div class="row">
        <div><a href="languageinsert.php"><img src="../../images/addrow.png"></a></div><br>
        <table class="table table-striped table-bordered">
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
                echo '<td><a><img src="../../images/edit32.png"></a><a><img src="../../images/delete32.png"></a>';

                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div> <!-- /container -->
</body>

</html>
