<?php
// ----------------------------------------------------------------
// TITLE
// ----------------------------------------------------------------

$pg_title = "Connexion";
$pg_subtitle = "veuillez-vous identifier";

// ----------------------------------------------------------------
// CSS - ajout des feuilles de style
// ----------------------------------------------------------------

$pg_css = <<<CSS
CSS;

// ----------------------------------------------------------------
// JS - ajout des plugins et scripts javascript
// ----------------------------------------------------------------

$pg_js = <<<JS
JS;

// ----------------------------------------------------------------
// HTML
// ----------------------------------------------------------------

$pg_content = <<<CONTENT
CONTENT;



$pg_content .= <<<CONTENT
        <div class="login-box">
            <div class="login-logo">
                <p><img src="public/images/logo.png" alt="CAHM" /><br />
                <b>RDM</b> | Connexion</p>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Connectez-vous pour commencer votre session</p>
                <form action="index.php?action=admin" method="post">
                <div class="form-group has-feedback">
                    <input type="text" name="login" class="form-control" placeholder="Identifiant" required />
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="pass" class="form-control" placeholder="Mot de passe" required />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Me connecter</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
CONTENT;

?>
<?php require('template.php'); ?>