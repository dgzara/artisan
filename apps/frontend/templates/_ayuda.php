<div>
<table>
    <tr>
        <tr align="center">
          <h1><?php echo 'Ayuda: '.$moduleName ?></h1>
          <p id="myclose"></p>
        </tr>
    </tr>
	
	<?php foreach($helps as $link => $link_name):?>
		<?php foreach($link_name as $name => $description):?>
		<tr>
			<td>
                            <a href ="<?php echo url_for($link); ?>"><?php echo $name.":"; ?></a>
                        </td>
                        <td>
                                <?php echo $description; ?>
                        </td>
		</tr>
        <?php endforeach;?>     
    <?php endforeach;?>
</table>
</div>
	
	