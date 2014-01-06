<div class = "leftBorder">
	<div>Navigation<br></div>
	<div class = "leftLinks"><a class = "left" href = "index.php">Index</a><br>
		<div class = "cat" onClick = "toggleDisplay('search')">Search</div>
		<div class = "search" id = "search" style="display: none">
			<?php
				if($screen <> 1)
				{
			?>
			<a class = "left" href = "search_trouble.php">Internal Trouble</a><br>
			<?php 
				}
				if($screen <> 2)
				{
			?>
			<a class = "left" href = "search.php">VCAR</a><br>
			<?php 
				}
			?>				
		</div>
			<?php if ($password_check == 1){?>
		<div class = "cat" onclick = "toggleDisplay('new')">New</div>
		<div class = "new" id = "new" style="display: none">
				<?php 
					if($screen <> 3)
					{
				?>	
			<a class = "left" href = "internal_trouble.php?action=add" style = "font-size: 10pt">Internal Trouble Report</a><br>
				<?php
					}					
					if($screen <> 4)
					{
				?>
			<a class = "left" href = "internal_nonconformance.php?action=add" >VCAR</a>
				<?php
					}
				?>
		</div>
			<?php }?>
		<?php if ($screen == 4)
		{?>
		<a href = "#guide" onclick = "openGuide()" class = "left">Color Guide</a>
		<a name = "guide"></a>			
		<?php }?>
	</div>
</div>