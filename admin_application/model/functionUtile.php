<?php
function dateFunction() {// date format
    date_default_timezone_set('Europe/Paris');
    $datetimefrm_short = 'd/m/Y H:i';
    return $datetimefrm_short;
}

function logWarning($text) {
$content = <<<LOGWARNING
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12" style="margin-top: 15px;">
                    <div class="alert alert-warning alert-dismissable">
                        <i class="icon fa fa-warning"></i>
                        {$text}
                    </div>
                </div>
            </div>
        </div>
LOGWARNING;
    return $content;
}

function logSuccess($text) {
$content = <<<LOGWARNING
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12" style="margin-top: 15px;">
                    <div class="alert alert-success">
                        <i class="icon fa fa-check"></i>
                        {$text}
                    </div>
                </div>
            </div>
        </div>
LOGWARNING;
    return $content;
}