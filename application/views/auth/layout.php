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
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/helper.css" rel="stylesheet">

    <?php if($this->session->userdata('lang') == 'arabic'){ ?>
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
    <div class="wrapper-page">
        <?php echo make_message() ?>
        <?php if(isset($content)) { ?>
            <?php print $content; ?>
        <?php } else { ?>
        <p>Not found</p>
        <?php } ?>
    </div>


    <script>
        $base_url     = '<?php echo base_url()?>';
        <?php if($this->session->userdata('lang') == 'arabic') { ?>
            $lang = 'arabic';
            $locale = 'ar';
        <?php } else { ?>
            $lang = 'english';
            $locale = 'en';
        <?php } ?>
    </script>
    <!-- js -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/form.validation.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.app.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/ertikaz.js"></script>
</body>
</html>