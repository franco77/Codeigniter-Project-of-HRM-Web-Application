<?php defined('BASEPATH') OR exit('No direct script access allowed');?> 
<div class="section main-section">
	<style>
	body{
		margin:0;
		padding:0;
		background-image: url(images/headerbg.gif);
		background-repeat: repeat-x;
		background-position: left -700px;
		font-family:Arial, Helvetica, sans-serif;
			font-size:12px;
	}
	#page{
		width:690px;
		display:block;
		margin: 0 auto;	
	}
	.logo{
		display:block;
		float:left;
		margin:10px 0;
	}
	.address{
		display:block;
		float:right;	
		font-size:12px;
		margin:15px 20px 0 0;
	}
	.company{
		font-weight:bold;
		font-size:16px;
	}
	#header{
		display:block;
		clear:both;
	}
	#content{
		display:block;
		clear:both;
		
	}
	h2{
		text-align:center;
		font-size: 15px;
	}
	.empheaderhalf{
		display:block;
		float:left;
		width:50%;
		font-size:13px;
		margin-bottom: 20px;
	}
	.empheaderhalf ul{
		list-style:none;
		margin:0;
		padding:0;
	}
	.empheaderhalf ul li{
		margin:7px 0 0 0;
		width:100%;
		display:block;
		float:left;
	}
	.empheaderhalf ul li .head{
		width: 110px;
		font-weight: bold;
		display:block;
		float:left;
	}
	.empheaderhalf ul li .head:after{
		width:20px;
		content:":";
		padding:0 5px 0;
	}
	#footer{
		background: #000;
		background-repeat: repeat-x;
		height: 15px;
		color: #fff;
		padding:5px;
		clear:both;
		font-size:10px;	
			margin-top: 20px;
	}
	#statement{
		clear:both;
		margin:10px 0 10px 0;
		font-size:14px;
	}
	.rowhead{
		background-color:#ccc;
		height: 20px;
		font-size:15px;
		font-weight: bold;
		border-collapse:collapse;
		text-align:center;
	}
	.row{
		line-height:25px;
	}
	.earning .col1{
		width:148px;
		display:block;
		float:left;
		border-left:1px solid #666;
		border-right:1px solid #666;
		text-indent:10px;
		min-height:340px;	
	}
	.earning .col2{
		width:84px;
		display:block;
		float:left;
		border-right:1px solid #666;
		text-align:right;
		padding-right:5px;	
		min-height:340px;	
	}
	.earning .col3{
		width:84px;
		border-right:1px solid #666;
		text-align:right;
		padding-right:5px;	
		display:block;
		float:left;
		min-height:340px;	
	}
	.earning .col4{
		width:84px;
		border-right:1px solid #666;
		text-align:right;
		padding-right:5px;	
		display:block;
		float:left;
		min-height:340px;	
	}
	.earning .col5{
		width:84px;
		border-right:1px solid #666;
		text-align:right;
		padding-right:5px;	
		display:block;
		float:left;
		min-height:340px;	
	}
	.deduction .col1{
		width:98px;
		border-right:1px solid #666;
		text-align:left;
		text-indent:5px;
		display:block;
		float:left;
		min-height:340px;	
	}
	.deduction .col2{
		width:55px;
		border-right:1px solid #666;
		text-align:right;
		padding-right:5px;	
		display:block;
		float:left;
		min-height:340px;	
	}
	.net{
		margin:10px 0 0 0;
		display:block;
		clear:both;
		padding:10px 0 10px 0;
	}
	.net h4{
		font-style:italic;
	}
	#gross{
		clear:both;
	background-color:#ccc;
	font-size:14px;
	height:40px;
	border: 1px solid #999;
	line-height:40px;	
	}
	#gross .grosspay{
		width:149px;
		display:block;
		float:left;
		text-indent:5px;
		font-weight:bold;
		text-transform:uppercase;
	}
	#gross .rate{
		width:90px;
		display:block;
		float:left;
	}
	#gross .monthly{
		width:85px;
		display:block;
		float:left;
		font-weight:bold;
		text-align:right;
		padding-right:5px;	
	}
	#gross .arrear{
		width:90px;
		display:block;
		float:left;
		text-align:right;	
		font-weight:bold;		
	}
	#gross .total{
		width:90px;
		display:block;
		float:left;
		text-align:right;
		font-weight:bold;	
	}
	#gross .grossdeduction{
		width:100px;
		display:block;
		float:left;
		text-indent:15px;
		font-weight:bold;
		text-transform:uppercase;
	}
	#gross .amount{
		width:54px;
		display:block;
		float:left;
		text-align:right;
		font-weight:bold;
		padding-right:5px;	
	}
	#gross2{
		clear:both;
	background-color:#ccc;
	font-size:14px;
	height:40px;
	border: 1px solid #999;
	line-height:40px;
	text-indent: 10px;	
	}
	.earnhead{
		width:508px;
		background-color:#CCCCCC;
		display:block;
		float:left;
		line-height:30px;
		border-right:1px solid #999;
		border-left:1px solid #999;	
		border-top:1px solid #999;			
	}
	.deducthead{
		width:159px;
		background-color:#ccc;
		display:block;
		float:left;
		line-height:30px;	
		border-right:1px solid #999;
		border-top:1px solid #999;				
	}
	#taxworksheet{
		width:920px;
		background-color:#CCCCCC;
		clear:both;
		margin:10px;
	}
	#taxworksheetcol1{
		width:296px;
		display:block;
		float:left;
		min-height:300px;
	}
	.taxworksheetcol1a{
		width:79px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-top:1px solid #000;
		border-left:1px solid #000;	
		padding:0 4px;
	}
	.taxworksheetcol1ad{
		width:286px;
		display:block;
		float:left;
		border:1px solid #000;
		padding:0 4px;
		text-align:center;
		border-top:none;
			height: 44px;
	}
	.taxworksheetcol1b{
		width:60px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-top:1px solid #000;
		border-left:1px solid #000;		
		padding:0 4px;		
	}
	.taxworksheetcol1c{
		width:60px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-top:1px solid #000;
		border-left:1px solid #000;		
		padding:0 4px;	
	}
	.taxworksheetcol1d{
		width:60px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-top:1px solid #000;
		border-left:1px solid #000;
		border-right:1px solid #000;				
		padding:0 4px;	
	}
	.taxworksheetcol1ar{
		width:79px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;		
		padding:0 4px;	
	}
	.taxworksheetcol1br{
		width:60px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;		
		text-align:right;
		padding:0 4px;	
	}
	.taxworksheetcol1cr{
		width:60px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;		
		text-align:right;	
		padding:0 4px;	
	}
	.taxworksheetcol1dr{
		width:60px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;
		border-right:1px solid #000;	
		text-align:right;	
		padding:0 4px;	
	}
	.taxworksheetcol1a3r{
		width:173px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;		
		padding:0 4px;	
	}
	.taxworksheetcol1d3r{
		width:104px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;
		border-right:1px solid #000;	
		text-align:right;	
		padding:0 4px;	
	}
	#taxworksheetcol2{
		width:331px;
		display:block;
		float:left;
		min-height:300px;
	}
	.taxworksheetcol2a{
		width:162px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-top:1px solid #000;
		padding:0 4px;
	}
	.taxworksheetcolfull{
		width:323px;
		display:block;
		float:left;
		border:1px solid #000;
		padding:0 4px;
		text-align:center;
		border-left:none;
		border-bottom:none;	
		border-right:none;	
	}
	.taxworksheetcol2b{
		width:71px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-top:1px solid #000;
		border-left:1px solid #000;		
		padding:0 4px;		
	}
	.taxworksheetcol2c{
		width:71px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-top:1px solid #000;
		border-left:1px solid #000;
		border-right:1px solid #000;				
		padding:0 4px;	
	}
	.taxworksheetcol2ar{
		width:162px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		padding:0 4px;
	}
	.taxworksheetcol2br{
		width:71px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;		
		padding:0 4px;
		text-align:right;		
	}
	.taxworksheetcol2cr{
		width:71px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;
		border-right:1px solid #000;				
		padding:0 4px;	
		text-align:right;	
	}
	.taxworksheetcolabc{
			width:322px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-right:1px solid #000;	
		padding:0 4px;
		font-weight:bold;
	}
	.taxworksheetcolab{
		width:242px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-right:1px solid #000;	
		padding:0 4px;
	}
	.taxworksheetcolab2{
		width:242px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-right:1px solid #000;	      
		padding:0 4px;
	}
	.taxworksheetcolac{
		width:71px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-right:1px solid #000;	
		padding:0 4px;
	}
	#taxworksheetcol3{
		width:290px;
		display:block;
		float:left;
		min-height:300px;
	}
	.taxworksheetcol3a{
		width:190px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		padding:0 4px;
	}
	.taxworksheetcol3ab{
		width:277px;
		display:block;
		float:left;
		padding:0 4px;
		text-align:center;
		border:1px solid #000;
	}
	.taxworksheetcol3b{
		width:79px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-left:1px solid #000;	
		border-right:1px solid #000;		
		padding:0 4px;
		text-align:right;
	}

	.taxworksheetcol3abc{
		width:278px;
		display:block;
		float:left;
		border-bottom:1px solid #000;
		border-right:1px solid #000;		
		padding:0 4px;;
	}
	.taxworksheetcol3abc2{
		width:278px;
		display:block;
		float:left;		
		border-right:1px solid #000;		
		padding:0 4px;;
	}
	#personalnote{
		border:1px solid #000;
		min-height: 20px;
		clear:both;
		padding:0 10px;
		border-left:none;
		border-right:none;
		border-bottom:none;	
	}
	#generalnote{
		border:1px solid #000;
		min-height: 40px;
		clear:both;
		padding:0 10px;
		border-left:none;
		border-right:none;
	}
	#intax{
		display:block;
		text-align:center;
		font-weight:bold;
	}
	#itax{
		clear:both;
		margin:10px;
		border:1px solid #000;
			width: 935px;
	}

	</style>
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-9 center-xs">
					<div class="row">
					<section id="page">
<?php if($count>0){ ?>
<section id="header">
  <div class="logo"><img src="<?php echo base_url('assets/images/logo.gif')?>" alt="" border="0"> </div>
  <div class="address">
  <span class="company">POLOSOFT TECHNOLOGIES Pvt. Ltd.</span><br />
<!--Plot No.E/54 & E/54/1, Infocity<br />
Chandaka Industrial Estate, Patia<br />
Bhubaneswar - 751024, Odisha, India<br />
Telephone (India): +91 (674) 662 1016-->
  </div>
  </section>
<section id="content">
<h2>Estimated Tax Computation Sheet For FY 2017-18</h2>
<div id="empheader">
<div class="empheaderhalf">
<ul>
<li><div class="head">Name</div>  <?php echo $empInfo[0]['full_name']?></li>
<li><div class="head">Designation</div> <?php echo $empInfo[0]['desg_name']?></li>
<li><div class="head">Department </div><?php echo $empInfo[0]['dept_name']?></li>
</ul>
</div>
<div class="empheaderhalf">
<ul>
<li><div class="head">PAN Card No</div> <?php echo $empInfo[0]['pan_card_no']?></li>
<li><div class="head">Employee ID</div> <?php echo $empInfo[0]['loginhandle']?></li>
<li><div class="head">Date of Joining</div><?php echo date("d-m-Y", strtotime($empInfo[0]['join_date']));?></li> 
</ul>

</div>
</div>
   <section id="itax"> 
 <!--<div id="intax">Income Tax Worksheet for the period <?php //echo $sfyear; ?> - <?php //echo $efyear; ?></div>-->
<section id="taxworksheet">
<section id="taxworksheetcol1">
<div class="row">
<div class="taxworksheetcol1a">Description</div>
<div class="taxworksheetcol1b">Gross</div>
<div class="taxworksheetcol1c">Exempt</div>
<div class="taxworksheetcol1d">Taxable</div>
</div>


<div class="row">
<div class="taxworksheetcol1ar">Basic</div>
<div class="taxworksheetcol1br"><?php echo number_format((float)($empInfo[0]['basic'])*12, 2, '.', '')?></div>
<div class="taxworksheetcol1cr">0.00</div>
<div class="taxworksheetcol1dr"><?php echo number_format((float)($empInfo[0]['basic'])*12, 2, '.', '')?></div>
</div>

<div class="row">
<div class="taxworksheetcol1ar">HRA</div>
<div class="taxworksheetcol1br"><?php echo number_format((float)($empInfo[0]['hra'])*12, 2, '.', '')?></div>
<div class="taxworksheetcol1cr"><?php 
$actual_hra_rec = number_format((float)($empInfo[0]['hra'])*12, 2, '.', '');
$per_basic1 = number_format((float)($empInfo[0]['basic']*12/100)*40, 2, '.', '');
$per_basic2 = number_format((float)($empInfo[0]['basic']*12/100)*50, 2, '.', '');
$rent_paid_over_basic = number_format((float)($empInfo[0]['eligible_rent_paid_by_employee'])-($empInfo[0]['basic']*12/100)*10, 2, '.', '');
$minValue1 = min($actual_hra_rec,min($per_basic1,$rent_paid_over_basic));
$minValue2 = min($actual_hra_rec,min($per_basic2,$rent_paid_over_basic));
if($empInfo[0]['eligible_rent_paid_by_employee'] == 0)
{
	echo number_format((float)(0.00), 2, '.', '');
}
elseif($userInfo[0]['state_region2'] == 26)
{
	echo $minValue2;
}
else
{
	echo $minValue1;
} 
?>
</div>
<div class="taxworksheetcol1dr">
<?php
if($empInfo[0]['eligible_rent_paid_by_employee'] == 0)
{
	echo number_format((float)($empInfo[0]['hra']*12)-0.00, 2, '.', '');
}
elseif($userInfo[0]['state_region2'] ==26)
{
	echo number_format((float)($empInfo[0]['hra']*12)-$minValue2, 2, '.', '');
}
else
{
	echo number_format((float)($empInfo[0]['hra']*12)-$minValue1, 2, '.', '');
}
?>
</div>
</div>

<div class="row">
<div class="taxworksheetcol1ar">Medical Exp.</div>
<div class="taxworksheetcol1br"><?php echo number_format((float)($empInfo[0]['medical_allowance'])*12, 2, '.', '')?></div>
<div class="taxworksheetcol1cr">
<?php
if($empInfo[0]['medical_allowance'] == 0)
{
	echo number_format((float)(0.00), 2, '.', '');
}
else
{
	echo number_format((float)($empInfo[0]['eligible_medicalexpensesperannum']), 2, '.', '');
}
?>
</div>
<div class="taxworksheetcol1dr">
<?php
if($empInfo[0]['medical_allowance'] == 0)
{
	echo number_format((float)($empInfo[0]['medical_allowance']*12-0.00), 2, '.', '');
}
else
{
	echo number_format((float)($empInfo[0]['medical_allowance']*12-$empInfo[0]['eligible_medicalexpensesperannum']), 2, '.', '');
}

?></div>
</div>

<div class="row">
<div class="taxworksheetcol1ar">Conv Allow.</div>
<div class="taxworksheetcol1br"><?php echo number_format((float)($empInfo[0]['conveyance_allowance'])*12, 2, '.', '')?></div>
<div class="taxworksheetcol1cr"><?php echo number_format((float)($empInfo[0]['eligible_conv_allowance']), 2, '.', '')?></div>
<div class="taxworksheetcol1dr"><?php if(($empInfo[0]['conveyance_allowance']*12-$empInfo[0]['eligible_conv_allowance'])>0) echo number_format((float)($empInfo[0]['conveyance_allowance']*12-$empInfo[0]['eligible_conv_allowance']), 2, '.', ''); else echo '0.00';?></div>
</div>


<div class="row">
<div class="taxworksheetcol1ar">Special Allow</div>
<div class="taxworksheetcol1br"><?php echo number_format((float)($empInfo[0]['special_allowance'])*12, 2, '.', '')?></div>
<div class="taxworksheetcol1cr"><?php echo number_format((float)($empInfo[0]['eligible_childreneducationalallowance']), 2, '.', '')?></div>
<div class="taxworksheetcol1dr"><?php echo number_format((float)($empInfo[0]['special_allowance']*12-$empInfo[0]['eligible_childreneducationalallowance']), 2, '.', '')?></div>
</div>
<div class="row">
<div class="taxworksheetcol1ar">Child Ed Allow</div>
<div class="taxworksheetcol1br"><?php echo number_format((float)($empInfo[0]['child_edu_allow']), 2, '.', '')?></div>
<div class="taxworksheetcol1cr">0.00</div>
<div class="taxworksheetcol1dr"><?php echo number_format((float)($empInfo[0]['child_edu_allow']), 2, '.', '')?></div>
</div>
<div class="row">
<div class="taxworksheetcol1ar">Other Income</div>
<div class="taxworksheetcol1br">
	<?php 
	$count = count($res_arr);
	for($i = 0; $i< $count; $i++)
	{
		echo number_format((float)($res_arr[0]['project_allowance_amount']+$res_arr[0]['statutory_bonus_amount']+$res_arr[0]['performance_bonus_amount']+$res_arr[0]['incentive_payment_amount']+$res_arr[0]['leave_incashment_amount']+$res_arr[0]['other_payment_amount']), 2, '.', '');
	}
	?>
</div>
<div class="taxworksheetcol1cr">0.00</div>
<div class="taxworksheetcol1dr">
	<?php 
	$count = count($res_arr);
	for($i = 0; $i< $count; $i++)
	{
		echo number_format((float)($res_arr[0]['project_allowance_amount']+$res_arr[0]['statutory_bonus_amount']+$res_arr[0]['performance_bonus_amount']+$res_arr[0]['incentive_payment_amount']+$res_arr[0]['leave_incashment_amount']+$res_arr[0]['other_payment_amount']), 2, '.', '');
	}
	?>
</div>
</div>
<div class="row">
<div class="taxworksheetcol1ar">Total</div>
<div class="taxworksheetcol1br">
<?php  
echo number_format((float)($empInfo[0]['basic']*12 + $empInfo[0]['hra']*12 + $empInfo[0]['medical_allowance']*12 + $empInfo[0]['conveyance_allowance']*12 + $empInfo[0]['special_allowance']*12 + $empInfo[0]['child_edu_allow'] + $res_arr[0]['project_allowance_amount']+$res_arr[0]['statutory_bonus_amount']+$res_arr[0]['performance_bonus_amount']+$res_arr[0]['incentive_payment_amount']+$res_arr[0]['leave_incashment_amount']+$res_arr[0]['other_payment_amount']), 2, '.', '');
?>
</div>
<div class="taxworksheetcol1cr">
<?php
if($empInfo[0]['eligible_rent_paid_by_employee'] == 0)
{
	echo number_format((float)(0.00 + 0.00 + $empInfo[0]['eligible_medicalexpensesperannum'] + $empInfo[0]['eligible_conv_allowance'] + $empInfo[0]['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
}
elseif($userInfo[0]['state_region2'] ==26)
{  
	echo number_format((float)(0.00 + $minValue2 + $empInfo[0]['eligible_medicalexpensesperannum'] + $empInfo[0]['eligible_conv_allowance'] + $empInfo[0]['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
}
else if($empInfo[0]['medical_allowance'] == 0)
{
	echo number_format((float)(0.00 + $minValue1 + 0.00 + $empInfo[0]['eligible_conv_allowance'] + $empInfo[0]['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
}
else
{
	echo number_format((float)(0.00 + $minValue1 + $empInfo[0]['eligible_medicalexpensesperannum'] + $empInfo[0]['eligible_conv_allowance'] + $empInfo[0]['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
} 
?>
</div>
<div class="taxworksheetcol1dr">
<?php 
$basic = $empInfo[0]['basic']*12;
$hra = $empInfo[0]['hra']*12-$minValue1;
$hra2 = $empInfo[0]['hra']*12-$minValue2;
$hra1 = $empInfo[0]['hra']*12-0.00;
$medical_allowance = $empInfo[0]['medical_allowance']*12-$empInfo[0]['eligible_medicalexpensesperannum'];
$conveyance_allowance = $empInfo[0]['conveyance_allowance']*12-$empInfo[0]['eligible_conv_allowance'];
$special_allowance = $empInfo[0]['special_allowance']*12-$empInfo[0]['childreneducationalallowance'];
$child_edu_allow = $empInfo[0]['child_edu_allow'];
$otherincome = $res_arr[0]['project_allowance_amount']+$res_arr[0]['statutory_bonus_amount']+$res_arr[0]['performance_bonus_amount']+$res_arr[0]['incentive_payment_amount']+$res_arr[0]['leave_incashment_amount']+$res_arr[0]['other_payment_amount'];
if($empInfo[0]['eligible_rent_paid_by_employee'] == 0)
{
	$total = number_format((float)($basic+$hra1+0.00+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
	echo $total;	
}
else if($empInfo[0]['medical_allowance'] == 0)
{
	$total = number_format((float)($basic+$hra+0.00+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
	echo $total;
}
else if($userInfo[0]['state_region2'] ==26)
{
	//echo $basic;
	//echo $hra2;
	//echo $empInfo[0]['eligible_medicalexpensesperannum'];
	//echo $medical_allowance;
	//echo $conveyance_allowance;
	//echo $special_allowance;
	//echo $child_edu_allow;
	//echo $otherincome;
	$total = number_format((float)($basic+$hra2+$medical_allowance+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
	echo $total;
}
else
{
	$total = number_format((float)($basic+$hra+$medical_allowance+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
	echo $total;
}
//echo $total;
?>
</div>
</div>


<div class="row">
<div class="taxworksheetcol1ad">
<h3>HRA Calculation</h3>
</div>
</div>
<div class="row">
<div class="taxworksheetcol1a3r">Rent Paid by Employee</div>
<div class="taxworksheetcol1d3r"><?php echo number_format((float)($empInfo[0]['eligible_rent_paid_by_employee']), 2, '.', '')?></div>
</div>


<div class="row">
<div class="taxworksheetcol1a3r">From</div>
<div class="taxworksheetcol1d3r">April<?php //echo $sfyear; ?></div>
</div>

<div class="row">
<div class="taxworksheetcol1a3r">To</div>
<div class="taxworksheetcol1d3r">March<?php //echo $efyear; ?></div>
</div>

<div class="row">
<div class="taxworksheetcol1a3r">1. Actual HRA Received</div>
<div class="taxworksheetcol1d3r"><?php echo number_format((float)($empInfo[0]['hra'])*12, 2, '.', '')?></div>
</div>


<div class="row">
<div class="taxworksheetcol1a3r">2. 40% or 50% of Basic</div>
<div class="taxworksheetcol1d3r">
	<?php 
		if($userInfo[0]['state_region2'] ==20)
		{
			echo number_format((float)($empInfo[0]['basic']*12/100)*40, 2, '.', '');
		}
		elseif($userInfo[0]['state_region2'] ==26)
		{
			echo number_format((float)($empInfo[0]['basic']*12/100)*50, 2, '.', '');
		} 
	?>
</div>
</div>


<div class="row">
<div class="taxworksheetcol1a3r">3. Rent paid > 10% Basic</div>

<div class="taxworksheetcol1d3r">
<?php
if($empInfo[0]['eligible_rent_paid_by_employee'] == 0)
{
	echo number_format((float)(0.00), 2, '.', '');
}
else
{
	echo number_format((float)($empInfo[0]['eligible_rent_paid_by_employee'])-($empInfo[0]['basic']*12/100)*10, 2, '.', '');
}

?>
</div>
</div>

<div class="row">
<div class="taxworksheetcol1a3r">Least of above is exempt</div>
<div class="taxworksheetcol1d3r">
<?php
$actual_hra_rec = number_format((float)($empInfo[0]['hra'])*12, 2, '.', '');
$per_basic1 = number_format((float)($empInfo[0]['basic']*12/100)*40, 2, '.', '');
$per_basic2 = number_format((float)($empInfo[0]['basic']*12/100)*50, 2, '.', '');
$rent_paid_over_basic = number_format((float)($empInfo[0]['eligible_rent_paid_by_employee'])-($empInfo[0]['basic']*12/100)*10, 2, '.', '');
$minValue1 = min($actual_hra_rec,min($per_basic1,$rent_paid_over_basic));
$minValue2 = min($actual_hra_rec,min($per_basic2,$rent_paid_over_basic));
if($empInfo[0]['eligible_rent_paid_by_employee'] == 0)
{
	echo number_format((float)(0.00), 2, '.', '');
}
elseif($userInfo[0]['state_region2'] ==26)
{
	echo $minValue2;
}
else{
	echo $minValue1;
}
?>
</div>
</div> 
<div class="row">
    <div class="taxworksheetcol1a3r">&nbsp;</div>
<div class="taxworksheetcol1d3r">&nbsp;</div>
</div>
<div class="row">
    <div class="taxworksheetcol1a3r">&nbsp;</div>
    <div class="taxworksheetcol1d3r">&nbsp;</div>
</div>
</section>

<section id="taxworksheetcol2">
<div class="taxworksheetcolfull">
<h3>Deduction Under Chapter VI-A</h3>
</div>

<div class="row">
<div class="taxworksheetcol2a">Description</div>
<div class="taxworksheetcol2b">Declared</div>
<div class="taxworksheetcol2c">Eligible</div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">A. Sec 80C, 80CCC, 80CCD</div>
<div class="taxworksheetcol2br">&nbsp;</div>
<div class="taxworksheetcol2cr">&nbsp;</div>
</div>


<div class="row">
<div class="taxworksheetcol2ar">a. U/S 80 C </div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['total_deduction80c']), 2, '.', '');?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['eligible_total_deduction80c']), 2, '.', '');?></div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">b. U/S 80CCC</div>
<div class="taxworksheetcol2br">&nbsp;</div>
<div class="taxworksheetcol2cr">&nbsp;</div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">c. U/S 80CCD</div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['deduction_under_80CCD']), 2, '.', '')?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['eligible_deduction_under_80CCD']), 2, '.', '')?></div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">B. Oth Sec.(e.g. 80E/G etc.)</div>
<div class="taxworksheetcol2br">&nbsp;</div>
<div class="taxworksheetcol2cr">&nbsp;</div>
</div>


<div class="row">
<div class="taxworksheetcol2ar">(1) Section 80CCF</div>
<div class="taxworksheetcol2br">&nbsp;</div>
<div class="taxworksheetcol2cr">&nbsp;</div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">(2) Section 80D</div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['total_deduction_incase_senior_citizen']), 2, '.', '')?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['eligible_total_deduction_incase_senior_citizen']), 2, '.', '')?></div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">(3) Section 80DD</div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['total_deduction_80dd']), 2, '.', '')?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['total_eligible_deduction_80dd']), 2, '.', '')?></div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">(4) Section 80DDB</div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['total_deduction_80ddb']), 2, '.', '')?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['total_eligible_deduction_80ddb']), 2, '.', '')?></div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">(5) Section 80E</div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['interest_loan_higher_education_80e']), 2, '.', '')?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['eligible_interest_loan_higher_education_80e']), 2, '.', '')?></div>
</div>

<div class="row">
	<div class="taxworksheetcol2ar">(6) Section 80EE</div>
	<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['interest_home_loan_80ee']), 2, '.', '')?></div>
	<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['eligible_interest_home_loan_80ee']), 2, '.', '')?></div> 
</div>

<div class="row">
<div class="taxworksheetcol2ar">(7) Section 80G</div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['actual_donation_80g']), 2, '.', '')?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['eligible_actual_donation_80g']), 2, '.', '')?></div> 
</div>

<div class="row">
<div class="taxworksheetcol2ar">(8) Section 80U</div>
<div class="taxworksheetcol2br"><?php echo number_format((float)($empInfo[0]['total_deduction_under_80U']), 2, '.', '')?></div>
<div class="taxworksheetcol2cr"><?php echo number_format((float)($empInfo[0]['eligible_deduction_under_80U']), 2, '.', '')?></div> 
</div>  
<div class="row">
<div class="taxworksheetcol2ar">Total</div>
<div class="taxworksheetcol2br">
<?php 
$total_declared = number_format((float)($empInfo[0]['total_deduction80c']+$empInfo[0]['deduction_under_80CCD']+$empInfo[0]['total_deduction_incase_senior_citizen']+$empInfo[0]['total_deduction_80dd']+$empInfo[0]['total_deduction_80ddb']+$empInfo[0]['interest_loan_higher_education_80e']+$empInfo[0]['interest_home_loan_80ee']+$empInfo[0]['actual_donation_80g']+$empInfo[0]['total_deduction_under_80U']), 2, '.', '');
echo $total_declared;
?></div>
<div class="taxworksheetcol2cr">
<?php 
$total_eligible = number_format((float)($empInfo[0]['eligible_total_deduction80c']+$empInfo[0]['eligible_deduction_under_80CCD']+$empInfo[0]['eligible_total_deduction_incase_senior_citizen']+$empInfo[0]['total_eligible_deduction_80dd']+$empInfo[0]['total_eligible_deduction_80ddb']+ $empInfo[0]['eligible_interest_loan_higher_education_80e']+$empInfo[0]['eligible_interest_home_loan_80ee']+$empInfo[0]['eligible_actual_donation_80g']+ $empInfo[0]['eligible_deduction_under_80U']), 2, '.', '');
echo $total_eligible;
?>
</div> 
</div>

<div class="row">
<div class="taxworksheetcol2ar">&nbsp;</div>
<div class="taxworksheetcol2br">&nbsp;</div>
<div class="taxworksheetcol2cr">&nbsp;</div>
</div>
 
<div class="row">
<div class="taxworksheetcol2ar">&nbsp;</div>
<div class="taxworksheetcol2br">&nbsp;</div>
<div class="taxworksheetcol2cr">&nbsp;</div>
</div>

<div class="row">
<div class="taxworksheetcol2ar">&nbsp;</div>
<div class="taxworksheetcol2br">&nbsp;</div>
<div class="taxworksheetcol2cr">&nbsp;</div>
</div>





</section>

<section id="taxworksheetcol3">
<div class="taxworksheetcol3ab">
<h3>Tax Calculation</h3>
</div>

<div class="row">
<div class="taxworksheetcol3a">Professional Tax (PT)</div>
<div class="taxworksheetcol3b">
<?php
$professional_tax = 2500;
$professional_tax2 = 1500;
//$professional_tax = number_format((float)($empInfo[0]['pt']*12), 2, '.', '');
if(number_format((float)($empInfo[0]['basic']*12 + $empInfo[0]['hra']*12 + $empInfo[0]['medical_allowance']*12 + $empInfo[0]['conveyance_allowance']*12 + $empInfo[0]['special_allowance']*12 + $empInfo[0]['child_edu_allow'] + $empInfo[0]['otherincome']), 2, '.', '') > 300001)
{
	echo $professional_tax;
}
else
{
	echo $professional_tax2;
}
//echo $professional_tax;
?></div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Under Chapter VI-A</div>
<div class="taxworksheetcol3b"><?php echo $total_eligible;?></div>
</div>


<div class="row">
<div class="taxworksheetcol3a">Any other Deduction</div>
<div class="taxworksheetcol3b">
<?php 
$eligible_self_occupied_property = number_format((float)($empInfo[0]['eligible_self_occupied_property']), 2, '.', '');
echo $eligible_self_occupied_property;
?></div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Any other income</div>
<div class="taxworksheetcol3b">0.00</div>
</div>


<div class="row">
<div class="taxworksheetcol3a">Perquisite Value</div>
<div class="taxworksheetcol3b">0.00</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Taxable Income</div>
<div class="taxworksheetcol3b">
<?php
$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
echo $taxableincome;
?>
</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Tax on Total Income</div>
<div class="taxworksheetcol3b">
<?php
$slab1 = 250000; 
$slab2 = 250001;
$slab3 = 500001;
$slab4 = 100001;
$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
if($taxableincome <= $slab1)
{ 
	echo 0.00;
}
elseif($taxableincome > $slab2 && $taxableincome < $slab3)
{
	$tax_slab2 = $taxableincome - $slab2; 
	$tax_pay2 = $tax_slab2/100*5;
	$result = number_format((float)($tax_pay2), 2, '.', '');
	echo $result;
}
elseif($taxableincome > $slab3 && $taxableincome > $slab4)
{
	$tax_slab3 = $taxableincome - $slab3; 
	$tax_pay3 = $tax_slab3/100*20+12500;
	$result = number_format((float)($tax_pay3), 2, '.', '');
	echo $result;
}
elseif($taxableincome > $slab4)
{
	$tax_slab4 = $taxableincome - $slab4; 
	$tax_pay4 = $tax_slab4/100*30+12500+100000;
	$result = number_format((float)($tax_pay4), 2, '.', '');
	echo $result;
} 
?>
</div>
</div>


<div class="row">
<div class="taxworksheetcol3a">Tax Rebate</div>
<div class="taxworksheetcol3b">0.00</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Tax Due</div>
<div class="taxworksheetcol3b">
<?php
$slab1 = 250000; 
$slab2 = 250001;
$slab3 = 500001;
$slab4 = 100001;
$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
if($taxableincome <= $slab1)
{ 
	echo 0.00;
}
elseif($taxableincome > $slab2 && $taxableincome < $slab3)
{
	$tax_slab2 = $taxableincome - $slab2; 
	$tax_pay2 = $tax_slab2/100*5;
	$result = number_format((float)($tax_pay2), 2, '.', '');
	echo $result;
}
elseif($taxableincome > $slab3 && $taxableincome > $slab4)
{
	$tax_slab3 = $taxableincome - $slab3; 
	$tax_pay3 = $tax_slab3/100*20+12500;
	$result = number_format((float)($tax_pay3), 2, '.', '');
	echo $result;
}
elseif($taxableincome > $slab4)
{
	$tax_slab4 = $taxableincome - $slab4; 
	$tax_pay4 = $tax_slab4/100*30+12500+100000;
	$result = number_format((float)($tax_pay4), 2, '.', '');
	echo $result;
} 
?>
</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Educational Cess</div>
<div class="taxworksheetcol3b">
<?php
$slab1 = 250000; 
$slab2 = 250001;
$slab3 = 500001;
$slab4 = 100001;
$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
$tax_slab4 = $taxableincome - $slab4; 
$tax_pay4 = $tax_slab4/100*30+12500+100000;
$result = number_format((float)($tax_pay4), 2, '.', '');
$educational = $result/100*3;
//echo $educational;
echo number_format((float)($educational), 2, '.', '')?>
</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Total Tax</div>
<div class="taxworksheetcol3b">
<?php
$result = number_format((float)($tax_pay4), 2, '.', '');
$total_tax = $result+$educational;
echo number_format((float)($total_tax ), 2, '.', '')
?>
</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Total Tax Rounded off</div>
<div class="taxworksheetcol3b"><?php echo round($total_tax )?></div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Tax Deducted till date</div>
<div class="taxworksheetcol3b">0.00</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Tax to be deducted</div>
<div class="taxworksheetcol3b">0.00</div>
</div>

<div class="row">
<div class="taxworksheetcol3a">Tax/Month</div>
<div class="taxworksheetcol3b">0.00</div>
</div>


<div class="row">
<div class="taxworksheetcol3a">Tax Deducted for this month</div>
<div class="taxworksheetcol3b">0.00</div>
</div>

<div class="row">
<div class="taxworksheetcol3abc">&nbsp;</div>
</div>

<div class="row">
<div class="taxworksheetcol3abc">&nbsp;</div>
</div>


</section>
</section>

<section id="personalnote">
<h3>&nbsp;</h3>
</section>
 
 </section>
<p style="text-align: center; font-size: 10px; color: #000; cursor: pointer;"><img alt="Print" id="imgprint" src="<?php echo base_url('assets/images/printer_icon.png')?>" /></p>
</section>

<?php } else{ ?>
      <p style="text-align: center; font-size: 10px; color: #000;">No Record Found for this Employee.</p>
 <?php } ?>

</section>
										
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
