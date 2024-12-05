<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['editAlbumBtn'])) {
	$album_id = $_POST['album_id'];
	$album_name = $_POST['album_name'];

	if (updateAlbumName($pdo, $album_id, $album_name)) {
			echo "Album name updated successfully!";
			header("Location: viewAlbum.php?album_id=" . $album_id); 
			exit;
	} else {
			echo "Failed to update album name.";
	}
}

if (isset($_POST['deleteAlbumBtn'])) {
	$album_id = $_POST['album_id'];

	if (deletePhotosInAlbum($pdo, $album_id)) {
			if (deleteAlbum($pdo, $album_id)) {
					$_SESSION['message'] = "Album and all photos deleted successfully!";
					$_SESSION['status'] = "200";
					header("Location: index.php");
					exit;
			} else {
					$_SESSION['message'] = "Failed to delete album.";
					$_SESSION['status'] = "400";
					header("Location: index.php");  
					exit;
			}
	} else {
			$_SESSION['message'] = "Failed to delete photos in album.";
			$_SESSION['status'] = "400";
			header("Location: index.php");  
			exit;
	}
}

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	header("Location: ../login.php");
}

if (isset($_POST['insertPhotoBtn'])) {
	$description = $_POST['photoDescription'];
	$album_id = $_POST['album_id'];
	
	if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
			$photo_name = $_FILES['image']['name'];
			$photo_tmp_name = $_FILES['image']['tmp_name'];
			$photo_size = $_FILES['image']['size'];
			$photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
			$allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

			if (in_array(strtolower($photo_ext), $allowed_exts)) {
					$new_photo_name = uniqid('', true) . '.' . $photo_ext;
					$photo_upload_dir = '../images/' . $new_photo_name;
					
					if (move_uploaded_file($photo_tmp_name, $photo_upload_dir)) {
							if (insertPhoto($pdo, $new_photo_name, $_SESSION['username'], $description, $album_id)) {
									echo "Photo uploaded successfully!";
									header("Location: viewAlbum.php?album_id=" . $album_id);
									exit;
							} else {
									echo "Failed to insert photo into database.";
							}
					} else {
							echo "Failed to upload the image.";
					}
			} else {
					echo "Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.";
			}
	} else {
			echo "No file uploaded.";
	}
}

if (isset($_POST['deletePhotoBtn'])) {
	$photo_name = $_POST['photo_name'];
	$photo_id = $_POST['photo_id'];
	$deletePhoto = deletePhoto($pdo, $photo_id);

	if ($deletePhoto) {
			unlink("../images/" . $photo_name);  
			$_SESSION['message'] = "Photo deleted successfully!";
			$_SESSION['status'] = "200";
			header("Location: index.php");  
			exit;
	} else {
			$_SESSION['message'] = "Failed to delete photo.";
			$_SESSION['status'] = "400";
			header("Location: index.php"); 
			exit;
	}
}

if (isset($_POST['createAlbumBtn'])) {
	$albumName = trim($_POST['albumName']);

	if (!empty($albumName)) {
			$createAlbum = createAlbum($pdo, $albumName, $_SESSION['username']);
			$_SESSION['message'] = $createAlbum['message'];
			$_SESSION['status'] = $createAlbum['status'];
			header("Location: ../index.php");
	} else {
			$_SESSION['message'] = "Album name cannot be empty";
			$_SESSION['status'] = '400';
			header("Location: ../index.php");
	}
}
