<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $sys_title.'-'.$page_title ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

<!--    <link href="--><?php //echo base_url("public/css/bootstrap.min.css");?><!--" rel="stylesheet">-->
<!--    <link href="--><?php //echo base_url("public/css/bootstrap-responsive.min.css");?><!--" rel="stylesheet">-->
    <link href="<?php echo base_url("public/css/bootstrap/css/bootstrap.min.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/css/bootstrap/css/bootstrap-responsive.min.css");?>" rel="stylesheet">
     <link href="<?php echo base_url("public/css/datepicker.css");?>" rel="stylesheet">
<!--    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">-->
    <link href="<?php echo base_url("public/css/font-awesome.css");?>" rel="stylesheet">
   
    <link href="<?php echo base_url("public/css/adminia.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/css/adminia-responsive.css");?>" rel="stylesheet">

    <link href="<?php echo base_url("public/css/pages/dashboard.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/css/vitastock.css");?>" rel="stylesheet">
    <!-- Le javascript
	================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url("public/js/jquery-1.7.2.min.js");?>"></script>
    <script src="<?php echo base_url("public/js/excanvas.min.js");?>"></script>
    <script src="<?php echo base_url("public/js/jquery.flot.js");?>"></script>
    <script src="<?php echo base_url("public/js/jquery.flot.pie.js");?>"></script>
    <script src="<?php echo base_url("public/js/jquery.flot.orderBars.js");?>"></script>
    <script src="<?php echo base_url("public/js/jquery.flot.resize.js");?>"></script>

    <script src="<?php echo base_url("public/js/json2.js");?>"></script>


<!--    <script src="--><?php //echo base_url("public/js/bootstrap.js");?><!--"></script>-->
    <script src="<?php echo base_url("public/css/bootstrap/js/bootstrap.js");?>"></script>

    <script src="<?php echo base_url("public/js/us.stock.js");?>"></script>
 
    <script src="<?php echo base_url("public/js/bootstrap-validation.min.js");?>"></script>
    <script src="<?php echo base_url("public/js/bootstrap-datepicker.js");?>"></script>
   
    <script src="<?php echo base_url("public/js/bootbox.min.js");?>"></script>
    <script src="<?php echo base_url("public/js/jquery.jqprint-0.3.js");?>"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url("public/js/html5.js");?>"></script>
    <![endif]-->
    
  <!--[if lte IE 6]>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css/bootstrap-ie6.min.css");?>">


  <link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css/ie.css");?>">

  <script type="text/javascript" src="<?php echo base_url("public/js/bootstrap-ie.js");?>"></script>
  <![endif]-->
    
</head>
<body>
