<?php
require "includes/header.php";
require "config.php";
if (isset($_GET['id'])) {
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

<div class="row">
  <form method="POST" id="comment_data">

    <div class=" form-floating">
      <input name="username" type="hidden" value="<?php echo $_SESSION['username']; ?>" class="form-control" id="username">
    </div>

    <div class="form-floating">
      <input name="post_id" type="hidden" value="<?php echo $posts->id; ?>" class="form-control" id="post_id">
    </div>

    <div class="form-floating mt-4">
      <textarea rows="9" name="comment" class="form-control" id="comment" placeholder="body"></textarea>
      <label for="floatingTextarea">Comment</label>
    </div>
    <button name="submit" id="submit" class="w-100 btn btn-lg btn-primary mt-5" type="submit">Create comment</button>
  </form>
</div>
<?php require "includes/footer.php"; ?>
<script>
  $(document).ready(function() {
    $(document).on('submit', function(e) {
      // alert('form submited');
      var formdata = $("#comment_data").serialize() + '&submit=submit';
      $.ajax({
        type: 'post',
        url: 'insert-comments.php',
        data: formdata,
        success: function() {
          // alert('success');
          $("#Comment").text(null);
        }
      })
    })
  });
</script>