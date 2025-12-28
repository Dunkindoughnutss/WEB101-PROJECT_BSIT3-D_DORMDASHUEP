<?php
// 1. Initialize Session and Database Connection
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

// 2. Security Check: Only allow logged-in renters
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = false;

// 3. Handle Support Form Submission (Optional Feature)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($subject) && !empty($message)) {
        try {
            // Check if you have a support_tickets table. If not, this block can be commented out.
            $stmt = $conn->prepare("INSERT INTO support_tickets (user_id, subject, message) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $subject, $message]);
            $success_msg = true;
        } catch (PDOException $e) {
            error_log("Support Ticket Error: " . $e->getMessage());
        }
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Help Center - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/renter_help.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="logo.png" alt="UEP" class="logo-top">
        </div>
        
        <div class="nav-icons">
            <a href="renter_profile.php" class="tab-link"><img class="icon" src="../icons/circle-user-round.svg" alt="profile"></a>
            <a href="renter_home.php" class="tab-link"><img class="icon" src="../icons/house.svg" alt="home"></a>
            <a href="renter_search.php" class="tab-link"><img class="icon" src="../icons/search.svg" alt="search"></a>
            <a href="renter_map.php" class="tab-link"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
            <a href="renter_activity.php" class="tab-link"><img class="icon" src="../icons/check-check.svg" alt="activity"></a>
            <a href="renter_settings.php" class="tab-link"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>

        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link active"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>    
            <a href="renter_logout.php" class="tab-link"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2 class="brand-title">UEP DORMDASH | HELP CENTER</h2>
        </div>

        <div class="scroll-area flex-center">
            <div class="help-container-large">
                
                <?php if ($success_msg): ?>
                    <div id="toast" style="background: #27ae60; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                        Support ticket submitted! We will contact you soon.
                    </div>
                <?php endif; ?>

                <div class="help-header">
                    <h1>How can we help?</h1>
                    <p>Browse Renter FAQs or connect with our support team</p>
                </div>

                <div class="help-grid-wide">
                    <div class="help-card">
                        <h3 class="card-label">Frequently Asked Questions</h3>
                        <div class="faq-item">
                            <h4>How do I reserve a room?</h4>
                            <p>Find a house in the 'Search' or 'Map' tab and click the 'Request' button to notify the owner.</p>
                        </div>
                        <div class="faq-item">
                            <h4>Where is my reservation status?</h4>
                            <p>Check the 'Activity' tab to track your pending, approved, or declined requests.</p>
                        </div>
                        <div class="faq-item">
                            <h4>Can I cancel a request?</h4>
                            <p>Yes, go to 'Activity' and click 'Cancel Request' on any pending application.</p>
                        </div>
                    </div>

                    <div class="help-card">
                        <h3 class="card-label">Contact Support</h3>
                        <div class="contact-info-row">
                            <strong>Email:</strong>
                            <span>support@dormdash.uep.edu.ph</span>
                        </div>
                        <div class="contact-info-row">
                            <strong>Phone:</strong>
                            <span>(055) 123-4567</span>
                        </div>
                        
                        <button class="btn-action" onclick="toggleSupportForm()">Message Support Team</button>
                        
                        <div id="supportForm" style="display: none; margin-top: 20px;">
                            <form method="POST" action="renter_help.php">
                                <input type="text" name="subject" placeholder="Subject (e.g. App Bug)" required style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ddd;">
                                <textarea name="message" placeholder="Describe your problem..." required style="width:100%; padding:10px; height:80px; margin-bottom:10px; border-radius:5px; border:1px solid #ddd;"></textarea>
                                <button type="submit" name="submit_ticket" class="btn-action" style="background:#27ae60; color:white;">Send Message</button>
                            </form>
                        </div>
                    </div>

                    <div class="help-card">
                        <h3 class="card-label">Office Hours</h3>
                        <div class="status-row">
                            <span>Monday - Friday</span>
                            <span class="time-badge">8:00 AM - 5:00 PM</span>
                        </div>
                        <hr class="divider">
                        <h3 class="card-label">Physical Office</h3>
                        <p class="office-text">UEP Admin Building, Ground Floor, Room 102, Catarman N. Samar</p>
                    </div>
                </div>

                <div class="system-footer">
                    <p>UEP DormDash Help Center • Version 1.0.0 • © 2025</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSupportForm() {
            const form = document.getElementById('supportForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        // Hide toast after 4 seconds
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => { toast.style.display = 'none'; }, 4000);
        }
    </script>
</body>
</html>