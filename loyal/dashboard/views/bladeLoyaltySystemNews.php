<?php 
if( isset($_GET["hide"]) && !empty($_GET["hide"]) ){
	if( updateDB('system_news',array('hidden'=> '2'),"`id` = '{$_GET["hide"]}'") ){
		header("LOCATION: ?v=LoyaltySystemNews");
	}
}

if( isset($_GET["show"]) && !empty($_GET["show"]) ){
	if( updateDB('system_news',array('hidden'=> '1'),"`id` = '{$_GET["show"]}'") ){
		header("LOCATION: ?v=LoyaltySystemNews");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('system_news',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=LoyaltySystemNews");
	}
}

if( isset($_POST["arTitle"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	$_POST["enDetails"] = urlencode($_POST["enDetails"]);
	$_POST["arDetails"] = urlencode($_POST["arDetails"]);
	$_POST["enTitle"] = urlencode($_POST["enTitle"]);
	$_POST["arTitle"] = urlencode($_POST["arTitle"]);
	$_POST["date"] = date('Y-m-d H:i:s');
	
	if ( $id == 0 ){
		if (is_uploaded_file($_FILES['imageurl']['tmp_name'])) {
			$_POST["imageurl"] = uploadImageBannerFreeImageHost($_FILES['imageurl']['tmp_name'],"banners");
		} else {
			$_POST["imageurl"] = "";
		}
		if( insertDB("system_news", $_POST) ){
			// Send notifications to all users
			$users = selectDB("users", "`status` = '0'");
			if ($users && is_array($users)) {
				foreach ($users as $user) {
					insertDB("notifications", array(
						'userId' => $user['id'],
						'enTitle' => urldecode($_POST["enTitle"]),
						'arTitle' => urldecode($_POST["arTitle"]),
						'enDetails' => urldecode($_POST["enDetails"]),
						'arDetails' => urldecode($_POST["arDetails"]),
						'type' => 'announcement',
						'isRead' => '0',
						'date' => date('Y-m-d H:i:s'),
						'status' => '0',
						'hidden' => '1'
					));
				}
			}
			header("LOCATION: ?v=LoyaltySystemNews");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if (is_uploaded_file($_FILES['imageurl']['tmp_name'])) {
			$_POST["imageurl"] = uploadImageBannerFreeImageHost($_FILES['imageurl']['tmp_name'],"banners");
		} else {
			$imageurl = selectDB("system_news", "`id` = '{$id}'");
			$_POST["imageurl"] = $imageurl[0]["imageurl"];
		}
		
		if( updateDB("system_news", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=LoyaltySystemNews");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}
}
?>
<div class="row">
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
	<h6 class="panel-title txt-dark"><?php echo direction("Announcement Details","تفاصيل الإعلان") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="" enctype="multipart/form-data">
		<div class="row m-0">

			<div class="col-md-4">
			<label><?php echo direction("English Title","العنوان بالإنجليزي") ?></label>
			<input type="text" name="enTitle" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Arabic Title","العنوان بالعربي") ?></label>
			<input type="text" name="arTitle" class="form-control" required>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Importance Level","مستوى الأهمية") ?></label>
			<select name="priority" class="form-control">
				<option value="normal"><?php echo direction("Normal","عادي") ?></option>
				<option value="important"><?php echo direction("Important","مهم") ?></option>
				<option value="urgent"><?php echo direction("Urgent","عاجل") ?></option>
			</select>
			</div>

			<div class="col-md-6">
			<label><?php echo direction("English Details","التفاصيل بالإنجليزي") ?></label>
			<textarea id="enDetails" name="enDetails" class="tinymce"></textarea>
			</div>

			<div class="col-md-6">
			<label><?php echo direction("Arabic Details","التفاصيل بالعربي") ?></label>
			<textarea id="arDetails" name="arDetails" class="tinymce"></textarea>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Target Audience","الفئة المستهدفة") ?></label>
			<select name="targetAudience" class="form-control">
				<option value="all"><?php echo direction("All Users","جميع المستخدمين") ?></option>
				<option value="customers"><?php echo direction("Customers Only","العملاء فقط") ?></option>
				<option value="merchants"><?php echo direction("Merchants Only","التجار فقط") ?></option>
			</select>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Cover Image (Optional)","صورة الغلاف (اختياري)") ?></label>
			<input type="file" name="imageurl" class="form-control">
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Hide Announcement","إخفاء الإعلان") ?></label>
			<select name="hidden" class="form-control">
				<option value="1"><?php echo direction("No","لا") ?></option>
				<option value="2"><?php echo direction("Yes","نعم") ?></option>
			</select>
			</div>
			
			<div id="imagePreview" style="margin-top: 10px; display:none" class="col-md-12">
				<img id="coverImg" src="" style="max-width:400px;max-height:300px;border-radius:10px">
			</div>
			
			<div class="col-md-12" style="margin-top:10px">
			<input type="submit" class="btn btn-primary" value="<?php echo direction("Submit","أرسل") ?>">
			<input type="hidden" name="update" value="0">
			</div>
		</div>
	</form>
</div>
</div>
</div>
</div>
				
<!-- News List -->
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("System Announcements","إعلانات النظام") ?></h6>
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
		<th><?php echo direction("Title","العنوان") ?></th>
		<th><?php echo direction("Priority","الأولوية") ?></th>
		<th><?php echo direction("Target","الهدف") ?></th>
		<th><?php echo direction("Date","التاريخ") ?></th>
		<th><?php echo direction("Status","الحالة") ?></th>
		<th class="text-nowrap"><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		if( $news = selectDB("system_news","`status` = '0' ORDER BY `date` DESC") ){
			for( $i = 0; $i < sizeof($news); $i++ ){
				$counter = $i + 1;
			if ( $news[$i]["hidden"] == 2 ){
				$icon = "fa fa-eye";
				$link = "?v={$_GET["v"]}&show={$news[$i]["id"]}";
				$hide = direction("Show","إظهار");
				$statusBadge = '<span class="badge badge-warning">'.direction("Hidden","مخفي").'</span>';
			}else{
				$icon = "fa fa-eye-slash";
				$link = "?v={$_GET["v"]}&hide={$news[$i]["id"]}";
				$hide = direction("Hide","إخفاء");
				$statusBadge = '<span class="badge badge-success">'.direction("Visible","ظاهر").'</span>';
			}
			
			$priorityColors = array(
				'normal' => 'primary',
				'important' => 'warning',
				'urgent' => 'danger'
			);
			$priority = $news[$i]["priority"] ?? 'normal';
			$priorityBadge = '<span class="badge badge-'.$priorityColors[$priority].'">'.ucfirst($priority).'</span>';
			?>
			<tr>
			<td><?php echo $counter ?></td>
			<td id="enTitle<?php echo $news[$i]["id"]?>" >
				<strong><?php echo urldecode($news[$i]["enTitle"]) ?></strong><br>
				<small style="color:#999"><?php echo urldecode($news[$i]["arTitle"]) ?></small>
			</td>
			<td><?php echo $priorityBadge ?></td>
			<td><?php echo ucfirst($news[$i]["targetAudience"] ?? 'all') ?></td>
			<td><?php echo date('Y-m-d', strtotime($news[$i]["date"])) ?></td>
			<td><?php echo $statusBadge ?></td>
			<td class="text-nowrap">
			
			<a id="<?php echo $news[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo $link ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo $hide ?>"> <i class="<?php echo $icon ?> text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo "?v={$_GET["v"]}&delId={$news[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>" onclick="return confirm('<?php echo direction("Delete this announcement?","حذف هذا الإعلان؟") ?>')"><i class="fa fa-close text-danger"></i>
			</a>
			<div style="display:none">
				<label id="arTitle<?php echo $news[$i]["id"]?>"><?php echo urldecode($news[$i]["arTitle"]) ?></label>
				<label id="hidden<?php echo $news[$i]["id"]?>"><?php echo $news[$i]["hidden"] ?></label>
				<label id="priority<?php echo $news[$i]["id"]?>"><?php echo $news[$i]["priority"] ?? 'normal' ?></label>
				<label id="targetAudience<?php echo $news[$i]["id"]?>"><?php echo $news[$i]["targetAudience"] ?? 'all' ?></label>
				<label id="image<?php echo $news[$i]["id"]?>"><?php echo $news[$i]["imageurl"] ?? '' ?></label>
				<label id="enDetails<?php echo $news[$i]["id"]?>"><?php echo urldecode($news[$i]["enDetails"]) ?></label>
				<label id="arDetails<?php echo $news[$i]["id"]?>"><?php echo urldecode($news[$i]["arDetails"]) ?></label>
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

<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			"order": [[ 4, "desc" ]]
		});
	});
	
	$(document).on("click",".edit", function(){
		var id = $(this).attr("id");
		$("input[name=update]").val(id);

		$("input[name=enTitle]").val($("#enTitle"+id).text().trim()).focus();
		$("input[name=arTitle]").val($("#arTitle"+id).text());
		$("select[name=hidden]").val($("#hidden"+id).text());
		$("select[name=priority]").val($("#priority"+id).text());
		$("select[name=targetAudience]").val($("#targetAudience"+id).text());
		$("input[type=file]").prop("required",false);
		
		var imagePath = $("#image"+id).text();
		if(imagePath) {
			$("#coverImg").attr("src","../logos/banners/"+imagePath);
			$("#imagePreview").show();
		}
		
		// Set TinyMCE content
		setTimeout(function() {
			var enDetails = tinymce.get('enDetails');
			var arDetails = tinymce.get('arDetails');
			
			if (enDetails) {
				enDetails.setContent($("#enDetails"+id).text());
			}
			if (arDetails) {
				arDetails.setContent($("#arDetails"+id).text());
			}
		}, 100);
		
		$('html, body').animate({
			scrollTop: 0
		}, 500);
	})
</script>
