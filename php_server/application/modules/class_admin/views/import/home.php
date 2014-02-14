<div class="container">
    <h2>Import home</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4><em><strong>Available classes to import from</strong></em></h4></div>
                <?php if (isset($classes) && sizeof($classes)>0) : ?>
                <div class="list-group">
                    <?php
                    foreach ($classes as $item):
                        //if ($item->id != $class_id):
                            $tag = '<strong>' . $item->class_name . '</strong>' . '<br /> Term: ' . $item->term;
                            if($item->id == $class_id){ $tag .= '<br />(<em class="">Current</em>)'; }
                            echo anchor("class_admin/import/cls/$class_id/$session_id/" . $item->id, $tag, 'class="list-group-item"');
                        //endif;
                    endforeach;
                    ?>
                </div>

                <?php else : ?>
                    <p>Sorry, no other classes under your name were found... </p>
                <?php endif; ?>

            </div>

            <?php echo anchor("class_admin/sessions/$class_id/$session_id", 'Back', 'class="btn btn-primary"'); ?>
        </div>

        <div class="col-md-4">

        </div>

    </div>

</div>