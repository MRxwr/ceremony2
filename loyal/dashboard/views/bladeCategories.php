<?php 
if( isset($_GET["hide"]) && !empty($_GET["hide"]) ){
	if( updateDB('categories',array('hidden'=> '2'),"`id` = '{$_GET["hide"]}'") ){
		header("LOCATION: ?v=Categories");
	}
}

if( isset($_GET["show"]) && !empty($_GET["show"]) ){
	if( updateDB('categories',array('hidden'=> '1'),"`id` = '{$_GET["show"]}'") ){
		header("LOCATION: ?v=Categories");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('categories',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=Categories");
	}
}

if( isset($_POST["updateRank"]) ){
	for( $i = 0; $i < sizeof($_POST["rank"]); $i++){
		updateDB("categories",array("rank"=>$_POST["rank"][$i]),"`id` = '{$_POST["id"][$i]}'");
	}
	header("LOCATION: ?v=Categories");
}

if( isset($_POST["arTitle"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	$_POST["enDetails"] = isset($_POST["enDetails"]) ? urlencode($_POST["enDetails"]) : "";
	$_POST["arDetails"] = isset($_POST["arDetails"]) ? urlencode($_POST["arDetails"]) : "";
	
    $columns = ['rank', 'arTitle', 'enTitle', 'enDetails', 'arDetails', 'imageurl', 'header', 'glow', 'hidden', 'status'];

	if ( $id == 0 ){
		$_POST["rank"] = 0;
		$_POST["glow"] = 0;
		$_POST["status"] = 0;
		if (is_uploaded_file($_FILES['imageurl']['tmp_name'])) {
			$_POST["imageurl"] = uploadImageBannerFreeImageHost($_FILES['imageurl']['tmp_name'], "categories");
		} else {
			$_POST["imageurl"] = "";
		}
		
		if (is_uploaded_file($_FILES['header']['tmp_name'])) {
			$_POST["header"] = uploadImageBannerFreeImageHost($_FILES['header']['tmp_name'], "categories");
		} else {
			$_POST["header"] = "";
		}
		
        $data = [];
        foreach($columns as $col){
            if(isset($_POST[$col])){
                $data[$col] = $_POST[$col];
            }
        }
		
		if( insertDB("categories", $data) ){
			header("LOCATION: ?v=Categories");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if (is_uploaded_file($_FILES['imageurl']['tmp_name'])) {
			$_POST["imageurl"] = uploadImageBannerFreeImageHost($_FILES['imageurl']['tmp_name'], "categories");
		} else {
			$imageurl = selectDB("categories", "`id` = '{$id}'");
			$_POST["imageurl"] = $imageurl[0]["imageurl"];
		}
		
		if (is_uploaded_file($_FILES['header']['tmp_name'])) {
			$_POST["header"] = uploadImageBannerFreeImageHost($_FILES['header']['tmp_name'], "categories");
		} else {
			$header = selectDB("categories", "`id` = '{$id}'");
			$_POST["header"] = $header[0]["header"];
		}
		
        $data = [];
        foreach($columns as $col){
            if(isset($_POST[$col])){
                $data[$col] = $_POST[$col];
            }
        }

		if( updateDB("categories", $data, "`id` = '{$id}'") ){
			header("LOCATION: ?v=Categories");
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
	<h6 class="panel-title txt-dark"><?php echo direction("Category Details","تفاصيل القسم") ?></h6>
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
				<label><?php echo direction("Hide Category","أخفي القسم") ?></label>
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
<h6 class="panel-title txt-dark"><?php echo direction("List of Categories","قائمة الأقسام") ?></h6>
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
			<th><?php echo direction("Logo","الشعار") ?></th>
			<th><?php echo direction("English Title","العنوان بالإنجليزي") ?></th>
			<th><?php echo direction("Arabic Title","العنوان بالعربي") ?></th>
			<th class="text-nowrap"><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		if( $categories = selectDB("categories","`status` = '0' ORDER BY `rank` ASC") ){
			for( $i = 0; $i < sizeof($categories); $i++ ){
				$counter = $i + 1;
			if ( $categories[$i]["hidden"] == 2 ){
				$icon = "fa fa-eye";
				$link = "?v={$_GET["v"]}&show={$categories[$i]["id"]}";
				$hide = direction("Show","إظهار");
			}else{
				$icon = "fa fa-eye-slash";
				$link = "?v={$_GET["v"]}&hide={$categories[$i]["id"]}";
				$hide = direction("Hide","إخفاء");
			}
			?>
			<tr>
				<td>
					<input name="rank[]" class="form-control" type="number" value="<?php echo $counter ?>">
					<input name="id[]" class="form-control" type="hidden" value="<?php echo $categories[$i]["id"] ?>">
				</td>
				<td><img src="../logos/categories/<?php echo $categories[$i]["imageurl"] ?>" style="width:50px;height:50px"></td>
				<td id="enTitle<?php echo $categories[$i]["id"]?>" ><?php echo $categories[$i]["enTitle"] ?></td>
				<td id="arTitle<?php echo $categories[$i]["id"]?>" ><?php echo $categories[$i]["arTitle"] ?></td>
				<td class="text-nowrap">
					<a id="<?php echo $categories[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i></a>
					<a href="<?php echo $link ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo $hide ?>"> <i class="<?php echo $icon ?> text-inverse m-r-10"></i></a>
					<a href="<?php echo "?v={$_GET["v"]}&delId={$categories[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>"><i class="fa fa-close text-danger"></i></a>
				<div style="display:none">
					<label id="hidden<?php echo $categories[$i]["id"]?>"><?php echo $categories[$i]["hidden"] ?></label>
					<label id="logo<?php echo $categories[$i]["id"]?>"><?php echo $categories[$i]["imageurl"] ?></label>
					<label id="header<?php echo $categories[$i]["id"]?>"><?php echo $categories[$i]["header"] ?></label>
					<label id="enDetails<?php echo $categories[$i]["id"]?>"><?php echo urldecode(htmlspecialchars($categories[$i]["enDetails"])) ?></label>
					<label id="arDetails<?php echo $categories[$i]["id"]?>"><?php echo urldecode(htmlspecialchars($categories[$i]["arDetails"])) ?></label>
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
		$("#headerImg").attr("src","../logos/"+$("#header"+id).html());
		$("#logoImg").attr("src","../logos/"+$("#logo"+id).html());
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