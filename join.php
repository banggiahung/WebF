<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');
$userid = $_SESSION['userid'];
$capping = 500;
?>
<?php
//Người dùng ấn vào tham gia
if(isset($_GET['join_user'])){
	$side='';
	$pin = mysqli_real_escape_string($con,$_GET['pin']);
	$email = mysqli_real_escape_string($con,$_GET['email']);
	$mobile = mysqli_real_escape_string($con,$_GET['mobile']);
	$address = mysqli_real_escape_string($con,$_GET['address']);
	$account = mysqli_real_escape_string($con,$_GET['account']);
	$under_userid = mysqli_real_escape_string($con,$_GET['under_userid']);
	$side = mysqli_real_escape_string($con,$_GET['side']);
	$password = "123456";
	
	$flag = 0;
	
	if($pin!='' && $email!='' && $mobile!='' && $address!='' && $account!='' && $under_userid!='' && $side!=''){
		//Người dùng đã điền tất cả các trường!
		if(pin_check($pin)){
			//Pin is ok
			if(email_check($email)){
				//Email is ok
				if(!email_check($under_userid)){
					//Under userid is ok
					if(side_check($under_userid,$side)){
						//Side check
						$flag=1;
					}
					else{
						echo '<script>alert("The side you selected is not available.");</script>';
					}
				}
				else{
					//check under userid
					echo '<script>alert("Invalid Under userid.");</script>';
				}
			}
			else{
				//check email
				echo '<script>alert("This user id already availble.");</script>';
			}
		}
		else{
			//check pin
			echo '<script>alert("Invalid pin");</script>';
		}
	}
	else{
		//check tất cả các trường đã điền
		echo '<script>alert("Please fill all the fields.");</script>';
	}
	
	// Tất cả thông tin đều đúng sẽ lưu tất cả thông tin
	if($flag==1){
		
		// Thêm user 
		$query = mysqli_query($con,"insert into user(`email`,`password`,`mobile`,`address`,`account`,`under_userid`,`side`) values('$email','$password','$mobile','$address','$account','$under_userid','$side')");
		
		// Thêm vào "TREE"
		
		$query = mysqli_query($con,"insert into tree(`userid`) values('$email')");
		
		//Thêm vào các bên
		$query = mysqli_query($con,"update tree set `$side`='$email' where userid='$under_userid'");
		
		//Cập nhật trạng thái Pin
		$query = mysqli_query($con,"update pin_list set status='close' where pin='$pin'");
		
		//Thêm vào thu nhập
		$query = mysqli_query($con,"insert into income (`userid`) values('$email')");
		echo mysqli_error($con);

		// Đây là phần chính để thêm user
		// Nếu sai web ngừng hoạt động
		
		//Cập nhật số lượng và thu nhập
		$temp_under_userid = $under_userid;
		$temp_side_count = $side.'count'; //số đếm bên trái hoặc bên phải
		
		$temp_side = $side;
		$total_count=1;
		$i=1;
		while($total_count>0){
			$i;
			$q = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
			$r = mysqli_fetch_array($q);
			$current_temp_side_count = $r[$temp_side_count]+1;
			$temp_under_userid;
			$temp_side_count;
			mysqli_query($con,"update tree set `$temp_side_count`=$current_temp_side_count where userid='$temp_under_userid'");
			
			//Thu nhập
			if($temp_under_userid!=""){
				$income_data = income($temp_under_userid);
				//check capping
				//$income_data['day_bal'];
				if($income_data['day_bal']<$capping){
					$tree_data = tree($temp_under_userid);
					
					//check leftplusright
					//$tree_data['leftcount'];
					//$tree_data['rightcount'];
					//$leftplusright;
					
					$temp_left_count = $tree_data['leftcount'];
					$temp_right_count = $tree_data['rightcount'];
					//Bên trái hoặc bên phải có ít nhất một người dùng
					if($temp_left_count>0 && $temp_right_count>0){
						if($temp_side=='left'){
							$temp_left_count;
							$temp_right_count;
							if($temp_left_count<=$temp_right_count){
								
								$new_day_bal = $income_data['day_bal']+100;
								$new_current_bal = $income_data['current_bal']+100;
								$new_total_bal = $income_data['total_bal']+100;
								
								//Cập nhập thu nhập
								mysqli_query($con,"update income set day_bal='$new_day_bal', current_bal='$new_current_bal', total_bal='$new_total_bal' where userid='$temp_under_userid' limit 1");	
							
							}
						}
						else{
							if($temp_right_count<=$temp_left_count){
						
								$new_day_bal = $income_data['day_bal']+100;
								$new_current_bal = $income_data['current_bal']+100;
								$new_total_bal = $income_data['total_bal']+100;
								$temp_under_userid;
								//Cập nhập thu nhập
								if(mysqli_query($con,"update income set day_bal='$new_day_bal', current_bal='$new_current_bal', total_bal='$new_total_bal' where userid='$temp_under_userid'")){
									
								}
							}
						}
					}			//Bên trái hoặc bên phải có ít nhất một người dùng		
				}
				// thay đổi unser_userid
				$next_under_userid = getUnderId($temp_under_userid);
				$temp_side = getUnderIdPlace($temp_under_userid);
				$temp_side_count = $temp_side.'count';
				$temp_under_userid = $next_under_userid;	
				
				$i++;
			}
			
			//Chaeck user cuối
			if($temp_under_userid==""){
				$total_count=0;
			}
			
		}//lặp
		
		
		
		
		echo mysqli_error($con);
		
		echo '<script>alert("Testing success.");</script>';
	}
	
}
?><!--/join user-->
<?php 
//functions
function pin_check($pin){
	global $con,$userid;
	
	$query =mysqli_query($con,"select * from pin_list where pin='$pin' and userid='$userid' and status='open'");
	if(mysqli_num_rows($query)>0){
		return true;
	}
	else{
		return false;
	}
}
function email_check($email){
	global $con;
	
	$query =mysqli_query($con,"select * from user where email='$email'");
	if(mysqli_num_rows($query)>0){
		return false;
	}
	else{
		return true;
	}
}
function side_check($email,$side){
	global $con;
	
	$query =mysqli_query($con,"select * from tree where userid='$email'");
	$result = mysqli_fetch_array($query);
	$side_value = $result[$side];
	if($side_value==''){
		return true;
	}
	else{
		return false;
	}
}
function income($userid){
	global $con;
	$data = array();
	$query = mysqli_query($con,"select * from income where userid='$userid'");
	$result = mysqli_fetch_array($query);
	$data['day_bal'] = $result['day_bal'];
	$data['current_bal'] = $result['current_bal'];
	$data['total_bal'] = $result['total_bal'];
	
	return $data;
}
function tree($userid){
	global $con;
	$data = array();
	$query = mysqli_query($con,"select * from tree where userid='$userid'");
	$result = mysqli_fetch_array($query);
	$data['left'] = $result['left'];
	$data['right'] = $result['right'];
	$data['leftcount'] = $result['leftcount'];
	$data['rightcount'] = $result['rightcount'];
	
	return $data;
}
function getUnderId($userid){
	global $con;
	$query = mysqli_query($con,"select * from user where email='$userid'");
	$result = mysqli_fetch_array($query);
	return $result['under_userid'];
}
function getUnderIdPlace($userid){
	global $con;
	$query = mysqli_query($con,"select * from user where email='$userid'");
	$result = mysqli_fetch_array($query);
	return $result['side'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FF Website  - Join</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="main.css">
 

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include('php-includes/menu.php'); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tham gia</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                	<div class="col-lg-4">
                    	<form method="get">
                            <div class="form-group">
                                <label>Pin</label>
                                <input type="text" name="pin" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Số Điện Thoại</label>
                                <input type="text" name="mobile" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Tài khoản</label>
                                <input type="text" name="account" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Bên dưới ID nào?</label>
                                <input type="text" name="under_userid" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Hướng</label><br>
                                <input type="radio" name="side" value="left"> Trái
                                <input type="radio" name="side" value="right"> Phải
                            </div>
                            
                            <div class="form-group">
                        	<input type="submit" name="join_user" class="btn btn-primary" value="Join">
                        </div>
                        </form>
                    </div>
                </div><!--/.row-->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
