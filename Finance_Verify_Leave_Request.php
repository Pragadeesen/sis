<?php
include_once 'staff-header.php';
include "DB_Connection.php";

	if(isset($_POST["approve"])){
		$leaveid = $_POST["leave_id"];
		$verified_by = $session->get_session('userid');
		date_default_timezone_set('Asia/Colombo');
		$verified_date = date("Y-m-d h:i:s",time());
		$status = "approved";
		
		$sql_approve = "update leave_request set status='$status', verified_by='$verified_by', verified_date='$verified_date' where id='$leaveid'";
							if($conn->query($sql_approve) == true){
									set_success_msg("<strong>Success!</strong> Leave has been successfully Approved!");
									header("Location: Finance_Verify_Leave_Request.php");
							}else{
									set_error_msg("<strong>Oops!</strong> Something went wrong!...!");
								  	header("Location: Finance_Verify_Leave_Request.php");
			
							}
	}
	
	if(isset($_POST["reject"])){
		$leaveid = $_POST["leave_id"];
		$verified_by = $session->get_session('userid');
		date_default_timezone_set('Asia/Colombo');
		$verified_date = date("Y-m-d h:i:s",time());
		$status = "rejected";
		
		$sql_approve = "update leave_request set status='$status', verified_by='$verified_by', verified_date='$verified_date' where id='$leaveid'";
							if($conn->query($sql_approve) == true){
									set_success_msg("<strong>Success!</strong> Leave has been successfully Rejected!");
									header("Location: Finance_Verify_Leave_Request.php");
							}else{
									set_error_msg("<strong>Oops!</strong> Something went wrong!...!");
								  	header("Location: Finance_Verify_Leave_Request.php");
			
							}
	}
 

?>
				<div class="col-md-10">
					<nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a href="Finance_Leave_Dashboard.php" class="nav-item nav-link"><strong> Leave Overview </strong></a>
                            <?php 
							$sql_tot = "select * from users where userid NOT IN (select userid from leaves)";
							$result_tot=mysqli_query($conn,$sql_tot);
							$row_tot=mysqli_num_rows($result_tot);
			
							$sql_del = "SELECT * FROM leave_request where status='rejected'";
							$result_del=mysqli_query($conn,$sql_del);
							$row_del=mysqli_num_rows($result_del);
			
							$sql_close = "SELECT * FROM leave_request where status='approved'";
							$result_close=mysqli_query($conn,$sql_close);
							$row_close=mysqli_num_rows($result_close);
			
							$sql_pen = "SELECT * FROM leave_request where status='pending'";
							$result_pen=mysqli_query($conn,$sql_pen);
							$row_pen=mysqli_num_rows($result_pen);
	
							$sql_lea = "select * from leaves";
							$result_lea=mysqli_query($conn,$sql_lea);
							$row_lea=mysqli_num_rows($result_lea);
							?>
                        	<a href="Finance_Add_Leaves.php" class="nav-item nav-link"><strong> Add Leaves 
                            <?php if($row_tot>0){
									echo "<span class='badge badge-danger badge-pill'> ".$row_tot." <span>";
								  } 
							?>
                            </strong></a>
                            <a href="Finance_Update_Leaves.php" class="nav-item nav-link"><strong> Update Leaves 
                            <?php if($row_lea>0){
									echo "<span class='badge badge-danger badge-pill'> ".$row_lea." <span>";
								  } 
							?>
                            </strong></a>
                            <a href="Finance_Delete_Leaves.php" class="nav-item nav-link"><strong> Delete Leaves 
                            <?php if($row_lea>0){
									echo "<span class='badge badge-danger badge-pill'> ".$row_lea." <span>";
								  } 
							?>
                            </strong></a>
                            <a class="nav-item nav-link active"><strong> Leave Requests 
                            <?php if($row_pen>0){
									echo "<span class='badge badge-success badge-pill'> ".$row_pen." <span>";
								  } 
							?>
                            </strong></a>
                            <a href="Finance_Approved_Leave_Request.php" class="nav-item nav-link"><strong> Approved Leaves 
                            <?php if($row_close>0){
									echo "<span class='badge badge-danger badge-pill'> ".$row_close." <span>";
								  } 
							?>
                            </strong></a>
                            <a href="Finance_Rejected_Leave_Request.php" class="nav-item nav-link"><strong> Rejected Leaves 
                            <?php if($row_del>0){
									echo "<span class='badge badge-danger badge-pill'> ".$row_del." <span>";
								  } 
							?>
                            </strong></a>
						</div>
					</nav>
					<div class="tab-content">
						<div class="tab-pane mt-4 show active">
							<form method="post" action="Finance_Verify_Leave_Request.php">
								<div class="row">
									<div class="col-md-12">
										<div class="input-group mb-3">
                                        	<select name="search_opt" class="form-control col-md-2" >
                                            	<option value="userid"> Staff ID </option>
                                                <option value="l_type"> Leave Type </option>
                                                <option value="days"> No. of Days </option>
                                                <option value="reason"> Reason </option>
                                            </select>
											<input name="s_text" type="text" class="form-control" value="<?php echo (isset($_POST['s_text'])) ? $_POST['s_text'] : '' ; ?>" placeholder=" Search Text " >
											<div class="input-group-append">
													<button class="btn btn-dark" type="submit" name="search"> Search </button>
                                                    <a class="btn btn-secondary" href="Finance_Verify_Leave_Request.php"> Refresh </a>
											</div>
										</div>
									</div>
								</div>
                              </form>
								<div class="row">
									<div class="col-md-12">
                                            <?php
												if(isset($_POST["search"])){
													$search = $_POST["search_opt"];
													$s_text = $_POST["s_text"];
													if(empty($s_text)){
														echo "<script>alert(' Search Text is Empty!... ')</script>";
														$sql_delete = "select * from leave_request where status='pending'";
													}else{
														$sql_delete = "select * from leave_request where status='pending' and ".$search." like '%".$s_text."%'";
													}
													
												}else{
													$sql_delete = "select * from leave_request where status='pending'";
												}
												$result = $conn->query($sql_delete);
												
												if($result->num_rows>0){
											
                                            		echo "<table class='table table-bordered'>";
											?>			<thead class="odd">
															<tr>
                                                            	<th scope="col"><center>ID</center></th>
                                                                <th scope="col" colspan="3"><center>Staff</center></th>
                                                                <th scope="col"><center>Leave Type</center></th>
                                                                <th scope="col"><center>Days</center></th>
                                                                <th scope="col"><center>Reason</center></th>
                                                                <th scope="col" colspan="2"><center>Verified By</center></th>
                                                                <th scope="col"><center>Verified Date</center></th>
                                                                <th scope="col"><center>Status</center></th>
                                                    			<th scope="col"><center>Operation</center></th>   
															</tr>
														</thead>
                                            <?php
													$b = 0;
													while($row = $result->fetch_assoc()){
														$sql_staff = "select * from users where userid = '".$row["userid"]."'";
														$result_staff = $conn->query($sql_staff);
														$row_staff = $result_staff->fetch_assoc();
														$bg_color = ($b++%2==1) ? 'odd' : 'even';
											?>
                                            			<tbody>
															<tr class="<?php echo $bg_color; ?>">
                                                            <form action="Finance_Verify_Leave_Request.php" method="post" onSubmit="return confirm('WARNING!\n\n1. Accidentally approving/rejecting of leaves record cannot backup from the system.\n2. There is no way to undo this action.\n\nDo you still really want to approve/reject the leaves?');">
                                                            	<th scope="row">LEA<?php echo $row["id"]; ?></th>
																<th scope="row"><?php echo $row["userid"]; ?></th>
																<td> <?php echo $row_staff["fname"]." ".$row_staff["lname"]; ?> </td>
                                                                <td><button class="btn btn-outline-info" type="button" onclick="openWinStaff(<?php echo $row["userid"]; ?>);"><span class="glyphicon glyphicon-user"></span></button></td>
                                                                <td><?php echo $row["l_type"]; ?> Days</td>
                                                                <td><?php echo $row["days"]; ?> Days</td>
                                                                <td><?php echo $row["reason"]; ?> Days</td>
                                                                 <?php if(empty($row["verified_by"])){
																		$result_verified_by = " _ ";
																		$result_verified_by_btn = "message-hide";
																	}else{
																		$result_verified_by = $row["verified_by"];
																		$result_verified_by_btn = "";
																	} 
																?>
                                                                <td><?php echo $result_verified_by; ?></td>
                                                                <td><button class="btn btn-outline-info <?php echo $result_verified_by_btn; ?>" type="button" onclick="openWinStaff(<?php echo $row["verified_by"]; ?>);"><span class="glyphicon glyphicon-user"></span></button></td>
                                                                <?php if($row["verified_date"]=="0000-00-00 00:00:00"){
																		$result_ver_date = " _ ";
																	}else{
																		$result_ver_date = $row["verified_date"];
																	} 
																?>
                                                    			<td><?php echo $result_ver_date; ?></td>
                                                                <th class="<?php echo $row["status"]; ?>"> <?php echo $row["status"]; ?> </th>
                                                                <th scope="row">
                                                               		<input type="hidden" value="<?php echo $row["id"]; ?>" name="leave_id">
                                                                	<div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
  																		<button type="submit" name="approve" class="btn btn-outline-success"> Approve </button>
  																		<button type="submit" name="reject" class="btn btn-outline-danger"> Reject </button>
																	</div>
                                                                </th>
                                                            </form>
															</tr>
														</tbody>
                                            <?php
									
                                                	}
													echo "</table>";
													
											
													$conn->close();
												}else{
												?>
															<tr>
                                               				    <th scope="col" colspan="18"><center>No Results Available</center></th>   
															</tr>
											<?php
													}
												 
											?>
											
									</div>
								</div>
						</div>
					</div>
				</div>
 

 
<?php include_once 'staff-footer.php'; ?>	