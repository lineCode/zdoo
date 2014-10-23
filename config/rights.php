<?php
/**
 * The config items for rights.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     config
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
/* Init the rights. */
$config->rights = new stdclass();

$config->rights->guest = array();

$config->rights->member['index']['index']            = 'index';
$config->rights->member['entry']['visit']            = 'visit';
$config->rights->member['entry']['blocks']           = 'blocks';
$config->rights->member['entry']['setBlock']         = 'setblock';
$config->rights->member['dashboard']['index']        = 'index';
$config->rights->member['error']['index']            = 'index';
$config->rights->member['block']['admin']            = 'admin';

$config->rights->member['misc']['qrcode']            = 'qrcode';
$config->rights->member['user']['setreferer']        = 'setreferer';
$config->rights->member['user']['profile']           = 'profile';
$config->rights->member['user']['uploadavatar']      = 'uploadavatar';
$config->rights->member['user']['cropavatar']        = 'cropavatar';
$config->rights->member['user']['edit']              = 'edit';
$config->rights->member['contract']['getorder']      = 'getorder';
$config->rights->member['customer']['getoptionmenu'] = 'getoptionmenu';
$config->rights->member['contact']['getoptionmenu']  = 'getoptionmenu';
$config->rights->member['thread']['locate']          = 'locate';

$config->rights->member['reply']['post']             = 'post';
$config->rights->member['reply']['edit']             = 'edit';
$config->rights->member['reply']['delete']           = 'delete';
$config->rights->member['reply']['deletefile']       = 'deletefile';

$config->rights->member['action']['createrecord']    = 'createrecord';
$config->rights->member['action']['editrecord']      = 'editrecord';
$config->rights->member['action']['history']         = 'history';
$config->rights->member['action']['editcomment']     = 'editcomment';

$config->rights->member['file']['buildform']         = 'buildform';
$config->rights->member['file']['buildlist']         = 'buildlist';
$config->rights->member['file']['printfiles']        = 'printfiles';
$config->rights->member['file']['ajaxupload']        = 'ajaxupload';
$config->rights->member['file']['browse']            = 'browse';
$config->rights->member['file']['edit']              = 'edit';
$config->rights->member['file']['upload']            = 'upload';
$config->rights->member['file']['download']          = 'download';
$config->rights->member['file']['senddownheader']    = 'senddownheader';
$config->rights->member['file']['delete']            = 'delete';
$config->rights->member['file']['ajaxpasteimage']    = 'ajaxpasteimage';
$config->rights->member['file']['filemanager']       = 'filemanager';
$config->rights->member['file']['sort']              = 'sort';
