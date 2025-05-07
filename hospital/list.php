<?php
// Include the database connection file
include("connection.php");

// Insert, update, and delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert new hospital
    if (isset($_POST['add_hospital'])) {
        $name = $_POST['name'];
        $contact_number = $_POST['contact_number'];
        $telegram_chat_id = $_POST['telegram_chat_id'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];

        $query = "INSERT INTO hospitals (name, contact_number, telegram_chat_id, address, city, country) 
                  VALUES ('$name', '$contact_number', '$telegram_chat_id', '$address', '$city', '$country')";
        $mysqli->query($query);
    }

    // Update existing hospital
    if (isset($_POST['update_hospital'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $contact_number = $_POST['contact_number'];
        $telegram_chat_id = $_POST['telegram_chat_id'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];

        $query = "UPDATE hospitals SET name='$name', contact_number='$contact_number', telegram_chat_id='$telegram_chat_id', address='$address', city='$city', country='$country' WHERE id='$id'";
        $mysqli->query($query);
    }

    // Delete hospital
    if (isset($_POST['delete_hospital'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM hospitals WHERE id='$id'";
        $mysqli->query($query);
    }
}
?>

<h2 class="text-center">Hospital Management</h2>

<!-- Button to open the add hospital modal -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addHospitalModal">Add New Hospital</button>

<!-- Hospitals Table -->
<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <h5>Hospital List</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Telegram Chat ID</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $mysqli->query("SELECT * FROM hospitals");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['contact_number']}</td>
                    <td>{$row['telegram_chat_id']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['city']}</td>
                    <td>{$row['country']}</td>
                    <td>
                        <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#viewHospitalModal{$row['id']}'>View</button>
                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editHospitalModal{$row['id']}'>Edit</button>
                        <form method='POST' class='d-inline'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <button type='submit' name='delete_hospital' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</button>
                        </form>
                    </td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Hospital Modal -->
<div class="modal fade" id="addHospitalModal" tabindex="-1" aria-labelledby="addHospitalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHospitalModalLabel">Add New Hospital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Hospital Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="telegram_chat_id" class="form-label">Telegram Chat ID</label>
                        <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" id="city" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" name="country" required>
                            <option value="">Select</option>
                            <option value="kh">Cambodia</option>
                        </select>
                    </div>
                    <button type="submit" name="add_hospital" class="btn btn-primary">Add Hospital</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Hospital Modal -->
<?php
$result = $mysqli->query("SELECT * FROM hospitals");
while ($row = $result->fetch_assoc()) {
?>
    <div class="modal fade" id="viewHospitalModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="viewHospitalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewHospitalModalLabel">View Hospital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <?= $row['name'] ?></p>
                    <p><strong>Contact Number:</strong> <?= $row['contact_number'] ?></p>
                    <p><strong>Telegram Chat ID:</strong> <?= $row['telegram_chat_id'] ?></p>
                    <p><strong>Address:</strong> <?= $row['address'] ?></p>
                    <p><strong>City:</strong> <?= $row['city'] ?></p>
                    <p><strong>Country:</strong> <?= $row['country'] ?></p>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Edit Hospital Modal -->
<?php
$result = $mysqli->query("SELECT * FROM hospitals");
while ($row = $result->fetch_assoc()) {
?>
    <div class="modal fade" id="editHospitalModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editHospitalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHospitalModalLabel">Edit Hospital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Hospital Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= $row['name'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control" value="<?= $row['contact_number'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telegram_chat_id" class="form-label">Telegram Chat ID</label>
                            <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="form-control" value="<?= $row['telegram_chat_id'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" required><?= $row['address'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control" value="<?= $row['city'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country" required>
                                <option value="">Select</option>
                                <option value="kh">Cambodia</option>
                            </select>
                        </div>
                        <button type="submit" name="update_hospital" class="btn btn-primary">Update Hospital</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>