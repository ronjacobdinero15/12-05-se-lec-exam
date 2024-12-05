<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<div class="insertPhotoForm" style="display: flex; justify-content: center;">
    <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <p>
            <label for="#">Description</label>
            <input type="text" name="photoDescription">
        </p>
        <p>
            <label for="#">Photo Upload</label>
            <input type="file" name="image">
        </p>
        <p>
            <label for="#">Album</label>
            <select name="album_id">
                <option value="">No Album</option>
                <?php
                $albums = getAllAlbums($pdo);
                foreach ($albums as $album) {
                    echo "<option value='{$album['album_id']}'>{$album['album_name']}</option>";
                }
                ?>
            </select>
        </p>
        <p>
            <input type="submit" name="insertPhotoBtn" value="Upload Photo">
        </p>
    </form>
	</div>


	<div class="createAlbumForm" style="display: flex; justify-content: center;">
    <form action="core/handleForms.php" method="POST">
        <p>
            <label for="#">Album Name</label>
            <input type="text" name="albumName">
        </p>
        <p>
            <input type="submit" name="createAlbumBtn" value="Create Album">
        </p>
    </form>
	</div>

	<!-- Display All Albums -->
	<?php 
		$getAllAlbums = getAllAlbums($pdo);
		foreach ($getAllAlbums as $album) { 
		?>
			<div class="album" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
					<h3><?php echo $album['album_name']; ?></h3>
					<a href="viewAlbum.php?album_id=<?php echo $album['album_id']; ?>">View Album</a>
			</div>
	<?php } ?>
	
	<?php $getAllPhotos = getAllPhotos($pdo); ?>
	<?php foreach ($getAllPhotos as $row) { ?>

	<div class="images" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="photoContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%;">

			<img src="images/<?php echo $row['photo_name']; ?>" alt="" style="width: 100%;">

			<div class="photoDescription" style="padding:25px;">
				<a href="profile.php?username=<?php echo $row['username']; ?>"><h2><?php echo $row['username']; ?></h2></a>
				<p>Date Added:<i><?php echo $row['date_added']; ?></i></p>
				<h4><?php echo $row['description']; ?></h4>

				<?php if ($_SESSION['username'] == $row['username']) { ?>
					<a href="editphoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Edit </a>
					<br>
					<br>
					<a href="deletephoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Delete</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
</body>
</html>