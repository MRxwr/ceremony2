<?php 
if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('events',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=Events");
	}
}

if( isset($_POST["title"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	if ( $id == 0 ){
		generateCode:
		$_POST["code"] = generateRandomString();
		if( selectDB("events","`code` = '{$_POST["code"]}'") ){
			goto generateCode;
		}
        if (is_uploaded_file($_FILES['background']['tmp_name'])) {
			$_POST["background"] = uploadImageBannerFreeImageHost($_FILES['background']['tmp_name']);
		} else {
			$_POST["background"] = "";
		}
		if( insertDB("events", $_POST) ){
			header("LOCATION: ?v=Events");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		} 
	}else{
		if( $code = selectDB("events","`id` = '{$id}'") ){
			if( empty($code[0]["code"]) ){
				generateCodeUpdate:
				$_POST["code"] = generateRandomString();
				if( selectDB("events","`code` = '{$_POST["code"]}'") ){
					goto generateCodeUpdate;
				}
			}
		}
        if (is_uploaded_file($_FILES['background']['tmp_name'])) {
			$_POST["background"] = uploadImageBannerFreeImageHost($_FILES['background']['tmp_name']);
		} else {
			$imageurl = selectDB("events", "`id` = '{$id}'");
			$_POST["background"] = $imageurl[0]["background"];
		}
		if( updateDB("events", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=Events");
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
	<h6 class="panel-title txt-dark"><?php echo direction("Invite Details","تفاصيل الدعوه") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="" enctype="multipart/form-data">
		<div class="row m-0">
            <div class="col-md-12">
			<label><?php echo direction("Category","القسم") ?></label>
				<select name="categoryId" class="form-control" required>
					<?php
                    if( $categories = selectDB("categories","`status` = '0' AND `hidden` = '1'") ){
                        for( $i = 0; $i < sizeof($categories); $i++ ){
                            $title = direction($categories[$i]["enTitle"],$categories[$i]["arTitle"]);
                            echo "<option value='{$categories[$i]["id"]}'>{$title}</option>";
                        }
                    }
                    ?>
				</select>
			</div>

			<div class="col-md-12">
			<label><?php echo direction("Title","العنوان") ?></label>
			<input type="text" name="title" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Date","التاريخ") ?></label>
			<input type="date" name="eventDate" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Time","الوقت") ?></label>
			<input type="time" name="eventTime" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Location","الموقع") ?></label>
			<input type="text" name="location" class="form-control" required>
			</div>

            <div class="col-md-4">
			<label><?php echo direction("Image","صورة") ?></label>
			<input type="file" name="background" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Soundtrack","صوت موسيقي") ?></label>
			<input type="text" name="sound" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Video","الفيديو") ?></label>
			<input type="text" name="video" class="form-control" required>
			</div>            <div class="col-md-12">
			<label><?php echo direction("Details","التفاصيل") ?></label>
			<textarea id="details" name="details" class="tinymce"></textarea>
			</div>

            <div class="col-md-12">
			<label><?php echo direction("Terms","الشروط") ?></label>
			<textarea id="terms" name="terms" class="tinymce"></textarea>
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
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("List of Events","قائمة المناسبات") ?></h6>
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
		<th><?php echo direction("No.","الرقم") ?></th>
		<th><?php echo direction("Title","العنوان") ?></th>
		<th><?php echo direction("Event Date","تاريخ المناسبة") ?></th>
		<th><?php echo direction("Event Time","وقت المناسبة") ?></th>
		<th class="text-nowrap"><?php echo direction("Actions","الخيارات") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
        $joinData = array(
            "select" => ["t.*","t1.enTitle as enTitle","t1.arTitle as arTitle"],
            "join" => ["categories"],
            "on" => ["t.categoryId = t1.id"]
        );
		if( $events = selectJoinDB("events",$joinData,"t.status = '0'") ){
			for( $i = 0; $i < sizeof($events); $i++ ){
				$counter = $i + 1;
				?>
				<tr>
				<td><?php echo $counter ?></td>
				<td id="title<?php echo $events[$i]["id"]?>" ><?php echo $events[$i]["title"] ?></td>
				<td id="eventDate<?php echo $events[$i]["id"]?>" ><?php echo $events[$i]["eventDate"] ?></td>
				<td id="eventTime<?php echo $events[$i]["id"]?>" ><?php echo $events[$i]["eventTime"] ?></td>
				<td class="text-nowrap">
					<label id="details<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["details"]?></label>
					<label id="terms<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["terms"]?></label>
					<label id="video<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["video"]?></label>
					<label id="sound<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["sound"]?></label>
					<label id="background<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["background"]?></label>
					<label id="categoryId<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["categoryId"]?></label>
					<label id="location<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["location"]?></label>
					<a href="<?php echo "?v=Invitees&inviteId={$events[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Invitees","الدعوات") ?>"><i class="mr-25 fa fa-users text-primary"></i>
					</a>
					<a id="<?php echo $events[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i>
					</a>
					<a href="<?php echo "?v={$_GET["v"]}&delId={$events[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>" onclick="return confirm('Delete entry?')" ><i class="fa fa-close text-danger"></i>
					</a>			
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
	$(document).on("click",".edit", function(){
		var id = $(this).attr("id");
		$("input[name=title]").val($("#title"+id).html()).focus();
		$("input[name=eventDate]").val($("#eventDate"+id).html());
		$("input[name=eventTime]").val($("#eventTime"+id).html());
		$("input[name=location]").val($("#location"+id).html());
		$("input[name=video]").val($("#video"+id).html());
		$("input[name=sound]").val($("#sound"+id).html());
		$("input[name=background]").val($("#background"+id).html());
		// make upload not required
		$("input[name=background]").removeAttr("required");
		$("select[name=categoreyId]").val($("#categoreyId"+id).html());
		$("input[name=update]").val(id);
		
		// Set TinyMCE content with a small delay to ensure editors are ready
		setTimeout(function() {
			var detailsEditor = tinymce.get('details');
			var termsEditor = tinymce.get('terms');
			
			if (detailsEditor) {
				detailsEditor.setContent($("#details"+id).html());
			}
			if (termsEditor) {
				termsEditor.setContent($("#terms"+id).html());
			}
		}, 100);
	})
</script>

<!-- Tinymce JavaScript -->
<script src="../vendors/bower_components/tinymce/tinymce.min.js"></script>
					
<!-- Tinymce Wysuhtml5 Init JavaScript -->
<script src="dist/js/tinymce-data.js"></script>