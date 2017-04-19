<div class="panel panel-default">
    <div class="panel-body">
    
        <?php echo make_form_open() ?>
        <?php foreach ($this->{class_name}->forms('edit') as $key => $input) { ?>
            <div class="form-group">
                <?php echo make_label($input) ?>
                <?php echo make_input($input, $row) ?>
            </div>
        <?php } ?>
        <?php echo make_form_submit() ?>
        <?php echo make_form_close() ?>

    </div>
</div>