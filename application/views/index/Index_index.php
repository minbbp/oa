<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>运维OA_login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
  <!-- <div class="container">
  <div class="row"> <h3> 运维OA</h3></div>
  </div> -->
      <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#">精准广告研发中心#运维支撑平台</a>
          <div class="nav-collapse collapse">
            <ul class="nav pull-right">
             <!--  <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li> -->
              <li class="active"><a href="#contact">欢迎您~<?php echo $userinfo['realname'];?></a></li>
              <li><?php echo anchor("/auth/logout/","<i class='icon-off icon-white'></i>安全退出","target='_parent'");?></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
 <!-- <div class="container"> --><div class="row">