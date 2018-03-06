<input type="hidden" name="_currentPage" id="_currentPage" value="<?php echo e($paginator->currentPage()); ?>">
<div class="col-sm-3">
    <div class="dataTables_info"><span class="page-link"><?php echo e(Lang::get('System::Common.page_list')); ?> <?php echo e($paginator->count()); ?>/ <?php echo e($paginator->total()); ?> <?php echo e(Lang::get('System::Common.page_namelist')); ?></span></div>
</div>
<div class="col-sm-6">
    <div class="main_paginate">
<?php if($paginator->hasPages()): ?>
        <ul class="pagination pagination-sm" style="margin: 0;white-space: nowrap;text-align: center;">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled"><span class="page-link"><?php echo e(Lang::get('System::Common.page_next')); ?></span></li>
            <?php else: ?>
                <li class="page-item"><a page="<?php echo e($paginator->currentPage() - 1); ?>" class="page-link" rel="prev"><?php echo e(Lang::get('System::Common.page_next')); ?></a></li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <li class="page-item disabled"><span class="page-link"><?php echo e($element); ?></span></li>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item active"><span class="page-link"><?php echo e($page); ?></span></li>
                        <?php else: ?>
                            <li class="page-item"><a page="<?php echo e($page); ?>" class="page-link"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item"><a page="<?php echo e($paginator->currentPage() + 1); ?>" class="page-link" rel="next"><?php echo e(Lang::get('System::Common.page_prev')); ?></a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link"><?php echo e(Lang::get('System::Common.page_prev')); ?></span></li>
            <?php endif; ?>
        </ul>
<?php endif; ?>
    </div>
</div>
<div class="col-sm-3">
    <div class="left_paginate">
        <div class="col-sm-6">
            <span class="page-link"><?php echo e(Lang::get('System::Common.page_viewselect')); ?></span>
        </div>
        <div class="col-sm-6">
            <select id="cbo_nuber_record_page" class="col-sm-1 form-control input-sm" name="cbo_nuber_record_page">
                <option id="15" name="15" value="15">15</option>
                <option id="50" name="50" value="50">50</option>
                <option id="100" name="100" value="100">100</option>
            </select>
        </div>
    </div>
</div>