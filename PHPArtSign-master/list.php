<?php
require_once("config.php");
$fontList = $artSign->getFontLists();
$baseUrl = $artSign->getBaseUrl();
echo '
<table border="1" cellspacing="0">
	<tr>
		<td>
			字体名称
		</td>
		<td>
			效果
		</td>
	</tr>
	';
foreach ($fontList as $key => $value) {
    $get = 'name=' . str_replace('字体', '', $key) . '&font=' . $key;
    echo '
	<tr>
		<td>
			' . $key . '
		</td>
		<td>
			<img src="image.php?' . $get . '"/>
		</td>
	    
	';

    echo '</tr>
	';
}
echo '</table>';
