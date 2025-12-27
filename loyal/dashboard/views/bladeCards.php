<?php 
if( isset($_GET["hide"]) && !empty($_GET["hide"]) ){
	if( updateDB("cards",array('hidden'=> '2'),"`id` = '{$_GET["hide"]}'") ){
		header("LOCATION: ?v=Cards");
	}
}

if( isset($_GET["show"]) && !empty($_GET["show"]) ){
	if( updateDB("cards",array('hidden'=> '1'),"`id` = '{$_GET["show"]}'") ){
		header("LOCATION: ?v=Cards");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB("cards",array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=Cards");
	}
}

if( isset($_POST["arTitle"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	$_POST["enTitle"] = urlencode($_POST["enTitle"]);
	$_POST["arTitle"] = urlencode($_POST["arTitle"]);
	$_POST["enPolicy"] = urlencode($_POST["enPolicy"]);
	$_POST["arPolicy"] = urlencode($_POST["arPolicy"]);
    $_POST["numberOfItems"] = json_encode($_POST["numberOfItems"]);
    $_POST["totalPoints"] = json_encode($_POST["totalPoints"]);
	if ( $id == 0 ){
		if (is_uploaded_file($_FILES['logo']['tmp_name'])) {
			$_POST["logo"] = uploadImageBannerFreeImageHost($_FILES['logo']['tmp_name'], "cards");
		} else {
			$_POST["logo"] = "";
		}
		
		if (is_uploaded_file($_FILES['image']['tmp_name'])) {
			$_POST["image"] = uploadImageBannerFreeImageHost($_FILES['image']['tmp_name'], "cards");
		} else {
			$_POST["image"] = "";
		}

        if (is_uploaded_file($_FILES['stampedImage']['tmp_name'])) {
			$_POST["stampedImage"] = uploadImageBannerFreeImageHost($_FILES['stampedImage']['tmp_name'], "cards");
		} else {
			$_POST["stampedImage"] = "";
		}
		
		
		if( insertDB("cards", $_POST) ){
			header("LOCATION: ?v=Cards");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if (is_uploaded_file($_FILES['logo']['tmp_name'])) {
			$_POST["logo"] = uploadImageBannerFreeImageHost($_FILES['logo']['tmp_name'], "cards");
		} else {
			$logo = selectDB("cards", "`id` = '{$id}'");
			$_POST["logo"] = $logo[0]["logo"];
		}
		
		if (is_uploaded_file($_FILES['image']['tmp_name'])) {
			$_POST["image"] = uploadImageBannerFreeImageHost($_FILES['image']['tmp_name'], "cards");
		} else {
			$header = selectDB("cards", "`id` = '{$id}'");
			$_POST["image"] = $header[0]["image"];
		}

        if (is_uploaded_file($_FILES['stampedImage']['tmp_name'])) {
            $_POST["stampedImage"] = uploadImageBannerFreeImageHost($_FILES['stampedImage']['tmp_name'], "cards");
        } else {
            $stampedImage = selectDB("cards", "`id` = '{$id}'");
            $_POST["stampedImage"] = $stampedImage[0]["stampedImage"];
        }
		
		if( updateDB("cards", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=Cards");
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
	<h6 class="panel-title txt-dark"><?php echo direction("Card Details","تفاصيل البطاقة") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="" enctype="multipart/form-data">
		<div class="row m-0">

			<div class="col-md-6">
				<label><?php echo direction("Select Store","اختر المتجر") ?></label>
				<select name="storeId" class="form-control" required>
					<option value="" disabled selected><?php echo direction("No Store","بدون متجر") ?></option>
					<?php 
					if( $stores = selectDB("stores","`status` = '0' ORDER BY `rank` ASC") ){
						for( $i = 0; $i < sizeof($stores); $i++ ){
							?>
							<option value="<?php echo $stores[$i]["id"] ?>"><?php echo urldecode(direction($stores[$i]["enTitle"], $stores[$i]["arTitle"])) ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>

            <div class="col-md-6">
				<label><?php echo direction("Card Type","نوع البطاقة") ?></label>
				<select id="cardTypeSelect" name="cardTypeId" class="form-control" required>
					<option value="" disabled selected><?php echo direction("No card Type","بدون نوع بطاقة") ?></option>
					<?php 
					if( $cardTypes = selectDB("card_types","`status` = '0' ORDER BY `rank` ASC") ){
						for( $i = 0; $i < sizeof($cardTypes); $i++ ){
							?>
							<option value="<?php echo $cardTypes[$i]["id"] ?>" data-enTitle="<?php echo $cardTypes[$i]["enTitle"] ?>" data-arTitle="<?php echo $cardTypes[$i]["arTitle"] ?>"><?php echo urldecode(direction($cardTypes[$i]["enTitle"], $cardTypes[$i]["arTitle"])) ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>

			<div class="col-md-6">
				<label><?php echo direction("English Title","العنوان بالإنجليزي") ?></label>
				<input type="text" name="enTitle" class="form-control" required>
			</div>

			<div class="col-md-6">
				<label><?php echo direction("Arabic Title","العنوان بالعربي") ?></label>
				<input type="text" name="arTitle" class="form-control" required>
			</div>

			<div class="col-md-4 numberOfItems-field">
				<label><?php echo direction("Number of Items - Level 1","عدد العناصر - مستوى 1") ?></label>
				<input type="number" name="numberOfItems[0]" class="form-control numberOfItems-input" required value="10">
			</div>

			<div class="col-md-4 numberOfItems-field">
				<label><?php echo direction("Number of Items - Level 2","عدد العناصر - مستوى 2") ?></label>
				<input type="number" name="numberOfItems[1]" class="form-control numberOfItems-input" required value="7">
			</div>

			<div class="col-md-4 numberOfItems-field">
				<label><?php echo direction("Number of Items - Level 3","عدد العناصر - مستوى 3") ?></label>
				<input type="number" name="numberOfItems[2]" class="form-control numberOfItems-input" required value="5">
			</div>

			<div class="col-md-4 totalPoints-field">
				<label><?php echo direction("Total Points - Level 1","إجمالي النقاط - مستوى 1") ?></label>
				<input type="number" name="totalPoints[0]" class="form-control totalPoints-input" required value="10">
			</div>

			<div class="col-md-4 totalPoints-field">
				<label><?php echo direction("Total Points - Level 2","إجمالي النقاط - مستوى 2") ?></label>
				<input type="number" name="totalPoints[1]" class="form-control totalPoints-input" required value="7">
			</div>

			<div class="col-md-4 totalPoints-field">
				<label><?php echo direction("Total Points - Level 3","إجمالي النقاط - مستوى 3") ?></label>
				<input type="number" name="totalPoints[2]" class="form-control totalPoints-input" required value="5">
			</div>

			<div class="col-md-6">
				<label><?php echo direction("English Policy","السياسة بالإنجليزي") ?></label>
				<textarea id="enDetails" name="enPolicy" class="tinymce"></textarea>
			</div>

            <div class="col-md-6">
				<label><?php echo direction("Arabic Policy","السياسة بالعربي") ?></label>
				<textarea id="arDetails" name="arPolicy" class="tinymce"></textarea>
			</div>

			<div class="col-md-4">
				<label><?php echo direction("Logo","الشعار") ?></label>
				<input type="file" name="logo" class="form-control" required>
			</div>
			
			<div class="col-md-4 stamp-image-field">
				<label><?php echo direction("Item Image Unstamped","صورة غير مختومة") ?></label>
				<input type="file" name="image" class="form-control stamp-image-input" required>
			</div>

            <div class="col-md-4 stamp-image-field">
				<label><?php echo direction("Item Stamped Image","الصورة المختومة") ?></label>
				<input type="file" name="stampedImage" class="form-control stamp-image-input" required>
			</div>
			
			<div id="images" style="margin-top: 10px; display:none">
				<div class="col-md-4">
					<img id="logoImg" src="" style="width:250px;height:250px">
				</div>
				
				<div class="col-md-4">
					<img id="imageImg" src="" style="width:250px;height:250px">
				</div>

                <div class="col-md-4">
					<img id="stampedImageImg" src="" style="width:250px;height:250px">
				</div>
			</div>
			
			<div class="col-md-6" style="margin-top:10px">
				<input type="submit" class="btn btn-primary" value="<?php echo direction("Submit","أرسل") ?>">
				<input type="hidden" name="update" value="0">
				<input type="hidden" name="hidden" value="1">
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
<h6 class="panel-title txt-dark"><?php echo direction("List of Cards","قائمة البطاقات") ?></h6>
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
			<th><?php echo direction("Store","المتجر") ?></th>
			<th><?php echo direction("Card Type","نوع البطاقة") ?></th>
			<th><?php echo direction("English Title","العنوان بالإنجليزي") ?></th>
			<th><?php echo direction("Arabic Title","العنوان بالعربي") ?></th>
			<th class="text-nowrap"><?php echo direction("Action","الإجراء") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
        $joinData = array(
            "select" => ["t.*","t1.enTitle as cardTypeEnTitle","t1.arTitle as cardTypeArTitle","t2.enTitle as storeEnTitle","t2.arTitle as storeArTitle"],
            "join" => ["card_types","stores"],
            "on" => ["t.cardTypeId = t1.id", "t.storeId = t2.id"]
        );
		if( $cards = selectJoinDB("cards", $joinData, " t.status = '0' ORDER BY t.id ASC") ){
			for( $i = 0; $i < sizeof($cards); $i++ ){
				$counter = $i + 1;
			if ( $cards[$i]["hidden"] == 2 ){
				$icon = "fa fa-eye";
				$link = "?v={$_GET["v"]}&show={$cards[$i]["id"]}";
				$hide = direction("Show","إظهار");
			}else{
				$icon = "fa fa-eye-slash";
				$link = "?v={$_GET["v"]}&hide={$cards[$i]["id"]}";
				$hide = direction("Hide","إخفاء");
			}
			?>
			<tr>
                <td><?php echo $counter ?></td>
				<td><?php echo urldecode(direction($cards[$i]["storeEnTitle"], $cards[$i]["storeArTitle"])) ?></td>
				<td><?php echo urldecode(direction($cards[$i]["cardTypeEnTitle"], $cards[$i]["cardTypeArTitle"])) ?></td>
				<td id="enTitle<?php echo $cards[$i]["id"]?>" ><?php echo urldecode($cards[$i]["enTitle"]) ?></td>
				<td id="arTitle<?php echo $cards[$i]["id"]?>" ><?php echo urldecode($cards[$i]["arTitle"]) ?></td>
				<td class="text-nowrap">
					<a id="<?php echo $cards[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i></a>
					<a href="<?php echo $link ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo $hide ?>"> <i class="<?php echo $icon ?> text-inverse m-r-10"></i></a>
					<a href="<?php echo "?v={$_GET["v"]}&delId={$cards[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>"><i class="fa fa-close text-danger"></i></a>
				<div style="display:none">
					<label id="hidden<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["hidden"] ?></label>
					<label id="logo<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["logo"] ?></label>
					<label id="image<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["image"] ?></label>
					<label id="stampedImage<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["stampedImage"] ?></label>
					<label id="storeId<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["storeId"] ?></label>
					<label id="cardTypeId<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["cardTypeId"] ?></label>
					<label id="enPolicy<?php echo $cards[$i]["id"]?>"><?php echo urldecode(htmlspecialchars($cards[$i]["enPolicy"])) ?></label>
					<label id="arPolicy<?php echo $cards[$i]["id"]?>"><?php echo urldecode(htmlspecialchars($cards[$i]["arPolicy"])) ?></label>
					<label id="numberOfItems<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["numberOfItems"] ?></label>
					<label id="totalPoints<?php echo $cards[$i]["id"]?>"><?php echo $cards[$i]["totalPoints"] ?></label>
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
	// Function to toggle fields based on card type
	function toggleCardTypeFields() {
		var selectedOption = $("#cardTypeSelect option:selected");
		var enTitle = selectedOption.data("entitle");
		var arTitle = selectedOption.data("artitle");
		
		// Check if it's Stamp or Points (case insensitive)
		var isStamp = (enTitle && enTitle.toLowerCase().includes("stamp")) || 
		              (arTitle && arTitle.includes("ختم"));
		var isPoints = (enTitle && enTitle.toLowerCase().includes("point")) || 
		               (arTitle && arTitle.includes("نقاط"));
		
		if (isStamp) {
			// Show stamp-related fields
			$(".numberOfItems-field").show();
			$(".numberOfItems-input").prop("required", true);
			$(".stamp-image-field").show();
			$(".stamp-image-input").prop("required", function() {
				return $("input[name=update]").val() == "0";
			});
			
			// Hide and reset points fields
			$(".totalPoints-field").hide();
			$(".totalPoints-input").prop("required", false).val(0);
		} else if (isPoints) {
			// Show points-related fields
			$(".totalPoints-field").show();
			$(".totalPoints-input").prop("required", true);
			
			// Hide and reset stamp fields
			$(".numberOfItems-field").hide();
			$(".numberOfItems-input").prop("required", false).val(0);
			$(".stamp-image-field").hide();
			$(".stamp-image-input").prop("required", false);
		} else {
			// Default: show all fields
			$(".numberOfItems-field, .totalPoints-field, .stamp-image-field").show();
			$(".numberOfItems-input, .totalPoints-input").prop("required", true);
			$(".stamp-image-input").prop("required", function() {
				return $("input[name=update]").val() == "0";
			});
		}
	}
	
	// Trigger on page load and on change
	$(document).ready(function() {
		$("#cardTypeSelect").on("change", toggleCardTypeFields);
	});

	$(document).on("click",".edit", function(){
		var id = $(this).attr("id");
		$("input[name=update]").val(id);

		$("input[type=file]").prop("required",false);
		$("input[name=enTitle]").val($("#enTitle"+id).html()).focus();
		$("input[name=arTitle]").val($("#arTitle"+id).html());
		$("select[name=hidden]").val($("#hidden"+id).html());
		$("select[name=categoryId]").val($("#categoryId"+id).html());
		$("select[name=storeId]").val($("#storeId"+id).html());
		$("select[name=cardTypeId]").val($("#cardTypeId"+id).html());
		$("#logoImg").attr("src","../storage/"+$("#logo"+id).html());
		$("#imageImg").attr("src","../storage/"+$("#image"+id).html());
		$("#stampedImageImg").attr("src","../storage/"+$("#stampedImage"+id).html());
        
		// Parse and populate numberOfItems array
		var numberOfItems = JSON.parse($("#numberOfItems"+id).html() || "[10,7,5]");
		$("input[name='numberOfItems[0]']").val(numberOfItems[0] || 10);
		$("input[name='numberOfItems[1]']").val(numberOfItems[1] || 7);
		$("input[name='numberOfItems[2]']").val(numberOfItems[2] || 5);
		
		// Parse and populate totalPoints array
		var totalPoints = JSON.parse($("#totalPoints"+id).html() || "[10,7,5]");
		$("input[name='totalPoints[0]']").val(totalPoints[0] || 10);
		$("input[name='totalPoints[1]']").val(totalPoints[1] || 7);
		$("input[name='totalPoints[2]']").val(totalPoints[2] || 5);
		
		$("#images").attr("style","margin-top:10px;display:block");
		
		// Trigger card type toggle after setting values
		toggleCardTypeFields();
		
		// Set TinyMCE content with a small delay to ensure editors are ready
		setTimeout(function() {
			var enPolicy = tinymce.get('enPolicy');
			var arPolicy = tinymce.get('arPolicy');
			
			if (enPolicy) {
				enPolicy.setContent($("#enPolicy"+id).html());
			}
			if (arPolicy) {
				arPolicy.setContent($("#arPolicy"+id).html());
			}
		}, 100);
	})
</script>