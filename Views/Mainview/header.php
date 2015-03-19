<?php
$language =new LanguageController();
$result =$language->getall();
?>
<!-- Header
   ================================================== -->
<header id="home">

    <nav id="nav-wrap">

        <a class="mobile-btn" href="#nav-wrap" title="Show navigation">Show navigation</a>
        <a class="mobile-btn" href="#" title="Hide navigation">Hide navigation</a>

        <ul id="nav" class="nav">
            <li class="current"><a class="smoothscroll" href="#home">Home</a></li>
            <li><a class="smoothscroll" href="#about">About</a></li>
            <li><a class="smoothscroll" href="#resume">Resume</a></li>
            <li><a class="smoothscroll" href="#portfolio">My Proyects</a></li>
            <li><a class="smoothscroll" href="#contact">Contact</a></li>

            <li>
                <?php
                foreach ($result as $row)
                {
                    echo '<a href="?lang='.$row['code'].'">'.$row['language'].'</a>|';
                }
                ?>


            </li>
        </ul> <!-- end #nav -->

    </nav> <!-- end #nav-wrap -->

    <div class="row banner">
        <div class="banner-text">
            <h1 class="responsive-headline"><?php echo $name;?></h1>
            <p><h3><?php echo $maintext;?></h3></p>
            <hr />

        </div>
    </div>

    <p class="scrolldown">
        <a class="smoothscroll" href="#about"><i class="icon-down-circle"></i></a>
    </p>

</header> <!-- Header End -->