<?php 
    use Modules\Core\Helpers\MenuSystemHelper;
 ?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">Menu chức năng</li>
            <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_url => $menu): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <?php if($menu_url == $module): ?>
                        <?php  echo MenuSystemHelper::print_menu($menu_url,$menu,true);  ?>
                    <?php else: ?>
                        <?php  echo MenuSystemHelper::print_menu($menu_url,$menu);  ?>
                    <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        </ul>
    </section>
</aside>