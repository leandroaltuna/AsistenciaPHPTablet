<!DOCTYPE html>

<html lang="es">
    <head>
        <title><?php echo isset($this->titulo) ? $this->titulo : ''; ?></title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="">
        <meta name="author" content="">
       
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/app/css/demo.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/app/css/jquery.mmenu.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/app/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/app/css/bootstrap-endes.css">
         <link rel="shortcut icon" href="<?php echo BASE_URL; ?>public/app/img/favicon.ico">
        <link rel="icon" href="<?php echo BASE_URL; ?>public/app/img/favicon.ico">
        <?php if (isset($_layoutParams['css']) && count($_layoutParams['css'])): ?>
            <?php foreach ($_layoutParams['css'] as $layout): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $layout; ?>">        
            <?php endforeach; ?>
        <?php endif; ?>
 
    </head>