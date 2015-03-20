<?php
/**
 * The admin view of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
include '../../common/view/header.html.php';
?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class='icon-building'></i> <?php echo $lang->entry->admin;?></strong>
    <div class='panel-actions pull-right'><?php echo html::a($this->inlink('create'), $lang->entry->create, "class='btn btn-primary'");?></div>
  </div>
  <form action='<?php echo inlink('order')?>' method='post' id='entryForm'>
  <table class='table table-bordered table-hover table-striped'>
    <thead>
      <tr class='text-center'>
        <th class='w-70px'></th>
        <th class='w-200px'><?php echo $lang->entry->name;?></th>
        <th class='w-80px'><?php echo $lang->entry->code;?></th>
        <th class='w-240px'><?php echo $lang->entry->key;?></th>
        <th><?php echo $lang->entry->ip;?></th>
        <th class='w-120px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($entries as $entry):?>
      <tr class='text-left'>
        <td><?php echo html::input("order[$entry->id]", $entry->order, "class='form-control input-sm text-center'")?></td>
        <td>
          <?php if($entry->logo):?> 
          <img src="<?php echo $entry->logo;?>" class='small-icon' /> 
          <?php else:?>
          <?php $name = $entry->abbr ? $entry->abbr : $entry->name;?>
          <?php $entryName = validater::checkCode(substr($name, 0, 1)) ? strtoupper(substr($name, 0, 1)) : substr($name, 0, 3);?>
          <?php if(validater::checkCode(substr($name, 0, 1)) and validater::checkCode(substr($name, 1, 1)))   $entryName .= strtoupper(substr($name, 1, 1));?>
          <?php if(validater::checkCode(substr($name, 0, 1)) and !validater::checkCode(substr($name, 1, 1)))  $entryName .= strtoupper(substr($name, 1, 3));?>
          <?php if(!validater::checkCode(substr($name, 0, 1)) and validater::checkCode(substr($name, 3, 1)))  $entryName .= strtoupper(substr($name, 3, 1));?>
          <?php if(!validater::checkCode(substr($name, 0, 1)) and !validater::checkCode(substr($name, 3, 1))) $entryName .= substr($name, 3, 3);?>
          <i class='icon icon-default' style="background-color: hsl(<?php echo $entry->id * 47 % 360;?>, 100%, 40%)"><span><?php echo $entryName;?></span></i>
          <?php endif;?>
          <?php echo $entry->name?>
        </td>
        <td><?php echo $entry->code?></td>
        <td><?php if($entry->integration) echo $entry->key?></td>
        <td class='text-center'><?php echo $entry->ip?></td>
        <td>
          <?php
          echo html::a($this->createLink('group', 'manageAppPriv', "type=byApp&appCode=$entry->code"), $lang->entry->priv);
          echo html::a($this->createLink('entry', 'edit', "code=$entry->code"), $lang->edit);
          if(!$entry->buildin)
          {
              echo html::a($this->createLink('entry', 'delete', "code=$entry->code"), $lang->delete, 'class="entry-deleter"');
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr><td colspan="6"><?php echo html::submitButton($lang->entry->order);?></td></tr>
    </tfoot>
    <?php if(empty($entries)):?>
    <tfoot>
      <tr><td colspan="6"><div style="float:right; clear:none;" class="page"><?php echo $lang->entry->nothing?></div></td></tr>
    </tfoot>
    <?php endif;?>
  </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
