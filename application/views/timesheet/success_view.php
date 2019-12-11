<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Time Sheet</span>
			</h4>
		</div>
	</div>
</div>
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-6"> 
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
							  <th colspan="7" >
								<a class="btn"><i class="icon-chevron-left"></i></a>
								<a class="btn">February 2012</a>
								<a class="btn"><i class="icon-chevron-right"></i></a>
							  </th>
							</tr>
							<tr>
								<th>Sun</th>
								<th>Mon</th>
								<th>Tue</th>
								<th>Wed</th>
								<th>Thu</th>
								<th>Fri</th>
								<th>Sat</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="muted">29</td>
								<td class="muted">30</td>
								<td class="muted">31</td>
								<td>1</td>
								<td>2</td>
								<td>3</td>
								<td>4</td>
							</tr>
							<tr>
								<td>5</td>
								<td>6</td>
								<td>7</td>
								<td>8</td>
								<td>9</td>
								<td>10</td>
								<td>11</td>
							</tr>
							<tr>
								<td>12</td>
								<td>13</td>
								<td>14</td>
								<td>15</td>
								<td>16</td>
								<td>17</td>
								<td>18</td>
							</tr>
							<tr>
								<td>19</td>
								<td><strong>20</strong></td>
								<td>21</td>
								<td>22</td>
								<td>23</td>
								<td>24</td>
								<td>25</td>
							</tr>
							<tr>
								<td>26</td>
								<td>27</td>
								<td>28</td>
								<td>29</td>
								<td class="muted">1</td>
								<td class="muted">2</td>
								<td class="muted">3</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-3"> 
					<h4>Color Info :</h4>
					<ul>
						<li><img src="<?= base_url('assets/images/attendence/color1.jpg')?>" alt="" align="left" /> Days Attended</li>
						<li><img src="<?= base_url('assets/images/attendence/color2.jpg')?>" alt="" align="left" /> Absent Days</li>
						<li><img src="<?= base_url('assets/images/attendence/color4.jpg')?>" alt="" align="left" /> Regularized Days</li> 
						<li><img src="<?= base_url('assets/images/attendence/color3.jpg')?>" alt="" align="left" /> Days on Leave</li>
						<li><img src="<?= base_url('assets/images/attendence/color5.jpg')?>" alt="" align="left" /> First Half Present</li>
						<li><img src="<?= base_url('assets/images/attendence/color6.jpg')?>" alt="" align="left" /> Second Half Present</li>
						<li><img src="<?= base_url('assets/images/attendence/color7.jpg')?>" alt="" align="left" /> First Half Absent</li>
						<li><img src="<?= base_url('assets/images/attendence/color8.jpg')?>" alt="" align="left" /> Second Half Absent</li>
						<li><img src="<?= base_url('assets/images/attendence/color9.jpg')?>" alt="" align="left" /> First Half Leave</li>
						<li><img src="<?= base_url('assets/images/attendence/color10.jpg')?>" alt="" align="left" /> Second Half Leave</li> 
						<li><img src="<?= base_url('assets/images/attendence/color11.jpg')?>" alt="" align="left" /> Holidays</li>
						<li><img src="<?= base_url('assets/images/attendence/color12.jpg')?>" alt="" align="left" /> Sorrow Feedback</li>
						<li><img src="<?= base_url('assets/images/attendence/color13.jpg')?>" alt="" align="left" /> Performer of the Day</li>
						<li><img src="<?= base_url('assets/images/attendence/color14.jpg')?>" alt="" align="left" /> Fullon attended Month</li>
					</ul> 
				</div> 
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>