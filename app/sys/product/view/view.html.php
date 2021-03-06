<?php 
/**
 * The view file of product module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     product 
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php'; ?>
<ul id='menuTitle'>
  <li><?php commonModel::printLink('product', 'browse', '', $lang->product->list);?></li>
  <li class='divider angle'></li>
  <li class='title'><?php echo $product->name;?></li>
</ul>
<div class='row-table'>
  <div class='col-main'>
    <?php if($product->files):?>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->files;?></strong></div>
      <div class='panel-body'><?php echo $this->fetch('file', 'printFiles', array('files' => $product->files, 'fieldset' => 'false'))?></div>
    </div>
    <?php endif;?>
    <?php echo $this->fetch('action', 'history', "objectType=product&objectID={$product->id}");?>
    <div class='page-actions'>
      <?php
      echo "<div class='btn-group'>";
      commonModel::printLink('product', 'edit',   "productID=$product->id", $this->lang->edit,   "class='btn btn-default' data-toggle='modal'");
      commonModel::printLink('product', 'delete', "productID=$product->id", $this->lang->delete, "class='deleter btn btn-default'");
      echo '</div>';
      echo html::backButton();
      ?>
    </div>
  </div>
  <div class='col-side'>
    <div class='panel'>
      <div class='panel-heading'><strong><i class='icon-file-text-alt'></i> <?php echo $lang->product->basicInfo;?></strong></div>
      <div class='panel-body'>
        <table class='table table-info'>
          <tr>
            <th class='w-50px'><?php echo $lang->product->name;?></th>
            <td><?php echo $product->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->code;?></th>
            <td><?php echo $product->code;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->line;?></th>
            <td><?php echo $lang->product->lineList[$product->line];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->type;?></th>
            <td><?php echo $lang->product->typeList[$product->type];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->status;?></th>
            <td><?php echo $lang->product->statusList[$product->status];?></td>
          </tr>
        </table>
      </div>
    </div> 
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
