<div class="panel panel-default">
    <div class="panel-body">

        <div class="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($setting_list as $setting) { ?>
                <li role="presentation" class="">
                    <a href="#<?php echo $setting->name?>" aria-controls="<?php echo $setting->name?>" role="tab" data-toggle="tab">
                        <?php echo $setting->value?>
                    </a>
                </li>
                <?php } ?>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <?php foreach ($setting_list as $setting) { ?>
                <div role="tabpanel" class="tab-pane" id="<?php echo $setting->name?>">
                    <?php echo make_form_open() ?>
                    <?php foreach ($setting->subSettings() as $input) { ?>
                        <div class="form-group">
                            <?php echo make_input(array(
                                'field' => $input->name,
                                'label' => $input->name,
                                'rules' => $input->rules,
                                'value' => $input->value
                            ), array(
                                $input->name => $input->value
                            )) ?>
                        </div>
                    <?php } ?>
                    <?php echo make_form_submit() ?>
                    <?php echo make_form_close() ?>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
