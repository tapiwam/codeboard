<div id="admin_class_panel">

    <div style="height:50px;"></div>

    <div class="panel">
        <h2>Management</h2>
        <ul>
            <?php
            echo anchor('class_admin/create_class_option', '<li>Create a Class</li>');
            echo anchor('#', '<li>Manage Classes</li>');
            ?>
        </ul>
    </div>

    <hr />

    <div class="panel">
        <h2>Classes</h2>
        <ul>
<?php
if (isset($classes)) {
    foreach ($classes as $item) {
        echo anchor('class_admin/sessions/' . $item->term . '/' . $item->class_name, "<li>$item->class_name</li>");
    }
} else {
    echo '<li>No Classes at the moment!</li>';
}
?>

        </ul>
    </div>

</div>

