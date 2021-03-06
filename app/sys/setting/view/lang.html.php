<?php
/**
 * The lang view file of setting module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     setting
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $isLineList = ($module == 'product' and $field == 'lineList') ? true : false;?>
<form method='post' id='ajaxForm'>
  <div class='panel'>
    <div class="panel-heading">
    <strong><i class="icon-wrench"></i> <?php echo ($module == 'common' and $field == 'currencyList') ? $lang->setting->system->fields['currencyList'] : $lang->setting->{$module}->fields[$field];?></strong>
    </div>
    <?php if($module == 'common' and $field == 'currencyList'):?>
    <div class='panel-body'>
      <table class='table table-form table-condensed table-currency'>
        <tr>
          <td colspan='3'><?php echo html::checkbox('currency', $lang->currencyList, isset($config->setting->currency) ? $config->setting->currency : '', '', 'block');?></td>
        </tr>
        <tr>
          <th class='w-60px'><?php echo $lang->setting->system->mainCurrency;?></th>
          <td class='w-200px'>
            <div class='required required-wrapper'></div>
            <?php echo html::select('mainCurrency', array('') + $lang->currencyList, isset($config->setting->mainCurrency) ? $config->setting->mainCurrency : '', "class='form-control chosen'");?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan='3'>
            <?php echo html::hidden('module', $module);?>
            <?php echo html::hidden('field', $field);?>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </div>
    <?php else:?>
    <?php 
    $tipsK = isset($lang->setting->{$module}->{$field}->key)   ? $lang->setting->{$module}->{$field}->key   : $lang->setting->key;
    $tipsV = isset($lang->setting->{$module}->{$field}->value) ? $lang->setting->{$module}->{$field}->value : $lang->setting->value;
    ?>
    <table class='table table-condensed'>
      <tr>
        <th class='w-150px text-center'><?php echo $tipsK;?></th>
        <th class='w-400px'><?php echo $tipsV;?></th>
        <th></th>
      </tr>
      <?php foreach($fieldList as $key => $value):?>
      <tr class='text-center'>
        <?php $system = isset($systemField[$key]) ? $systemField[$key] : 1;?>
        <?php $readonly = (($module == 'customer' and $field == 'statusList' and $key == 'payed') or (empty($key))) ? "readonly='readonly'" : '';?>
        <td class='text-middle'><?php echo $key === '' ? 'NULL' : $key; echo html::hidden('keys[]', $key, "$readonly") . html::hidden('systems[]', $system, "$readonly");?></td>
        <td>
          <div class='input-group'>
            <?php echo html::input("values[]", $value, "class='form-control' $readonly");?>
            <?php if($module == 'customer' and $field == 'sizeNameList'):?>
            <span class="input-group-addon fix-border fix-padding"></span>
            <?php echo html::input("sizeNoteList[]", $lang->customer->sizeNoteList[$key], "class='form-control' size='75' $readonly");?>
            <?php elseif($module == 'customer' and $field == 'levelNameList'):?>
            <span class="input-group-addon fix-border fix-padding"></span>
            <?php echo html::input("levelNoteList[]", $lang->customer->levelNoteList[$key], "class='form-control' size='75' $readonly");?>
            <?php endif;?>
          </div>
        </td>
        <td class='text-left text-middle'>
          <?php if(!($module == 'product' and $field == 'statusList' and $system == 1)):?>
          <a href='javascript:;' class='btn btn-mini add'><i class='icon-plus'></i></a>
          <?php endif;?>
          <?php if(!$system):?><a href='javascript:;' class='btn btn-mini remove'><i class='icon-remove'></i></a><?php endif;?>
        </td>
      </tr>
      <?php endforeach;?>
      <tfoot>
        <?php $langClass = $isLineList ? "class='hidden'" : ''?>
        <tr <?php echo $langClass;?>>
          <td></td>
          <td colspan='2'>
          <?php 
          $scope = array('all' => $lang->setting->allLang, $clientLang => $lang->setting->currentLang);
          echo html::radio('lang', $scope, 'all');
          ?>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
          <?php
          echo html::submitButton();
          if(!$isLineList) echo html::a(inlink('reset', "module=$module&field=$field&appName=$appName"), $lang->setting->reset, "class='btn deleter'");
          ?>
          </td>
          <td></td>
        </tr>
      </tfoot>
    </table>
   <?php endif;?>
  </div>
</form>
<?php
$placeholderK = (isset($lang->setting->placeholder->$field) and isset($lang->setting->placeholder->{$field}->key))   ? $lang->setting->placeholder->{$field}->key   : $lang->setting->placeholder->key; 
$placeholderV = (isset($lang->setting->placeholder->$field) and isset($lang->setting->placeholder->{$field}->value)) ? $lang->setting->placeholder->{$field}->value : $lang->setting->placeholder->value; 
$placeholderI = (isset($lang->setting->placeholder->$field) and isset($lang->setting->placeholder->{$field}->info))  ? $lang->setting->placeholder->{$field}->info  : $lang->setting->placeholder->info; 
$itemRow = <<<EOT
  <tr class='text-center'>
    <td>
      <input type='text' value="" name="keys[]" class='form-control' placeholder='{$placeholderK}'>
      <input type='hidden' value="0" name="systems[]">
    </td>
    <td>
      <div class='input-group'>
        <input type='text' value="" name="values[]" class='form-control' placeholder='{$lang->setting->placeholder->value}'>
      </div>
    </td>
    <td class='text-left text-middle'>
      <a href='javascript:;' class='btn btn-mini add'><i class='icon-plus'></i></a>
      <a href='javascript:;' class='btn btn-mini remove'><i class='icon-remove'></i></a>
    </td>
  </tr>
EOT;
?>
<?php js::set('itemRow', $itemRow)?>
<?php js::set('module', $module)?>
<?php js::set('field', $field)?>
<?php js::set('valueplaceholder', $placeholderV)?>
<?php js::set('infoplaceholder', $placeholderI)?>
<?php include '../../common/view/footer.html.php';?>
