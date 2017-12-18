<div class="sortcontainer">
	<p class="sortLabel">Sort By:</p>
	<form class="sortForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		<select class="sortselect" name="sortselect" onchange="this.form.submit()">
			<option class="sortBtn" value="default">Default</option>
			<option class="sortBtn" value="nameasc" <?php if (isset($_SESSION["sortselect"]) && $_SESSION["sortselect"] == "nameasc") echo 'selected="selected"'; ?>>Name: A to Z</option>
			<option class="sortBtn" value="namedesc" <?php if (isset($_SESSION["sortselect"]) && $_SESSION["sortselect"] == "namedesc") echo 'selected="selected"'; ?>>Name: Z to A</option>
			<option class="sortBtn" value="pricedesc" <?php if (isset($_SESSION["sortselect"]) && $_SESSION["sortselect"] == "pricedesc") echo 'selected="selected"'; ?>>Price: High to Low</option>
			<option class="sortBtn" value="priceasc" <?php if (isset($_SESSION["sortselect"]) && $_SESSION["sortselect"] == "priceasc") echo 'selected="selected"'; ?>>Price: Low to High</option>
		</select>
	</form>

	<?php 
	//if filters have been applied on page, echo reset filters button

	if (isset($_SESSION['catFilter']) || isset($_SESSION["sortselect"])) {
		echo "<form class='sortForm' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' method='POST'>";
			echo "<input type='hidden' name='resetFilter' value='reset'>";
			echo "<button class='resetFilterBtn' type='button' name='resetFilterBtn' onclick='this.form.submit()'>X Reset Filters</button>";
		echo "</form>";
	}

	?>
</div>

