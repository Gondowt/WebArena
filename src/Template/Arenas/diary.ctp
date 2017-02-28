<?php $this->assign('title', 'Diary'); ?>



<table class="table table-striped">
    <?php foreach ($events as $event) { ?>
        <tr>
            <td><?php echo $event['name']; ?></td>
            <td><?php echo $event['date']; ?></td>
            <td><?php echo $event['coordinate_x']; ?></td>
            <td><?php echo $event['coordinate_y']; ?></td>
        </tr>
    <?php } ?>
</table>