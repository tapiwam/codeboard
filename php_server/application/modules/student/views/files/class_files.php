<div class="container">

    <h2>File cabinet</h2>
    <h4>
        <strong><em>
            Class: <?php echo $classinfo[0]->class_name; ?><br />
            Term: <?php echo $classinfo[0]->term; ?>
        </em></strong>
    </h4>


    <div class="row">
        <div class="col-md-8">
            <h4><em><strong>Class files</strong></em></h4>

            <?php if (isset($msg) && !empty($msg)): ?>
                <p class="well"><?php echo $msg; ?></p>
            <?php endif; ?>

            <?php if (isset($files)): ?>

                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <th>File name</th>
                    <th>File size</th>
                    <th></th>
                    </thead>

                    <?php foreach ($files as $file): ?>
                        <tr>
                            <td><?php echo $file->file['filename']; ?></td>
                            <td><?php echo ceil($file->file['length'] / 1024) . ' KB'; ?></td>
                            <td><?php echo anchor('file_cabinet/download/' . $class_id . '/' . $file->file['filename'], 'Download', 'class="btn"'); ?></td>
                        </tr>	
                    <?php endforeach; ?>

                </table>
            <?php else: ?>
                <p>This file cabinet seems to be empty right now.</p>
            <?php endif; ?>

            <?php echo anchor("student/classes/$class_id", 'Back', 'class="btn btn-primary"') ?>

        </div>

        <div class="col-md-4">

        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="upload_file" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Upload file" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="Update Email">Upload file</h3>
    </div>
    <div class="modal-body">
        <p>Please select the file that you are trying to upload.</p>

        <?php echo form_open_multipart('file_cabinet/upload/' . $class_id, 'id="upload"'); ?>
        <input type="file" name="userfile" size="20" />

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php echo form_submit('submit', 'Upload file', 'class = "btn btn-primary" id="sendit"'); ?>
    </div>
</div>

<script>
    function disp_confirm() {
        return confirm("Are you sure you want to delete the file?");
    }
</script>
