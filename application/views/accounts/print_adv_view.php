<!DOCTYPE html>
<html>
 <head> 
 <style>
 body{
        font-size:20px !important;
        line-height:24px !important;
    }
 .page{
	width: 980px;
	height:1000px;
	padding:20px; 
}
.note{
	font-size:10px;
	text-align:right;
	font-style:italic;
	line-height:17px;
}
h1{
	text-align:center;
	text-transform:uppercase;
	text-shadow: 2px 2px 2px #999999;
}

h2{
	text-align:left;
	text-transform:uppercase;
	text-decoration: underline;
}
.row{
	width:100%;
	clear:both;
	line-height:26px;
}
.span1{
	width:100%;
	float:left;
}
.span2{
	width:50%;
	float:left;
}
.span3{
	width:33%;
	float:left;
}
.center{
	text-align:center;
}
.bold{
	font-weight:bold;
	padding-right:3px;
}
.bi{
	font-weight:bold;
	font-style:italic;
	padding-right:3px;
}
</style>
</head>
<body>
<div class="page">
<div class="note">
Document No: PTPL/HR Policy/14<br />
Annexure "A1"
</div>
<div style="margin:50px 0;"><h1><?php if($prinfEmpInfo[0]["applyfor"] == 'advance') echo "Salary Advance"; else echo "Loan";  ?> Application form</h1></div>

<div class="row"><div class="span1"><span class="bold">Date:</span><?= date("d/m/Y") ?></div></div>
<div class="row"><div class="span1"><span class="bold">Emp. Code:</span><?= $prinfEmpInfo[0]["loginhandle"] ?></div></div>
<div class="row"><div class="span1"><span class="bold">Name of the Employee:</span><?= $prinfEmpInfo[0]["full_name"] ?></div></div>
<div class="row"><div class="span2"><span class="bold">Date of Joining:</span><?= date("d/m/Y",strtotime($prinfEmpInfo[0]["join_date"])) ?></div> <div class="span2"><span class="bold">Designation:</span><?= $prinfEmpInfo[0]["desg_name"] ?></div></div>
<div class="row"><div class="span2"><span class="bold">Department:</span><?= $prinfEmpInfo[0]["dept_name"] ?></div><div class="span2"><span class="bold">Location:</span><?= $prinfEmpInfo[0]["branch_name"] ?></div></div>
<div class="row" style="margin-top:40px;"><hr /></div>

<div class="row" style="margin-top:20px;">
Kindly sanction me a salary advance of <span class="bi">Rs <?= $prinfEmpInfo[0]["amountapplied"] ?> (In words <?= $wordssss ?> )</span> for the purpose of <span class="bi"><?= $prinfEmpInfo[0]["message"] ?></span>
</div>
<div class="row" style="margin:75px 0 120px;">
<div class="span3 bold center"><img src="<?= base_url('assets/upload/profile/').$prinfEmpInfo[0]['user_sign_name'] ?>" alt="" width="225" height="40" class="form_img" /></div>
<div class="span3 bold center"><img src="<?= base_url('assets/upload/profile/').$rp[0]['user_sign_name']?>" alt="" width="225" height="40" class="form_img" /></div>
<div class="span3 bold center"><img src="<?= base_url('assets/upload/profile/').$dh[0]['user_sign_name']?>" alt="" width="225" height="40" class="form_img" /></div>
</div>
<div class="row" style="margin:80px 0 120px;">
<div class="span3 bold center">Sign of the applicant</div>
<div class="span3 bold center">Sign of Reporting Manager</div>
<div class="span3 bold center">Sign of HOD</div>
</div>

<div class="row" style="margin-top:20px;"><hr /></div>

<div class="row">
<h2>For HR Department:</h2>
Date of Joining:<span class="bi"><?= date("d/m/Y",strtotime($prinfEmpInfo[0]["join_date"])) ?></span> Monthly Gross Salary:<span class="bi">Rs <?= $prinfEmpInfo[0]["gross_salary"] ?></span> Eligable Amount: <span class="bi">Rs <?= $prinfEmpInfo[0]["gross_salary"]*$prinfEmpInfo[0]["eligibleamount"]/100 ?></span>
Approved Loan Amount: <span class="bold">Rs <?= $prinfEmpInfo[0]["approvedamount"] ?></span> No. of EMIs : <span class="bi"><?= $prinfEmpInfo[0]["approvedinstalment"] ?></span>  To be deducted from: <span class="bi"><?= $month ?></span>months salary. Date of previous salary advance taken: <span class="bold">_________________</span> amounting <span class="bold">Rs ____________</span></div>

<div class="row" style="margin-top:60px;"><span class="bold"><img src="<?= base_url('assets/upload/profile/').$hr[0]['user_sign_name']?>" alt="" width="225" height="40" class="form_img" /></span></div>
<div class="row"><span class="bold">Sign of HR Head</span></div>
<div class="row" style="margin-top:20px;"><hr /></div>


<div class="row" style="margin-top:30px;">
<span class="span1"><span class="bold">Remarks by CEO/ED</span> <span class="bi">Approved/ Not Approved</span> (Please Tick)</span>
</div>
<div class="row" style="margin-top:120px;"><span class="bold">Sign of CEO/ED</span></div>
<div class="row" style="margin-top:20px;"><hr /></div>
<div class="row" style="margin-top:30px;">
<h2>For Accounts Department:</h2>
Salary advance of <span class="bi">Rs <?= $prinfEmpInfo[0]["approvedamount"] ?></span> has/to be been paid on <span class="bi">_________________</span> to be deducted <span class="bi"><?= $month ?></span> months salary</div>
<div class="row" style="margin-top:60px;"><span class="bold"><img src="<?= base_url('assets/upload/profile/').$ac[0]['user_sign_name']?>" alt="" width="225" height="40" class="form_img" /></span></div>
<div class="row"><span class="bold">Sign of Head Accounts</span></div>
<br>
<br>
<center><a onclick="javascript:window.print();" style="cursor:pointer"><img src="<?= base_url('assets/images/printer_icon.png') ?>" alt="printer_icon"></a></center>
<br>
</div>
</body>
</html>