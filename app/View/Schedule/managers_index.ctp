			
			<div class="row">
				<?php echo $this->Session->flash('auth'); ?>
				<br />
				<div class="col-md-12">
				<?php
					$current= date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
					$date=time();
					$this_day=date('d', $date);
					$this_month=date('m', $date);
					$this_year=date('Y', $date);
					$first_day=mktime(0,0,0,$month, 1, $year);
					$day_of_week=date('D', $first_day);
					switch($day_of_week){
						case 'Sun': $blank='blank_0'; break;
						case 'Mon': $blank='blank_1'; break;
						case 'Tue': $blank='blank_2'; break;
						case 'Wed': $blank='blank_3'; break;
						case 'Thu': $blank='blank_4'; break;
						case 'Fri': $blank='blank_5'; break;
						case 'Sat': $blank='blank_6'; break;
					}
					$days_in_month=date('t', mktime(0, 0, 0, $month, 1, $year)); 
					
					echo '<div class="text-center">';
					echo '<button type="button" class="btn btn-default" aria-label="Left Align" onclick="go_schedule(\'prev\');">
								<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
							</button>';
					echo $this->Form->input('date', array('div'=>false, 'label'=>false, 'type'=>'date', 'class'=>'select_schedule', 'dateFormat' => 'MY', 'minYear' => 2015, 'maxYear' => date('Y') + 2, 'onchange'=>'go_schedule();'));
					echo '<button type="button" class="btn btn-default" aria-label="Left Align" onclick="go_schedule(\'next\');">
								<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
							</button>';
					echo '</div>';
					
					echo '
						<div class="calendar_label">
							<span class="block_day_label">Sun</span>
							<span class="block_day_label">Mon</span>
							<span class="block_day_label">Tue</span>
							<span class="block_day_label">Wed</span>
							<span class="block_day_label">Thu</span>
							<span class="block_day_label">Fri</span>
							<span class="block_day_label">Sat</span>
						</div>
						';
					echo '<ul class="calendar">';
					for($i=1; $i<=$days_in_month; $i++){
						if($i==$this_day && $month==$this_month && $year==$this_year) $class='today';
						else $class='';
						
						if($i==1) $class.=' '.$blank;
						
						if(strlen($i)==1) $day_str='0'.$i;
						else $day_str=$i;
						
						echo '<li class="block_day '.$class.'" id="day-section-'.$year.'-'.$month.'-'.$day_str.'">'.$i.'</li>';
						
					}
					echo '</ul>';
				?>
				</div>
			</div>
			
			<script>
				$(function(){
					$.getJSON(main_pageUrl+'/Managers/Schedule/ScheduleList/<?php echo $year.'/'.$month; ?>')
						.success(function(data){
							$.each(data, function(key, item){
								var lists='<br /><a href="'+main_pageUrl+'/Managers/Invoice/Detail/'+item['IvInvoice'].i_idx+'" class="none_effect"> <span>'+item['IvInvoiceCustomer'].ic_company+'</span></a>'
								
								$('#day-section-'+item[0].ip_enddate).append(lists);
							});
						})
						 .fail(function( jqxhr, textStatus, error ) {
							var err = textStatus + ", " + error;
							console.log( "Request Failed: " + err );
						});
				});
			</script>
			
			<?php //echo $this->element('sql_dump'); ?>
