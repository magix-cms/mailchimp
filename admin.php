<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
require_once('db/mailchimp.php');
require_once 'MailChimp.php';

class plugins_mailchimp_admin extends database_plugins_mailchimp {
    protected $header,$template,$message;
    public static $notify = array('plugin'=>'true','template'=>'message-mailchimp.tpl','method'=>'fetch','assignFetch'=>'notifier');

    /**
     * Les variables globales
     */
    public $action,$tab,$getlang;

    /**
     * Les variables du plugin gategorylink
     */
    public $idapi,$list_id,$idlist;

    /**
     * Construct class
     */
    public function __construct(){
        if(class_exists('backend_model_message')){
            $this->message = new backend_model_message();
        }

        if(magixcjquery_filter_request::isGet('getlang')){
            $this->getlang = magixcjquery_form_helpersforms::inputNumeric($_GET['getlang']);
        }
        if(magixcjquery_filter_request::isGet('action')){
            $this->action = magixcjquery_form_helpersforms::inputClean($_GET['action']);
        }
        if(magixcjquery_filter_request::isGet('tab')){
            $this->tab = magixcjquery_form_helpersforms::inputClean($_GET['tab']);
        }

        if(magixcjquery_filter_request::isPost('idapi')){
            $this->idapi = magixcjquery_form_helpersforms::inputClean($_POST['idapi']);
        }
        if(magixcjquery_filter_request::isPost('list_id')){
            $this->list_id = magixcjquery_form_helpersforms::inputClean($_POST['list_id']);
        }
        if(magixcjquery_filter_request::isPost('idlist')){
            $this->idlist = magixcjquery_form_helpersforms::inputNumeric($_POST['idlist']);
        }

        $this->template = new backend_controller_plugins();
    }

    /**
     * @access private
     * Installation des tables mysql du plugin
     */
    private function install_table(){
        if(parent::c_show_tables() == 0){
            $this->template->db_install_table('db.sql', 'request/install.tpl');
        }else{
            return true;
        }
    }

    /**
     * Retourne le message de notification
     * @param $type
     */
    private function notify($type){
        $this->message->getNotify($type,self::$notify);
    }

    ///////////////
    // DATA ///////
    ///////////////

    /**
     * @return array
     */
    private function setListCall() {
        $api = parent::getApi();

        if ($api != null) {
            $id = parent::g_list($api['idapi'], $this->getlang);

            if ($id != null) {
                $MailChimp = new \Drewm\MailChimp($api['account_api']);

                return $MailChimp->call('lists/members', array(
                    'id' => $id['list_id']
                ));
            }
        }
    }

    /**
     * @param $api
     * @return array
     */
    private function getlist($api) {
        return parent::g_list($api, $this->getlang);
    }

    /////////////////
    // ACTION ///////
    /////////////////

    /**
     *
     */
    private function saveApi() {
        if (isset($this->idapi)) {
            parent::s_api($this->idapi);
        }
    }

    /**
     * @param $api
     */
    private function delApi($api) {
        if (isset($api['idapi'])) {
            parent::d_api($api['idapi']);
        }
    }

    /**
     *
     */
    private function addList() {
        if (isset($this->list_id)) {
            $api = parent::getApi();

            if ($api != null) {
                parent::a_list($this->list_id,$api['idapi'],$this->getlang);
            }
        }
    }

    /**
     *
     */
    private function delList() {
        if (isset($this->idlist)) {
            parent::d_list($this->idlist);
        }
    }

    /**
     * @throws Exception
     */
    public function run(){
        if(self::install_table() == true){
            if(magixcjquery_filter_request::isGet('getlang')) {
                $api = parent::getApi();

                if (isset($this->tab)) {
                    if($this->tab == 'about')
                    {
                        $this->template->display('about.tpl');
                    }
                    else if($this->tab == 'list')
                    {
                        if (isset($this->action)) {
                            switch ($this->action) {
                                case 'add':
                                    $this->addList();
                                    $this->notify('add');
                                    break;
                                case 'deleteList':
                                    $this->delList();
                                    $this->notify('delete');
                                    break;
                            }
                        }

                        $this->template->assign('account', $api['account_api']);
                        $this->template->assign('list', $this->getList($api['idapi']));
                        $this->template->assign('getListCall', $this->setListCall());
                        $this->template->display('list.tpl');
                    }
                    else if ($this->tab == 'account')
                    {
                        switch ($this->action) {
                            case 'save':
                                if ($this->idapi != null) {
                                    $this->saveApi();
                                    $this->notify('save');
                                }
                                $this->template->assign('account', $this->idapi);
                                break;
                            case 'deleteApi':
                                $this->delApi($api);
                                $this->notify('reset');
                                break;
                            default:
                                if ($api != null)
                                    $this->template->assign('account', $api['account_api']);
                        }

                        $this->template->display('account.tpl');
                    }
                }
                else
                {
                    if ($api != null)
                        $this->template->assign('account', $api['account_api']);
                    $this->template->display('account.tpl');
                }
            }
        }
    }

    /**
     * @return array
     */
    public function setConfig(){
        return array(
            'url'=> array(
                'lang'=>'list',
                'name'=>'MailChimp'
            ),
            'icon'=> array(
                'type'=>'font',
                'name'=>'fa fa-envelope-o'
            )
        );
    }
}
?>
