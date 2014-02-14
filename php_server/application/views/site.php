<style>
    .jumbotron {
        background-image:url('../components/img/computer-code.jpg');
        height:300px;
    }

    .jumbotron h1 {
        font-weight:bold;
    }
</style>

<div class="jumbotron">
    <div class="container">
        <h1>Codeboard</h1>
        <h2>An Introductory Programming Environment</h2>
        <p>Simple | Efficiency | Useful</p>

        <?php
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (!(!isset($is_logged_in) || $is_logged_in != true)) {
            echo anchor('site/members', 'My Account', 'class="btn btn-primary btn-large"');
        } else {
            echo anchor('user', 'Login/Register', 'class="btn btn-primary btn-large"');
        }
        ?>
    </div>
</div>

<div class='container'>


    <div class="row featurette">
        <div class="col-md-7">
            <br><br>
            <h2 class="featurette-heading">Practice <span class="text-muted">Anytime!</span></h2>
            <p class="lead">Code wherever, whenever, on your PC, MAC or on your mobile device. All program compiling occurs remotely so don't worry about all the technical stuff!</p>
        </div>
        <div class="col-md-5">
            <a href=<?php echo site_url("ide/basic_ide"); ?>>
                <img class="featurette-image img-responsive" src="http://www.hanselman.com/blog/content/binary/WindowsLiveWriter/VisualStudioProgrammerThemesGallery_CF56/ide_colors_regular_3.png" alt="Generic placeholder image">
            </a>
        </div>
    </div>



    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-5">
            <img class="featurette-image img-responsive" src="../components/img/swot.jpg" alt="SWOT Image">
        </div>
        <div class="col-md-7">
            <br><br>
            <h2 class="featurette-heading">Simplified grading. <span class="text-muted">Now who wouldn't want that?</span></h2>
            <p class="lead">Automated grading and instant analysis. Give us some time and we'll be telling you more than just A grade. What would you do with if you knew your habits, patterns, pass rates, and the works?</p>
        </div>
    </div>

</div>


