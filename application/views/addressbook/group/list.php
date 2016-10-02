<div class="panel panel-default">
    <div class="panel-body">

    <table class="table table-striped">
        <thead>
            <tr>
            <?php foreach ($this->group->forms('list') as $key => $input) { ?>
                <th><?php echo lang($input['field']) ?></th>
            <?php } ?>
                <th><?php echo lang('actions')?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows_list as $row) { ?>
            <tr>
            <?php foreach ($this->group->forms('list') as $key => $input) { ?>
                <td><?php echo make_input_value($input, $row) ?></td>
            <?php } ?>
                <td><?php echo make_form_actions($row) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    </div>
</div>