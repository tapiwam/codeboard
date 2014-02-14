<div class="wrapper">

    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="container">

                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <?php echo anchor('site', 'Codeboard', array('class' => 'navbar-brand')); ?>

                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav navbar-right">
                        <li><?php echo anchor('site', 'Home', array('class' => 'active')); ?></li>
                        <li><?php echo anchor('ide', 'IDE'); ?></li>
                        <li><?php echo anchor('site/contact', 'Contact Us'); ?></li>
                        <?php
                        $is_logged_in = $this->session->userdata('is_logged_in');

                        if (!isset($is_logged_in) || $is_logged_in != true) {
                            echo '<li>';
                            echo anchor('user/login', 'Login');
                            echo '</li><li>';
                            echo anchor('site/register', 'Register');
                            echo '</li>';
                        } else {
                            // add in dropdown

                            echo '<li class="dropdown">';
                            echo anchor('site/members', 'My Account <b class="caret"></b>', 'class="dropdown-toggle" data-toggle="dropdown"');
                            //echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">Filters <b class="caret"></b></a>';
                            echo '<ul class="dropdown-menu">';
                            echo '<li>' . anchor('site/members', 'Home') . '</li>';
                            echo '<li>' . anchor('site/settings', 'Settings') . '</li>';
                            //echo '<li>'. anchor('site/profile', 'Profile') .'</li>';
                            echo '</ul>';

                            echo '</li>';

                            //echo '<li>'. anchor('forum', 'Forum' ) . '</li>';
                            echo '<li>';
                            echo anchor('user/logout', 'Logout');
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>