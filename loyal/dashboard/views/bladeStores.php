<?php 
if( isset($_GET["hide"]) && !empty($_GET["hide"]) ){
	if( updateDB('categories',array('hidden'=> '2'),"`id` = '{$_GET["hide"]}'") ){
		header("LOCATION: ?v=Stores");
	}
}

if( isset($_GET["show"]) && !empty($_GET["show"]) ){
	if( updateDB('categories',array('hidden'=> '1'),"`id` = '{$_GET["show"]}'") ){
		header("LOCATION: ?v=Stores");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('categories',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=Stores");
	}
}

if( isset($_POST["updateRank"]) ){
	for( $i = 0; $i < sizeof($_POST["rank"]); $i++){
		updateDB("categories",array("rank"=>$_POST["rank"][$i]),"`id` = '{$_POST["id"][$i]}'");
	}
	header("LOCATION: ?v=Stores");
}

if( isset($_POST["arTitle"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	$_POST["enDetails"] = urlencode($_POST["enDetails"]);
	$_POST["arDetails"] = urlencode($_POST["arDetails"]);
	if ( $id == 0 ){
		if (is_uploaded_file($_FILES['logo']['tmp_name'])) {
			$_POST["logo"] = uploadImageBannerFreeImageHost($_FILES['logo']['tmp_name'], "stores");
		} else {
			$_POST["logo"] = "";
		}
		
		if (is_uploaded_file($_FILES['header']['tmp_name'])) {
			$_POST["header"] = uploadImageBannerFreeImageHost($_FILES['header']['tmp_name'], "stores");
		} else {
			$_POST["header"] = "";
		}
		
		
		if( insertDB("stores", $_POST) ){
			header("LOCATION: ?v=Stores");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if (is_uploaded_file($_FILES['logo']['tmp_name'])) {
			$_POST["logo"] = uploadImageBannerFreeImageHost($_FILES['logo']['tmp_name'], "stores");
		} else {
			$logo = selectDB("stores", "`id` = '{$id}'");
			$_POST["logo"] = $logo[0]["logo"];
		}
		
		if (is_uploaded_file($_FILES['header']['tmp_name'])) {
			$_POST["header"] = uploadImageBannerFreeImageHost($_FILES['header']['tmp_name'], "stores");
		} else {
			$header = selectDB("stores", "`id` = '{$id}'");
			$_POST["header"] = $header[0]["header"];
		}
		
		if( updateDB("stores", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=Stores");
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
	<h6 class="panel-title txt-dark"><?php echo direction("Store Details","تفاصيل المتجر") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="" enctype="multipart/form-data">
		<div class="row m-0">

			<div class="col-md-4">
				<label><?php echo direction("Select Category","اختر القسم") ?></label>
				<select name="categoryId" class="form-control" required>
					<option value="" disabled selected><?php echo direction("No Category","بدون قسم") ?></option>
					<?php 
					if( $stores = selectDB("categories","`status` = '0' ORDER BY `rank` ASC") ){
						for( $i = 0; $i < sizeof($stores); $i++ ){
							?>
							<option value="<?php echo $stores[$i]["id"] ?>"><?php echo direction($stores[$i]["enTitle"], $stores[$i]["arTitle"]) ?></option>
							<?php
						}
					}
					?>
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
			
			<div class="col-md-4">
				<label><?php echo direction("Hide Store","أخفي المتجر") ?></label>
				<select name="hidden" class="form-control">
					<option value="1">No</option>
					<option value="2">Yes</option>
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

			<div class="col-md-6">
				<label><?php echo direction("Logo","الشعار") ?></label>
				<input type="file" name="imageurl" class="form-control" required>
			</div>
			
			<div class="col-md-6">
				<label><?php echo direction("Header","الصورة الكبيرة") ?></label>
				<input type="file" name="header" class="form-control" required>
			</div>
			
			<div id="images" style="margin-top: 10px; display:none">
				<div class="col-md-6">
					<img id="logoImg" src="" style="width:250px;height:250px">
				</div>
				
				<div class="col-md-6">
					<img id="headerImg" src="" style="width:250px;height:250px">
				</div>
			</div>
			
			
			<div class="col-md-6" style="margin-top:10px">
				<input type="submit" class="btn btn-primary" value="<?php echo direction("Submit","أرسل") ?>">
				<input type="hidden" name="update" value="0">
			</div>
		</div>
	</form>
</div>
</div>
</div>
</div>
				
				<!-- Bordered Table -->
<form method="post" action="">
<input name="updateRank" type="hidden" value="1">
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("List of Stores","قائمة المتاجر") ?></h6>
</div>
<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
<button class="btn btn-primary">
<?php echo direction("Submit rank","أرسل الترتيب") ?>
</button>  
<div class="table-wrap mt-40">
<div class="table-responsive">
	<table class="table display responsive product-overview mb-30" id="myTable">
		<thead>
		<tr>
			<th>#</th>
			<th><?php echo direction("Category","القسم") ?></th>
			<th><?php echo direction("Logo","الشعار") ?></th>
			<th><?php echo direction("English Title","العنوان بالإنجليزي") ?></th>
			<th><?php echo direction("Arabic Title","العنوان بالعربي") ?></th>
			<th class="text-nowrap"><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		if( $stores = selectDB("stores","`status` = '0' ORDER BY `rank` ASC") ){
			for( $i = 0; $i < sizeof($stores); $i++ ){
				$counter = $i + 1;
			if ( $stores[$i]["hidden"] == 2 ){
				$icon = "fa fa-eye";
				$link = "?v={$_GET["v"]}&show={$stores[$i]["id"]}";
				$hide = direction("Show","إظهار");
			}else{
				$icon = "fa fa-eye-slash";
				$link = "?v={$_GET["v"]}&hide={$stores[$i]["id"]}";
				$hide = direction("Hide","إخفاء");
			}
			$category = selectDB("categories","`id` = '{$stores[$i]["categoryId"]}'");
			?>
			<tr>
				<td>
					<input name="rank[]" class="form-control" type="number" value="<?php echo $counter ?>">
					<input name="id[]" class="form-control" type="hidden" value="<?php echo $stores[$i]["id"] ?>">
				</td>
				<td><?php echo direction($category[0]["enTitle"], $category[0]["arTitle"]) ?></td>
				<td><img src="../storage/<?php echo $stores[$i]["imageurl"] ?>" style="width:50px;height:50px"></td>
				<td id="enTitle<?php echo $stores[$i]["id"]?>" ><?php echo $stores[$i]["enTitle"] ?></td>
				<td id="arTitle<?php echo $stores[$i]["id"]?>" ><?php echo $stores[$i]["arTitle"] ?></td>
				<td class="text-nowrap">
					<a id="<?php echo $stores[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i></a>
					<a href="<?php echo $link ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo $hide ?>"> <i class="<?php echo $icon ?> text-inverse m-r-10"></i></a>
					<a href="<?php echo "?v={$_GET["v"]}&delId={$stores[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>"><i class="fa fa-close text-danger"></i></a>
				<div style="display:none">
					<label id="hidden<?php echo $stores[$i]["id"]?>"><?php echo $stores[$i]["hidden"] ?></label>
					<label id="logo<?php echo $stores[$i]["id"]?>"><?php echo $stores[$i]["imageurl"] ?></label>
					<label id="header<?php echo $stores[$i]["id"]?>"><?php echo $stores[$i]["header"] ?></label>
					<label id="categoryId<?php echo $stores[$i]["id"]?>"><?php echo $stores[$i]["categoryId"] ?></label>
					<label id="enDetails<?php echo $stores[$i]["id"]?>"><?php echo urldecode(htmlspecialchars($stores[$i]["enDetails"])) ?></label>
					<label id="arDetails<?php echo $stores[$i]["id"]?>"><?php echo urldecode(htmlspecialchars($stores[$i]["arDetails"])) ?></label>
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
</form>
</div>
<script>
	$(document).on("click",".edit", function(){
		var id = $(this).attr("id");
		$("input[name=update]").val(id);

		$("input[type=file]").prop("required",false);
		$("input[name=enTitle]").val($("#enTitle"+id).html()).focus();
		$("input[name=arTitle]").val($("#arTitle"+id).html());
		$("select[name=hidden]").val($("#hidden"+id).html());
		$("select[name=categoryId]").val($("#categoryId"+id).html());
		$("#headerImg").attr("src","../storage/"+$("#header"+id).html());
		$("#logoImg").attr("src","../storage/"+$("#logo"+id).html());
		$("#images").attr("style","margin-top:10px;display:block");
		// Set TinyMCE content with a small delay to ensure editors are ready
		setTimeout(function() {
			var enDetails = tinymce.get('enDetails');
			var arDetails = tinymce.get('arDetails');
			
			if (enDetails) {
				enDetails.setContent($("#enDetails"+id).html());
			}
			if (arDetails) {
				arDetails.setContent($("#arDetails"+id).html());
			}
		}, 100);
	})
</script>