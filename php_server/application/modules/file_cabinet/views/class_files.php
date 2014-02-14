<div class="container">

    <h2>File cabinet</h2>
    <h4>Term: <?php echo $classinfo[0]->term; ?></h4>
    <h4>Class: <?php echo $classinfo[0]->class_name; ?></h4>

    <div class="row">
        <div class="col-md-8">
            <h4><em><strong>Class files</strong></em></h4>

            <?php if (isset($msg) && !empty($msg)): ?>
                <div class="well"><?php echo $msg; ?></div>
            <?php endif; ?>

            <?php if (isset($files)): ?>

                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <th>File name</th>
                    <th>File size</th>
                    <th></th>
                    <th></th>
                    </thead>

                    <?php foreach ($files as $file): ?>
                        <tr>
                            <td><?php echo $file->file['filename']; ?></td>
                            <td><?php echo ceil($file->file['length'] / 1024) . ' KB'; ?></td>
                            <td><?php echo anchor('file_cabinet/download/' . $class_id . '/' . $file->file['filename'], 'Download', 'class="btn"'); ?></td>
                            <td><?php echo anchor('file_cabinet/delete_file/' . $class_id . '/' . $file->file['filename'], 'Delete', 'class="btn" onclick="disp_confirm()"'); ?></td>
                        </tr>	
                    <?php endforeach; ?>

                </table>
            <?php else: ?>
                <p>This file cabinet seems to be empty right now.</p>
            <?php endif; ?>


            
            <a href="#upload_file" role="button" class="btn btn-primary " data-toggle="modal">Upload file</a>
            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>

            <?php if ($classinfo[0]->active == 1): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Class Availability</h4></div>
                    <div class="list-group">
                        <?php echo anchor('class_admin/manage/deactivate_class/' . $class_id, 'Deactivate Class', 'class="list-group-item"'); ?>
                        <?php echo anchor('class_admin/manage/add_instructor_option/' . $class_id, 'Add an Instructor', 'class="list-group-item"'); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Class Availability</h4></div>
                    <div class="list-group">
                        <?php echo anchor('class_admin/manage/activate_class/' . $class_id, 'Reactivate Class', 'class="list-group-item"'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="upload_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Upload file" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="Update Email">Upload file</h3>
            </div>
            <div class="modal-body">
                <p>Please select the file that you are trying to upload (Max size is 25MB).</p>

                <?php echo form_open_multipart('file_cabinet/upload/' . $class_id, 'id="upload"'); ?>
                <input type="file" name="userfile" size="20" />

            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <?php echo form_submit('submit', 'Upload file', 'class = "btn btn-primary" id="sendit"'); ?>
            </div>
        </div>
    </div>
    
</div>

<script>
    function disp_confirm() {
        return confirm("Are you sure you want to delete the file?");
    }
</script>
