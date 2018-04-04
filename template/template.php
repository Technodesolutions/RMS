<?php 
// include "template/header.php";
// include "template/sidebar.php";
// for testing
include "header.php";
include "sidebar.php";
 ?>
 <!-- the breadcrump for pages that needed it -->
 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Dashboard
    <small>Control panel</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>

<!-- the content page -->
 <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
          
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
  </div>
 <?php include "footer.php" ?>
