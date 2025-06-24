<?php 
if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('invitees',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=Invitees&inviteId={$_GET["inviteId"]}");
	}
}
if( isset($_GET["isDeclined"]) && !empty($_GET["isDeclined"]) ){
	if( updateDB('invitees',array('isConfirmed'=> '2'),"`id` = '{$_GET["isDeclined"]}'") ){
		header("LOCATION: ?v=Invitees&inviteId={$_GET["inviteId"]}");
	}
}
if( isset($_GET["isConfirmed"]) && !empty($_GET["isConfirmed"]) ){
	if( updateDB('invitees',array('isConfirmed'=> '1'),"`id` = '{$_GET["isConfirmed"]}'") ){
		header("LOCATION: ?v=Invitees&inviteId={$_GET["inviteId"]}");
	}
}

if( isset($_POST["name"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	if ( $id == 0 ){
		if( insertDB("invitees", $_POST) ){
			header("LOCATION: ?v=Invitees&inviteId={$_POST["inviteId"]}");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if( updateDB("invitees", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=Invitees&inviteId={$_POST["inviteId"]}");
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
	<h6 class="panel-title txt-dark"><?php echo direction("Invitee Details","تفاصيل المدعو") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="" enctype="multipart/form-data">
		<div class="row m-0">

			<div class="col-md-6">
			<label><?php echo direction("Name","الإسم") ?></label>
			<input type="text" name="name" class="form-control" required>
			</div>

            <div class="col-md-6">
			<label><?php echo direction("Attendees","الحضور") ?></label>
			<input type="number" step="1" min="1" name="attendees" class="form-control" required>
			</div>

            <div class="col-md-6">
			<label><?php echo direction("Country Code","كود الدولة") ?></label>
				<select name="countryCode" class="form-control" required>
					<?php
                    if( $countries = selectDB("cities","`status` = '0' GROUP BY `countryCode` ORDER BY `CountryName` ASC") ){
                        for( $i = 0; $i < sizeof($countries); $i++ ){
                            echo "<option value='{$countries[$i]["areaCode"]}'>{$countries[$i]["CountryName"]}</option>";
                        }
                    }
                    ?>
				</select>
			</div>

			<div class="col-md-6">
			<label><?php echo direction("Mobile","الهاتف") ?></label>
			<input type="number" min="0" step="1" name="mobile" class="form-control" required>
			</div>
			
			<div class="col-md-6" style="margin-top:10px">
			<input type="submit" class="btn btn-primary" value="<?php echo direction("Submit","أرسل") ?>">
			<input type="hidden" name="update" value="0">
			<input type="hidden" name="inviteId" value="<?php echo isset($_GET["id"]) ? $_GET["id"] : 0; ?>">
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
		<th><?php echo direction("Name","الإسم") ?></th>
        <th><?php echo direction("Attendees","الحضور") ?></th>
        <th><?php echo direction("Country Code","كود الدولة") ?></th>
        <th><?php echo direction("Mobile","الهاتف") ?></th>
        <th class="text-nowrap"><?php echo direction("Status","الحالة") ?></th>
		<th class="text-nowrap"><?php echo direction("Actions","الخيارات") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		if( $invitees = selectDB("invitees","`inviteId` = '{$_GET["inviteId"]}' AND `status` = '0' AND `hidden` = '0' ORDER BY `id` ASC") ){
			for( $i = 0; $i < sizeof($invitees); $i++ ){
				$counter = $i + 1;
                $status = ( $invitees[$i]["isConfirmed"] == 1 ) ? direction("Confirmed","مؤكد") : ( ($invitees[$i]["isConfirmed"] == 2) ? direction("Declined","مرفوض") : direction("Pending","قيد الانتظار") );
				?>
				<tr>
				<td><?php echo $counter ?></td>
				<td id="name<?php echo $invitees[$i]["id"]?>" ><?php echo $invitees[$i]["name"] ?></td>
				<td id="attendees<?php echo $invitees[$i]["id"]?>" ><?php echo $invitees[$i]["attendees"] ?></td>
				<td id="countryCode<?php echo $invitees[$i]["id"]?>" ><?php echo $invitees[$i]["countryCode"] ?></td>
				<td id="mobile<?php echo $invitees[$i]["id"]?>" ><?php echo $invitees[$i]["mobile"] ?></td>
                <td class="text-nowrap"><?php echo $status ?></td>
				<td class="text-nowrap">
                    <a href="<?php echo "?v={$_GET["v"]}&isConfirmed={$invitees[$i]["id"]}&inviteId={$invitees[$i]["inviteId"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Confirm","تاكيد") ?>" onclick="return confirm('are you sure you want to confirm this invitee?')" ><i class="mr-25 fa fa-check text-success"></i>
                    </a>
                    <a href="<?php echo "?v={$_GET["v"]}&isDeclined={$invitees[$i]["id"]}&inviteId={$invitees[$i]["inviteId"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Decline","رفض") ?>" onclick="return confirm('are you sure you want to decline this invitee?')" ><i class="mr-25 fa fa-close text-warning"></i>
					</a>
					<a id="<?php echo $invitees[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i>
					</a>
					<a href="<?php echo "?v={$_GET["v"]}&delId={$invitees[$i]["id"]}&inviteId={$invitees[$i]["inviteId"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>" onclick="return confirm('Delete entry?')" ><i class="fa fa-trash text-danger"></i>
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
		$("input[name=name]").val($("#name"+id).html()).focus();
        $("input[name=attendees]").val($("#attendees"+id).html());
        $("select[name=countryCode]").val($("#countryCode"+id).html());
        $("input[name=mobile]").val($("#mobile"+id).html());
        $("input[name=update]").val(id);
        $("input[name=inviteId]").val(id);
    });
</script>

<!-- Tinymce JavaScript -->
<script src="../vendors/bower_components/tinymce/tinymce.min.js"></script>
					
<!-- Tinymce Wysuhtml5 Init JavaScript -->
<script src="dist/js/tinymce-data.js"></script>