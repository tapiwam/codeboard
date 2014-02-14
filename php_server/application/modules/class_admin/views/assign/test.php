<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FAMU CIS Codeboard</title>

        <link rel="stylesheet" href="http://localhost/cbl/css/bootstrap.css">
        <link rel="stylesheet" href="http://localhost/cbl/css/bootstrap-responsive.css">
        <link rel="stylesheet" href="http://localhost/cbl/css/mod.css">
        <link rel="stylesheet" href="http://localhost/cbl/css/datepicker.css">
        <link rel="stylesheet" href="http://localhost/cbl/css/bootstrap-timepicker.css">

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
        <script src="http://localhost/cbl/js/bootstrap.js"></script>
        <script src="http://localhost/cbl/js/bootstrap-datepicker.js"></script>
        <script src="http://localhost/cbl/js/bootstrap-timepicker.js"></script>

        <link rel="stylesheet" href="http://localhost/cbl/codemirror/lib/codemirror.css">
        <link rel="stylesheet" href="http://localhost/cbl/codemirror/theme/ambiance.css">
        <script src="http://localhost/cbl/codemirror/lib/codemirror.js"></script>
        <script src="http://localhost/cbl/codemirror/mode/clike/clike.js"></script>

        <title>FAMU Codeboard</title>

    </head>

    <body><div id="wrapper">
            <div class="wrapper">

                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container">
                            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </a>

                            <a href="http://localhost/cbl/index.php/site" class="brand">FAMU CIS Codeboard</a>				
                            <div class="nav-collapse collapse" >
                                <ul class="nav pull-right">
                                    <li><a href="http://localhost/cbl/index.php/site" class="active">Home</a></li>
                                    <li><a href="http://localhost/cbl/index.php/site/contact">Contact Us</a></li>
                                    <li><a href="http://localhost/cbl/index.php/site/members">My Account</a></li><li><a href="http://localhost/cbl/index.php/user/logout">Logout</a></li>					</ul>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="container">
                    <ul class="breadcrumb">


                        <li><a href="http://localhost/cbl/index.php/class_admin/tc/stage1/9/1/file">Step 1</a> <span class="divider">/</span> </li>





                        <li><a href="http://localhost/cbl/index.php/class_admin/tc/stage2/9/1/file">Step 2</a> <span class="divider">/</span> </li>





                        <li><a href="http://localhost/cbl/index.php/class_admin/tc/stage3/9/1/file">Step 3</a> <span class="divider">/</span> </li>





                        <li class="active">Step 4 <span class="divider">/</span> </li>





                        <li><a href="http://localhost/cbl/index.php/class_admin/tc/stage5/9/1/file">Step 5</a> <span class="divider">/</span> </li>



                    </ul>
                    <form action="http://localhost/cbl/index.php/class_admin/tc/filecontent_multi/9/1/file" class="form-horizontal well" method="post" accept-charset="utf-8">
                        <fieldset>

                            <h2>Content for Individual Test Cases</h2>




                            <h4>Test Case 1</h4>
                            <div class="control-group">
                                <label>CIN for file</label>
                                <div class="controls">
                                    <textarea name="cin_1" cols="40" rows="10" class="input-xlarge" rows="8" col="14" id="cin_1">1</textarea>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label for="3_1">File: evenodd.txt</label>
                                <div class="controls">
                                    <textarea name="3_1" cols="40" rows="10" class="input-xlarge" rows="8" col="14" id="3_1">0</textarea>
                                </div>
                            </div>
                            <hr />
                            
                            <h4>Test Case 2</h4>
                            <div class="control-group">
                                <label>CIN for file</label>
                                <div class="controls">
                                    <textarea name="cin_2" cols="40" rows="10" class="input-xlarge" rows="8" col="14" id="cin_2">2</textarea>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label for="2_2">File: evenodd.txt</label>
                                <div class="controls">
                                    <textarea name="2_2" cols="40" rows="10" class="input-xlarge" rows="8" col="14" id="2_2">0</textarea>
                                </div>
                            </div>
                            <hr />

                            <div class="form-actions">
                                <a href="http://localhost/cbl/index.php/class_admin/tc/stage3/9/1/file" class = "btn btn-large btn-primary">Back</a>            <input type="submit" name="submit" value="Next step..." class = "btn btn-large btn-primary" />
                            </div>

                        </fieldset>

                    </form>
                </div>


                <script>
                    var editor = CodeMirror.fromTextArea(document.getElementById("cin_1"), {
                        lineNumbers: true,
                        matchBrackets: true,
                        theme: "ambiance"
                    });


                    var editor = CodeMirror.fromTextArea(document.getElementById("3_1"), {
                        lineNumbers: true,
                        matchBrackets: true,
                        theme: "ambiance"
                    });


                    var editor = CodeMirror.fromTextArea(document.getElementById("cin_2"), {
                        lineNumbers: true,
                        matchBrackets: true,
                        theme: "ambiance"
                    });


                    var editor = CodeMirror.fromTextArea(document.getElementById("2_2"), {
                        lineNumbers: true,
                        matchBrackets: true,
                        theme: "ambiance"
                    });


                </script>
            </div>

        </div>

    <footer id="footer">
        <hr />
        <div class="footer">
            <div class="container">
                <div class="container narrow row-fluid">
                    &copy; codeboard. 2013					<br />

                    <div class="links">
                        
                        <a href="http://localhost/cbl/index.php/#">News</a>				  		<a href="http://localhost/cbl/index.php/#">FAMU</a>				  		<a href="http://localhost/cbl/index.php/#">FAMU-CIS</a>				  	</div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
