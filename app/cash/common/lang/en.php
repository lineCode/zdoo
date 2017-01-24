<?php
/**
 * The en file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     common 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->app = new stdclass();
$lang->app->name = 'CASH';

$lang->menu->cash = new stdclass();
$lang->menu->cash->dashboard = 'Home|dashboard|index|';
$lang->menu->cash->all       = 'All|trade|browse|mode=all';
$lang->menu->cash->in        = 'Income|trade|browse|mode=in';
$lang->menu->cash->out       = 'Expense|trade|browse|mode=out';
$lang->menu->cash->transfer  = 'Transfer|trade|browse|mode=transfer';
$lang->menu->cash->invest    = 'Invest|trade|browse|mode=invest';
$lang->menu->cash->loan      = 'Loan|trade|browse|mode=loan';
$lang->menu->cash->check     = 'Reconcile|depositor|check|';
$lang->menu->cash->report    = 'Report|trade|report|';
$lang->menu->cash->depositor = 'Account|depositor|index|';
$lang->menu->cash->provider  = 'Supplier|provider|index|';
//$lang->menu->cash->contact   = 'Contact|contact|browse|';
$lang->menu->cash->setting   = 'Settings|tree|browse|type=in|';

/* Menu of depositor module. */
if(!isset($lang->depositor)) $lang->depositor = new stdclass();

/* Menu of trade module. */
if(!isset($lang->trade)) $lang->trade = new stdclass();
$lang->trade->menu = new stdclass();

/* Menu of provider module. */
if(!isset($lang->provider)) $lang->provider = new stdclass();
$lang->provider->menu = new stdclass();
$lang->provider->menu->browse = array('link' => 'Provider List|provider|browse|', 'alias' => 'create,edit,view');

/* Menu of contact module. */
if(!isset($lang->contact)) $lang->contact = new stdclass();
$lang->contact->menu = new stdclass();
$lang->contact->menu->browse = array('link' => 'Contact List|contact|browse|', 'alias' => 'create,edit,view');

/* Menu of report module. */
if(!isset($lang->report)) $lang->report = new stdclass();
$lang->report->menu = new stdclass();
$lang->report->menu->annual  = 'Annual Income and Expense|trade|report|';
$lang->report->menu->compare = 'Annual Compare|trade|compare|';
$lang->report->menu->export  = 'Depositor Profit and Loss|trade|export2Excel|mode=depositor';

/* Menu of setting module. */
$lang->setting = new stdclass();
$lang->setting->menu = new stdclass();
$lang->setting->menu->income    = 'Income|tree|browse|type=in|';
$lang->setting->menu->expend    = 'Expend|tree|browse|type=out|';
$lang->setting->menu->currency  = 'Currency|setting|lang|module=common&field=currencyList';
$lang->setting->menu->schema    = 'Schema|schema|browse|';
$lang->setting->menu->tradePriv = 'Expend Browse Privilege|group|managetradepriv|';
