<?php 
if( isset($_GET["hide"]) && !empty($_GET["hide"]) ){
	if( updateDB('employees',array('hidden'=> '2'),"`id` = '{$_GET["hide"]}'") ){
		header("LOCATION: ?v=ListOfEmployees");
	}
}

if( isset($_GET["show"]) && !empty($_GET["show"]) ){
	if( updateDB('employees',array('hidden'=> '0'),"`id` = '{$_GET["show"]}'") ){
		header("LOCATION: ?v=ListOfEmployees");
	}
}

if( isset($_GET["delId"]) && !empty($_GET["delId"]) ){
	if( updateDB('employees',array('status'=> '1'),"`id` = '{$_GET["delId"]}'") ){
		header("LOCATION: ?v=ListOfEmployees");
	}
}

if( isset($_POST["firstName"]) ){
	$id = $_POST["update"];
	unset($_POST["update"]);
	if ( $id == 0 ){
		$_POST["password"] = sha1($_POST["password"]);
		if( insertDB("employees", $_POST) ){
			header("LOCATION: ?v=ListOfEmployees");
		}else{
		?>
		<script>
			alert("Could not process your request, Please try again.");
		</script>
		<?php
		}
	}else{
		if( !empty($_POST["password"]) ){
			$_POST["password"] = sha1($_POST["password"]);
		}else{
			unset($_POST["password"]);
		}
		if( updateDB("employees", $_POST, "`id` = '{$id}'") ){
			header("LOCATION: ?v=ListOfEmployees");
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
	<h6 class="panel-title txt-dark"><?php echo direction("Employee Details","تفاصيل الموظف") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="" enctype="multipart/form-data">
		<div class="row m-0">
			<div class="col-md-6">
			<label><?php echo direction("First Name","الإسم الأول") ?></label>
			<input type="text" name="firstName" class="form-control" required>
			</div>

			<div class="col-md-6">
			<label><?php echo direction("Last Name","الإسم الأخير") ?></label>
			<input type="text" name="lastName" class="form-control" required>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Email","البريد الإلكتروني") ?></label>
			<input type="text" name="email" class="form-control" required>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Password","كلمة المرور") ?></label>
			<input type="text" name="password" class="form-control" required>
			</div>
			
			<div class="col-md-4">
			<label><?php echo direction("Mobile","الهاتف") ?></label>
			<input type="number" min="0" maxlength="8" name="phone" class="form-control" required>
			</div>

			<div class="col-md-6">
			<label><?php echo direction("Shop","المحل") ?></label>
			<select name="shopId" class="form-control">
				<?php
				$orderByTitle = direction("enTitle","arTitle");
				if( $stores = selectDB("stores","`status` = '0' AND `hidden` = '1' ORDER BY `{$orderByTitle}` ASC") ){
					for( $i = 0; $i < sizeof($stores); $i++ ){
						$shopTitle = direction($stores[$i]["enTitle"],$stores[$i]["arTitle"]);
						echo "<option value='{$stores[$i]["id"]}'>{$shopTitle}</option>";
					}
				}
				?>
			</select>
			</div>
			
			<div class="col-md-6">
			<label><?php echo direction("Type","النوع") ?></label>
			<select name="empType" class="form-control">
				<?php 
				if( $roles = selectDB("roles","`status` = '0' AND `hidden` = '1'") ){
					for( $i = 0; $i < sizeof($roles); $i++ ){
						$title = direction($roles[$i]["enTitle"],$roles[$i]["arTitle"]);
						echo "<option value='{$roles[$i]["id"]}'>{$title}</option>";
					}
				}
				?>
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
				
				<!-- Bordered Table -->
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
<h6 class="panel-title txt-dark"><?php echo direction("List of Employees","قائمة الموظفين") ?></h6>
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
		<th><?php echo direction("Name","الإسم") ?></th>
		<th><?php echo direction("Email","الإيميل") ?></th>
		<th><?php echo direction("Mobile","الهاتف") ?></th>
		<th><?php echo direction("Type","النوع") ?></th>
		<?php /* <th><?php echo direction("Shop","المحل") ?></th> */ ?>
		<th class="text-nowrap"><?php echo direction("الخيارات","Actions") ?></th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
		if( $employees = selectDB("employees","`status` = '0' AND `hidden` != '1'") ){
			for( $i = 0; $i < sizeof($employees); $i++ ){
				$counter = $i + 1;
				if ( $employees[$i]["hidden"] == 2 ){
					$icon = "fa fa-unlock";
					$link = "?v={$_GET["v"]}&show={$employees[$i]["id"]}";
					$hide = direction("Unlock","فتح الحساب");
				}else{
					$icon = "fa fa-lock";
					$link = "?v={$_GET["v"]}&hide={$employees[$i]["id"]}";
					$hide = direction("Lock","قفل الحساب");
				}
				if ( $employees[$i]["empType"] == 0 ){
					$type = "Admin";
				}elseif( $employees[$i]["empType"] == 1 ){
					$type = "Employee";
				}elseif( $employees[$i]["empType"] == 2 ){
					$type = "POS";
				}
				if( $employee = selectDB("roles","`id` = '{$employees[$i]["empType"]}'") ){
					$employee = direction($employee[0]["enTitle"],$employee[0]["arTitle"]);
				}else{
					$employee = "";
				}
				
				?>
				<tr>
				<td><?php echo "{$employees[$i]["firstName"]} {$employees[$i]["lastName"]}" ?></td>
				<td id="email<?php echo $employees[$i]["id"]?>" ><?php echo $employees[$i]["email"] ?></td>
				<td id="mobile<?php echo $employees[$i]["id"]?>" ><?php echo $employees[$i]["phone"] ?></td>
				<td><?php echo $employee ?></td>
				<td class="text-nowrap">
					<a id="<?php echo $employees[$i]["id"] ?>" class="mr-25 edit" data-toggle="tooltip" data-original-title="<?php echo direction("Edit","تعديل") ?>"> <i class="fa fa-pencil text-inverse m-r-10"></i></a>
					<a href="<?php echo $link ?>" class="mr-25" data-toggle="tooltip" data-original-title="<?php echo $hide ?>"> <i class="<?php echo $icon ?> text-inverse m-r-10"></i></a>
					<a href="<?php echo "?v={$_GET["v"]}&delId={$employees[$i]["id"]}" ?>" data-toggle="tooltip" data-original-title="<?php echo direction("Delete","حذف") ?>"><i class="fa fa-close text-danger"></i></a>
					<div style="display:none">
						<label id="type<?php echo $employees[$i]["id"]?>"><?php echo $employees[$i]["empType"] ?></label>
						<label id="shop<?php echo $employees[$i]["id"]?>"><?php echo $employees[$i]["shopId"] ?></label>
						<label id="firstName<?php echo $employees[$i]["id"]?>"><?php echo $employees[$i]["firstName"] ?></label>
						<label id="lastName<?php echo $employees[$i]["id"]?>"><?php echo $employees[$i]["lastName"] ?></label>
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
	$(document).on("click",".edit", function(){
		var id = $(this).attr("id");
		$("input[name=update]").val(id);

		$("input[name=password]").prop("required",false);
		$("input[name=email]").val($("#email"+id).html());
		$("input[name=phone]").val($("#mobile"+id).html());
		$("input[name=firstName]").val($("#firstName"+id).html()).focus();
		$("input[name=lastName]").val($("#lastName"+id).html());
		$("select[name=empType]").val($("#type"+id).html());
		$("select[name=shopId]").val($("#shop"+id).html());
	})
</script>