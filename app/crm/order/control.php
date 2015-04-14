<?php
/**
 * The control file of order module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     order
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class order extends control
{
    /** 
     * The index page, locate to browse.
     * 
     * @access public
     * @return void
     */
    public function index()
    {   
        $this->locate(inlink('browse'));
    }   

    /**
     * Browse order.
     * 
     * @param string $orderBy     the order by
     * @param int    $recTotal 
     * @param int    $recPerPage 
     * @param int    $pageID 
     * @access public
     * @return void
     */
    public function browse($mode = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $orders = $this->order->getList($mode, '', $orderBy, $pager);

        /* Set pre and next condition. */
        $this->session->set('orderQueryCondition', $this->dao->get());
        $this->session->set('orderList', $this->app->getURI(true));

        /* Set allowed edit order ID list. */
        $this->app->user->canEditOrderIdList = ',' . implode(',', $this->order->getOrdersSawByMe('edit', array_keys($orders))) . ',';

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $this->config->order->search['actionURL'] = $this->createLink('order', 'browse', 'mode=bysearch');
        $this->config->order->search['params']['o.customer']['values'] = $this->loadModel('customer')->getPairs('client', true);
        $this->config->order->search['params']['o.product']['values']  = array('' => '') + $this->loadModel('product')->getPairs();
        $this->search->setSearchParams($this->config->order->search);

        $this->view->title        = $this->lang->order->browse;
        $this->view->orders       = $orders;
        $this->view->customers    = $this->loadModel('customer')->getList('client');
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->pager        = $pager;
        $this->view->mode         = $mode;
        $this->view->orderBy      = $orderBy;
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->currencyList = $this->common->getCurrencyList();
        if($orders) $this->view->totalAmount = $this->order->countAmount($orders);
        $this->display();
    }

    /**
     * Create an order.
     * 
     * @access public
     * @return viod
     */
    public function create()
    {
        if($_POST)
        {
            $return = $this->order->create();
            $this->send($return);
        }

        unset($this->lang->order->menu);
        $products = $this->loadModel('product')->getPairs();
        $this->view->products     = array( 0 => '') + $products;
        $this->view->customers    = $this->loadModel('customer')->getPairs('client');
        $this->view->title        = $this->lang->order->create;
        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();

        $this->display();
    }

    /**
     * Edit an order.
     * 
     * @param  int $orderID 
     * @access public
     * @return void
     */
    public function edit($orderID)
    {
        $order = $this->order->getByID($orderID);
        $this->loadModel('common', 'sys')->checkPrivByCustomer(empty($order)? '0' : $order->customer, 'edit');

        if($_POST)
        {
            $changes = $this->order->update($orderID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {   
                $orderActionID = $this->loadModel('action')->create('order', $orderID, 'Edited');
                $this->action->logHistory($orderActionID, $changes);

                $customerActionID = $this->loadModel('action')->create('customer', $order->customer, 'editOrder', '', html::a($this->createLink('order', 'view', "orderID=$order->id"), $order->id));
                $this->action->logHistory($customerActionID, $changes);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "orderID=$orderID")));
        }

        $this->view->title        = $this->lang->order->edit;
        $this->view->order        = $order;
        $this->view->products     = $this->loadModel('product')->getPairs();
        $this->view->customers    = $this->loadModel('customer')->getPairs('client');
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();

        $this->display();
    }

    /**
     * View an order.
     * 
     * @param  int $orderID 
     * @access public
     * @return void
     */
    public function view($orderID)
    {
        $order = $this->order->getByID($orderID);
        $this->loadModel('common', 'sys')->checkPrivByCustomer(empty($order)? '0' : $order->customer);

        /* Set allowed edit order ID list. */
        $this->app->user->canEditOrderIdList = ',' . implode(',', $this->order->getOrdersSawByMe('edit', (array)$orderID)) . ',';

        $this->app->loadLang('resume');
        $this->app->loadLang('contract');

        $uri = $this->app->getURI(true);
        $this->session->set('customerList', $uri);
        $this->session->set('productList',  $uri);
        $this->session->set('contactList',  $uri);
        if(!$this->session->contractList) $this->session->set('contractList', $uri);

        $this->view->order        = $order;
        $this->view->title        = $this->lang->order->view;
        $this->view->customer     = $this->loadModel('customer')->getByID($order->customer);
        $this->view->contract     = $this->order->getContract($orderID);
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();
        $this->view->currencySign = $this->common->getCurrencySign();
        $this->view->preAndNext   = $this->common->getPreAndNextObject('order', $orderID);
    
        $this->display();
    }
    
    /**
     * Close an order.
     * 
     * @param  int    $orderID 
     * @access public
     * @return void
     */
    public function close($orderID) 
    {
        $order = $this->order->getByID($orderID);
        $this->loadModel('common', 'sys')->checkPrivByCustomer(empty($order)? '0' : $order->customer, 'edit');

        if(!empty($_POST))
        {
            $this->order->close($orderID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('order', $orderID, 'Closed', $this->post->closedNote, $this->lang->order->closedReasonList[$this->post->closedReason]);
            $this->loadModel('action')->create('customer', $order->customer, 'closeOrder', $this->lang->order->closedReason . $this->lang->order->closedReasonList[$this->post->closedReason] . '<br />' . $this->post->closedNote, html::a($this->createLink('order', 'view', "orderID=$orderID"), $orderID));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title   = $this->lang->order->close;
        $this->view->orderID = $orderID;
        $this->display();
    }

    /**
     * Activate an order.
     * 
     * @param  int    $orderID 
     * @access public
     * @return void
     */
    public function activate($orderID) 
    {
        $order = $this->order->getByID($orderID); 
        $this->loadModel('common', 'sys')->checkPrivByCustomer(empty($order)? '0' : $order->customer, 'edit');

        if(!empty($_POST))
        {
            $this->order->activate($orderID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('order', $orderID, 'Activated', $this->post->comment);
            $this->loadModel('action')->create('customer', $order->customer, 'activateOrder', $this->post->comment, html::a($this->createLink('order', 'view', "orderID=$orderID"), $orderID));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title   = $this->lang->order->activate;
        $this->view->orderID = $orderID;
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Get contact of an customer.
     *
     * @param  int    $order
     * @param  string $orderBy     the order by
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function contact($order, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $order = $this->order->getByID($order);
        $contacts = $this->loadModel('contact')->getList($order->customer, 'client', $orderBy, $pager);

        $this->view->title    = $this->lang->order->contact;
        $this->view->contacts = $contacts;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;

        $this->display();
    }

    /**
     * Assign an order function.
     *
     * @param  int    $orderID
     * @param  null   $table  
     * @access public
     * @return void
     */
    public function assign($orderID, $table = null)
    {
        $order   = $this->order->getByID($orderID);
        $members = $this->loadModel('user')->getPairs('noclosed, nodeleted, devfirst');
        $this->loadModel('common', 'sys')->checkPrivByCustomer(empty($order)? '0' : $order->customer, 'edit');

        if($_POST)
        {
            $this->order->assign($orderID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->assignedTo)
            {
                $actionID = $this->loadModel('action')->create('order', $orderID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->sendmail($orderID, $actionID);

                $this->loadModel('action')->create('customer', $order->customer, 'assignOrder',  $this->lang->order->assignedTo . $members[$this->post->assignedTo] . '<br />' . $this->post->comment, html::a($this->createLink('order', 'view', "orderID=$orderID"), $orderID));
            }


            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title   = $this->lang->order->assignedTo;
        $this->view->orderID = $orderID;
        $this->view->order   = $order;
        $this->view->members = $members;
        $this->display();
    }

    /**
     * delete 
     * 
     * @param  int    $orderID 
     * @access public
     * @return void
     */
    public function delete($orderID)
    {
        $this->order->delete(TABLE_ORDER, $orderID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    /**
     * Send email.
     * 
     * @param  int    $orderID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($orderID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Set toList and ccList. */
        $order  = $this->order->getByIdList($orderID);
        $users  = $this->loadModel('user')->getPairs('noletter');
        $order  = $order[$orderID];
        $toList = $order->assignedTo;

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Create the email content. */
        $this->view->order  = $order;
        $this->view->action = $action;
        $this->view->users  = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, 'ORDER#' . $order->id . $this->lang->colon . $order->title, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * get data to export.
     * 
     * @param  string $mode 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function export($mode, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    { 
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if($mode == 'all') $pager = null;

        if($_POST)
        {
            $orderLang   = $this->lang->order;
            $orderConfig = $this->config->order;

            /* Create field lists. */
            $fields = explode(',', $orderConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($orderLang->$fieldName) ? $orderLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            $orders = $this->dao->select('*')->from(TABLE_ORDER)->where('deleted')->eq(0)->orderBy($orderBy)->page($pager)->fetchAll('id');

            /* Get users, products and projects. */
            $users     = $this->loadModel('user')->getPairs('noletter');
            $products  = $this->loadModel('product')->getPairs();
            $customers = $this->loadModel('customer')->getPairs();

            /* Get related products id lists. */
            $relatedProductIdList = array();

            foreach($orders as $order)
            {
                /* Process related stories. */
                $relatedProducts = explode(',', $order->product);
                foreach($relatedProducts as $productID)
                {
                    if($productID) $relatedProductIdList[$productID] = trim($productID);
                }
            }

            /* Get related products names. */
            $relatedProducts = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('id')->in($relatedProductIdList)->fetchPairs();

            foreach($orders as $order)
            {
                /* fill some field with useful value. */
                if(isset($customers[$order->customer]))                       $order->customer     = $customers[$order->customer] . "(#$order->customer)";
                if(isset($orderLang->statusList[$order->status]))             $order->status       = $orderLang->statusList[$order->status];
                if(isset($orderLang->closedReasonList[$order->closedReason])) $order->closedReason = $orderLang->closedReasonList[$order->closedReason];
                if(isset($this->lang->currencyList[$order->currency]))        $order->currency     = $this->lang->currencyList[$order->currency];

                if(isset($users[$order->createdBy]))   $order->createdBy   = $users[$order->createdBy];
                if(isset($users[$order->editedBy]))    $order->editedBy    = $users[$order->editedBy];
                if(isset($users[$order->assignedTo]))  $order->assignedTo  = $users[$order->assignedTo];
                if(isset($users[$order->assignedBy]))  $order->assignedBy  = $users[$order->assignedBy];
                if(isset($users[$order->signedBy]))    $order->signedBy    = $users[$order->signedBy];
                if(isset($users[$order->activatedBy])) $order->activatedBy = $users[$order->activatedBy];
                if(isset($users[$order->contactedBy])) $order->contactedBy = $users[$order->contactedBy];
                if(isset($users[$order->closedBy]))    $order->closedBy    = $users[$order->closedBy]; 

                $order->createdDate    = substr($order->createdDate, 0, 10);
                $order->editedDate     = substr($order->editedDate, 0, 10);
                $order->assignedDate   = substr($order->assignedDate, 0, 10);
                $order->signedDate     = substr($order->signedDate, 0, 10);
                $order->activatedDate  = substr($order->activatedDate, 0, 10);
                $order->contactedDate  = substr($order->contactedDate, 0, 10);
                $order->nextDate       = substr($order->contactedDate, 0, 10);
                $order->closedDate     = substr($order->closedDate, 0, 10);

                if($order->product)
                {
                    $tmpProducts = array();
                    $productIdList = explode(',', $order->product);
                    foreach($productIdList as $productID)
                    {
                        if(!$productID) continue;
                        $productID = trim($productID);
                        $tmpProducts[] = isset($relatedProducts[$productID]) ? $relatedProducts[$productID] : $productID;
                    }
                    $order->product = join("; \n", $tmpProducts);
                }
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $orders);
            $this->post->set('kind', 'order');
            $this->fetch('file', 'export2CSV' , $_POST);
        }

        $this->display();
    }
}
