<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- set the page title, for seo purposes too -->
    <title><?php echo isset($page_title) ? strip_tags($page_title) : "Store Front"; ?></title>
    <!-- Bootstrap CSS -->
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<!-- Awesome font -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- <link rel="stylesheet" href="libs/font-awesome-4.7.0/css/font-awesome.min.css">-->
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	
	<!-- Custom Javascript -->
	<script src="add_paging.js"></script>
	
    <!-- admin custom CSS Graph API-->
    <link href="<?php echo $home_url . "libs/css/customer.css" ?>" rel="stylesheet" />
    <link href="<?php echo $home_url . "libs/css/map.css" ?>" rel="stylesheet" />
    <link href="<?php echo $home_url . "libs/css/navigation.css" ?>" rel="stylesheet" />
    <link href="<?php echo $home_url . "libs/css/salesOverview.css" ?>" rel="stylesheet" />
    <link href="<?php echo $home_url . "libs/css/debtorAnalysis.css" ?>" rel="stylesheet" />
    <link href="<?php echo $home_url . "libs/css/inventoryVal.css" ?>" rel="stylesheet" />
    
	<!-------------------------Tabulator library--------------------------------->
	<link href="tabulator-master/dist/css/tabulator.min.css" rel="stylesheet">
	<script type="text/javascript" src="tabulator-master/dist/js/tabulator.min.js"></script>    
	
  	<!-------------------------Sweert Alert library--------------------------------->	
  	<script src="sweetalert-master/dist/sweetalert.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="sweetalert-master/dist/sweetalert.css">	
	
	<!-------------------------Allow Data to be downloaded to a xlsx file----------->
	<script type="text/javascript" src="http://oss.sheetjs.com/js-xlsx/xlsx.full.min.js"></script>
 	
 	<!-------------------------Allow Data to be downloaded to a pdf file----------->
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.2/jspdf.plugin.autotable.js"></script>   
</head>
<div class="overlay"></div>
<body>
 
    <!-- include the navigation bar -->
    <?php include_once 'navigation.php'; ?>
 
    <!-- container -->
    <div class="container">
 
        <?php
        // if given page title is 'Login', do not display the title
        if($page_title!="Login"){
        ?>
        <!-- <div class='col-md-12'>
            <div class="page-header">
                <h3><?php /*echo isset($page_title);*/ ?></h3>
            </div>
        </div>-->
        <?php
        }
        ?>