<?php 
  require "includes/header.php"; 
  require "config.php"; 
  if(isset($_GET['id'])){
    $id = $_GET['id'];
    $post = $conn->prepare("SELECT * FROM posts WHERE id = :id");
    $post->execute([':id' => $id]);

    $posts = $post->fetch(PDO::FETCH_OBJ);
  }
?>
<main class="row">
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title"><?php echo $posts->title; ?></h5>
            <p class="card-text"><?php echo $posts->body; ?></p>
        </div>
    </div>
</main>

<?php require "includes/footer.php"; ?>
