<?php 
// Handle ticket status updates
if( isset($_GET["close"]) && !empty($_GET["close"]) ){
	if( updateDB('support_tickets',array('ticketStatus'=> 'closed'),"`id` = '{$_GET["close"]}'") ){
		header("LOCATION: ?v=LoyaltySupportTickets");
	}
}

if( isset($_GET["resolve"]) && !empty($_GET["resolve"]) ){
	if( updateDB('support_tickets',array('ticketStatus'=> 'resolved'),"`id` = '{$_GET["resolve"]}'") ){
		header("LOCATION: ?v=LoyaltySupportTickets");
	}
}

if( isset($_GET["reopen"]) && !empty($_GET["reopen"]) ){
	if( updateDB('support_tickets',array('ticketStatus'=> 'open'),"`id` = '{$_GET["reopen"]}'") ){
		header("LOCATION: ?v=LoyaltySupportTickets");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('support_tickets',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=LoyaltySupportTickets");
	}
}

// Handle reply submission
if( isset($_POST["replyMessage"]) ){
	$ticketId = $_POST["ticketId"];
	$adminId = $_SESSION["userId"];
	
	$replyData = array(
		'ticketId' => $ticketId,
		'userId' => $adminId,
		'message' => urlencode($_POST["replyMessage"]),
		'isAdminReply' => '1',
		'date' => date('Y-m-d H:i:s'),
		'status' => '0',
		'hidden' => '1'
	);
	
	if( insertDB("ticket_replies", $replyData) ){
		// Update ticket status to "in progress"
		updateDB('support_tickets', array('ticketStatus' => 'in_progress'), "`id` = '{$ticketId}'");
		
		// Send notification to ticket creator
		$ticketData = selectDB("support_tickets", "`id` = '{$ticketId}'");
		if ($ticketData && is_array($ticketData) && count($ticketData) > 0) {
			$userId = $ticketData[0]['userId'];
			insertDB("notifications", array(
				'userId' => $userId,
				'enTitle' => 'Support Reply',
				'arTitle' => 'رد الدعم',
				'enDetails' => 'You have a new reply on your support ticket',
				'arDetails' => 'لديك رد جديد على تذكرة الدعم الخاصة بك',
				'type' => 'support',
				'isRead' => '0',
				'date' => date('Y-m-d H:i:s'),
				'status' => '0',
				'hidden' => '1'
			));
		}
		
		header("LOCATION: ?v=LoyaltySupportTickets&view={$ticketId}");
	}
}
?>
<div class="row">
<!-- Ticket Statistics -->
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-primary">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim">
								<?php 
								$openCount = selectDB("support_tickets", "`ticketStatus` = 'open' AND `status` = '0'");
								echo is_array($openCount) ? count($openCount) : 0;
								?>
								</span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("Open","مفتوح") ?></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-envelope-open data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-warning">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim">
								<?php 
								$inProgressCount = selectDB("support_tickets", "`ticketStatus` = 'in_progress' AND `status` = '0'");
								echo is_array($inProgressCount) ? count($inProgressCount) : 0;
								?>
								</span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("In Progress","قيد المعالجة") ?></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-clock data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-success">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim">
								<?php 
								$resolvedCount = selectDB("support_tickets", "`ticketStatus` = 'resolved' AND `status` = '0'");
								echo is_array($resolvedCount) ? count($resolvedCount) : 0;
								?>
								</span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("Resolved","محلول") ?></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-check data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-danger">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim">
								<?php 
								$closedCount = selectDB("support_tickets", "`ticketStatus` = 'closed' AND `status` = '0'");
								echo is_array($closedCount) ? count($closedCount) : 0;
								?>
								</span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("Closed","مغلق") ?></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-lock data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if(isset($_GET["view"]) && !empty($_GET["view"])): 
	$ticketId = $_GET["view"];
	$ticketData = selectDB("support_tickets st
						   JOIN users u ON st.userId = u.id",
						   "st.id = '{$ticketId}' LIMIT 1");
	if ($ticketData && is_array($ticketData) && count($ticketData) > 0):
		$ticket = $ticketData[0];
?>
<!-- Ticket Details View -->
<div class="col-sm-12">
	<div class="panel panel-default card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark"><?php echo direction("Ticket Details","تفاصيل التذكرة") ?> #<?php echo $ticket["id"] ?></h6>
			</div>
			<div class="pull-right">
				<a href="?v=LoyaltySupportTickets" class="btn btn-sm btn-default"><?php echo direction("Back to List","العودة للقائمة") ?></a>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<!-- Ticket Header -->
				<div class="row">
					<div class="col-md-8">
						<h4><?php echo urldecode($ticket["subject"]) ?></h4>
						<p><strong><?php echo direction("Category","الفئة") ?>:</strong> <?php echo ucfirst($ticket["category"]) ?></p>
						<p><strong><?php echo direction("Priority","الأولوية") ?>:</strong> 
							<?php 
							$priorityColors = array('low' => 'success', 'medium' => 'warning', 'high' => 'danger');
							$priority = $ticket["priority"] ?? 'medium';
							echo '<span class="badge badge-'.$priorityColors[$priority].'">'.ucfirst($priority).'</span>';
							?>
						</p>
					</div>
					<div class="col-md-4 text-right">
						<p><strong><?php echo direction("Status","الحالة") ?>:</strong> 
							<?php 
							$statusColors = array('open' => 'primary', 'in_progress' => 'warning', 'resolved' => 'success', 'closed' => 'danger');
							$status = $ticket["ticketStatus"] ?? 'open';
							echo '<span class="badge badge-'.$statusColors[$status].'">'.ucfirst(str_replace('_',' ',$status)).'</span>';
							?>
						</p>
						<p><strong><?php echo direction("Created","تاريخ الإنشاء") ?>:</strong> <?php echo date('Y-m-d H:i', strtotime($ticket["date"])) ?></p>
						<p><strong><?php echo direction("By","من قبل") ?>:</strong> <?php echo $ticket["firstName"].' '.$ticket["lastName"] ?></p>
					</div>
				</div>
				
				<hr>
				
				<!-- Original Message -->
				<div class="ticket-message" style="background:#f8f9fa;padding:20px;border-radius:10px;margin-bottom:20px">
					<div style="margin-bottom:10px">
						<strong><?php echo $ticket["firstName"].' '.$ticket["lastName"] ?></strong>
						<span style="color:#999;font-size:12px;margin-left:10px"><?php echo date('Y-m-d H:i', strtotime($ticket["date"])) ?></span>
					</div>
					<div><?php echo urldecode($ticket["message"]) ?></div>
				</div>
				
				<!-- Replies -->
				<?php 
				$replies = selectDB("ticket_replies tr
								    LEFT JOIN users u ON tr.userId = u.id
								    LEFT JOIN employees e ON tr.userId = e.id",
								   "tr.ticketId = '{$ticketId}' AND tr.status = '0' ORDER BY tr.date ASC");
				if ($replies && is_array($replies)):
					foreach ($replies as $reply):
						$isAdmin = $reply["isAdminReply"] == '1';
						$bgColor = $isAdmin ? '#e7f3ff' : '#f8f9fa';
						$userName = $isAdmin ? direction('Support Team','فريق الدعم') : ($reply["firstName"].' '.$reply["lastName"]);
				?>
				<div class="ticket-message" style="background:<?php echo $bgColor ?>;padding:20px;border-radius:10px;margin-bottom:20px">
					<div style="margin-bottom:10px">
						<strong><?php echo $userName ?></strong>
						<?php if($isAdmin): ?>
						<span class="badge badge-info"><?php echo direction('Staff','موظف') ?></span>
						<?php endif; ?>
						<span style="color:#999;font-size:12px;margin-left:10px"><?php echo date('Y-m-d H:i', strtotime($reply["date"])) ?></span>
					</div>
					<div><?php echo urldecode($reply["message"]) ?></div>
				</div>
				<?php 
					endforeach;
				endif;
				?>
				
				<!-- Reply Form -->
				<?php if($ticket["ticketStatus"] != 'closed'): ?>
				<div style="margin-top:30px">
					<h5><?php echo direction("Add Reply","أضف رد") ?></h5>
					<form method="POST" action="">
						<div class="form-group">
							<textarea name="replyMessage" class="form-control" rows="5" required placeholder="<?php echo direction("Type your reply...","اكتب ردك...") ?>"></textarea>
						</div>
						<input type="hidden" name="ticketId" value="<?php echo $ticketId ?>">
						<button type="submit" class="btn btn-primary"><?php echo direction("Send Reply","أرسل الرد") ?></button>
						<?php if($ticket["ticketStatus"] != 'resolved'): ?>
						<a href="?v=LoyaltySupportTickets&resolve=<?php echo $ticketId ?>" class="btn btn-success" onclick="return confirm('<?php echo direction("Mark as resolved?","تحديد كمحلول؟") ?>')"><?php echo direction("Mark as Resolved","حدد كمحلول") ?></a>
						<?php endif; ?>
						<a href="?v=LoyaltySupportTickets&close=<?php echo $ticketId ?>" class="btn btn-danger" onclick="return confirm('<?php echo direction("Close this ticket?","إغلاق هذه التذكرة؟") ?>')"><?php echo direction("Close Ticket","أغلق التذكرة") ?></a>
					</form>
				</div>
				<?php else: ?>
				<div class="alert alert-warning">
					<?php echo direction("This ticket is closed. ","هذه التذكرة مغلقة. ") ?>
					<a href="?v=LoyaltySupportTickets&reopen=<?php echo $ticketId ?>"><?php echo direction("Click here to reopen","انقر هنا لإعادة الفتح") ?></a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php 
	endif;
else:
?>
<!-- Tickets List -->
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("Support Tickets","تذاكر الدعم") ?></h6>
</div>
<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
<div class="table-wrap mt-40">
<div class="table-responsive">
	<table class="table display responsive product-overview mb-30" id="myTable">
		<thead>
		<tr>
		<th>#</th>
		<th><?php echo direction("Subject","الموضوع") ?></th>
		<th><?php echo direction("User","المستخدم") ?></th>
		<th><?php echo direction("Category","الفئة") ?></th>
		<th><?php echo direction("Priority","الأولوية") ?></th>
		<th><?php echo direction("Status","الحالة") ?></th>
		<th><?php echo direction("Date","التاريخ") ?></th>
		<th class="text-nowrap"><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		$tickets = selectDB("support_tickets st
						    JOIN users u ON st.userId = u.id",
						   "st.status = '0' ORDER BY 
						    CASE st.ticketStatus 
						        WHEN 'open' THEN 1 
						        WHEN 'in_progress' THEN 2 
						        WHEN 'resolved' THEN 3 
						        WHEN 'closed' THEN 4 
						    END,
						    st.date DESC");
		if( $tickets && is_array($tickets) ){
			for( $i = 0; $i < sizeof($tickets); $i++ ){
				$counter = $i + 1;
				
				$statusColors = array('open' => 'primary', 'in_progress' => 'warning', 'resolved' => 'success', 'closed' => 'danger');
				$status = $tickets[$i]["ticketStatus"] ?? 'open';
				$statusBadge = '<span class="badge badge-'.$statusColors[$status].'">'.ucfirst(str_replace('_',' ',$status)).'</span>';
				
				$priorityColors = array('low' => 'success', 'medium' => 'warning', 'high' => 'danger');
				$priority = $tickets[$i]["priority"] ?? 'medium';
				$priorityBadge = '<span class="badge badge-'.$priorityColors[$priority].'">'.ucfirst($priority).'</span>';
			?>
			<tr>
			<td><?php echo $tickets[$i]["id"] ?></td>
			<td><strong><?php echo urldecode($tickets[$i]["subject"]) ?></strong></td>
			<td><?php echo $tickets[$i]["firstName"].' '.$tickets[$i]["lastName"] ?></td>
			<td><?php echo ucfirst($tickets[$i]["category"]) ?></td>
			<td><?php echo $priorityBadge ?></td>
			<td><?php echo $statusBadge ?></td>
			<td><?php echo date('Y-m-d H:i', strtotime($tickets[$i]["date"])) ?></td>
			<td class="text-nowrap">
			
			<a href="?v=<?php echo $_GET["v"] ?>&view=<?php echo $tickets[$i]["id"] ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo direction("View","عرض") ?>"> 
				<i class="fa fa-eye text-inverse m-r-10"></i>
			</a>
			<?php if($tickets[$i]["ticketStatus"] != 'resolved'): ?>
			<a href="<?php echo "?v={$_GET["v"]}&resolve={$tickets[$i]["id"]}" ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo direction("Resolve","حل") ?>" onclick="return confirm('<?php echo direction("Mark as resolved?","تحديد كمحلول؟") ?>')"> 
				<i class="fa fa-check text-success m-r-10"></i>
			</a>
			<?php endif; ?>
			<?php if($tickets[$i]["ticketStatus"] != 'closed'): ?>
			<a href="<?php echo "?v={$_GET["v"]}&close={$tickets[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Close","إغلاق") ?>" onclick="return confirm('<?php echo direction("Close this ticket?","إغلاق هذه التذكرة؟") ?>')">
				<i class="fa fa-times text-danger"></i>
			</a>
			<?php endif; ?>
			</td>
			</tr>
			<?php
			}
		}
		?>
		</tbody>
		
	</table>
</div>
</div>
</div>
</div>
</div>
</div>
<?php endif; ?>

</div>

<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			"order": [[ 6, "desc" ]]
		});
	});
</script>
