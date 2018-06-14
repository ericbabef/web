<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/x-icon" href="public/images/favicon_eb.ico"> 
        <title>RDM | Administration</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="robots" content="noindex, nofollow">
        <meta name="author" content="Eric BABEF">
        <meta name="copyright" content="© 2017 Eric BABEF">
        <!-- PACE 1.0.2 -->
        <link rel="stylesheet" href="vendor/pace/pace-theme-minimal.css">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="vendor/bootstrap-3.2.0/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="vendor/font-awesome-4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="vendor/ionicons-2.0.1/css/ionicons.min.css">
        
        <?php echo $pg_css; ?>
        
        <!-- Theme style -->
        <link rel="stylesheet" href="vendor/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="vendor/dist/css/skins/_all-skins.min.css">
        <!-- Custom style -->
        <link href="public/css/custom.css" rel="stylesheet" type="text/css" />
    
  <style>
.error {
  color: red;
  margin-top: 6px;
}
    </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <?php if ($_GET['action'] == "admin"): ?>
    <body class="hold-transition login-page">
        <?php echo $pg_content_log; ?>
        <?php echo $pg_content; ?>
        <!-- jQuery 2.1.4 -->
        <script src="vendor/jquery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="vendor/bootstrap-3.2.0/dist/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="vendor/dist/js/app.min.js"></script>
        <?php echo $pg_js; ?>
    </body>
     <?php else: ?>
        <body class="hold-transition skin-blue sidebar-mini">
            <div class="wrapper">
                <?php include('header.php'); ?>
                <?php include('menu.php'); ?>
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            <?php echo $pg_title; ?>
                            <small><?php echo $pg_subtitle; ?></small>
                        </h1>
                    </section>
                    <?php echo $pg_content_log; ?>

                    <!-- Main content -->
                    <section class="content">
                        <?php echo $pg_content; ?>
                    </section><!-- /.content -->
                </div><!-- /.content-wrapper -->
                
                <?php include('footer.php'); ?>
            </div><!-- ./wrapper -->

            <!-- PACE 1.0.2 -->
            <script src="vendor/pace/pace.min.js"></script>
            <!-- jQuery 2.1.4 -->
            <script src="vendor/jquery/jquery-2.2.3.min.js"></script>
            <!-- jQuery UI 1.11.4 -->
            <script src="vendor/jQueryUI/jquery-ui.min.js"></script>
            <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
            <script>
              $.widget.bridge('uibutton', $.ui.button);
            </script>
            <!-- Bootstrap 3.3.5 -->
            <script src="vendor/bootstrap-3.2.0/dist/js/bootstrap.min.js"></script>
            <!-- Resolve conflict with button -->
            <script>
              var btn = $.fn.button.noConflict();
              $.fn.btn = btn;
            </script>
            <!-- Slimscroll -->
            <script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
            <!-- FastClick -->
            <script src="vendor/fastclick/fastclick.min.js"></script>
            <!-- AdminLTE App -->
            <script src="vendor/dist/js/app.min.js"></script>

            <?php echo $pg_js; ?>

            <!-- Custom js -->
            <script src="public/js/scripts.js"></script>
        </body>
    <?php endif; ?>
</html>