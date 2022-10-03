<?php

define('TITLE', 'Xóa một Trích dẫn');
include '../partials/header.php';

echo '<h2>Xóa một Trích dẫn</h2>';

include '../partials/check_admin.php';

// echo '<p>Trang đang được xây dựng...</p>';
include '../partials/db_connect.php';
if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
   $query = "SELECT quote, source, favorite FROM quotes WHERE id={$_GET['id']}";

   try {
      $sth = $pdo->query($query);
      $row = $sth->fetch();
   }catch(PDOException $e) {
      $pdo_error = $e->getMessage();
   }

   if(!empty($row)) {
      ?>
      <form action="delete_quote.php" method="post">
         <p>Bạn có chắc muốn xóa trích dẫn này?</p>
         <div>
            <blockquote>
               <?php 
                  htmlspecialchars($row['quote']);
               ?>
            </blockquote>--
               <?php
                  htmlspecialchars($row['source']);
                  if($row['favorite']) echo '<strong>Yêu thích!</strong>';
               ?>
         </div>
         <br>
         <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
         <p><input type="submit" name="submit" value="Xóa trích dẫn này!"></p>
      </form>
      <?php
   }else {
      $error_message = 'Không thể lấy được trích dẫn này';
      $reason = $pdo_error ?? 'Không rõ nguyên nhân';
      include '../partials/show_error.php';
   }
}elseif (isset($_POST['id']) && is_numeric($_POST['id']) && ($_POST['id'] > 0)) {
   $query = "DELETE FROM quotes WHERE id={$_POST['id']} LIMIT 1";

   try {
      $sth = $pdo->query($query);
   }catch (PDOException $e) {
      $pdo_error = $e->getMessage();
   }

   if($sth && $sth->rowCount() == 1) {
      echo "<p>Trích dẫn đã bị xóa.</p>";
   }else {
      $error_message = 'Không thể xóa trích dẫn này';
      $reason = $pdo_error ?? 'Không rõ nguyên nhân';
      include '../partials/show_error.php';
   }
}else {
   include '../partials/show_error.php';
}

include '../partials/footer.php';
?>