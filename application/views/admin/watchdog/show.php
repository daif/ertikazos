<div class="panel panel-default">
    <div class="panel-body">

    <table class="table table-striped">
    <?php foreach ($this->watchdog->forms('show') as $key => $input) { ?>
        <tr>
            <th class="col-md-3"><?php echo lang($input['field']) ?></th>
            <td><?php echo make_input_value($input, $row) ?></td>
        </tr>
    <?php } ?>
    </table>

    </div>
</div>