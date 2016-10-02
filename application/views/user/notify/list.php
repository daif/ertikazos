<div class="panel panel-default">
    <div class="panel-body">

    <table class="table table-striped">
        <thead>
            <tr>
            <?php foreach ($this->notify->forms('list') as $key => $input) { ?>
                <th><?php echo lang($input['field']) ?></th>
            <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows_list as $row) { ?>
            <tr>
            <?php foreach ($this->notify->forms('list') as $key => $input) { ?>
                <td><?php echo make_input_value($input, $row) ?></td>
            <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    </div>
</div>
