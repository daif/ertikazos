<div class="panel panel-default">
    <div class="panel-body">

    <table class="table table-striped">
    <?php foreach ($this->user->forms('edit_account') as $key => $input) { ?>
        <tr>
            <th class="col-md-3"><?php echo lang($input['field']) ?></th>
            <td><?php echo make_input_value($input, $row) ?></td>
        </tr>
    <?php } ?>
    </table>
    <a href="<?php echo base_url(); ?>User/Account/update" class="btn btn-success"><?php echo lang('update')?></a>
    
    </div>
</div>