<?php
/**
 * The dynamic view file of my of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include './header.html.php';?>
<?php js::set('type', $type);?>
<table class='table table-condensed table-hover table-striped tablesorter table-fixed'>
  <?php $vars = "type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
  <thead>
  <tr class='colhead'>
    <th class='w-150px'><?php commonModel::printOrderLink('date',       $orderBy, $vars, $lang->action->date);?></th>
    <th class='w-user'> <?php commonModel::printOrderLink('actor',      $orderBy, $vars, $lang->action->actor);?></th>
    <th class='w-100px'><?php commonModel::printOrderLink('action',     $orderBy, $vars, $lang->action->action);?></th>
    <th class='w-80px'> <?php commonModel::printOrderLink('objectType', $orderBy, $vars, $lang->action->objectType);?></th>
    <th class='w-id'>   <?php commonModel::printOrderLink('id',         $orderBy, $vars, $lang->action->actionID);?></th>
    <th><?php echo $lang->action->objectName;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($actions as $action):?>
  <?php if($action->appName == 'sys') $action->appName = 'superadmin';?>
  <?php if($action->objectType == 'todo') $action->appName = 'dashboard';?>
  <tr class='text-center'>
    <td><?php echo $action->date;?></td>
    <td><?php echo zget($users, $action->actor, $action->actor);?></td>
    <td><?php echo $action->actionLabel;?></td>
    <td><?php echo $lang->action->objectTypes[$action->objectType];?></td>
    <td><?php echo $action->objectID;?></td>
    <td class='text-left'><?php if($action->objectName) echo html::a("javascript:$.openEntry(\"" . $action->appName . "\",\"" . $action->objectLink . "\")", $action->objectName);?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>