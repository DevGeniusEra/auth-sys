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

$ratings = $conn->prepare("SELECT * FROM rates WHERE post_id = '$id' AND user_id='$_SESSION[user_id]'");
$ratings->execute();
$rating = $ratings->fetch(PDO::FETCH_OBJ);
?>
<div class="row">
  <div class="card mt-5">
    <div class="card-body">
      <h5 class="card-title"><?php echo $posts->title; ?></h5>
      <p class="card-text"><?php echo $posts->body; ?></p>
      <form id="formdata" method="post">
        <div class="my-rating"></div>
        <input id="rating" type="hidden" name="rating">
        <input id="post_id" type="hidden" name="post_id" value="<?php echo $posts->id; ?>">
        <input id="user_id" type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
      </form>
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
        <?php if (isset($_SESSION['username']) and $_SESSION['username'] == $singleComment->username) : ?>
          <button id="delete-btn" value="<?php echo $singleComment->id; ?> " class="btn btn-danger mt-5">Delete </button>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require "includes/footer.php"; ?>
<script>
  $(document).ready(function() {
    $(document).on('submit', function(e) {
      e.preventDefault(); // Prevent the default form submission
      var formdata = $("#comment_data").serialize() + '&submit=submit';
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
  //Rating Sys
  $(document).ready(function() {
    $(".my-rating").starRating({
      starSize: 25,
      initialRating: <?php
                      if (isset($rating->rating) AND isset($rating->user_id) AND $rating->user_id == $_SESSION['user_id'])  {
                        echo $rating->rating;
                      } else {
                        echo '0';
                      }
                      ?>,
      callback: function(currentRating, $el) {
        $("#rating").val(currentRating);
      }
    });

    $(".my-rating").click(function(e) {
      e.preventDefault();
      var formdata = $("#formdata").serialize() + '&insert=insert';
      $.ajax({
        type: "post",
        url: 'insert-rating.php',
        data: formdata,
        success: function() {
          // alert(formdata);
        }
      });
    });
  });
</script>