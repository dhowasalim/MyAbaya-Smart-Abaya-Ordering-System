<?php
session_start();

$msg = '';

if (isset($_POST['send_message'])) {

    $name = $_POST['contact_name'];
    $email = $_POST['contact_email'];
    $message = $_POST['contact_message'];

    if ($name == '' || $email == '' || $message == '') {
        $msg = '<p class="msg-error">Please fill in all fields.</p>';
    } else {
        $msg = '<p class="msg-success">Thank you, ' . $name . '! Your message has been received.</p>';
    }
}

include 'includes/header.php';
?>

<div class="container">

    <h2>Contact Us</h2>

    <?php echo $msg; ?>

    <table border="0" cellpadding="15" width="100%">
        <tr>
            <td valign="top" width="50%">

                <h3>Send Us a Message</h3>

                <form id="contactForm" method="POST" style="background: #fff; padding: 25px; border: 1px solid #ddd;">

                    <label><strong>Your Name:</strong></label><br>
                    <input type="text" id="c_name" name="contact_name"
                        style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;"><br>

                    <label><strong>Email:</strong></label><br>
                    <input type="email" id="c_email" name="contact_email"
                        style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;"><br>

                    <label><strong>Message:</strong></label><br>
                    <textarea id="c_message" name="contact_message" rows="4"
                        style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;"></textarea><br>

                    <button type="submit" name="send_message" class="btn">Send Message</button>
                </form>

            </td>

            <td valign="top" width="50%">

                <h3>MyAbaya Store</h3>

                <p style="line-height:2; color:#444;">
                    <strong>Address:</strong><br>
                    King Fahd Road, Al Khobar<br>
                    Eastern Province, Saudi Arabia<br><br>

                    <strong>Phone:</strong><br>
                    <a href="tel:+966504642956">+966 50 464 2956</a>

                    <strong>Email:</strong><br>
                    <a href="mailto:info@myabaya.sa">info@myabaya.sa</a><br><br>

                    <strong>Working Hours:</strong><br>
                    Sat - Thu: 9:00 AM - 10:00 PM<br>
                    Friday: 4:00 PM - 10:00 PM
                </p>

                <h3>Our Location</h3>

                <iframe src="https://www.google.com/maps?q=Al%20Khobar%20Saudi%20Arabia&output=embed" width="450"
                    height="300" style="border:1px solid #ccc;">
                </iframe>

            </td>
        </tr>
    </table>

</div>

<script>
    function validateContact() {
        var name = document.getElementById('c_name').value;
        var email = document.getElementById('c_email').value;
        var message = document.getElementById('c_message').value;

        if (name == '') {
            alert('Please enter your name.');
            return false;
        }
        if (email == '') {
            alert('Please enter your email.');
            return false;
        }
        if (message == '') {
            alert('Please enter your message.');
            return false;
        }
        return true;
    }

    document.getElementById('contactForm').addEventListener('submit', function (event) {
        if (!validateContact()) {
            event.preventDefault();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>