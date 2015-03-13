<?php

?>


<!-- Contact Section
================================================== -->
<section id="contact">

    <div class="row section-head">

        <div class="two columns header-col">

            <h1><span>Get In Touch.</span></h1>

        </div>

        <div class="ten columns">

            <p class="lead">If you have any comments or would like to contact me, you can fill the form below
            </p>

        </div>

    </div>

    <div class="row">

        <div class="eight columns">

            <!-- form -->
            <form action="Formactions/sendmail.php" method="post" id="contactForm" name="contactForm">
                <fieldset>
                    <div>
                        <label for="contactName">Name <span class="required">*</span></label>
                        <input type="text" value="" size="35" id="contactName" name="contactName">
                    </div>

                    <div>
                        <label for="contactEmail">Email <span class="required">*</span></label>
                        <input type="text" value="" size="35" id="contactEmail" name="contactEmail">
                    </div>

                    <div>
                        <label for="contactSubject">Subject</label>
                        <input type="text" value="" size="35" id="contactSubject" name="contactSubject">
                    </div>

                    <div>
                        <label for="contactMessage">Message <span class="required">*</span></label>
                        <textarea cols="50" rows="15" id="contactMessage" name="contactMessage"></textarea>
                    </div>

                    <div>
                        <button class="submit">Submit</button>
                     <span id="image-loader">
                        <img alt="" src="images/loader.gif">
                     </span>
                    </div>

                </fieldset>
            </form> <!-- Form End -->

            <!-- contact-warning -->
            <div id="message-warning"></div>
            <!-- contact-success -->
            <div id="message-success">
                <i class="fa fa-check"></i>Your message was sent, thank you!<br>
            </div>

        </div>


        <aside class="four columns footer-widgets">

            <div class="widget widget_contact">

                <h4>E-mail and Phone</h4>
                <p class="address">
                    andresfranco@cableonda.net<br>

                    <span>(507) 6981-0649</span>
                </p>

            </div>



        </aside>

    </div>

</section> <!-- Contact Section End-->