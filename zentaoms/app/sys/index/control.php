<?php
/**
 * The control file of index module of ZenTaoMS.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     index 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class index extends control
{
    /**
     * Construct.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $entries    = $this->loadModel('entry')->getEntries();
        $leftEntry  = ',';
        $allEntries = '';

        foreach($entries as $entry)
        {
            if($entry->visible) $leftEntry .= $entry->id . ',';

            $sso  = $this->createLink('entry', 'visit', "entryID=$entry->id");
            $logo = !empty($entry->logo) ? $entry->logo : '';
            $size = !empty($entry->size) ? ($entry->size != 'max' ? $entry->size : "'$entry->size'") : "'max'";
            
            if(!isset($entry->control))  $entry->control = '';
            if(!isset($entry->position)) $entry->position = '';
            $allEntries .= "entries.push(
            {
                id:       '$entry->id',
                name:     '$entry->name',
                url:      '$sso',
                open:     '$entry->open', 
                desc:     '$entry->name',
                size:     $size,
                icon:     '$logo',
                control:  '$entry->control',
                position: '$entry->position'
                display:  'fixed',
            });\n";
        }

        $blocks = empty($this->config->index->block) ? array() : (array)$this->config->index->block;
        ksort($blocks);

        $this->view->allEntries = $allEntries;
        $this->view->leftEntry  = $leftEntry;
        $this->view->blocks     = $blocks;
        $this->display();
    }
}
