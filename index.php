<?php
session_start();

$allowed_extensions = ['jpg', 'jpeg', 'png'];

$max_file_size      = 5 * 1024 * 1024; // 20MB

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    $file_name      = $_FILES['photo']['name'];
    $file_size      = $_FILES['photo']['size'];
    $file_tmp       = $_FILES['photo']['tmp_name'];
    $file_type      = $_FILES['photo']['type'];
    $file_ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array( $file_ext, $allowed_extensions )) {
        if (empty($_FILES['photo']['name'])) {
            $_SESSION['message']   = "Please Choose a Picture..!!!";
        }
    } elseif ( $file_size  > $max_file_size ) {
        $_SESSION['message'] = "File size must be less than 2MB";
    }else {
        $upload_dir   = "uploads/";
        $destination  = $upload_dir .$file_name;

        if (move_uploaded_file( $file_tmp, $destination )) {
            $_SESSION['message']  = "File uploaded successfull";
        }else {
            $_SESSION['message']  = "Error uploading file.";
        }
    }
    header('location: index.php');
    exit();
    
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Upload File</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-50 text-gray-800">

    <?php
      if (isset($_SESSION['message'])) {
    ?>
        <div class="mx-auto mt-6 max-w-lg px-4">
          <div class="bg-blue-100 border border-blue-300 text-blue-800 text-sm rounded p-4 relative" role="alert">
            <strong class="font-semibold">Message:</strong> <?= $_SESSION['message'] ?>
            <button type="button" class="absolute top-2 right-2 text-blue-600" onclick="this.parentElement.style.display='none'">&times;</button>
          </div>
        </div>
    <?php
        unset($_SESSION['message']);
      }
    ?>

    <div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
      <form action="index.php" method="post" enctype="multipart/form-data">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Upload File</h2>

        <div class="mb-4">
          <label for="fileSelect" class="block text-sm font-medium text-gray-700 mb-2">Select a file</label>
          <input
            type="file"
            name="photo"
            id="fileSelect"
            class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
          />
        </div>

        <button
          type="submit"
          name="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition"
        >
          Upload
        </button>

        <p class="text-sm text-gray-500 mt-4">
          <strong>Note:</strong> Only .jpg, .jpeg, .png formats allowed. Max size: 5MB.
        </p>
      </form>
    </div>
  </body>
</html>
