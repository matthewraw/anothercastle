<?php 

if (isset($_POST)) {print_r($_POST);};

?>

<form class="pageNumbers" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
<input type="hidden" name="hiddenField" value="1">
<input type="submit" value="1">
<input type="submit" value="2">
<input type="submit" value="3">
<input type="submit" value="ViewAll">
</form>