<?php
/**
 * The control file of leave of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL 
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     leave
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class leave extends control
{
    /**
     * index 
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /**
     * browse 
     * 
     * @param  string $type 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function browse($type = 'personal', $date = '')
    {
        if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = date("Ym");
        $currentYear  = substr($date, 0, 4);
        $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';
        $monthList    = $this->leave->getAllMonth();
        $yearList     = array_reverse(array_keys($monthList));
        $deptList     = array();

        if($type == 'personal')
        {
            $leaveList = $this->leave->getList($currentYear, $currentMonth, $this->app->user->account);
        }
        elseif($type == 'department')
        {
            $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
            foreach($deptList as $key => $value) $deptList[$key] = $value->name;
            $leaveList = $this->leave->getList($currentYear, $currentMonth, '', array_keys($deptList));
        }
        elseif($type == 'company')
        {
            $leaveList = $this->leave->getList($currentYear, $currentMonth);
        }

        $this->view->title        = $this->lang->leave->browse;
        $this->view->type         = $type;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->deptList     = $deptList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->leaveList    = $leaveList;
        $this->display();
    }

    /**
     * review 
     * 
     * @param  int    $id 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function review($id, $status)
    {
        $leave = $this->leave->getById($id);

        /* Check privilage. */
        $user = $this->loadModel('user')->getByAccount($leave->createdBy);
        $dept = $this->loadModel('tree')->getById($user->dept);
        if(empty($dept) or $this->app->user->account == ",$dept->moderators,")
        {
            $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));
        }

        $this->leave->review($id, $status);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
    }

    /**
     * create leave.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->leave->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title = $this->lang->leave->create;
        $this->display();
    }

    /**
     * Edit leave.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $leave = $this->leave->getById($id);
        /* check privilage. */
        if($leave->createdBy != $this->app->user->account) 
        {
            $locate = helper::createLink('oa.leave', 'browse');
            $errorLink = helper::createLink('error', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($errorLink));
        }

        if($_POST)
        {
            $this->leave->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title = $this->lang->leave->edit;
        $this->view->leave = $leave;
        $this->display();
    }

    /**
     * Delete leave.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $leave = $this->leave->getByID($id);
        if($leave->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));

        $this->leave->delete($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }
}
