<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo get_setting('site/desc')?>">
        <meta name="author" content="<?php echo get_setting('site/name')?>">
        <title><?php echo get_setting('site/name')?></title>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>assets/home/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/home/css/bootstrap-reset.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/home/css/font-awesome.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>assets/home/css/style.css" rel="stylesheet">
        <?php if($this->session->userdata('lang') == 'arabic'){ ?>
        <link href="<?php echo base_url(); ?>assets/home/css/bootstrap-rtl.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/home/css/style-rtl.css" rel="stylesheet">
        <?php } ?>

        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/logo.png">
        <meta property="og:url" content="<?php echo base_url(); ?>" />
        <meta property="og:site_name" content="<?php echo get_setting('site/name')?>" />
        <meta property="og:title" content="<?php echo get_setting('site/name')?>" />
        <meta property="og:description" content="<?php echo get_setting('site/desc')?>" />
        <meta property="og:image" content="<?php echo base_url(); ?>assets/img/logo.png" />
    </head>

    <body>

        <header>
            <section class="hero">
                <div class="container">
                    <div class="row nav-wrapper">
                        <nav class="navbar navbar-custom">
                            <div class="container-fluid">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-collapse">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a class="navbar-brand" href="<?php echo base_url(); ?>" style="padding-top: 0px;"><img src="<?php echo base_url(); ?>assets/img/logo.png" alt="<?php echo get_setting('site/name')?>" style="height: 60px;"></a>
                                    <h2 style="float: right; margin-top: 12px;"><?php echo get_setting('site/name')?></h2>
                                </div>

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="top-navbar-collapse">
                                    <ul class="nav navbar-nav <?php echo ($this->session->userdata('lang') == 'arabic')?'navbar-left':'navbar-right'?>">
                                        <?php if(get_instance()->userdata('user_id')) {?>
                                            <li><a href="<?php echo base_url(); ?>User/Account" class="btn-signup"><?php echo lang('user/profile')?></a></li>
                                            <li><a href="<?php echo base_url(); ?>Auth/Logout" class="btn-signup btn-primary"><?php echo lang('logout')?></a></li>
                                        <?php } else { ?>
                                             <li><a href="<?php echo base_url(); ?>Auth/Login" class="btn-signup btn-primary"><?php echo lang('login')?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div><!-- /.navbar-collapse -->
                            </div><!-- /.container-fluid -->
                        </nav> <!-- end nav -->
                    </div> <!-- End row -->
                </div> <!-- end container -->
            </section> <!-- end hero -->
        </header>


        <section class="section features" style="padding-top: 5%;">
            <div class="container text-center">

                <div class="row" style="padding-bottom: 5%;">
                    <div class="col-sm-12">
                        <h3 class="intro"><?php echo get_setting('site/desc')?></h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div>
                            <h3 style="font-size: 48px;"><i class="fa fa-dashboard"></i></h3>
                            <p><?php echo lang('user/dashboard')?></p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div>
                            <h3 style="font-size: 48px;"><i class="fa fa-user"></i></h3>
                            <p><?php echo lang('user/account')?></p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div>
                            <h3 style="font-size: 48px;"><i class="fa fa-bell-o"></i></h3>
                            <p><?php echo lang('user/notify')?></p>
                        </div>
                    </div>
                </div> <!-- End row -->
            </div>
        </section> 
        <!-- features -->


        <section class="content-wrap bg-light section" id="features">
            <div class="container">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="feature-sec">
                            <h3>What is it?</h3>
                            <p>Build your web applications faster using the built-in features users management, permissions, group, application and more ...</p>
                            <a href="http://ertikazos.com/" class="use-btn btn-primary">Get Started</a>
                        </div>
                    </div><!-- end col -->



                </div> <!-- end row -->
            </div><!-- end container -->
        </section>
        <!-- end Features -->



        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a  href="<?php echo base_url(); ?>" style="color:#eee"><?php echo date('Y')?> © <?php echo get_setting('site/name')?>.</a>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end container -->
        </footer>
        <!-- End Footer -->

        <script src="<?php echo base_url(); ?>assets/home/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/home/js/bootstrap.min.js"></script>
    </body>
</html>