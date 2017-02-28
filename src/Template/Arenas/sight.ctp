<?php $this->assign('title', 'Sight');?>



<table class="table table-bordered">
	<tbody>
    <?php for ($y = 0; $y < 10; ++$y) { ?>
        <tr>
            <?php for ($x = 0; $x < 15; ++$x) {
                if (abs(intval($porteeVision['coordinate_x']) - $x) + abs(intval($porteeVision['coordinate_y']) - $y) <= $porteeVision['skill_sight']) { ?>
                    <td><?php if($map[$y][$x]['name']=='0'){echo $this->Html->image('/img/grass.png', ['alt' => 'Gras']);}
					else if($map[$y][$x]['name']=='C'){echo $this->Html->image('/img/colomn.png', ['alt' => 'colomn']);}
					else { echo $map[$y][$x]['name'];echo $this->Html->image('/img/knight-sprite.png', ['alt' => 'player']);}
					
               } else { ?>
                    <td><?php echo $this->Html->image('/img/brouillard.png', ['alt' => 'Brouillard']); ?></td>
                <?php } ?>
            <?php } ?>
        </tr>
    <?php } ?>
	</tbody>
</table>



<div  id="actions" class="panel panel-default"> 
	<div class="col-md-2"> Actions : </div>
    <div class="col-md-4">
    <?php
    echo $this->Form->create();
    echo $this->Form->input('formAction', ['type' => 'hidden']);
    echo $this->Form->radio(
        'action',
        [
            ['text' => $action['gauche']['action'] . ' a gauche', 'value' => $action['gauche']['action'] . '/gauche/' . $action['gauche']['fighter']],
            ['text' => $action['droite']['action'] . ' a droite', 'value' => $action['droite']['action'] . '/droite/' . $action['droite']['fighter']],
            ['text' => $action['haut']['action'] . ' en haut', 'value' => $action['haut']['action'] . '/haut/' . $action['haut']['fighter']],
            ['text' => $action['bas']['action'] . ' en bas', 'value' => $action['bas']['action'] . '/bas/' . $action['bas']['fighter']]
        ]
		
    );?>
    </div>

    <div class="col-md-3">

    <?php
    echo $this->Form->button('Action');
    echo $this->Form->end();
    
    ?>
    </div>
	<div class="col-md-3">
		<?php echo $surroundingMessage; ?>
	</div>

</div>
