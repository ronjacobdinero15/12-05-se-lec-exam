<?php 
    require_once 'core/dbConfig.php';  
    require_once 'core/models.php';     

    $album_id = $_GET['album_id'];
    $album = getAlbumById($pdo, $album_id);  
    $getPhotos = getPhotosInAlbum($pdo, $album_id);  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Album</title>
    <link rel="stylesheet" href="styles/styles.css"> 
</head>
<body>

<div class="editAlbumForm" style="display: flex; justify-content: center; margin-bottom: 20px;">
    <form action="core/handleForms.php" method="POST">
        <p>
            <label for="album_name">Album Name</label>
            <input type="text" name="album_name" value="<?php echo htmlspecialchars($album['album_name']); ?>" required>
        </p>
        <p>
            <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">
            <input type="submit" name="editAlbumBtn" value="Update Album Name">
        </p>
    </form>
</div>

<!-- Delete Album Form -->
<div class="deleteAlbumForm" style="display: flex; justify-content: center;">
    <form action="core/handleForms.php" method="POST">
        <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">
        <input type="submit" name="deleteAlbumBtn" value="Delete Album (This will also delete all photos)" style="background-color: red; color: white;">
    </form>
</div>

<!-- Upload Photo Form -->
<div class="insertPhotoForm" style="display: flex; justify-content: center;">
    <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <p>
            <label for="photoDescription">Description</label>
            <input type="text" name="photoDescription" required>
        </p>
        <p>
            <label for="image">Photo Upload</label>
            <input type="file" name="image" accept="image/*" required>
        </p>
        <p>
            <label for="album_id">Album</label>
            <select name="album_id">
                <option value="<?php echo htmlspecialchars($album_id); ?>" selected>Current Album</option>
            </select>
        </p>
        <p>
            <input type="submit" name="insertPhotoBtn" value="Upload Photo">
        </p>
    </form>
</div>

<h1 style="text-align: center;">Photos in this Album</h1>

<?php if ($getPhotos): ?>
    <?php foreach ($getPhotos as $photo): ?>
        <div class="images" style="display: flex; justify-content: center; margin-top: 25px;">
            <div class="photoContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray; width: 50%;">
                <img src="images/<?php echo htmlspecialchars($photo['photo_name']); ?>" alt="photo" style="width: 100%;">
                <div class="photoDescription" style="padding: 25px;">
                    <a href="profile.php?username=<?php echo htmlspecialchars($photo['username']); ?>"><h2><?php echo htmlspecialchars($photo['username']); ?></h2></a>
                    <p>Date Added: <i><?php echo htmlspecialchars($photo['date_added']); ?></i></p>
                    <h4><?php echo htmlspecialchars($photo['description']); ?></h4>

                    <?php if ($_SESSION['username'] == $photo['username']): ?>
                        <a href="editphoto.php?photo_id=<?php echo $photo['photo_id']; ?>" style="float: right;"> Edit </a>
                        <br>
                        <br>
                        <a href="deletephoto.php?photo_id=<?php echo $photo['photo_id']; ?>" style="float: right;"> Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No photos in this album yet.</p>
<?php endif; ?>

</body>
</html>
