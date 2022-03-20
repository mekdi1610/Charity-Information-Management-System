<?php
include_once('PHP/BankAccount.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
  header("Location: Login.php");
  exit();
}
if (isset($_SESSION["EmpID"])) {
  $empID = $_SESSION["EmpID"];
}

//Call the Bank Account class
$accObj = new BankAccount();
//To all the bank accounts
$accept = $accObj->viewBankAccount();
//////////////////////////
$accID = $accObj->generateID();
//To add or update a bank account
if (isset($_REQUEST["datasent"])) {
  $datasent = $_REQUEST["datasent"];
  $accept = explode("/", $datasent);
  $accObj->setAccID($accept[0]);
  $accObj->setBankName($accept[1]);
  $accObj->setBranchName($accept[2]);
  $accObj->setAccName($accept[3]);
  $accObj->setAccNo($accept[4]);
  $accObj->setTransferInfo($accept[5]);
  $accObj->setCountry($accept[6]);
  $accObj->setBelongsTo($accept[7]);
  if ($accept[8] == "Add") {
    $accObj->addBankAccount($empID);
    exit();
  } else if ($accept[8] == "Update") {
    $accObj->updateBankAccount($empID);
    exit();
  }
}

//To remove a bank account
if (isset($_POST['Remove'])) {
  $accObj->setAccID($_POST['AccID']);
  $accObj->removeBankAccount();
}
if (isset($_POST['No'])) {
  echo "<script>alert('Data was not removed');
    document.location = '/ManageBankAccount.php' </script>";
}

//To prevent XSS Attack
function _e($string)
{
  echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Manage Bank Account</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="CSS/css/bootstrap.min.css">
  <!-- FontAwesome CSS -->
  <link rel="stylesheet" href="CSS/css/font-awesome.min.css">
  <!-- ElegantFonts CSS -->
  <link rel="stylesheet" href="CSS/css/elegant-fonts.css">
  <!-- themify-icons CSS -->
  <link rel="stylesheet" href="CSS/css/themify-icons.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="CSS/css/swiper.min.css">
  <!-- Styles -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Table Styles -->
  <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <!-- Poppin font for table -->
  <link rel="stylesheet" href="CSS/css/style.css">
</head>

<body>
  <?php include_once("HeaderEmployee.php"); ?>

  <body>
    <hr>
    <!-- Blog section  -->
    <div class="section">
      <div class="container">
        <!-- Blog Posts -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header" style="display:flex;">
                <h4 class="card-title" style="text-align: center"><i class="fa fa-university" aria-hidden="true"></i>Manage Bank Accounts</h4>
                <a href="#modal" data-toggle="modal" data-id="<?php echo "$accID" ?>" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" style="margin-left:auto;" onclick="buttonAdd()"><i class="fa fa-plus"></i></a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Acc ID</th>
                        <th>Bank Name</th>
                        <th>Branch Name</th>
                        <th>Account Name</th>
                        <th>Account No</th>
                        <th>TransferInfo</th>
                        <th>Emp ID</th>
                        <th>Update</th>
                        <th>Remove</th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <?php
                      $accIDs = $accept[0];
                      $bankName = $accept[1];
                      $branchName = $accept[2];
                      $accName = $accept[3];
                      $accno = $accept[4];
                      $transferInfo = $accept[5];
                      $empID = $accept[6];
                      $country = $accept[7];
                      $belongsTo = $accept[8];
                      echo $belongsTo[0];
                      //To display the retrieved accounts
                      for ($i = 0; $i < sizeof($accIDs); $i++) {
                        echo "<tr>";
                        echo "<td>$accIDs[$i]</td>";
                        echo "<td>";
                        _e($bankName[$i]);
                        echo "</td>";
                        echo "<td>";
                        _e($branchName[$i]);
                        echo "</td>";
                        echo "<td>";
                        _e($accName[$i]);
                        echo "</td>";
                        echo "<td>$accno[$i]</td>";
                        echo "<td>";
                        _e($transferInfo[$i]);
                        echo "</td>";
                        echo "<td>";
                        _e($empID[$i]);
                        echo "</td>";
                        echo "<td>";
                        echo '<a href="#modal" data-toggle="modal" data-id="' . $accIDs[$i] . '" data-bankname="' . $bankName[$i] . '" data-branchname="' . $branchName[$i] . '" data-accname="' . $accName[$i] . '" data-accno="' . $accno[$i] . '" data-transferinfo="' . $transferInfo[$i] . '" data-belongsto="' . $belongsTo[$i] . '" data-country="' . $country[$i] . '" data-empid="' . $empID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" onclick="buttonUpdate()"><i class="fa fa-eye"></i></a></td>';
                        echo "<td>";
                        echo '<a href="#modal2" data-toggle="modal" data-id="' . $accIDs[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td></tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                  <div class="card-body">
                    <!-- Modal -->
                    <div class="modal" id="modal" style="top:50px; height:fit-content;">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header no-bd">
                            <h5 class="modal-title">
                              <span class="fw-mediumbold"> Add/Update Account
                              </span>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close" onclick="Hide()">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" id="toLoad">
                            <p class="small"></p>
                            <div class="form-horizontal row-fluid">
                              <div class="row">
                                <div class="control-group">
                                  <label class="control-label" for="AccID">Bank Account ID</label>
                                  <div class="controls">
                                    <input type="text" id="id" name="AccID" class="span12" value="<?php echo $accID ?>" readonly>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="AccID">Bank Name</label>
                                  <div class="controls">
                                    <input type="text" id="bankName" name="BankName" class="span12" maxlength="30" required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="BranchName">Branch Name</label>
                                  <div class="controls">
                                    <input type="text" id="branchName" name="BranchName" class="span12" maxlength="100" required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="AccName">Account Name</label>
                                  <div class="controls">
                                    <input type="text" id="accName" name="AccName" class="span12" maxlength="100" required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="des">Account No:</label>
                                  <div class="controls">
                                    <input type="number" id="accNo" name="AccNo" class="span12" required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="quantity">TransferInfo:</label>
                                  <div class="controls">
                                    <input type="text" id="transferInfo" name="TransferInfo" class="span12" maxlength="30">
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="quantity">Country:</label>
                                  <div class="controls">
                                    <input type="text" id="country" name="Country" class="span12" maxlength="30" required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="empID">Belongs To:</label>
                                  <div class="controls">
                                    <input type="text" id="belongsTo" name="BelongsTo" class="span12" placeholder="Organization|MemberID" value="Organization" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer no-bd">
                                <input type="submit" onclick="manageBankAccount(); clearForm()" class="button" id="btn" style="width:100%;" name="Add" value="Add">
                              </div>
                            </div>
                            <script type='text/javascript' src='Javascript/BankAccount.js'></script>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Modal -->
      <div class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content">
        <div class="modal-dialog" role="document">
          <div class="modal-content" id="forMember">
            <div class="modal-header no-bd">
              <h5 class="modal-title">
                <span class="fw-mediumbold"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Confirmation Box
                </span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width:15%">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="small" style="padding-left:75px;"> Are you sure you want remove this account?</p>
              <form class="form-horizontal row-fluid" method="post" action="ManageBankAccount.php" style="border:none;">
                <div class="row">
                  <div class="control-group" style="border:none;">
                    <label class="control-label" for="quantity">Account ID:</label>
                    <div class="controls">
                      <input type="text" id="id" name="AccID" class="span12" readonly>
                    </div>
                  </div>
                </div>
                <div class="modal-footer no-bd" style="background-color:white;">
                  <button type="submit" class="button-rounded margin-top-20" name="Remove">Yes</button>
                  <button type="submit" class="button-rounded margin-top-20" name="No">No</button>
                </div>
            </div>
            </form>
            <script type='text/javascript' src='Javascript/Buttons.js'></script>
            <script type='text/javascript' src='Javascript/Member.js'></script>
          </div>
        </div>
      </div>
    </div>
    <?php include("Footer.html"); ?>

    <!--Javascript-->
    <script src="CSS/table/assets/js/jquery.min.js"></script>
    <script src="CSS/table/assets/js/bootstrap.min.js"></script>
    <script src="CSS/table/assets/js/datatables.min.js"></script>
    <script>
      //JQuery Code to close the modal
      $("#close").click(function() {
        $("#modal").hide();
      });
    </script>
    <script>
      //JQuery Code to display basic database table
      $(document).ready(function() {
        $('#basic-datatables').DataTable({});
        $('#multi-filter-select').DataTable({
          "pageLength": 5,
          initComplete: function() {
            this.api().columns().every(function() {
              var column = this;
              var select = $('<select class="form-control"><option value=""></option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function() {
                  var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                  );
                  column
                    .search(val ? '^' + val + '$' : '', true, false)
                    .draw();
                });
              column.data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
              });
            });
          }
        });
      });
    </script>
    <script>
      //To display all the values from database on the update dialog box
      $(document).on("click", ".modal-trigger", function() {
        var ID = $(this).data('id');
        var bankName = $(this).data('bankname');
        var branchName = $(this).data('branchname');
        var accName = $(this).data('accname');
        var accNo = $(this).data('accno');
        var transferInfo = $(this).data('transferinfo');
        var country = $(this).data('country');
        var belongsTo = $(this).data('belongsto');
        var empid = $(this).data('empid');
        $(".modal-content #id").val(ID);
        $("#bankName").val(bankName);
        $("#branchName").val(branchName);
        $("#accName").val(accName);
        $("#accNo").val(accNo);
        $("#transferInfo").val(transferInfo);
        $("#country").val(country);
        $("#belongsTo").val(belongsTo);
        $("#empid").val(empid);
      });
    </script>
    <script>
    </script>
  </body>

</html>