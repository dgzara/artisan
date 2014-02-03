
<select name="local" size="1">
   				<option value="vacio">-- Seleccione --</option>
   				<?php
            foreach($locales as $local): 
                                
            echo ("<option value=".$local->getId().">".$local->getNombre()."</option>");
                                    
            endforeach; ?>
    </select>
    

