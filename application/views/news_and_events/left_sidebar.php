<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_type = "";
$get_seg = $this->uri->segment(2);
if (isset($_GET['type']))
{
	$get_type = '?type='.$_GET['type'];
}
?>
<legend class="pkheader_breadcrumb">News & Events</legend>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
	<!-- Main Menu -->
	<div class="side-menu-container">
		<ul class="nav navbar-nav">
			 
			<li class="<?php echo ($get_seg.$get_type == 'news_and_events' || $get_seg.$get_type == 'news_and_events?type=Upcoming') ? 'active':''; ?>"><a href="<?php echo base_url('en/news_and_events');?>">News And Events</a></li> 
			<li class="<?php echo ($get_seg.$get_type == 'news_and_events?type=Today' || $this->uri->segment(1).$get_type == 'news_and_events?type=Today') ? 'active':''; ?>"><a href="<?php echo base_url('en/news_and_events?type=Today');?>">News And Events Today</a></li>  
			<li class="<?php echo ($get_seg.$get_type == 'news_and_events?type=ThisWeek' || $this->uri->segment(1).$get_type == 'news_and_events?type=ThisWeek') ? 'active':''; ?>"><a href="<?php echo base_url('en/news_and_events?type=ThisWeek');?>">News And Events Weekly</a></li>  
			<li class="<?php echo ($get_seg.$get_type == 'news_and_events?type=ThisMonth' || $this->uri->segment(1).$get_type == 'news_and_events?type=ThisMonth') ? 'active':''; ?>"><a href="<?php echo base_url('en/news_and_events?type=ThisMonth');?>">News And Events Monthly</a></li>  
			<li class="<?php echo ($get_seg.$get_type == 'news_and_events?type=Archive') ? 'active':''; ?>"><a href="<?php echo base_url('en/news_and_events?type=Archive');?>">News And Events Archive</a></li>  
			<li class="<?php echo ($get_seg.$get_type == 'anniversary?type=ThisMonth' || $this->uri->segment(1).$get_type == 'anniversary?type=ThisMonth') ? 'active':''; ?>"><a href="<?php echo base_url('en/anniversary?type=ThisMonth');?>">Anniversary Of This Month</a></li>  
			<li class="<?php echo ($get_seg.$get_type == 'anniversary') ? 'active':''; ?>"><a href="<?php echo base_url('en/anniversary');?>">Anniversary</a></li>  
			<li class="<?php echo ($get_seg.$get_type == 'anniversary?type=Anniversary' || $this->uri->segment(1).$get_type == 'anniversary?type=Anniversary') ? 'active':''; ?>"><a href="<?php echo base_url('en/anniversary?type=Anniversary');?>">Upcoming Anniversary</a></li>  
		</ul>
	</div><!-- /.navbar-collapse -->
	</nav> 
</div> 