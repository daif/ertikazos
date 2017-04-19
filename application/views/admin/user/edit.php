<div class="panel panel-default">
    <div class="panel-body">
    
        <?php echo make_form_open() ?>
        <?php foreach ($this->user->forms('edit') as $key => $input) { ?>
            <div class="form-group">
                <?php echo make_label($input) ?>
                <?php echo make_input($input, $row) ?>
            </div>
        <?php } ?>
        <div class="form-group">
        <?php echo lang('permissions')?>
        <?php $apps_list = $this->app->getMenu(); ?>
        <?php foreach ($apps_list as $app) { ?>
            <div class="app">
                <div class="checkbox parent">
                    <label>
                        <input type="checkbox" name="apps[]" value="<?php echo $app->app_id ?>" 
                        <?php echo (user_hasaccess($row, $app)) ? 'checked' : '' ?>
                        />
                        <i class="input-helper"></i>
                        <?php echo lang(strtolower($app->app_path)) ?>
                    </label>
                </div>
                <div class="children" style="padding: 0 15px;">
                <?php foreach ($app->sub as $sub) { ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="apps[]" value="<?php echo $sub->app_id ?>" 
                        <?php echo (user_hasaccess($row, $sub)) ? 'checked' : '' ?>
                        />
                        <i class="input-helper"></i>
                        <?php echo lang(strtolower($sub->app_path)) ?>
                    </label>
                </div>
                <?php } ?>
                </div>
            </div>
        <?php } ?>
        </div>

        <div class="form-group">
        <?php echo lang('groups')?>
        <?php $groups_list = $this->group->rows(); ?>
        <?php foreach ($groups_list as $group) { ?>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="groups[]" value="<?php echo $group->group_id ?>" 
                    <?php echo ($row->hasgroup($group)) ? 'checked' : '' ?>
                    />
                    <i class="input-helper"></i>
                    <?php echo $group->group_name ?>
                </label>
            </div>
        <?php } ?>
        </div>
        <?php echo make_form_submit() ?>
        <?php echo make_form_close() ?>

    </div>
</div>