<?php
include_once('PHP/Supply.php');
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

//Call the Supply class
$supObj = new Supply();
//To display supplies from the list
$accept = $supObj->viewSupplies();
/////////////////////////////////
$supID = $supObj->generateID();
//To add or update a supply
if (isset($_POST['Add']) || isset($_POST['Update'])) {
  $supObj->setSupID($_POST['SupID']);
  $supObj->setProduct($_POST['Product']);
  $supObj->setType($_POST['Type']);
  $supObj->setDescription($_POST['Description']);
  $supObj->setQuantity($_POST['Quantity']);
  $supObj->setUrgencyLevel($_POST['UrgencyLevel']);
  //Add a supply to the list
  if (isset($_POST['Add'])) {
    $supObj->addSupply($empID);
  }
  //Update the supply list
  if (isset($_POST['Update'])) {
    $supObj->updateSupply($empID);
  }
}

//To remove a supply from list
if (isset($_POST['Remove'])) {
  $supObj->setSupID($_POST['SupID']);
  $supObj->removeSupply();
}
if (isset($_POST['No'])) {
  echo "<script>alert('Data was not removed');
    document.location = '/Manage Supply List.php' </script>";
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
  <title>Manage Supply List</title>
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
  <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
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
                <h4 class="card-title" style="text-align: center"><i class="fa fa-list" aria-hidden="true"></i>Supply List</h4>
                <a href="#modal" data-toggle="modal" data-id="<?php echo "$supID" ?>" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" style="margin-left:auto;" onclick="buttonAdd()"><i class="fa fa-plus"></i></a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Supply ID</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Urgency Level</th>
                        <th>Emp ID</th>
                        <th>Update</th>
                        <th>Remove</th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <?php
                      $supID = $accept[0];
                      $product = $accept[1];
                      $type = $accept[2];
                      $description = $accept[3];
                      $quantity = $accept[4];
                      $urgencyLevel = $accept[5];
                      $empID = $accept[6];
                      //To display all the supplies
                      for ($i = 0; $i < sizeof($supID); $i++) {
                        echo "<tr>";
                        echo "<td>$supID[$i]</td>";
                        echo "<td>";
                        _e($product[$i]);
                        echo "</td>";
                        echo "<td>$type[$i]</td>";
                        echo "<td>";
                        _e($description[$i]);
                        echo "</td>";
                        echo "<td>";
                        _e($quantity[$i]);
                        echo "</td>";
                        echo "<td>$urgencyLevel[$i]</td>";
                        echo "<td>$empID[$i]</td>";
                        echo "<td>";
                        echo '<a href="#modal" data-toggle="modal" data-id="' . $supID[$i] . '" data-product="' . $product[$i] . '" data-type="' . $type[$i] . '" data-description="' . $description[$i] . '" data-quantity="' . $quantity[$i] . '" data-urgencylevel="' . $urgencyLevel[$i] . '" data-empid="' . $empID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" onclick="buttonUpdate()"><i class="fa fa-eye"></i></a></td>';
                        echo "<td>";
                        echo '<a href="#modal2" data-toggle="modal" data-id="' . $supID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td></tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                  <div class="card-body">
                    <!-- Modal -->
                    <div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header no-bd">
                            <h5 class="modal-title">
                              <span class="fw-mediumbold"> Add/Update Supplies
                              </span>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="small"></p>
                            <form class="form-horizontal row-fluid" method="post" action="Manage Supply List.php">
                              <div class="row">
                                <div class="control-group">
                                  <label class="control-label" for="SupID">Supply ID</label>
                                  <div class="controls">
                                    <input type="text" id="id" name="SupID" class="span12" readonly required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="Product">Product</label>
                                  <div class="controls">
                                    <input type="text" id="product" name="Product" class="span12" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="100" required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="Type">Type</label>
                                  <div class="controls">
                                    <select id="type" name="Type" required>
                                      <option value="Please Choose">Please Choose</option>
                                      <option id="SP" value="Sanitary Product">Sanitary Product</option>
                                      <option id="Clothing" value="Clothing">Clothing</option>
                                      <option id="FD" value="Food and Drink">Food and Drink</option>
                                      <option id="Medication" value="Medication">Medication</option>
                                      <option id="Equipment" value="Equipment">Equipment</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="control-group">
                                  <label class="control-label" for="des">Description:</label>
                                  <div class="controls">
                                    <input type="text" id="description" name="Description" class="span12" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers">
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="quantity">Quantity:</label>
                                  <div class="controls">
                                    <input type="text" id="quantity" name="Quantity" class="span12" pattern="[^()/><\][\\\x22,;" title="Should not contain special characters or numbers" maxlength="20" required>
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="ul">Urgency Level:</label>
                                  <div class="controls">
                                    <select id="urgencylevel" name="UrgencyLevel" class="span12" required>
                                      <option value="Please Choose">Please Choose</option>
                                      <option value="High">High</option>
                                      <option value="Medium">Medium</option>
                                      <option value="Low">Low</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer no-bd">
                                <input type="submit" class="button" id="btn" name="Add" value="Add" style="width:100%;"></button>
                              </div>
                            </form>
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
      <div class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content;">
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
              <p class="small" style="text-align: center;"> Are you sure you want remove this supply?</p>
              <form class="form-horizontal row-fluid" method="post" action="Manage Supply List.php" style="border:none;">
                <div class="row">
                  <div class="control-group" style="border:none;">
                    <label class="control-label" for="quantity">Suppy ID:</label>
                    <div class="controls">
                      <input type="text" id="id" name="SupID" class="span12" readonly>
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
          </div>
        </div>
      </div>
    </div>
    <?php include("Footer.html"); ?>

    <!-- Javascript-->
    <script src="CSS/table/assets/js/jquery.min.js"></script>
    <script src="CSS/table/assets/js/bootstrap.min.js"></script>
    <script src="CSS/table/assets/js/datatables.min.js"></script>
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
        var product = $(this).data('product');
        var type = $(this).data('type');
        var description = $(this).data('description');
        var quantity = $(this).data('quantity');
        var urgencylevel = $(this).data('urgencylevel');
        var empid = $(this).data('empid');
        $(".modal-content #id").val(ID);
        $("#product").val(product);
        $(".modal-content #type").val(type);
        $("#description").val(description);
        $("#quantity").val(quantity);
        $("#urgencylevel").val(urgencylevel);
        $("#empid").val(empid);
      });
    </script>
  </body>

</html>