<?php
	if(Session::get_flash('notice'))
	{
		$flash = Session::get_flash('notice');
		echo '<div class="alert alert-'.$flash['type'].'">';
		echo '<a class="close" data-dismiss="alert">×</a>';
		echo $flash['msg'];
		echo "</div>";
	}
?>