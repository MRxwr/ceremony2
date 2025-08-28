<?php 
if( isset($_GET["hide"]) && !empty($_GET["hide"]) ){
	if( updateDB("packages",array('hidden'=> '2'),"`id` = '{$_GET["hide"]}'") ){
		header("LOCATION: ?v=Packages");
	}
}

if( isset($_GET["show"]) && !empty($_GET["show"]) ){
	if( updateDB("packages",array('hidden'=> '1'),"`id` = '{$_GET["show"]}'") ){
		header("LOCATION: ?v=Packages");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB("packages",array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=Packages");
	}
}

if( isset($_POST["updateRank"]) ){
	for( $i = 0; $i < sizeof($_POST["rank"]); $i++){
		updateDB("packages",array("rank"=>$_POST["rank"][$i]),"`id` = '{$_POST["id"][$i]}'");
	}
	header("LOCATION: ?v=Packages");
}

if( isset($_POST["arTitle"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	if ( $id == 0 ){
		if( insertDB("packages", $_POST) ){
			header("LOCATION: ?v=Packages");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if( updateDB("packages", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=Packages");
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

            <div class="col-md-4">
			<label><?php echo direction("Attendees","عدد الحضور") ?></label>
			<input type="number" step="1" min="1" name="attendees" class="form-control" required>
			</div>

            <div class="col-md-4">
			<label><?php echo direction("Price","السعر") ?></label>
			<input type="number" step="0.01" min="0" name="price" class="form-control" required>
			</div>

            <div class="col-md-4">
			<label><?php echo direction("Discount","الخصم") ?></label>
			<input type="number" step="0.01" min="0" name="discount" class="form-control" required>
			</div>

            <div class="col-md-6">
			<label><?php echo direction("English Details","التفاصيل بالإنجليزي") ?></label>
			<textarea id="enDetails" name="enDetails" class="tinymce"></textarea>
			</div>

            <div class="col-md-6">
			<label><?php echo direction("Arabic Details","التفاصيل بالعربي") ?></label>
			<textarea id="arDetails" name="arDetails" class="tinymce"></textarea>
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
				
				<!-- Bordered Table -->
<form method="post" action="">
<input name="updateRank" type="hidden" value="1">
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("List of Packages","قائمة الباقات") ?></h6>
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
		<th><?php echo direction("English Title","العنوان بالإنجليزي") ?></th>
		<th><?php echo direction("Arabic Title","العنوان بالعربي") ?></th>
		<th><?php echo direction("Attendees","عدد الحضور") ?></th>
		<th><?php echo direction("Price","السعر") ?></th>
		<th><?php echo direction("Discount","الخصم") ?></th>
		<th class="text-nowrap"><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		if( $packages = selectDB("packages","`status` = '0' ORDER BY `rank` ASC") ){
			for( $i = 0; $i < sizeof($packages); $i++ ){
				$counter = $i + 1;
			if ( $packages[$i]["hidden"] == 2 ){
				$icon = "fa fa-eye";
				$link = "?v={$_GET["v"]}&show={$packages[$i]["id"]}";
				$hide = direction("Show","إظهار");
			}else{
				$icon = "fa fa-eye-slash";
				$link = "?v={$_GET["v"]}&hide={$packages[$i]["id"]}";
				$hide = direction("Hide","إخفاء");
			}
			?>
			<tr>
			<td>
			<input name="rank[]" class="form-control" type="number" value="<?php echo str_pad($counter, 2, '0', STR_PAD_LEFT) ?>">
			<input name="id[]" class="form-control" type="hidden" value="<?php echo $packages[$i]["id"] ?>">
			</td>
			<td id="enTitle<?php echo $packages[$i]["id"]?>" ><?php echo $packages[$i]["enTitle"] ?></td>
			<td id="arTitle<?php echo $packages[$i]["id"]?>" ><?php echo $packages[$i]["arTitle"] ?></td>
			<td id="attendees<?php echo $packages[$i]["id"]?>" ><?php echo $packages[$i]["attendees"] ?></td>
			<td id="price<?php echo $packages[$i]["id"]?>" ><?php echo $packages[$i]["price"] ?></td>
			<td id="discount<?php echo $packages[$i]["id"]?>" ><?php echo $packages[$i]["discount"] ?></td>
			<td class="text-nowrap">
			
			<a id="<?php echo $packages[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo $link ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo $hide ?>"> <i class="<?php echo $icon ?> text-inverse m-r-10"></i>
			</a>
			<a href="<?php echo "?v={$_GET["v"]}&delId={$packages[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>"><i class="fa fa-close text-danger"></i>
			</a>
			<div style="display:none"><label id="hidden<?php echo $packages[$i]["id"]?>"><?php echo $packages[$i]["hidden"] ?></label></div>
			<div style="display:none"><label id="enDetails<?php echo $packages[$i]["id"]?>"><?php echo $packages[$i]["enDetails"] ?></label></div>
			<div style="display:none"><label id="arDetails<?php echo $packages[$i]["id"]?>"><?php echo $packages[$i]["arDetails"] ?></label></div>
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
        tinymce.get('enDetails').setContent($("#enDetails"+id).html());
        tinymce.get('arDetails').setContent($("#arDetails"+id).html());
		$("#images").attr("style","margin-top:10px;display:block");
	})
</script>