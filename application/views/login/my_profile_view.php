<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Page Content -->
<div class="container">

    <!-- Page Heading/Breadcrumbs -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">My Account</h1>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a></li>
                <li class="active"><?php echo $this->uri->segment(3);?></li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <!-- Content Row -->
    <div class="row">
        <!-- Sidebar Column -->
        <div class="col-md-3">
            <div class="list-group">
	            <a href="<?= base_url('my_account/my_booking') ?>" class="list-group-item active">My Bookings</a>
	            <a href="<?= base_url('my_account/my_details') ?>" class="list-group-item">My Details</a>
	            <a href="<?= base_url('my_account/my_rewards') ?>" class="list-group-item">My Rewards</a>
	            <a href="<?= base_url('my_account/my_faves') ?>" class="list-group-item">My Favs</a>
	            <a href="<?= base_url('my_account/my_public_profile') ?>" class="list-group-item">My Public Profile</a> 
	            <a href="<?= base_url('my_account/faqs') ?>" class="list-group-item">Faq's</a> 
	            <a href="<?= base_url('logout') ?>" class="list-group-item">Logout</a> 
            </div>
        </div>
        <!-- Content Column -->
        <div class="col-md-9">
            <h2>Section Heading</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta, et temporibus, facere perferendis veniam beatae non debitis, numquam blanditiis necessitatibus vel mollitia dolorum laudantium, voluptate dolores iure maxime ducimus fugit.</p>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container -->