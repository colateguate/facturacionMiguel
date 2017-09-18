<?php
$faqs = FAQ::loadAll ();
?>

<div class="row">
    <div class="col-md-12">

        <h2 class="panel-title">FAQs</h2>

        <div class="toggle" >

            <?php
            foreach ($faqs as $item) {
                echo "<section class=\"toggle\">
                <label>" . $item -> getQuestion () . "</label>
                <p style=\"height: 0px;\">" . nl2br ($item -> getAnswer ()) . "</p>
            </section>";
            }
            ?>

        </div>
    </div>
</div>
