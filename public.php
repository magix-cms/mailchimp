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

class plugins_mailchimp_public extends database_plugins_mailchimp {
    protected $template;
    public $email_chimp,$firstname_chimp,$lastname_chimp;
    /**
     * Class constructor
     */
    public function __construct(){
        if(magixcjquery_filter_request::isPost('email_chimp')){
            $this->email_chimp = magixcjquery_form_helpersforms::inputClean($_POST['email_chimp']);
        }
        if(magixcjquery_filter_request::isPost('firstname_chimp')){
            $this->firstname_chimp = magixcjquery_form_helpersforms::inputClean($_POST['firstname_chimp']);
        }
        if(magixcjquery_filter_request::isPost('lastname_chimp')){
            $this->lastname_chimp = magixcjquery_form_helpersforms::inputClean($_POST['lastname_chimp']);
        }
        $this->template = new frontend_controller_plugins();
    }

    /**
     * @access private
     * Installation des tables mysql du plugin
     */
    private function install_table(){
        if(parent::c_show_tables() == 0){
            $this->getNotify('error');
        }else{
            return true;
        }
    }

    /**
     * Retourne le message de notification
     * @param $type
     * @param bool $display
     */
    private function getNotify($type,$display = true,$var = 'login_message'){
        $this->template->assign('message',$type);
        if($display){
            $this->template->display('message.tpl');
        }else{
            $fetch = $this->template->fetch('message.tpl');
            $this->template->assign($var,$fetch);
        }
    }

    /**
     * Inscription sur mailchimp
     * @param $mail
     * @param $fstname
     * @param $lstname
     * @param bool $notify
     */
    public function subscribe($mail, $fstname, $lstname, $notify = true){
        if(self::install_table() == true) {
            $api = parent::getApi();

            if ($api != null) {
                $iso = frontend_model_template::current_Language();
                $list = parent::getCode($api['idapi'], $iso);

                if ($list != null) {
                    $code = $list['list_id'];

                    $MailChimp = new \Drewm\MailChimp($api['account_api']);
                    $result = $MailChimp->call('lists/subscribe', array(
                        'id' => $code,
                        'email' => array('email' => $mail),
                        'merge_vars' => array('FNAME' => $fstname, 'LNAME' => $lstname),
                        'double_optin' => false,
                        'update_existing' => true,
                        'replace_interests' => false,
                        'send_welcome' => false
                    ));

                    if($notify)
                        $this->getNotify('add');
                } else {
                    $this->getNotify('error');
                }
            } else {
                $this->getNotify('error');
            }
        }
    }

    /**
     *
     */
    public function run(){
        if(isset($this->email_chimp)){
            $this->subscribe($this->email_chimp, $this->firstname_chimp, $this->lastname_chimp);
        }
    }
}
?>