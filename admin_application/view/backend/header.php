<!-- header logo: style can be found in header.less -->
<header class="main-header">
    <a href="index.php?action=home" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><i class="fa fa-cutlery"></i></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><i class="fa fa-cutlery"></i></b> Manzé créol</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Menu</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li><a href="#" onClick="window.location.reload();"><i class="fa fa-refresh"></i><span class="sr-only"> Actualiser</span></a></li>
                <li class="hidden-xs"><a href="#"><i class="fa fa-clock-o"></i> <?php echo date($datetimefrm_short); ?></a></li>
                <!--<li><a href="index.php?action=logout"><i class="fa fa-power-off"></i><span class="sr-only"> <?php echo $login; ?></span></a></li>-->

                <li class="dropdown user-menu">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">                                       
                        <li class="user-header bg-light-blue">
                            <img src="http://www.gravatar.com/avatar/7315c04268d12b0b7032e8818e0f7fb1?s=200&amp;r=g&amp;d=mm" class="img-circle" alt="Avatar" />
                            <p><?php echo $login; ?></p>
                        </li>
                        <li class="user-footer">
                            <div>
                                <a href="index.php?action=logout" class="btn btn-default btn-block btn-flat"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                </li>
        </div>
    </nav>
</header>