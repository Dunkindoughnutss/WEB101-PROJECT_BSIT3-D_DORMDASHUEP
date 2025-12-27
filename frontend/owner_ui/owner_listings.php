<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<?php
include __DIR__ . '/../../backend/dbconnection.php';


$current_page = basename($_SERVER['PHP_SELF']);
$listingSuccess = false; // Flag for toast

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['images']) || count($_FILES['images']['name']) < 4) {
        die("❌ At least 4 images are required.");
    }

    $uploadDir = "../../uploads/listings/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    try {
        // INSERT listing first
        $stmt = $conn->prepare("
            INSERT INTO bh_listing (user_id, title, bh_description)
            VALUES (:user_id, :title, :desc)
        ");
        $stmt->execute([
            ':user_id' => 1,
            ':title' => $_POST['title'],
            ':desc' => $_POST['description']
        ]);

        $bh_id = $conn->lastInsertId();

        // Insert images
        $imgStmt = $conn->prepare("
            INSERT INTO bh_listing_images (bh_id, image_path)
            VALUES (:bh_id, :image)
        ");

        $uploaded = 0;

        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] !== 0) continue;
            $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
            $newName = uniqid("bh_", true) . "." . $ext;
            move_uploaded_file($tmp, $uploadDir . $newName);

            $imgStmt->execute([
                ':bh_id' => $bh_id,
                ':image' => $newName
            ]);

            $uploaded++;
        }

        if ($uploaded < 4) {
            die("❌ Upload failed. Minimum 4 images required.");
        }

        $listingSuccess = true; // Set flag for JS toast

    } catch (PDOException $e) {
        die("❌ Error: " . $e->getMessage());
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Listing - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_add_listing.css">
</head>

<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="../res/logo1.png" alt="UEP" class="logo-top">
        </div>


        <div class="nav-icons">
            <a href="owner_profile.php" class="<?php echo ($current_page == 'owner_profile.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/circle-user-round.svg" alt="profile">
            </a>

            <a href="owner_home.php" class="<?php echo ($current_page == 'owner_home.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/house.svg" alt="home">
            </a>

            <a href="owner_search.php" class="<?php echo ($current_page == 'owner_search.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/search.svg" alt="search">
            </a>

            <a href="owner_map.php" class="<?php echo ($current_page == 'owner_map.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/map-pin-house.svg" alt="map">
            </a>

            <a href="owner_listings.php" class="<?php echo ($current_page == 'owner_listings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/pencil-line.svg" alt="listings">
            </a>

            <a href="owner_manage.php" class="<?php echo ($current_page == 'owner_manage.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/check-check.svg" alt="manage">
            </a>

            <a href="owner_settings.php" class="<?php echo ($current_page == 'owner_settings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/settings.svg" alt="settings">
            </a>
        </div>

        <div class="bottom-icons">
            <a href="owner_help.php"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            <a href="javascript:void(0);" onclick="handleLogout();">
                <img class="icon" src="../icons/log-out.svg" alt="Logout">
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>UEP DORMDASH</h2>
        </div>

        <div class="content-wrapper">
            <div class="form-header">
                <div>
                    <h1>Add new <b>Listing</b></h1>
                </div>
                <div class="header-icon">
                    <img src="../icons/pencil-line.svg" alt="icon" width="50">
                </div>
            </div>

            <!-- LISTING FORM STARTS HERE -->

            <form class="listing-form" action="../owner_ui/includes/insert_listing.php" method="POST" enctype="multipart/form-data">

                <section class="form-section">
                    <h3>Owner Details</h3>
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="ownername" placeholder="Owner's full name" required>
                    </div>
                    <div class="form-row">
                        <div class="input-group">
                            <label>Mobile Number</label>
                            <input type="text" name="contact" placeholder="Contact number" required>
                        </div>
                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="Email address">
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <h3>Boarding House Details</h3>

                    <!-- IMAGE UPLOAD -->
                    <label>Add images <small>(minimum 4)</small></label>

                    <div class="image-upload-container">


                        <!-- URUPAYON INE DD, NAMOMOVE AN PREVIEW TAG, TAS ERROR SA JS -->
                        <!-- okay na sha  -->

                        <!-- Hidden file input -->
                        <input
                            type="file"
                            id="images"
                            name="images[]"
                            accept="image/*"
                            multiple
                            hidden>

                        <!-- Clickable upload box -->
                        <div class="upload-box" onclick="document.getElementById('images').click()">
                            <img src="image_placeholder.png" alt="upload">
                            <span>Click to add images</span>
                        </div>

                        <!-- Preview area -->
                        <div id="previewContainer" class="preview-grid"></div>

                    </div>

                    <!-- LISTING INFORMATION -->
                    <div class="input-group">
                        <label>Listing Information Title</label>
                        <input type="text" name="title" placeholder="e.g. Parcon Dormitory" required>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label>Monthly Rent</label>
                            <input type="number" name="monthly_rent" placeholder="₱" required>
                        </div>
                        <div class="input-group">
                            <label>Available Rooms</label>
                            <input type="number" name="available_rooms" value="5">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Address</label>
                        <input type="text" name="address" placeholder="Full address" required>
                    </div>

                    <div class="input-group">
                        <label>Description</label>
                        <textarea name="bh_description" placeholder="Tell us more about your boarding house..."></textarea>
                    </div>
                </section>

                <div class="checkbox-grid">
                    <div class="check-column">
                        <h4>Room Type</h4>
                        <label><input type="radio" name="room_type" value="Single Room" checked> Single Room</label>
                        <label><input type="radio" name="room_type" value="Studio Room"> Studio Type</label>
                        <label><input type="radio" name="room_type" value="Shared Room"> Shared Room</label>
                    </div>

                    <div class="check-column">
                        <h4>Amenities</h4>
                        <label><input type="checkbox" name="amenities[]" value="WiFi"> WiFi</label>
                        <label><input type="checkbox" name="amenities[]" value="Kitchen Area"> Kitchen Area</label>
                        <label><input type="checkbox" name="amenities[]" value="Laundry Area"> Laundry Area</label>
                        <label><input type="checkbox" name="amenities[]" value="Parking Space"> Parking Space</label>
                        <label><input type="checkbox" name="amenities[]" value="Study Area"> Study Area</label>
                    </div>

                    <div class="check-column">
                        <h4>Preferred Gender</h4>
                        <label><input type="radio" name="preferred_gender" value="Male only"> Male only</label>
                        <label><input type="radio" name="preferred_gender" value="Female only" checked> Female only</label>
                        <label><input type="radio" name="preferred_gender" value="Mixed"> Mixed</label>
                    </div>

                    <div class="check-column">
                        <h4>Curfew Policy</h4>
                        <label><input type="radio" name="curfew_policy" value="No Curfew" checked> No Curfew</label>
                        <label><input type="radio" name="curfew_policy" value="With Curfew"> With Curfew</label>
                    </div>
                </div>

                <div class="confirmation">
                    <label><input type="checkbox" required> I confirm that all information provided is true and accurate.</label>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="window.location.href='owner_home.php'">Cancel</button>
                    <button type="submit" name="submit" class="btn-submit">Add Listing</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" class="toast">Listing Successful!</div>

    <script>
        <?php if ($listingSuccess): ?>
            // Show toast
            const toast = document.getElementById('toast');
            toast.classList.add('show');

            // Hide toast after 2 seconds and redirect
            setTimeout(() => {
                toast.classList.remove('show');
                window.location.href = 'owner_home.php';
            }, 2000); // 2 seconds
        <?php endif; ?>
    </script>

    <script src="js/img_prev.js"></script>

    <script>
        // Logout Function
        function handleLogout() {
            if(confirm("Log out of UEP DormDash?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>


</body>

</html>