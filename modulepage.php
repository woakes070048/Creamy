<?php
	/**
		The MIT License (MIT)
		
		Copyright (c) 2015 Ignacio Nieto Carvajal
		
		Permission is hereby granted, free of charge, to any person obtaining a copy
		of this software and associated documentation files (the "Software"), to deal
		in the Software without restriction, including without limitation the rights
		to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
		copies of the Software, and to permit persons to whom the Software is
		furnished to do so, subject to the following conditions:
		
		The above copyright notice and this permission notice shall be included in
		all copies or substantial portions of the Software.
		
		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
		AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
		OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
		THE SOFTWARE.
	*/
	
require_once('./php/UIHandler.php');
require_once('./php/LanguageHandler.php');
require_once('./php/CRMDefaults.php');
require_once('./php/ModuleHandler.php');
require_once('./php/Module.php');
@include('./php/Session.php');

$ui = \creamy\UIHandler::getInstance();
$lh = \creamy\LanguageHandler::getInstance();
$user = \creamy\CreamyUser::currentUser();

$error = false;
if (isset($_GET["module_name"])) {
	$moduleName = urldecode($_GET["module_name"]);
	$mh = \creamy\ModuleHandler::getInstance();
	$instance = $mh->getInstanceOfModuleNamed($moduleName);
	if (isset($instance)) {
		// generate title and subtitle
		$title = $instance->mainPageViewTitle();
		$subtitle = $instance->mainPageViewSubtitle();
		// get arguments
		$args = (isset($_GET["args"])) ? \creamy\ModuleHandler::decodeModuleArguments($_GET["args"]) : array();
		// generate main content.
		$content = $instance->mainPageViewContent($args);
	} else { $error = true; } // Unable to instantiate the module.
} else { $error = true; } // Unable to get the module.

// check if error happened
if ($error) {
	$title = $lh->translationFor("modules");
	$subtitle = $lh->translationFor("module_management");
	$content = $ui->boxWithMessage($lh->translationFor("error"), $lh->translationFor("error_loading_module"));
}


?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Creamy</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Creamy style -->
        <link href="css/creamycrm.css" rel="stylesheet" type="text/css" />
        <?php print $ui->creamyThemeCSS(); ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<!-- Forms and actions -->
		<script src="js/jquery.validate.min.js" type="text/javascript"></script>
        <!-- Creamy App -->
        <script src="js/app.min.js" type="text/javascript"></script>

    </head>
    <?php print $ui->creamyBody(); ?>
        <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
		<?php print $ui->creamyHeader($user); ?>
            <!-- Left side column. contains the logo and sidebar -->
			<?php print $ui->getSidebar($user->getUserId(), $user->getUserName(), $user->getUserRole(), $user->getUserAvatar()); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1><?php print $title; ?>
                        <small><?php print $subtitle; ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="./index.php"><i class="fa fa-dashboard"></i> <?php $lh->translateText("home"); ?></a></li>
                        <li class="active"><?php $lh->translateText("modules"); ?></li>
                    </ol>
                </section>

                <!-- Main content: this section will be filled with the module content. -->
                <section class="content">
					<?php print $content; ?>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
            <?php print $ui->creamyFooter(); ?>
        </div><!-- ./wrapper -->

		<!-- Modal Dialogs -->
		<?php include_once "./php/ModalPasswordDialogs.php" ?>
		
    </body>
</html>
