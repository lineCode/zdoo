<?php
/**
 * The html template file of index method of install module of ZenTaoMS.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='jumbotron shadow'>
    <h3><?php echo $lang->install->welcome;?></h3>
    <div><?php printf(nl2br(trim($lang->install->desc)), $config->version);?></div>
    
    <?php if(!isset($latestRelease)):?>
    <p class='a-center'><?php echo html::a($this->createLink('install', 'step1'), $lang->install->start, "class='btn btn-primary'");?></p>
    <?php else:?>
    <?php vprintf($lang->install->newReleased, $latestRelease);?>
    <p>
      <?php 
      echo $lang->install->choice;
      echo html::a($latestRelease->url, $lang->install->seeLatestRelease, "target='_blank'");
      echo html::a($this->createLink('install', 'step1'), $lang->install->keepInstalling, "class='btn btn-primary'");
      ?>
    </p>
    <?php endif;?>
  </div>
</div>
<?php include './footer.html.php';?>
