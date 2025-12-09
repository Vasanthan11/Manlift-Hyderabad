<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize inputs
    $name     = htmlspecialchars(trim($_POST["full_name"] ?? ''));
    $phone    = htmlspecialchars(trim($_POST["phone"] ?? ''));
    $email    = htmlspecialchars(trim($_POST["email"] ?? ''));
    $location = htmlspecialchars(trim($_POST["location"] ?? ''));
    $equipment = htmlspecialchars(trim($_POST["equipment"] ?? ''));
    $message  = htmlspecialchars(trim($_POST["message"] ?? ''));

    // Your email
    $to = "info@manlifthyderabad.com"; // ✅ change if needed
    $subject = "New Enquiry - Manlift Rentals";

    // Email body
    $body = "
New Enquiry Received:

Full Name: $name
Phone: $phone
Email: $email
Location: $location
Equipment: $equipment

Message:
$message
";

    // Headers
    $headers  = "From: Enquiry Form <noreply@yourdomain.com>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8";

    // Send mail
    if (mail($to, $subject, $body, $headers)) {
        // Redirect to thank you page
        header("Location: thank-you.html");
        exit();
    } else {
        echo "Error: Mail not sent. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
