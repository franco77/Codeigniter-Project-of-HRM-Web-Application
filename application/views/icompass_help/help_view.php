<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('icompass_help/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">About POLOHRM</legend>
					<div class="row well">
						<div class="form-content page-content">  
							<p>This site was developed by our in house team to provide a common platform for all POLOHRM employees and to facilitate easier access to useful information such as official holidays, attendances and leaves, birthdays, internal vacancies and others. This intranet page can only be accessed from within the POLOHRM office. This site is a substantive upgrade from the old i-compass system which many of you may be familiar with. We have used the i-compass core and have built the new intranet around it.</p>
							<h4><strong>Adjusting your browser (if required)</strong></h4>
							<p>Our POLOHRM intranet site is best viewed in all browsers <strong>except Internet Explorer Version6</strong> (because HTML does not support it). It is our request to those of you who are using lower versions to please upgrade to Internet Explorer v8, v9 or v10. The download link is the following:  <a href="http://www.microsoft.com/india/windows/ie/IE8.aspx?os=WinXP&browser=Firefox">http://www.microsoft.com/india/windows/ie/IE8.aspx?os=WinXP&browser=Firefox</a> </p>
							<p>In order to ensure uniformity in our approach, <strong>we ask that you make this link</strong> <a href="http://icompass.POLOHRM.com">http://icompass.POLOHRM.com</a> <strong>your default page</strong> in your web browser so that you will automatically come to the POLOHRM intranet page (default page). We have attached three mock ups on how to set up the default page to the intranet site in the different browsers.</p>
							<p><span class="red">Mozilla:</span> <strong>Tools-option-General-Home page-URL-ok</strong>
								<img src="<?= base_url('assets/images/mozilla.jpg')?>" class="img_style" alt="" />
							</p>
							<p><span class="red">IE:</span> <strong>Tools- Internet Option- General - URL-ok</strong> <img src="<?= base_url('assets/images/ie.jpg')?>" class="img_style" alt="" /></p>
							<p><span class="red">Chrome:</span> <strong>customize and control Google Chrome- Option - Home Page - URL - ok</strong> <img src="<?= base_url('assets/images/crome.jpg')?>" class="img_style" alt="" /></p>
							<h5>Logging in</h5>
							<p>Once you open the browser, the POLOHRM intranet will launch and ask you for your login and password. <br />
							<strong>The login is your employee code, i.e.: <span class="red2">PTPL-XXXXX</span>      the password is <span class="red2">polohrm@123</span>.</strong> <br />
							A pop up will appear which will ask you to change the password to your own personal password the first time you use the site.</p>
							<h5>Attendance</h5>
							<p>After you login, a pop up will appear asking you whether you would like to update your attendance. Click "yes" if this is your first login of the day. If you say "yes" the system will record the login date (at a later stage it will also record the login time). Use "No" only in case you have come to the office on a leave day and don't need to record your attendance. Once you have recorded your attendance for today, you will not be asked again - even if you log out.</p>
							<h5>Intranet Home Page </h5>
							<p>The next page is the (intranet) home page which is the central page from where you can navigate to all other pages. The homepage has the following tabs: "Home" "Timesheet" " Production" "New and Events" "Resources" "I compass help". Several of these tabs are under construction. The different tabs are explained below. If you work in HR, Admin/Accounts or BD, a separate site will be visible to you with links and resources related to your area of work. For sharing of documents you can also use the existing "resources" page.</p>
							<p>The most prominent section is the yellow “Alert section”. In this section alerts and important announcements appear.  For example, If you did not log in the previous day or days, an alert will pop up that will ask you whether you would like to regularize your attendance. (You can also do this soon by going to the timesheet tab and click the button "regularize attendance"). Other alerts include for example any current internal job openings or announcements of staff activities. </p>
							<p>The homepage includes the additional "key" features (repeated from the other tabs) in 6 categories</p>
							<ol type="1">
								<li>Event calendar (news and events)</li>
								<li>My active projects</li>
								<li>Important links</li>
								<li>Staff directory</li>
								<li>POLOHRM classifieds</li>
								<li>Employee of the month (not yet functional)</li>
							</ol>
							<p><span class="red2">1. <u>Event calendar</u></span><br /> The event calendar lists important new and events related to the company such as birthdays , company related news and events this week (this month and the following month). You can see whose birthdays are coming up, which events are planned, who is visiting POLOHRM and what are the events are planned. </p>
							<p><span class="red2">2. <u>My active projects</u></span><br /> You can view at a glance the status of the projects that you are working on. These can also be accessed through the "Production" tab where you will get more details.  </p>
							<p><span class="red2">3. <u>Important links</u></span><br /> This section provide links to important documents such as the holiday list, service rules, operating procedures, organizational structure (Organogram), etc, These documents and many more can also be found under "Resources"</p>
							<p><span class="red2">4. <u>Staff directory</u></span><br /> A searchable address book provides you a the phone numbers of your colleagues at a fingertip. It includes official phone numbers and extension numbers. If you like your personal phone number to be included here please go to "Update my profile" on the top of the page where your name is written and click on "Show My Mobile No on POLOHRM Staff Directory". This will make your number available to your colleagues in the organization.</p>
							<p><span class="red2">5. <u>POLOHRM classifieds</u></span><br /> The  POLOHRM classifieds provides a space for POLOHRMians to exchange important information such as when you want to sell something, when you looking to buy something, information about housing, commuting to the office, fooding etc etc. PLease post if you want to sell anything, if you are looking for something of if you want to share some information. </p>
							<p><span class="red2">6. <u>Employee of the month (not yet functional)</u></span><br /> On the top you can see the employee of the past month. In the lower half you see the top 3-5 positions of the current month and your own position. This ranking can change any day based on your performance.</p>
							<p>On the very left hand side of the scree is a static button (throughout the pages) where you can provide comments, feedback and suggestions which will go directly to the director. If you don’t sign your post, the message will be anonymous.</p>
							<h5>Timesheet Tab</h5>
							<p>The next tab on the top of your screen is the "Timesheet" tab. Here you can review your attendance, leaves taken. You can also regularize and leaves in case you were sick or on official travel. <strong>Currently the time sheet does NOT record your in and out time which you will have to record manually in the registers</strong>.  We are working on a more efficient system for recording of in and out time, so please be patient.</p>
							<h5>Production Tab - visible to people in production</h5>
							<p>The "Production" tab is for all people in production. The page is re designed but contains the structure that you are already used to. No functional changes were made here only the design was slightly modified.</p>
							<h5>News and Events Tab</h5>
							<p>Here all the detailed news of the past months / years will be hosted and the site is searchable. </p>
							<h5>Resources Tab</h5>
							<p>Includes important resources which are currently in the i compass - this site will be further updated. </p>
							<h5>I compass Help</h5>
							<p>This page is currently under construction but will provide information on how to use i compass</p>
							<h5>HR/Admin/BD Tab - not visible now</h5>
							<p>Are under construction and can only be accessed by people in the respective sections</p>
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
</div>