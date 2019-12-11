<!DOCTYPE html><html> <head> <style>
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
<div style="margin:50px 0;"><h1>Loan Application form</h1></div>

<div class="row"><div class="span1"><span class="bold">Date:</span><?= date("d/m/Y") ?></div></div>
<div class="row"><div class="span1"><span class="bold">Emp. Code:</span><?= $prinfEmpInfo[0]["loginhandle"] ?></div></div>
<div class="row"><div class="span1"><span class="bold">Name of the Employee:</span><?= $prinfEmpInfo[0]["full_name"] ?></div></div>
<div class="row"><div class="span2"><span class="bold">Date of Joining:</span><?= date("d/m/Y",strtotime($prinfEmpInfo[0]["join_date"])) ?></div> <div class="span2"><span class="bold">Designation:</span><?= $prinfEmpInfo[0]["desg_name"] ?></div></div>
<div class="row"><div class="span2"><span class="bold">Department:</span><?= $prinfEmpInfo[0]["dept_name"] ?></div><div class="span2"><span class="bold">Location:</span><?= $prinfEmpInfo[0]["branch_name"] ?></div></div>
<div class="row" style="margin-top:40px;"><hr /></div>

<div class="row" style="margin-top:20px;">
Kindly sanction me an loan amounting <span class="bi">Rs <?= $prinfEmpInfo[0]["amountapplied"]?> (In words <?= ucwords($wordssss) ?>)</span> for the purpose of <span class="bi"><?= $prinfEmpInfo[0]["message"] ?></span>
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
Date of Previous Loan taken: ________________ Last EMIs paid date of previous loan: ______________ Outstanding loan amount as on date , <i>if any</i>: Rs __________
Current Eligibility : YES/NO (<i>Please tick</i>)</br>
Date of Joining:<span class="bi"><?= date("d/m/Y",strtotime($prinfEmpInfo[0]["join_date"])) ?></span> Monthly Gross Salary:<span class="bi">Rs <?= $prinfEmpInfo[0]["gross_salary"] ?></span> Eligible Amount: <span class="bi">Rs <?= ($prinfEmpInfo[0]["gross_salary"]*$prinfEmpInfo[0]["eligibleamount"]) ?></span>
Loan amount requested by the Employee : <span class="bold">Rs <?= $prinfEmpInfo[0]["amountapplied"]; ?></span> 
Approved Loan Amount: <span class="bold">Rs <?= $prinfEmpInfo[0]["approvedamount"]; ?></span> EMI Amount: <span class="bold">Rs <?= $prinfEmpInfo[0]["approvedamount"]/$prinfEmpInfo[0]["approvedinstalment"]; ?></span> </br>No. of EMIs : <span class="bi"><?= $prinfEmpInfo[0]["approvedinstalment"]; ?></span> EMI Statring Month & Year <?= $prinfEmpInfo[0]["lmonth"].'/'.$prinfEmpInfo[0]["lyear"]; ?></div>
<div class="row" style="margin-top:60px;"><span class="bold"><img src="<?= base_url('assets/upload/profile/').$hr[0]['user_sign_name']?>" alt="" width="225" height="40" class="form_img" /></span></div>
<div class="row"><span class="bold">Sign of HR Head</span></div>
<div class="row" style="margin-top:20px;"><hr /></div>


<div class="row" style="margin-top:30px;">
<span class="span1"><span class="bold">Remarks by CEO/ED</span> <span class="bi">Approved/ Not Approved</span> (<i>Please Tick</i>)</span>
</div>
<div class="row" style="margin-top:120px;"><span class="bold">Sign of CEO/ED</span></div>
<div class="row" style="margin-top:20px;"><hr /></div>
<div class="row" style="margin-top:30px;">
<h2>For Accounts Department:</h2>
Approved INTEREST FREE/INTEREST PAID (<i>please tick</i>) Loan Amount: <span class="bi">Rs <?= $prinfEmpInfo[0]["approvedamount"] ?></span> EMI Amount: <?= $prinfEmpInfo[0]["approvedamount"]/$prinfEmpInfo[0]["approvedinstalment"] ?> in <?= $prinfEmpInfo[0]["approvedinstalment"] ?> EMI starting from <span class="bi"><?= $month ?></span> month. EMI schedule is enclosed herewith.</div>
<div class="row" style="margin-top:60px;"><span class="bold"><img src="<?= base_url('assets/upload/profile/').$ac[0]['user_sign_name']?>" alt="" width="225" height="40" class="form_img" /></span></div>
<div class="row"><span class="bold">Sign of Head Accounts</span></div>

<center><a onclick="javascript:window.print();" style="cursor:pointer"><img src="<?= base_url('assets/images/printer_icon.png') ?>" alt="printer_icon"></a></center>
<br>
</div>
</body>
</html>