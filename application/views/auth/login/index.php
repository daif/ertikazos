    <?php echo make_message() ?>
    <div class="panel panel-color panel-primary">
        <div class="panel-heading"> 
           <h3 class="text-center m-t-10"> <?php echo lang('login_to') ?> <strong><?php echo get_setting('site/name')?></strong> </h3>
        </div>
        <?php echo make_form_open('', ['class'=>'form-horizontal m-t-40']) ?>
            <div class="form-group ">
                <div class="col-xs-12">
                    <input class="form-control" placeholder="<?php echo lang('email') ?>" name="email" type="email" data-rules="required|email" autofocus>
                </div>
            </div>
            <div class="form-group ">
                <div class="col-xs-12">
                    <input class="form-control" placeholder="<?php echo lang('password') ?>" name="password" type="password" value="" data-rules="required|min_length[7]">
                </div>
            </div>
            
            <div class="form-group text-right">
                <div class="col-xs-12">
                    <button class="btn btn-purple w-md" type="submit"><?php echo lang('login') ?></button>
                </div>
            </div>
            <div class="form-group m-t-30">
                <?php if(get_setting('auth/driver') == 'Database') {?>
                <div class="col-sm-7">
                    <a href="<?php echo base_url('/Auth/Lost'); ?>"><i class="fa fa-lock m-r-5"></i> <?php echo lang('lost_password') ?></a>
                </div>
                <?php } ?>
                <?php if(get_setting('auth/registration') == 'Allowed') {?>
                <div class="col-sm-5 text-right">
                    <a href="<?php echo base_url('/Auth/Register'); ?>"><i class="fa fa-edit m-r-5"></i> <?php echo lang('register') ?></a>
                </div>
                <?php } ?>
            </div>
        <?php echo make_form_close() ?>
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