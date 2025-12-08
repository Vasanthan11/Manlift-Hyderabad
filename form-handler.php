<?php
// ---------------------------------------------------------------------
// form-handler.php
// Handles enquiry form submissions for Manlift Rentals India
// ---------------------------------------------------------------------

// 1. Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo "Method not allowed.";
    exit;
}

// 2. Helper function to safely get POST values
function get_post_value($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

// 3. Read form fields
$fullName  = get_post_value('full_name');
$phone     = get_post_value('phone');
$email     = get_post_value('email');
$location  = get_post_value('location');
$equipment = get_post_value('equipment');
$message   = get_post_value('message');

// 4. Basic validation (server-side)
//    Required: full name and phone
$errors = [];

if ($fullName === '') {
    $errors[] = "Full Name is required.";
}

if ($phone === '') {
    $errors[] = "Contact Number is required.";
}

// Optional email format check
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

// If there are errors, show a simple error page
if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Form Error - Manlift Rentals India</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                background: #F9F5F0;
                color: #344F1F;
                padding: 2rem;
            }
            .box {
                max-width: 600px;
                margin: 0 auto;
                background: #FFFFFF;
                border-radius: 12px;
                padding: 1.8rem;
                box-shadow: 0 14px 40px rgba(0,0,0,0.08);
            }
            h1 {
                font-size: 1.3rem;
                margin-bottom: 0.8rem;
            }
            ul {
                margin-left: 1.2rem;
                margin-bottom: 1rem;
            }
            a {
                color: #F4991A;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>There was a problem with your enquiry</h1>
            <p>Please check the following:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
            <p>
                <a href="javascript:history.back();">Click here to go back to the form</a>
                and fix the highlighted details.
            </p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// 5. Build the email
$to      = "info@boomliftrentalsindia.com";   // destination email
$subject = "New Enquiry - Manlift Rentals India";

$bodyLines = [
    "You have received a new enquiry from the website:",
    "",
    "Full Name: " . $fullName,
    "Contact Number: " . $phone,
    "Email Address: " . ($email !== '' ? $email : 'Not provided'),
    "Area / Location: " . ($location !== '' ? $location : 'Not provided'),
    "Selected Equipment: " . ($equipment !== '' ? $equipment : 'Not specified'),
    "",
    "Request / Message:",
    $message !== '' ? $message : 'No additional message provided.',
    "",
    "----",
    "This enquiry was submitted from the online form."
];

$body = implode("\n", $bodyLines);

// 6. Build headers
$headers   = "MIME-Version: 1.0\r\n";
$headers  .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Use email as FROM if user provided a valid one, else use a default
$fromEmail = "no-reply@manlifthyderabad.com";
if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fromEmail = $email;
}
$headers .= "From: Manlift Website <" . $fromEmail . ">\r\n";
$headers .= "Reply-To: " . $fromEmail . "\r\n";

// 7. Send the email (note: this depends on your hosting mail configuration)
$mailSent = @mail($to, $subject, $body, $headers);

// 8. Show a simple thank-you page (you can also redirect instead)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You - Manlift Rentals India</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #F9F5F0;
            color: #344F1F;
            padding: 2rem;
        }
        .box {
            max-width: 600px;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 12px;
            padding: 1.8rem;
            box-shadow: 0 14px 40px rgba(0,0,0,0.08);
            text-align: left;
        }
        h1 {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
        }
        p {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        a {
            color: #F4991A;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .status {
            margin-top: 0.8rem;
            font-size: 0.85rem;
            opacity: 0.85;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Thank you for your enquiry</h1>
        <p>
            We’ve received your details. Our team will review your requirement for boom lifts,
            scissor lifts or man lifts and get back to you as soon as possible.
        </p>
        <p>
            If your work is urgent, you can also call us directly on
            <strong>+91 99999 99999</strong>.
        </p>
        <p class="status">
            <?php if ($mailSent): ?>
                Your enquiry has been sent successfully.
            <?php else: ?>
                We couldn’t send the email automatically. Please call or email us directly
                if you don’t hear back soon.
            <?php endif; ?>
        </p>
        <p style="margin-top:1.1rem;">
            <a href="index.html">Click here to return to the homepage</a>
        </p>
    </div>
</body>
</html>
