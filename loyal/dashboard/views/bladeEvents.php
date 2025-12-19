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
        if (is_uploaded_file($_FILES['whatsappImage']['tmp_name'])) {
			$_POST["whatsappImage"] = uploadImageBannerFreeImageHost($_FILES['whatsappImage']['tmp_name']);
		} else {
			$_POST["whatsappImage"] = "";
		}
        
        // Handle gallery images
        $galleryImages = array();
        if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['tmp_name'][0])) {
            for ($i = 0; $i < count($_FILES['gallery']['tmp_name']); $i++) {
                if (is_uploaded_file($_FILES['gallery']['tmp_name'][$i])) {
                    $uploadedImage = uploadImageBannerFreeImageHost($_FILES['gallery']['tmp_name'][$i]);
                    if (!empty($uploadedImage)) {
                        $galleryImages[] = $uploadedImage;
                    }
                }
            }
        }
        $_POST["gallery"] = json_encode($galleryImages);
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
		}else{
			$imageurl = selectDB("events", "`id` = '{$id}'");
			$_POST["background"] = $imageurl[0]["background"];
		}
        if (is_uploaded_file($_FILES['whatsappImage']['tmp_name'])) {
			$_POST["whatsappImage"] = uploadImageBannerFreeImageHost($_FILES['whatsappImage']['tmp_name']);
		}else{
			$imageurl = selectDB("events", "`id` = '{$id}'");
			$_POST["whatsappImage"] = $imageurl[0]["whatsappImage"];
		}
        
        // Handle gallery images for update
        $existingGallery = array();
        if (isset($_POST['existing_gallery'])) {
            $existingGallery = json_decode($_POST['existing_gallery'], true);
            if (!is_array($existingGallery)) {
                $existingGallery = array();
            }
        }
        
        // Add new gallery images
        if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['tmp_name'][0])) {
            for ($i = 0; $i < count($_FILES['gallery']['tmp_name']); $i++) {
                if (is_uploaded_file($_FILES['gallery']['tmp_name'][$i])) {
                    $uploadedImage = uploadImageBannerFreeImageHost($_FILES['gallery']['tmp_name'][$i]);
                    if (!empty($uploadedImage)) {
                        $existingGallery[] = $uploadedImage;
                    }
                }
            }
        }
        $_POST["gallery"] = json_encode($existingGallery);
		unset($_POST["existing_gallery"]); // Remove this field as it's not needed in the database
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
            <div class="col-md-6">
			<label><?php echo direction("Language","اللغة") ?></label>
				<select name="language" class="form-control" required>
					<option value="ltr" <?php echo (isset($_POST["language"]) && $_POST["language"] == "en") ? "selected" : "" ?>>English</option>
					<option value="rtl" <?php echo (isset($_POST["language"]) && $_POST["language"] == "ar") ? "selected" : "" ?>>العربية</option>
				</select>
			</div>

			<div class="col-md-6">
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

			<div class="col-md-4">
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
			<label><?php echo direction("Venue Name","اسم الموقع") ?></label>
			<input type="text" name="venueName" class="form-control" required>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Venue Address","عنوان الموقع") ?></label>
			<input type="text" name="venueAddress" class="form-control" required>
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
			</div>

			<div class="col-md-6">
			<label><?php echo direction("Details","التفاصيل") ?></label>
			<textarea id="details" name="details" class="tinymce"></textarea>
			</div>

            <div class="col-md-6">
			<label><?php echo direction("Terms","الشروط") ?></label>
			<textarea id="terms" name="terms" class="tinymce"></textarea>
			</div>

			<div class="col-md-4">
			<label><?php echo direction("WhatsApp Image","صورة الواتساب") ?></label>
			<input class="form-control" type="file" name="whatsappImage" >
			<img src="" style="height:250p x; width:250px; border-radius: 10px; margin-top: 10px; display:none" id="whatsappImagePreview" alt="<?php echo direction("WhatsApp Image","صورة الواتساب") ?>">
			</div>

			<div class="col-md-8">
			<label><?php echo direction("WhatsApp Caption","وصف الواتساب") ?></label>
			<input class="form-control" name="whatsappCaption" placeholder="<?php echo direction("Caption[ for new line use \n]","وصف [ لاستخدام سطر جديد استخدم \n]") ?>">
			</div>

			<div class="col-md-12">
			<label><?php echo direction("Gallery Images","صور المعرض") ?></label>
			<input class="form-control" type="file" name="gallery[]" multiple accept="image/*" id="galleryInput">
			<small class="text-muted"><?php echo direction("Select multiple images for the gallery","اختر عدة صور للمعرض") ?></small>
			</div>

			<div class="col-md-12" id="galleryPreview" style="margin-top: 10px;">
			<!-- Gallery preview will be inserted here -->
			</div>

			<div class="col-md-4">
			<img src="" style="height:250p x; width:250px; border-radius: 10px; margin-top: 10px; display:none" id="whatsappImagePreview" alt="<?php echo direction("WhatsApp Image","صورة الواتساب") ?>">
			</div>

			<div class="col-md-8">
			</div>
			
			<div class="col-md-12" style="margin-top:10px">
			<input type="submit" class="btn btn-primary" value="<?php echo direction("Submit","أرسل") ?>">
			<input type="hidden" name="update" value="0">
			<input type="hidden" name="existing_gallery" id="existing_gallery" value="">
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
					<label id="venueName<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["venueName"]?></label>
					<label id="venueAddress<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["venueAddress"]?></label>
					<label id="whatsappCaption<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["whatsappCaption"]?></label>
					<label id="whatsappImage<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["whatsappImage"]?></label>
					<label id="language<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["language"]?></label>
					<label id="gallery<?php echo $events[$i]["id"]?>" style="display:none"><?php echo htmlspecialchars($events[$i]["gallery"])?></label>
					<a href="<?php echo "/{$events[$i]["code"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("View Event","عرض المناسبة") ?>" target="_blank"><i class="mr-25 fa fa-eye text-black"></i></a>
					<a href="<?php echo "?v=Invitees&eventId={$events[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Invitees","الدعوات") ?>"><i class="mr-25 fa fa-users text-primary"></i></a>
					<a id="<?php echo $events[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-gray m-r-10"></i></a>
					<a href="<?php echo "?v={$_GET["v"]}&delId={$events[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>" onclick="return confirm('Delete entry?')" ><i class="fa fa-close text-danger"></i></a>			
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
	var currentGallery = [];
	
	// Function to display gallery preview
	function displayGalleryPreview() {
		var previewContainer = $("#galleryPreview");
		previewContainer.empty();
		
		if (currentGallery.length > 0) {
			var galleryHtml = '<div class="row" style="margin-top: 10px;">';
			galleryHtml += '<div class="col-md-12"><h6><?php echo direction("Current Gallery Images","صور المعرض الحالية") ?></h6></div>';
			for (var i = 0; i < currentGallery.length; i++) {
				galleryHtml += '<div class="col-md-2 gallery-item" data-index="' + i + '" style="margin-bottom: 10px;">';
				galleryHtml += '<div style="position: relative; border: 2px solid #ddd; border-radius: 5px; padding: 5px;">';
				galleryHtml += '<img src="../logos/' + currentGallery[i] + '" style="width: 100%; height: 120px; object-fit: cover; border-radius: 5px;">';
				galleryHtml += '<button type="button" class="btn btn-danger btn-xs remove-gallery-item" style="position: absolute; top: 0px; right: 0px; padding: 2px 6px; font-size: 12px;">';
				galleryHtml += '<i class="fa fa-times"></i>';
				galleryHtml += '</button>';
				galleryHtml += '</div>';
				galleryHtml += '</div>';
			}
			galleryHtml += '</div>';
			previewContainer.html(galleryHtml);
		}
		
		// Update hidden field
		$("#existing_gallery").val(JSON.stringify(currentGallery));
	}
	
	// Handle new file selection
	$("#galleryInput").on("change", function() {
		var files = this.files;
		if (files.length > 0) {
			$("#newGalleryPreview").remove(); // Remove previous preview
			var previewHtml = '<div class="row" id="newGalleryPreview" style="margin-top: 10px;">';
			previewHtml += '<div class="col-md-12"><h6><?php echo direction("New Images to Upload","الصور الجديدة للرفع") ?></h6></div>';
			
			var filesLoaded = 0;
			for (var i = 0; i < files.length; i++) {
				(function(index) {
					var reader = new FileReader();
					reader.onload = function(e) {
						previewHtml += '<div class="col-md-2" style="margin-bottom: 10px;">';
						previewHtml += '<div style="border: 2px solid #5cb85c; border-radius: 5px; padding: 5px;">';
						previewHtml += '<img src="' + e.target.result + '" style="width: 100%; height: 120px; object-fit: cover; border-radius: 5px;">';
						previewHtml += '</div>';
						previewHtml += '</div>';
						
						filesLoaded++;
						if (filesLoaded === files.length) {
							previewHtml += '</div>';
							$("#galleryPreview").append(previewHtml);
						}
					};
					reader.readAsDataURL(files[index]);
				})(i);
			}
		}
	});
	
	// Handle gallery item removal
	$(document).on("click", ".remove-gallery-item", function() {
		var index = $(this).closest('.gallery-item').data('index');
		currentGallery.splice(index, 1);
		displayGalleryPreview();
	});

	$(document).on("click",".edit", function(){
		var id = $(this).attr("id");
		$("input[name=update]").val(id);
		$("input[name=title]").val($("#title"+id).html()).focus();
		$("input[name=eventDate]").val($("#eventDate"+id).html());
		$("input[name=eventTime]").val($("#eventTime"+id).html());
		$("input[name=location]").val($("#location"+id).html());
		$("input[name=video]").val($("#video"+id).html());
		$("input[name=sound]").val($("#sound"+id).html());
		$("input[name=venueName]").val($("#venueName"+id).html());
		$("input[name=venueAddress]").val($("#venueAddress"+id).html());
		$("input[name=background]").prop("required", false);
		$("select[name=categoryId]").val($("#categoryId"+id).html());
		$("select[name=language]").val($("#language"+id).html());
		$("input[name=whatsappCaption]").val($("#whatsappCaption"+id).html());
		$("input[name=whatsappImage]").prop("required", false);
		
		// Clear gallery input and preview
		$("#galleryInput").val('');
		$("#newGalleryPreview").remove();
		
		// Load existing gallery
		var galleryData = $("#gallery"+id).html();
		if (galleryData && galleryData !== 'null' && galleryData !== '' && galleryData !== '[]') {
			try {
				currentGallery = JSON.parse(galleryData);
				if (!Array.isArray(currentGallery)) {
					currentGallery = [];
				}
			} catch (e) {
				currentGallery = [];
			}
		} else {
			currentGallery = [];
		}
		displayGalleryPreview();
		
		// Show WhatsApp image preview if available
		var whatsappImage = $("#whatsappImage"+id).html();
		if (whatsappImage != "") {
			$("#whatsappImagePreview").attr("src", "../logos/"+whatsappImage);
			$("#whatsappImagePreview").attr("style", "height:250px; width:250px; border-radius: 10px; margin-top: 10px; display:block");
		} else {
			$("#whatsappImagePreview").attr("style", "display:none");
		}

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
	
	// Reset form when creating new event
	function resetForm() {
		currentGallery = [];
		$("#galleryPreview").empty();
		$("#existing_gallery").val('');
		$("#galleryInput").val('');
		$("#newGalleryPreview").remove();
		$("#whatsappImagePreview").attr("style", "display:none");
		$("input[name=update]").val(0);
	}
	
	// Add reset functionality to form
	$('form').on('reset', function() {
		setTimeout(resetForm, 10);
	});
	
	// Clear form when page loads for new entries
	$(document).ready(function() {
		if ($("input[name=update]").val() == 0) {
			resetForm();
		}
	});
</script>