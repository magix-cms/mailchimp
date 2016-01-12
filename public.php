<?php
require_once 'MailChimp.php';
class plugins_mailchimp_public extends database_plugins_mailchimp{
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
        if(parent::c_show_table() == 0){
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

                    //print_r($result);
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
class database_plugins_mailchimp{
    /**
     * Vérifie si les tables du plugin sont installé
     * @access protected
     * return integer
     */
    protected function c_show_table()
    {
        $table = 'mc_plugins_mailchimp';
        return magixglobal_model_db::layerDB()->showTable($table);
    }

    ///////////////
    // GET ////////
    ///////////////

    /**
     * @return array
     */
    protected function getApi() {
        $query = 'SELECT * FROM `mc_plugins_mailchimp` LIMIT 1';

        return magixglobal_model_db::layerDB()->selectOne($query);
    }

    /**
     * @param $api
     * @param $lang
     * @return array
     */
    protected function getCode($api, $iso) {
        $query = 'SELECT `list_id` FROM `mc_plugins_mailchimp_list` as ml
                  LEFT JOIN `mc_lang` ON `ml`.`idlang` = `mc_lang`.`idlang`
                  WHERE iso = :iso AND idapi = :api';

        return magixglobal_model_db::layerDB()->selectOne($query, array(
            ':iso' => $iso,
            ':api' => $api
        ));
    }
}
?>