<div class="container">

    <h1>Registration</h1>

    <p>Thank you for showing interest in our site. There are 2 main types of accounts available within this system.</p> 

    <p>What kind of account would you like?</p>

    <hr />
    <?php
    echo anchor('user/signup', 'Student', 'class="btn btn-primary btn-large"');
    echo "               ";
    echo anchor('user/signup_admin', 'Instuctor', 'class="btn btn-primary btn-large"');
    ?>

</div>