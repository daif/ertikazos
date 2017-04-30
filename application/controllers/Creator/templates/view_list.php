<?php echo make_search_form($this->{class_name}->forms('search'), '{App_name}/{Class_name}/list') ?>

<div class="panel panel-default">
    <div class="panel-body">

    <table class="table table-striped">
        <thead>
            <tr>
            <?php foreach ($this->{class_name}->forms('list') as $key => $input) { ?>
                <th><?php echo lang($input['field']) ?></th>
            <?php } ?>
                <th class="hidden-print"><?php echo lang('actions')?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows_list as $row) { ?>
            <tr>
            <?php foreach ($this->{class_name}->forms('list') as $key => $input) { ?>
                <td><?php echo make_input_value($input, $row) ?></td>
            <?php } ?>
                <td class="hidden-print"><?php echo make_form_actions($row) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php echo $paging ?>

    </div>
</div>