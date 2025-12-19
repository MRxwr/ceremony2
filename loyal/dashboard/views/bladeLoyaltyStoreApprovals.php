<?php 
if( isset($_GET["approve"]) && !empty($_GET["approve"]) ){
	if( updateDB('stores',array('isApproved'=> '1'),"`id` = '{$_GET["approve"]}'") ){
		// Send notification to store owner
		$storeData = selectDB("stores", "`id` = '{$_GET["approve"]}'");
		if ($storeData && is_array($storeData) && count($storeData) > 0) {
			$ownerId = $storeData[0]['ownerId'];
			insertDB("notifications", array(
				'userId' => $ownerId,
				'enTitle' => 'Store Approved',
				'arTitle' => 'تمت الموافقة على المتجر',
				'enDetails' => 'Your store has been approved and is now live!',
				'arDetails' => 'تمت الموافقة على متجرك وهو الآن مباشر!',
				'type' => 'store_approval',
				'isRead' => '0',
				'date' => date('Y-m-d H:i:s'),
				'status' => '0',
				'hidden' => '1'
			));
		}
		header("LOCATION: ?v=LoyaltyStoreApprovals");
	}
}

if( isset($_GET["reject"]) && !empty($_GET["reject"]) ){
	if( updateDB('stores',array('isApproved'=> '2'),"`id` = '{$_GET["reject"]}'") ){
		header("LOCATION: ?v=LoyaltyStoreApprovals");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('stores',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=LoyaltyStoreApprovals");
	}
}
?>
<div class="row">
<!-- Pending Approvals -->
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("Pending Store Approvals","طلبات الموافقة على المتاجر المعلقة") ?></h6>
</div>
<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
<div class="table-wrap mt-40">
<div class="table-responsive">
	<table class="table display responsive product-overview mb-30" id="pendingTable">
		<thead>
		<tr>
		<th>#</th>
		<th><?php echo direction("Store Name","اسم المتجر") ?></th>
		<th><?php echo direction("Category","الفئة") ?></th>
		<th><?php echo direction("Owner","المالك") ?></th>
		<th><?php echo direction("Phone","الهاتف") ?></th>
		<th><?php echo direction("Submitted Date","تاريخ التقديم") ?></th>
		<th><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		// First, let's try a simpler approach - get stores and categories separately
		$pendingStores = selectDB("stores", "isApproved = '0' AND status = '0' ORDER BY date DESC");
		if( $pendingStores && is_array($pendingStores) ){
			for( $i = 0; $i < sizeof($pendingStores); $i++ ){
				$counter = $i + 1;
				// Get category info
				$catId = $pendingStores[$i]["categoryId"];
				$category = selectDB("categories", "`id` = '$catId'");
				$categoryEN = ($category && is_array($category)) ? urldecode($category[0]["enTitle"]) : "N/A";
				$categoryAR = ($category && is_array($category)) ? urldecode($category[0]["arTitle"]) : "غير محدد";
			?>
			<tr>
			<td><?php echo $counter ?></td>
			<td>
				<div><strong><?php echo urldecode($pendingStores[$i]["enStoreName"]) ?></strong></div>
				<div style="font-size:12px;color:#999"><?php echo urldecode($pendingStores[$i]["arStoreName"]) ?></div>
			</td>
			<td><?php echo direction($categoryEN, $categoryAR) ?></td>
			<td><?php echo $pendingStores[$i]["ownerName"] ?? "N/A" ?></td>
			<td><?php echo $pendingStores[$i]["phone"] ?? "N/A" ?></td>
			<td><?php echo date('Y-m-d', strtotime($pendingStores[$i]["date"])) ?></td>
			<td class="text-nowrap">
			<a class="mr-25 viewDetails" data-id="<?php echo $pendingStores[$i]["id"] ?>" data-toggle="tooltip" data-original-title="<?php echo direction("View Details","عرض التفاصيل") ?>"> 
				<i class="fa fa-eye text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo "?v={$_GET["v"]}&approve={$pendingStores[$i]["id"]}" ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo direction("Approve","الموافقة") ?>" onclick="return confirm('<?php echo direction("Approve this store?","الموافقة على هذا المتجر؟") ?>')"> 
				<i class="fa fa-check text-success m-r-10"></i>
			</a>
			<a href="<?php echo "?v={$_GET["v"]}&reject={$pendingStores[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Reject","رفض") ?>" onclick="return confirm('<?php echo direction("Reject this store?","رفض هذا المتجر؟") ?>')">
				<i class="fa fa-times text-danger"></i>
			</a>
			
			<div style="display:none">
				<label id="storeEmail<?php echo $pendingStores[$i]["id"]?>"><?php echo $pendingStores[$i]["email"] ?? "" ?></label>
				<label id="storeWebsite<?php echo $pendingStores[$i]["id"]?>"><?php echo $pendingStores[$i]["website"] ?? "" ?></label>
				<label id="storeAddress<?php echo $pendingStores[$i]["id"]?>"><?php echo urldecode($pendingStores[$i]["address"] ?? "") ?></label>
				<label id="storeEnDesc<?php echo $pendingStores[$i]["id"]?>"><?php echo urldecode($pendingStores[$i]["enDescription"] ?? "") ?></label>
				<label id="storeArDesc<?php echo $pendingStores[$i]["id"]?>"><?php echo urldecode($pendingStores[$i]["arDescription"] ?? "") ?></label>
				<label id="storeLogo<?php echo $pendingStores[$i]["id"]?>"><?php echo $pendingStores[$i]["logo"] ?? "" ?></label>
			</div>
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

<!-- Approved Stores -->
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("Approved Stores","المتاجر المعتمدة") ?></h6>
</div>
<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
<div class="table-wrap mt-40">
<div class="table-responsive">
	<table class="table display responsive product-overview mb-30" id="approvedTable">
		<thead>
		<tr>
		<th>#</th>
		<th><?php echo direction("Store Name","اسم المتجر") ?></th>
		<th><?php echo direction("Category","الفئة") ?></th>
		<th><?php echo direction("Members","الأعضاء") ?></th>
		<th><?php echo direction("Active Cards","البطاقات النشطة") ?></th>
		<th><?php echo direction("Approved Date","تاريخ الموافقة") ?></th>
		<th><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		$approvedStores = selectDB("stores", "isApproved = '1' AND status = '0' ORDER BY date DESC");
		if( $approvedStores && is_array($approvedStores) ){
			for( $i = 0; $i < sizeof($approvedStores); $i++ ){
				$counter = $i + 1;
				$storeId = $approvedStores[$i]["id"];
				
				// Get category info
				$catId = $approvedStores[$i]["categoryId"];
				$category = selectDB("categories", "`id` = '$catId'");
				$categoryEN = ($category && is_array($category)) ? urldecode($category[0]["enTitle"]) : "N/A";
				$categoryAR = ($category && is_array($category)) ? urldecode($category[0]["arTitle"]) : "غير محدد";
				
				// Get member count
				$membersQuery = "SELECT COUNT(DISTINCT cc.customerId) as count
								FROM customer_cards cc
								JOIN loyalty_programs lp ON cc.programId = lp.id
								WHERE lp.storeId = '$storeId' AND cc.status = '0'";
				$membersResult = queryDB($membersQuery);
				$memberCount = ($membersResult && is_array($membersResult)) ? intval($membersResult[0]['count']) : 0;
				
				// Get card count
				$cardsQuery = "SELECT COUNT(*) as count
							  FROM customer_cards cc
							  JOIN loyalty_programs lp ON cc.programId = lp.id
							  WHERE lp.storeId = '$storeId' AND cc.status = '0'";
				$cardsResult = queryDB($cardsQuery);
				$cardCount = ($cardsResult && is_array($cardsResult)) ? intval($cardsResult[0]['count']) : 0;
			?>
			<tr>
			<td><?php echo $counter ?></td>
			<td>
				<div><strong><?php echo urldecode($approvedStores[$i]["enStoreName"]) ?></strong></div>
				<div style="font-size:12px;color:#999"><?php echo urldecode($approvedStores[$i]["arStoreName"]) ?></div>
			</td>
			<td><?php echo direction($categoryEN, $categoryAR) ?></td>
			<td><span class="badge badge-primary"><?php echo $memberCount ?></span></td>
			<td><span class="badge badge-success"><?php echo $cardCount ?></span></td>
			<td><?php echo date('Y-m-d', strtotime($approvedStores[$i]["date"])) ?></td>
			<td class="text-nowrap">
			<a class="mr-25 viewDetails" data-id="<?php echo $approvedStores[$i]["id"] ?>" data-toggle="tooltip" data-original-title="<?php echo direction("View Details","عرض التفاصيل") ?>"> 
				<i class="fa fa-eye text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo "?v={$_GET["v"]}&delId={$approvedStores[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>" onclick="return confirm('<?php echo direction("Delete this store?","حذف هذا المتجر؟") ?>')">
				<i class="fa fa-close text-danger"></i>
			</a>
			
			<div style="display:none">
				<label id="storeEmail<?php echo $approvedStores[$i]["id"]?>"><?php echo $approvedStores[$i]["email"] ?? "" ?></label>
				<label id="storeWebsite<?php echo $approvedStores[$i]["id"]?>"><?php echo $approvedStores[$i]["website"] ?? "" ?></label>
				<label id="storeAddress<?php echo $approvedStores[$i]["id"]?>"><?php echo urldecode($approvedStores[$i]["address"] ?? "") ?></label>
				<label id="storeEnDesc<?php echo $approvedStores[$i]["id"]?>"><?php echo urldecode($approvedStores[$i]["enDescription"] ?? "") ?></label>
				<label id="storeArDesc<?php echo $approvedStores[$i]["id"]?>"><?php echo urldecode($approvedStores[$i]["arDescription"] ?? "") ?></label>
				<label id="storeLogo<?php echo $approvedStores[$i]["id"]?>"><?php echo $approvedStores[$i]["logo"] ?? "" ?></label>
			</div>
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

</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo direction("Store Details","تفاصيل المتجر") ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3">
						<img id="modalLogo" src="" style="width:100%;border-radius:10px">
					</div>
					<div class="col-md-9">
						<h4 id="modalStoreName"></h4>
						<p><strong><?php echo direction("Email","البريد الإلكتروني") ?>:</strong> <span id="modalEmail"></span></p>
						<p><strong><?php echo direction("Website","الموقع") ?>:</strong> <span id="modalWebsite"></span></p>
						<p><strong><?php echo direction("Address","العنوان") ?>:</strong> <span id="modalAddress"></span></p>
					</div>
				</div>
				<div class="row" style="margin-top:20px">
					<div class="col-md-12">
						<h5><?php echo direction("Description","الوصف") ?></h5>
						<p id="modalDescription"></p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo direction("Close","إغلاق") ?></button>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#pendingTable').DataTable({
		"order": [[ 5, "desc" ]]
	});
	
	$('#approvedTable').DataTable({
		"order": [[ 5, "desc" ]]
	});
	
	$(document).on("click",".viewDetails", function(){
		var id = $(this).data("id");
		
		$("#modalStoreName").html($("#enTitle"+id).html());
		$("#modalEmail").html($("#storeEmail"+id).html());
		$("#modalWebsite").html($("#storeWebsite"+id).html());
		$("#modalAddress").html($("#storeAddress"+id).html());
		$("#modalDescription").html($("#storeEnDesc"+id).html());
		
		var logo = $("#storeLogo"+id).html();
		if(logo) {
			$("#modalLogo").attr("src", "../logos/" + logo);
		} else {
			$("#modalLogo").attr("src", "../img/placeholder.png");
		}
		
		$('#detailsModal').modal('show');
	});
});
</script>
