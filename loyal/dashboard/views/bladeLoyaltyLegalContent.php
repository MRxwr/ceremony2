<?php 
if( isset($_GET["hide"]) && !empty($_GET["hide"]) ){
	if( updateDB('legal_content',array('hidden'=> '2'),"`id` = '{$_GET["hide"]}'") ){
		header("LOCATION: ?v=LoyaltyLegalContent");
	}
}

if( isset($_GET["show"]) && !empty($_GET["show"]) ){
	if( updateDB('legal_content',array('hidden'=> '1'),"`id` = '{$_GET["show"]}'") ){
		header("LOCATION: ?v=LoyaltyLegalContent");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('legal_content',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=LoyaltyLegalContent");
	}
}

if( isset($_POST["arTitle"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	$_POST["enContent"] = urlencode($_POST["enContent"]);
	$_POST["arContent"] = urlencode($_POST["arContent"]);
	$_POST["enTitle"] = urlencode($_POST["enTitle"]);
	$_POST["arTitle"] = urlencode($_POST["arTitle"]);
	$_POST["lastUpdated"] = date('Y-m-d H:i:s');
	
	if ( $id == 0 ){
		$_POST["date"] = date('Y-m-d H:i:s');
		if( insertDB("legal_content", $_POST) ){
			header("LOCATION: ?v=LoyaltyLegalContent");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if( updateDB("legal_content", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=LoyaltyLegalContent");
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
	<h6 class="panel-title txt-dark"><?php echo direction("Legal Content Editor","محرر المحتوى القانوني") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="">
		<div class="row m-0">

			<div class="col-md-4">
			<label><?php echo direction("Content Type","نوع المحتوى") ?></label>
			<select name="contentType" class="form-control" required>
				<option value=""><?php echo direction("Select Type","اختر النوع") ?></option>
				<option value="terms"><?php echo direction("Terms & Conditions","الشروط والأحكام") ?></option>
				<option value="privacy"><?php echo direction("Privacy Policy","سياسة الخصوصية") ?></option>
				<option value="cookies"><?php echo direction("Cookie Policy","سياسة ملفات تعريف الارتباط") ?></option>
				<option value="refund"><?php echo direction("Refund Policy","سياسة الاسترداد") ?></option>
				<option value="merchant_agreement"><?php echo direction("Merchant Agreement","اتفاقية التاجر") ?></option>
				<option value="user_agreement"><?php echo direction("User Agreement","اتفاقية المستخدم") ?></option>
			</select>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("English Title","العنوان بالإنجليزي") ?></label>
			<input type="text" name="enTitle" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Arabic Title","العنوان بالعربي") ?></label>
			<input type="text" name="arTitle" class="form-control" required>
			</div>

			<div class="col-md-6">
			<label><?php echo direction("English Content","المحتوى بالإنجليزي") ?></label>
			<textarea id="enContent" name="enContent" class="tinymce"></textarea>
			</div>

			<div class="col-md-6">
			<label><?php echo direction("Arabic Content","المحتوى بالعربي") ?></label>
			<textarea id="arContent" name="arContent" class="tinymce"></textarea>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Version","الإصدار") ?></label>
			<input type="text" name="version" class="form-control" placeholder="1.0" required>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Effective Date","تاريخ السريان") ?></label>
			<input type="date" name="effectiveDate" class="form-control" value="<?php echo date('Y-m-d') ?>" required>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Hide Content","إخفاء المحتوى") ?></label>
			<select name="hidden" class="form-control">
				<option value="1"><?php echo direction("No","لا") ?></option>
				<option value="2"><?php echo direction("Yes","نعم") ?></option>
			</select>
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
				
<!-- Legal Content List -->
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("Legal Documents","المستندات القانونية") ?></h6>
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
		<th><?php echo direction("Type","النوع") ?></th>
		<th><?php echo direction("Title","العنوان") ?></th>
		<th><?php echo direction("Version","الإصدار") ?></th>
		<th><?php echo direction("Effective Date","تاريخ السريان") ?></th>
		<th><?php echo direction("Last Updated","آخر تحديث") ?></th>
		<th><?php echo direction("Status","الحالة") ?></th>
		<th class="text-nowrap"><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		if( $legalDocs = selectDB("legal_content","`status` = '0' ORDER BY `date` DESC") ){
			for( $i = 0; $i < sizeof($legalDocs); $i++ ){
				$counter = $i + 1;
			if ( $legalDocs[$i]["hidden"] == 2 ){
				$icon = "fa fa-eye";
				$link = "?v={$_GET["v"]}&show={$legalDocs[$i]["id"]}";
				$hide = direction("Show","إظهار");
				$statusBadge = '<span class="badge badge-warning">'.direction("Hidden","مخفي").'</span>';
			}else{
				$icon = "fa fa-eye-slash";
				$link = "?v={$_GET["v"]}&hide={$legalDocs[$i]["id"]}";
				$hide = direction("Hide","إخفاء");
				$statusBadge = '<span class="badge badge-success">'.direction("Active","نشط").'</span>';
			}
			?>
			<tr>
			<td><?php echo $counter ?></td>
			<td>
				<?php 
				$typeLabels = array(
					'terms' => direction('Terms & Conditions','الشروط والأحكام'),
					'privacy' => direction('Privacy Policy','سياسة الخصوصية'),
					'cookies' => direction('Cookie Policy','سياسة الكوكيز'),
					'refund' => direction('Refund Policy','سياسة الاسترداد'),
					'merchant_agreement' => direction('Merchant Agreement','اتفاقية التاجر'),
					'user_agreement' => direction('User Agreement','اتفاقية المستخدم')
				);
				echo $typeLabels[$legalDocs[$i]["contentType"]] ?? $legalDocs[$i]["contentType"];
				?>
			</td>
			<td id="enTitle<?php echo $legalDocs[$i]["id"]?>" >
				<strong><?php echo urldecode($legalDocs[$i]["enTitle"]) ?></strong><br>
				<small style="color:#999"><?php echo urldecode($legalDocs[$i]["arTitle"]) ?></small>
			</td>
			<td><span class="badge badge-primary"><?php echo $legalDocs[$i]["version"] ?></span></td>
			<td><?php echo date('Y-m-d', strtotime($legalDocs[$i]["effectiveDate"])) ?></td>
			<td><?php echo date('Y-m-d', strtotime($legalDocs[$i]["lastUpdated"])) ?></td>
			<td><?php echo $statusBadge ?></td>
			<td class="text-nowrap">
			
			<a id="<?php echo $legalDocs[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo $link ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo $hide ?>"> <i class="<?php echo $icon ?> text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo "?v={$_GET["v"]}&delId={$legalDocs[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>" onclick="return confirm('<?php echo direction("Delete this document?","حذف هذا المستند؟") ?>')"><i class="fa fa-close text-danger"></i>
			</a>
			<div style="display:none">
				<label id="arTitle<?php echo $legalDocs[$i]["id"]?>"><?php echo urldecode($legalDocs[$i]["arTitle"]) ?></label>
				<label id="contentType<?php echo $legalDocs[$i]["id"]?>"><?php echo $legalDocs[$i]["contentType"] ?></label>
				<label id="hidden<?php echo $legalDocs[$i]["id"]?>"><?php echo $legalDocs[$i]["hidden"] ?></label>
				<label id="version<?php echo $legalDocs[$i]["id"]?>"><?php echo $legalDocs[$i]["version"] ?></label>
				<label id="effectiveDate<?php echo $legalDocs[$i]["id"]?>"><?php echo $legalDocs[$i]["effectiveDate"] ?></label>
				<label id="enContent<?php echo $legalDocs[$i]["id"]?>"><?php echo urldecode($legalDocs[$i]["enContent"]) ?></label>
				<label id="arContent<?php echo $legalDocs[$i]["id"]?>"><?php echo urldecode($legalDocs[$i]["arContent"]) ?></label>
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
			"order": [[ 5, "desc" ]]
		});
	});
	
	$(document).on("click",".edit", function(){
		var id = $(this).attr("id");
		$("input[name=update]").val(id);

		$("select[name=contentType]").val($("#contentType"+id).text());
		$("input[name=enTitle]").val($("#enTitle"+id).text().trim()).focus();
		$("input[name=arTitle]").val($("#arTitle"+id).text());
		$("input[name=version]").val($("#version"+id).text());
		$("input[name=effectiveDate]").val($("#effectiveDate"+id).text());
		$("select[name=hidden]").val($("#hidden"+id).text());
		
		// Set TinyMCE content
		setTimeout(function() {
			var enContent = tinymce.get('enContent');
			var arContent = tinymce.get('arContent');
			
			if (enContent) {
				enContent.setContent($("#enContent"+id).text());
			}
			if (arContent) {
				arContent.setContent($("#arContent"+id).text());
			}
		}, 100);
		
		$('html, body').animate({
			scrollTop: 0
		}, 500);
	})
</script>
