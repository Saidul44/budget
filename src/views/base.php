<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Finance manager: <?php $viewManager->renderFragment('title'); ?></title>
        <link rel="stylesheet" type="text/css" href="<?php $viewManager->renderResourceLink('css/default.css'); ?>" />
    </head>
    <body>
        <div id="title"><h1>Budget application<h1></div>
        <div id="menu">
            <ul>
                <li><a href="<?php $viewManager->renderLink('default'); ?>">Home</a></li>
                <li><a href="<?php $viewManager->renderLink('category', 'list'); ?>">Categories</a></li>
                <li><a href="<?php $viewManager->renderLink('transaction', 'list'); ?>">Transactions</a></li>
            </ul>
        </div>
        
        <div id="main-area">
        	<?php $viewManager->renderFragment('main-area'); ?>
        </div>
    </body>
</html>