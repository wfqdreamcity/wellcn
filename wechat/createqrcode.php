<?php
header("Content-type:text/html;charset=utf-8");

echo "<form action='getQrcode.php' method='get'>
			<table>	scene_id:
				<input type='text' name='scene_id' id='scene_id' value=123 />
				<input type='submit' value='Post' />
			</table>
	 </form>";