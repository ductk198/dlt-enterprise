 <h4>Thông tin bảng: <?php echo e($name_table); ?></h4>
 <table class="table table-condensed table-striped">
    <thead>
      <tr>
        <th>Name column</th>
        <th>Data type</th>
		<th>Max value</th>
        <th>Allows Null</th>
      </tr>
    </thead>
    <tbody>
	<?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
	  <tr>
        <td><?php echo e($table['COLUMN_NAME']); ?></td>
        <td><?php echo e($table['DATA_TYPE']); ?></td>
        <td><?php echo e($table['CHARACTER_MAXIMUM_LENGTH']); ?></td>
		<td><?php echo e($table['IS_NULLABLE']); ?></td>
      </tr>	
	<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </tbody>
  </table>