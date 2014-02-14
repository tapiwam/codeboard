<?php
$template = '
// =================================
// Header Information
// -| More information here 
//==================================

#include &lt;iostream&gt;
using namespace std;

int main()
{
	// Copyright notice
	
	// Code
	
	// Copyright notice
	
	return 0;

} //main';
?>
<div class="container">

	<div class="row_fluid">
		<div class="span8">
			<div id="content">
				<ul  id="tabs" class="nav nav-tabs" data-tabs="tabs">
					<li class="active"><a href="#code" data-toggle="tab">Main</a></li>
					<li><a href="#input" data-toggle="tab">Input</a></li>
					<li><a href="#sample" data-toggle="tab">Sample</a></li>
				</ul>

				<div id="my-tab-content" class="tab-content">

					<div class="tab-pane active" id="code">
						<h1>main.cpp</h1>
						<?php
							$l = set_value('coder');
							if (!empty($l)) { $r = set_value('coder'); } else { $r = $template; }
							echo form_textarea('coder', $r, 'id="coder"');
						?>
					</div>

					<div class="tab-pane" id="input">
						<h1>input.txt</h1>
						<?php echo form_textarea('input', set_value('input'), 'id="input"'); ?>
					</div>

					<div class="tab-pane" id="sample">
						<h1>Sample output</h1>
						<?php echo form_textarea('sample', set_value('sample'), 'id="sample"'); ?>
					</div>

				</div>

				<div class="span4">
					Sidebar
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		$('#tabs').tabs();
	});

	var input = CodeMirror.fromTextArea(document.getElementById("input"), {
		lineNumbers : true,
		matchBrackets : true,
		mode : "text/x-c++src",
		theme : "ambiance"
	});

	var code = CodeMirror.fromTextArea(document.getElementById("coder"), {
		lineNumbers : true,
		matchBrackets : true,
		mode : "text/x-c++src",
		theme : "ambiance"
	});

	var code = CodeMirror.fromTextArea(document.getElementById("sample"), {
		lineNumbers : true,
		matchBrackets : true,
		mode : "text/x-c++src",
		theme : "ambiance"
	});

</script>
