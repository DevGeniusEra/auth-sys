<?php
require "includes/header.php";
require "config.php";
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $post = $conn->prepare("SELECT * FROM posts WHERE id = :id");
  $post->execute([':id' => $id]);

  $posts = $post->fetch(PDO::FETCH_OBJ);
}
$comments = $conn->prepare("SELECT * FROM comments WHERE post_id = :post_id");
$comments->execute([':post_id' => $id]);

$comments = $comments->fetchAll(PDO::FETCH_OBJ);
?>
<div class="row">
  <div class="card mt-5">
    <div class="card-body">
      <h5 class="card-title"><?php echo $posts->title; ?></h5>
      <p class="card-text"><?php echo $posts->body; ?></p>
    </div>
  </div>
</div>

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
  <div id="msg" class="nothing"></div>
  <div id="delete-msg" class="nothing"></div>
</div>
<div class="row">
  <?php foreach ($comments as $singleComment) : ?>
    <div class="card mt-5">
      <div class="card-body">
        <h5 class="card-title"><?php echo $singleComment->username; ?></h5>
        <p class="card-text"><?php echo $singleComment->comment; ?></p>
        <button id="delete-btn" value="<?php echo $singleComment->id; ?> " class="btn btn-danger mt-5">Delete </button>

      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require "includes/footer.php"; ?>
<script>
  $(document).on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
    var formdata = $(this).serialize() + '&submit=submit';
    $.ajax({
      type: 'post',
      url: 'insert-comments.php',
      data: formdata,
      success: function() {
        $('#comment').val(''); // Clear the comment textarea
        $('#msg').html('Added Successfully').addClass('alert alert-success bg-success text-white mt-3');
        fetch();
      }
    });
  });
  $("#delete-btn").on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    var id = $(this).val();
    $.ajax({
      type: 'post',
      url: 'delete-comment.php',
      data: {
        delete: 'delete',
        id: id
      },
      success: function() {
        $('#delete-msg').html('Deleted Successfully').toggleClass('alert alert-success bg-success text-white mt-3');
        fetch();
      }
    });
  });

  function fetch() {
    setInterval(function() {
      $("body").load("show.php?id=<?php echo $_GET['id']; ?>")
    }, 4000);
  }
</script>