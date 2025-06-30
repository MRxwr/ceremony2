<?php 
// Function to directly upload images to local logos folder (as fallback)
function uploadGalleryImageToLogos($tmpName, $fileName) {
    // Generate a unique filename
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = 'gallery_' . uniqid() . '.' . $ext;
    $targetPath = "../logos/" . $newFileName;
    
    // Move the file to the logos directory
    if (move_uploaded_file($tmpName, $targetPath)) {
        return $newFileName;
    }
    
    return false;
}

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
		if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
			// For debugging
			error_log("Gallery upload detected with " . count($_FILES['gallery']['name']) . " files");
			
			foreach ($_FILES['gallery']['tmp_name'] as $key => $tmp_name) {
				if (is_uploaded_file($tmp_name)) {
					error_log("Processing file: " . $_FILES['gallery']['name'][$key]);
					
					// Try the main upload function first
					$uploadedImage = uploadImageBannerFreeImageHost($tmp_name);
					
					// If main upload fails, try the fallback
					if (!$uploadedImage) {
						error_log("Main upload failed, trying fallback");
						$uploadedImage = uploadGalleryImageToLogos($tmp_name, $_FILES['gallery']['name'][$key]);
					}
					
					error_log("Upload result: " . ($uploadedImage ? $uploadedImage : "failed"));
					
					if ($uploadedImage) {
						$galleryImages[] = $uploadedImage;
					}
				}
			}
			
			error_log("Total images processed: " . count($galleryImages));
		}
		
		// If galleryData has content (from edit mode), use that instead
		if (!empty($_POST['galleryData'])) {
			$_POST["gallery"] = $_POST['galleryData'];
			error_log("Using galleryData: " . $_POST['galleryData']);
		} else {
			$_POST["gallery"] = !empty($galleryImages) ? json_encode($galleryImages) : "[]";
			error_log("Using new uploads: " . $_POST["gallery"]);
		}
		
		// Remove the array input from POST
		unset($_POST['galleryData']);
		
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
		
		// Handle gallery images
		$existingGallery = array();
		$currentEvent = selectDB("events", "`id` = '{$id}'");
		
		// Parse existing gallery if it exists
		if (!empty($currentEvent[0]["gallery"])) {
			$existingGallery = json_decode($currentEvent[0]["gallery"], true);
			if (!is_array($existingGallery)) {
				$existingGallery = array();
			}
			error_log("Existing gallery has " . count($existingGallery) . " images");
		}
		
		// If galleryData field is set (from edit/delete operations), use that
		if (!empty($_POST['galleryData'])) {
			$_POST["gallery"] = $_POST['galleryData'];
			error_log("Using galleryData in update: " . $_POST['galleryData']);
		} 
		// If new files are uploaded, add them to existing gallery
		else if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
			error_log("Gallery update detected with " . count($_FILES['gallery']['name']) . " files");
			$newImages = array();
			foreach ($_FILES['gallery']['tmp_name'] as $key => $tmp_name) {
				if (is_uploaded_file($tmp_name)) {
					error_log("Processing update file: " . $_FILES['gallery']['name'][$key]);
					
					// Try the main upload function first
					$uploadedImage = uploadImageBannerFreeImageHost($tmp_name);
					
					// If main upload fails, try the fallback
					if (!$uploadedImage) {
						error_log("Main upload failed, trying fallback for update");
						$uploadedImage = uploadGalleryImageToLogos($tmp_name, $_FILES['gallery']['name'][$key]);
					}
					
					error_log("Upload result: " . ($uploadedImage ? $uploadedImage : "failed"));
					
					if ($uploadedImage) {
						$newImages[] = $uploadedImage;
					}
				}
			}
			
			error_log("New images count: " . count($newImages));
			
			// Merge existing and new images
			$existingGallery = array_merge($existingGallery, $newImages);
			$_POST["gallery"] = json_encode($existingGallery);
			error_log("Merged gallery (update): " . $_POST["gallery"]);
		} 
		// Otherwise keep existing gallery
		else {
			$_POST["gallery"] = $currentEvent[0]["gallery"];
			error_log("Keeping existing gallery: " . $_POST["gallery"]);
		}
		
		// Remove the array input from POST
		unset($_POST['galleryData']);
		
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

			<div class="col-md-4">
			<img src="" style="height:250px; width:250px; border-radius: 10px; margin-top: 10px; display:none" id="whatsappImagePreview" alt="<?php echo direction("WhatsApp Image","صورة الواتساب") ?>">
			</div>

			<div class="col-md-4">
			<label><?php echo direction("Gallery Images","صور المعرض") ?></label>
			<input class="form-control" type="file" name="gallery[]" multiple>
			<input type="hidden" name="galleryData" id="galleryData" value="">
			</div>

			<div class="col-md-8">
			<label><?php echo direction("Gallery Preview","معاينة المعرض") ?></label>
			<div class="row" id="galleryPreview"></div>
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
					<label id="gallery<?php echo $events[$i]["id"]?>" style="display:none"><?php echo $events[$i]["gallery"]?></label>
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
// Function to display gallery images
function displayGalleryImages(galleryData) {
	var galleryPreview = $("#galleryPreview");
	galleryPreview.empty();
	
	console.log("Displaying gallery data:", galleryData);
	
	if (galleryData && galleryData.length > 0) {
		galleryData.forEach(function(image, index) {
			var imageCol = $('<div class="col-md-2 col-sm-3 mb-3 gallery-item">');
			var imageContainer = $('<div class="image-container position-relative">');
			
			// Create image element with proper path handling
			var imgSrc = image;
			// If image doesn't already have a path prefix, add the logos path
			if (imgSrc.indexOf('http://') !== 0 && imgSrc.indexOf('https://') !== 0 && imgSrc.indexOf('../logos/') !== 0) {
				imgSrc = '../logos/' + imgSrc;
			}
			
			var img = $('<img>')
				.attr('src', imgSrc)
				.attr('alt', 'Gallery Image')
				.css({
					'width': '100%',
					'height': '100px',
					'object-fit': 'cover',
					'border-radius': '5px'
				});
			
			// Create delete button
			var deleteBtn = $('<button>')
				.attr('type', 'button')
				.addClass('btn btn-sm btn-danger delete-gallery-img')
				.attr('data-index', index)
				.css({
					'position': 'absolute',
					'top': '5px',
					'right': '5px',
					'padding': '2px 6px',
					'opacity': '0.8'
				})
				.html('<i class="fa fa-times"></i>');
			
			// Append elements
			imageContainer.append(img);
			imageContainer.append(deleteBtn);
			imageCol.append(imageContainer);
			galleryPreview.append(imageCol);
		});
	}
	
	// Update hidden input with current gallery data
	$("#galleryData").val(JSON.stringify(galleryData));
	console.log("Updated galleryData input:", $("#galleryData").val());
}

// Handle deletion of gallery images
$(document).on('click', '.delete-gallery-img', function(e) {
	e.preventDefault();
	var index = $(this).data('index');
	var galleryData = [];
	
	// Get current gallery data
	try {
		galleryData = JSON.parse($("#galleryData").val() || '[]');
	} catch (error) {
		galleryData = [];
	}
	
	// Remove the image at the specified index
	if (galleryData.length > index) {
		galleryData.splice(index, 1);
	}
	
	// Update display and hidden input
	displayGalleryImages(galleryData);
});

// Handle file selection for gallery
$('input[name="gallery[]"]').change(function() {
	// This just shows a message about upload readiness
	if (this.files.length > 0) {
		var count = this.files.length;
		var message = count + ' ' + (count == 1 ? '<?php echo direction("image", "صورة") ?>' : '<?php echo direction("images", "صور") ?>') + ' <?php echo direction("ready to upload", "جاهزة للرفع") ?>';
		alert(message);
	}
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
		$("input[name=whatsappCaption]").val($("#whatsappCaption"+id).html());
		$("input[name=whatsappImage]").prop("required", false);
		// Show WhatsApp image preview if available
		var whatsappImage = $("#whatsappImage"+id).html();
		if (whatsappImage != "") {
			$("#whatsappImagePreview").attr("src", "../logos/"+whatsappImage);
			$("#whatsappImagePreview").attr("style", "height:250px; width:250px; border-radius: 10px; margin-top: 10px; display:block");
		}
		
		// Load gallery images
		var galleryData = [];
		try {
			var galleryJson = $("#gallery"+id).html();
			console.log("Raw gallery JSON:", galleryJson);
			
			if (galleryJson && galleryJson.trim() !== '') {
				// Try to parse as JSON
				try {
					galleryData = JSON.parse(galleryJson);
				} catch (jsonError) {
					console.error("JSON parse error:", jsonError);
					// If it's not valid JSON but contains filenames, try to extract them
					if (galleryJson.indexOf(',') > -1) {
						galleryData = galleryJson.split(',').map(function(item) {
							return item.trim().replace(/['"]/g, '');
						});
					} else if (galleryJson.trim() !== '[]') {
						// Single filename
						galleryData = [galleryJson.trim().replace(/['"]/g, '')];
					}
				}
			}
		} catch (error) {
			console.error("Error processing gallery data:", error);
			galleryData = [];
		}
		
		console.log("Processed gallery data:", galleryData);
		
		// Display gallery images
		displayGalleryImages(galleryData);

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
<script>
// Add form submission event handler
$('form').on('submit', function(e) {
    // Ensure galleryData is populated if there are images in the preview
    if ($('#galleryPreview').children().length > 0 && !$('#galleryData').val()) {
        // Collect visible gallery images if galleryData is empty
        var visibleGallery = [];
        $('.gallery-item img').each(function() {
            var src = $(this).attr('src');
            // Extract just the filename from the path
            var filename = src.split('/').pop();
            visibleGallery.push(filename);
        });
        
        if (visibleGallery.length > 0) {
            $('#galleryData').val(JSON.stringify(visibleGallery));
            console.log("Auto-populated galleryData:", $('#galleryData').val());
        }
    }
    
    // For debugging
    console.log("Form submitted with galleryData:", $('#galleryData').val());
});
</script>