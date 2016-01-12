<?php
require_once 'MailChimp.php';
class plugins_mailchimp_admin extends DBmailchimp {
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
        if(parent::c_show_table() == 0){
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
     * @param $api
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
class DBmailchimp
{
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
    protected function g_list($api, $lang) {
        $query = 'SELECT * FROM `mc_plugins_mailchimp_list`
                  WHERE idlang = :lang AND idapi = :api';

        return magixglobal_model_db::layerDB()->selectOne($query, array(
            ':lang' => $lang,
            ':api' => $api
        ));
    }

    ///////////////////
    // ACTIONS ////////
    ///////////////////

    /**
     * @param $api
     */
    protected function s_api($api) {
        $query = 'INSERT INTO `mc_plugins_mailchimp` (`account_api`)
                  VALUES (:api)';

        magixglobal_model_db::layerDB()->insert($query, array(
            ':api' => $api
        ));
    }

    /**
     * @param $id
     */
    protected function d_api($api) {
        $query = 'DELETE FROM `mc_plugins_mailchimp`
                  WHERE `idapi` = :api';

        magixglobal_model_db::layerDB()->delete($query, array(
            ':api' => $api
        ));
    }

    /**
     * @param $list
     * @param $api
     * @param $lang
     */
    protected function a_list($list,$api,$lang) {
        $query = 'INSERT INTO `mc_plugins_mailchimp_list` (`idapi`,`list_id`,`idlang`)
                  VALUES (:api,:list,:lang)';

        magixglobal_model_db::layerDB()->insert($query, array(
            ':api'  => $api,
            ':list' => $list,
            ':lang' => $lang
        ));
    }

    /**
     * @param $id
     */
    protected function d_list($id) {
        $query = 'DELETE FROM `mc_plugins_mailchimp_list`
                  WHERE `idlist` = :id';

        magixglobal_model_db::layerDB()->delete($query, array(
            ':id' => $id
        ));
    }
}
?>
