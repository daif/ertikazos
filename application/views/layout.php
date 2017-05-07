<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo get_setting('site/desc')?>">
    <meta name="author" content="<?php echo get_setting('site/name')?>">

    <title><?php echo get_setting('site/name')?></title>

    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-reset.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/ionicon/css/ionicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/select2.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/helper.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/trumbowyg/ui/trumbowyg.css" rel="stylesheet">

    <?php if($this->session->userdata('lang') == 'arabic'){ ?>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style-rtl.css" rel="stylesheet">
    <?php } ?>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="icon" href="<?php echo base_url(); ?>assets/img/logo.png">
</head>

<body>


        <!-- Aside Start-->
        <aside class="left-panel hidden-print">

            <!-- brand -->
            <div class="logo">
                <a href="<?php echo base_url(); ?>" class="logo-expanded">
                    <img src="<?php echo base_url(); ?>assets/img/logo.png" style="max-width: 60px; margin-bottom: 5px;"/>
                    <br>
                    <span class="nav-label"><?php echo get_setting('site/name')?></span>
                </a>
            </div>
            <!-- / brand -->
        
            <!-- Navbar Start -->
            <nav class="navigation hidden-print">
                <ul class="list-unstyled">
                    <?php $apps_list = $this->app->getMenu(); ?>
                    <?php foreach($apps_list as $app) { ?>
                    <?php if(user_hasaccess(get_instance()->userdata(), $app)) { ?>
                    <?php if($app->app_menu == App_model::MENU_SHOW) { ?>

                        <?php if(is_array($app->sub) && count($app->sub)>0) { ?>
                            <li class="has-submenu <?php echo (($router_dir == $app->app_path.'/')?'active':'') ?>">
                                <a href="<?php echo base_url($app->app_path) ?>">
                                    <i class="<?php echo $app->app_icon?>"></i>
                                    <span class="nav-label"><?php echo lang(strtolower($app->app_path))?></span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="list-unstyled <?php echo (($router_dir == $app->app_path.'/')?'in':'') ?>">
                                    <?php foreach($app->sub as $subapp) { ?>
                                    <?php if(user_hasaccess(get_instance()->userdata(), $subapp)) { ?>
                                    <?php if($subapp->app_menu == App_model::MENU_SHOW) { ?>
                                        <li class="<?php echo (($router_dir.$router_class == $subapp->app_path)?'active':'') ?>">
                                            <a  href="<?php echo base_url($subapp->app_path) ?>">
                                                <i class="<?php echo $subapp->app_icon?>"></i>
                                                <span class="nav-label"><?php echo lang(strtolower($subapp->app_path))?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                        <?php } else { ?>
                            <li class="<?php echo (($router_dir.$router_class == $app->app_path)?'active':'') ?>">
                                <a href="<?php echo base_url($app->app_path) ?>">
                                    <i class="<?php echo $app->app_icon?>"></i> <span class="nav-label"><?php echo lang(strtolower($app->app_path))?></span>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <?php if(is_array($app->sub) && count($app->sub)>0) { ?>
                            <?php foreach($app->sub as $subapp) { ?>
                            <?php if(user_hasaccess(get_instance()->userdata(), $subapp) && $subapp->app_menu == App_model::MENU_SHOW) { ?>
                            <li class="<?php echo (($router_dir.$router_class == $subapp->app_path)?'active':'') ?>">
                                <a href="<?php echo base_url($subapp->app_path) ?>">
                                    <i class="<?php echo $subapp->app_icon?>"></i> 
                                    <span class="nav-label"><?php echo lang(strtolower($subapp->app_path))?></span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                </ul>
            </nav>
                
        </aside>
        <!-- Aside Ends-->


        <!--Main Content Start -->
        <section class="content">
            
            <!-- Header -->
            <header class="top-head container-fluid hidden-print">
                <button type="button" class="navbar-toggle <?php echo ($this->session->userdata('lang') == 'arabic')?'pull-right':'pull-left'?>">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <!-- Right navbar -->
                <ul class="list-inline top-menu <?php echo ($this->session->userdata('lang') == 'arabic')?'navbar-left top-left-menu':'navbar-right top-right-menu'?>">
                    <!-- Notification -->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-bell-o"></i>
                            <?php if(get_instance()->session->userdata('userdata')->notifications_count()) { ?>
                            <span class="badge badge-sm up bg-pink count"><?php echo get_instance()->session->userdata('userdata')->notifications_count()?></span>
                            <?php } ?>
                        </a>

                        <ul class="dropdown-menu extended" tabindex="5002">
                            <?php foreach(get_instance()->session->userdata('userdata')->notifications() as $key=>$notify) { ?>
                            <li>
                                <a href="<?php echo base_url(); ?>User/Notify/list/<?php echo $notify->notify_id ?>">
                                    <span class="pull-left"><i class="fa fa-user-plus fa-2x text-info"></i></span>
                                    <span><?php echo $notify->notify_title ?><br><small class="text-muted"><?php echo $notify->notify_create_at ?></small></span>
                                </a>
                            </li>
                            <?php } ?>
                            <li>
                                <p><a href="<?php echo base_url(); ?>User/Notify/list" class="text-right"><?php echo lang('see_all_notifications')?></a></p>
                            </li>
                        </ul>
                    </li>
                    <!-- /Notification -->

                    <!-- user login dropdown start-->
                    <li class="dropdown text-center">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="<?php echo get_instance()->userdata('user_name')?>" src="<?php echo base_url(); ?>assets/avatars/<?php echo get_instance()->userdata('user_avatar') ?>" class="img-circle profile-img thumb-sm">
                            <span class="username"><?php echo get_instance()->userdata('user_name')?> </span> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu extended pro-menu fadeInUp animated" tabindex="5003" style="overflow: hidden; outline: none;">
                            <li><a href="<?php echo base_url() ?>User/Account"><i class="fa fa-briefcase"></i> <?php echo lang('profile')?></a></li>
                            <?php if($this->session->userdata('lang') == 'arabic'){ ?>
                                <li><a href="<?php echo base_url() ?>Home/lang/english"><i class="fa fa-language fa-fw"></i> English</a></li>
                            <?php } else { ?>
                                <li><a href="<?php echo base_url() ?>Home/lang/arabic"><i class="fa fa-language fa-fw"></i> عربي</a></li>
                            <?php } ?>
                            <li><a href="<?php echo base_url(); ?>Auth/Login/logout"><i class="fa fa-sign-out"></i> <?php echo lang('logout')?></a></li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->       
                </ul>
                <!-- End right navbar -->
            </header>
            <!-- Header Ends -->

            <!-- Page Content Start -->
            <!-- ================== -->

            <div class="wraper container-fluid">
                
                <div class="page-title hidden-print">
                    <?php if($subapp->breadcrumb) { ?>
                        <ol class="breadcrumb <?php echo ($this->session->userdata('lang') == 'arabic')?'pull-right':'pull-left' ?>" style="margin-bottom: 5px;">
                            <li><a href="<?php echo base_url() ?>"><?php echo lang('home')?></a></li>
                            <?php if($app = $this->app->getByPath($router_dir)) { ?>
                                <li><a href="<?php echo base_url() . $app->app_path?>"><?php echo lang(strtolower($app->app_path))?></a></li>
                            <?php } ?>

                            <?php if($subapp = $this->app->getByPath($router_dir.$router_class)) { ?>
                                <li><a href="<?php echo base_url() . $subapp->app_path?>"><?php echo lang(strtolower($subapp->app_path))?></a></li>
                            <?php } ?>

                            <?php if($action = $this->uri->segment(3)) { ?>
                                <li class="active"><?php echo lang(strtolower($action)) ?></li>
                            <?php } ?>
                        </ol>
                    <?php } ?>

                    <div class="<?php echo ($this->session->userdata('lang') == 'arabic')?'pull-left':'pull-right' ?> btn-create">
                        <?php if($subapp->search_button) { ?>
                        <a class="btn btn-primary" id="btn-search" href="<?php echo base_url() . $subapp->app_path?>/search"><i class="fa fa-search"></i> <?php echo lang('search')?></a>
                        <?php } ?>
                        <?php if($subapp->create_button) { ?>
                        <a class="btn btn-primary" href="<?php echo base_url() . $subapp->app_path?>/create"><i class="fa  fa-plus "></i> <?php echo lang('create')?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>

                <?php echo make_message() ?>
                <?php if(isset($content)) { ?>
                    <?php print $content; ?>
                <?php } else { ?>
                    <p>Not Found</p>
                <?php } ?>

            </div>
            <!-- Page Content Ends -->
            <!-- ================== -->
            <!-- Footer Start -->
            <footer class="footer hidden-print">
                <?php echo date('Y')?> © <?php echo get_setting('site/name')?> - v<?php echo ER_Controller::ER_VERSION?>.
            </footer>
            <!-- Footer Ends -->
        </section>



    <script type="text/javascript">
        $base_url     = '<?php echo base_url()?>';
        $router_dir   = '<?php echo $router_dir?>';
        $router_class = '<?php echo $router_class?>';
        $msg_are_yousure = '<?php echo lang('msg_are_yousure')?>';
        <?php if($this->session->userdata('lang') == 'arabic') { ?>
            $lang = 'arabic';
            $locale = 'ar';
        <?php } else { ?>
            $lang = 'english';
            $locale = 'en';
        <?php } ?>
    </script>

    <!-- js -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/form.validation.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment-with-locales.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/trumbowyg/trumbowyg.js"></script>
<?php if($this->session->userdata('lang') == 'arabic') { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/trumbowyg/langs/ar.min.js"></script>
<?php } ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ertikaz.js"></script>

    @yield('javascript')

</body>

</html>
