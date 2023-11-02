<!DOCTYPE html>
<html>

<head>
    <title>Change Information</title>
    <link rel="stylesheet" type="text/css" href="stylePage.css">
    <style>
        /* Add your custom CSS styles here */
        .container {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        .form-input input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            box-sizing: border-box;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #000;
        }

        .form-input input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-input input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php include 'navbarHomepage.php'; ?>
    <main class="container">
        <h1>Change Information</h1>

        <?php
        if (isset($success_message)) {
            echo '<p style="color: green;">' . $success_message . '</p>';
        } elseif (isset($error_message)) {
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
        ?>

        <form method="post" class="form-input">
            <label for="new_username">New Username:</label>
            <input type="text" placeholder="Enter username..." id="new_username" name="new_username" required>
            <label for="new_phone">New Phone:</label>
            <input type="text" placeholder="Enter new phone number..." id="new_phone" name="new_phone" required>
            <label for="new_address">New Address:</label>
            <input type="text" placeholder="Enter new address..." id="new_address" name="new_address" required>
            <input type="submit" name="update_profile" value="Update Profile">
        </form>
    </main>
    <?php include 'footer.php'; ?>

</body>

</html>