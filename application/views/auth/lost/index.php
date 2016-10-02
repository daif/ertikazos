    <?php echo make_message() ?>
    <div class="login-panel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-lock"></i> <?php echo get_setting('config/site_name',true)?> <?php echo lang('lost') ?></h3>
        </div>
        <div class="panel-body">
        <?php echo make_form_open() ?>
            <fieldset>
                <?php echo make_form_open() ?>
                <?php foreach ($this->user->forms('lost') as $key => $input) { ?>
                    <div class="form-group">
                        <?php echo make_input($input) ?>
                    </div>
                <?php } ?>
                <?php echo make_form_submit() ?>
                <?php echo make_form_close() ?>
            </fieldset>
        <?php echo make_form_close() ?>
        </div>
    </div>
    <div class="dropdown <?php echo ($this->session->userdata('lang') == 'arabic')?'pull-left':'pull-right' ?>" style="margin: 2px;">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownLang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-language fa-fw"></i> <?php echo ($this->session->userdata('lang') == 'arabic')?'عربي':'English' ?> <i class="fa fa-caret-down"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownLang">
            <li><a href="<?php echo base_url() ?>Auth/Login/lang/english"><i class="fa fa-language fa-fw"></i> English</a></li>
            <li><a href="<?php echo base_url() ?>Auth/Login/lang/arabic"><i class="fa fa-language fa-fw"></i> عربي</a></li>
        </ul>
    </div>