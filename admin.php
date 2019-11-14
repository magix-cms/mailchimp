<?php
require_once ('db.php');
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
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
 /**
 * MAGIX CMS
 * @category mailchimp
 * @package plugins
 * @copyright MAGIX CMS Copyright (c) 2008 - 2019 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * Author: Salvatore Di Salvo
 * Date: 11-10-2019
 * @name plugins_mailchimp_admin
 */
require_once 'MailChimp.php';
class plugins_mailchimp_admin extends plugins_mailchimp_db
{
	/**
	 * @var object
	 */
	protected $controller,
		$data,
		$template,
		$message,
		$plugins,
		$modelLanguage,
		$collectionLanguage,
		$header,
		$settings,
		$setting,
		$api;

	/**
	 * Les variables globales
	 * @var integer $edit
	 * @var string $action
	 * @var string $tabs
	 */
	public $edit = 0,
		$edit_type = '',
		$action = '',
		$tabs = '';

	/**
	 * Les variables plugin
	 * @var array $adv
	 * @var integer $id
	 * @var array $advantage
	 */
	public $id = 0,
		$id_api = 0,
		$api_key = null,
		$list_id = null,
		$name_list = null,
		$content = array(),
		$page,
		$offset;

    /**
	 * Construct class
	 */
	public function __construct(){
		$this->template = new backend_model_template();
		$this->plugins = new backend_controller_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->data = new backend_model_data($this);
		$this->settings = new backend_model_setting();
		$this->setting = $this->settings->getSetting();
		$this->header = new http_header();

		$formClean = new form_inputEscape();

		// --- GET
		if(http_request::isGet('controller')) $this->controller = (string)$formClean->simpleClean($_GET['controller']);
		if (http_request::isGet('edit')) $this->edit = (int)$formClean->numeric($_GET['edit']);
		if (http_request::isGet('action')) $this->action = (string)$formClean->simpleClean($_GET['action']);
		elseif (http_request::isPost('action')) $this->action = (string)$formClean->simpleClean($_POST['action']);
		if (http_request::isPost('edit_type')) $this->edit_type = (string)$formClean->simpleClean($_POST['edit_type']);
		if (http_request::isGet('tabs')) $this->tabs = (string)$formClean->simpleClean($_GET['tabs']);
		if (http_request::isGet('page')) $this->page = intval($formClean->simpleClean($_GET['page']));
		$this->offset = (http_request::isGet('offset')) ? intval($formClean->simpleClean($_GET['offset'])) : 25;

		// --- ADD or EDIT
		if (http_request::isPost('id')) $this->id = intval($formClean->simpleClean($_POST['id']));
		if (http_request::isPost('id_api')) $this->id_api = (int)$formClean->numeric($_POST['id_api']);
		if (http_request::isPost('list_id')) $this->list_id = (string)$formClean->simpleClean($_POST['list_id']);
		if (http_request::isPost('content')) $this->content = (array)$formClean->arrayClean($_POST['content']);

		// --- Config
		if (http_request::isPost('api_key')) $this->api_key = (string)$formClean->simpleClean($_POST['api_key']);
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName()
	{
		return $this->template->getConfigVars('mailchimp_plugin');
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @param $data
	 * @return array
	 */
	private function setItemContentData($data){
		$arr = array();
		foreach ($data as $page) {
			if (!array_key_exists($page['id_list'], $arr)) {
				$arr[$page['id_list']] = array();
				$arr[$page['id_list']]['id_list'] = $page['id_list'];
				$arr[$page['id_list']]['list_id'] = $page['list_id'];
			}
			$arr[$page['id_list']]['content'][$page['id_lang']] = array(
				'id_lang' => $page['id_lang'],
				'name_list' => $page['name_list'],
				'active' => $page['active']
			);
		}
		return $arr;
	}

	/**
	 * Retrieve all available lists
	 */
	private function getLists()
	{
		$lists = null;
		if ($this->api['api_key'] != null) {
			$MailChimp = new \Drewm\MailChimp($this->api['api_key']);
			$arg = array(
				'fields' => 'lists.id,lists.name'
			);
			$lists = $MailChimp->call('lists','GET',$arg);
		}
		$this->template->assign('lists',$lists['lists']);
	}

	/**
	 * Retrieve members of a list
	 * @param string $id
	 */
	private function getMembers($id)
	{
		$lists = null;
		if ($this->api['api_key'] !== null && $id !== null) {
			$MailChimp = new \Drewm\MailChimp($this->api['api_key']);

			$arg = array(
				'fields' => 'members.email_address,members.language',
				'count' => (int)$this->offset,
				'offset' => $this->offset * (isset($this->page) ? $this->page : 0)
			);

			$members = $MailChimp->call('lists/'.$id.'/members','GET',$arg);
			$this->template->assign('nbp',ceil($members['total_items'] / $this->offset));
		}
		$this->template->assign('members',$members);
	}

	/**
	 * @param $id_list
	 */
	private function saveContent($id_list)
	{
		$langs = $this->modelLanguage->setLanguage();

		foreach ($langs as $id => $iso) {
			$content = isset($this->content[$id]) ? $this->content[$id] : array();
			$content['id_lang'] = $id;
			$content['id_list'] = $id_list;
			$content['active'] = isset($content['active']) ? 1 : 0;
			if(!isset($content['name_list']) || empty($content['name_list'])) $content['name_list'] = $this->name_list;
			else $this->name_list = $content['name_list'];
			$params = array(
				'type' => 'content',
				'data' => $content
			);

			$contentList = $this->getItems('content',array('id_list'=>$id_list, 'id_lang'=>$id),'one',false);

			if($contentList) {
				$this->upd($params);
			}
			else {
				$this->add($params);
			}
		}
	}

	/**
	 * Insert data
	 * @param array $config
	 */
	private function add($config)
	{
		switch ($config['type']) {
			case 'list':
			case 'content':
				parent::insert(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Update data
	 * @param array $config
	 */
	private function upd($config)
	{
		switch ($config['type']) {
			case 'api':
			case 'content':
				parent::update(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete a record
	 * @param $config
	 */
	private function del($config)
	{
		switch ($config['type']) {
			case 'list':
				parent::delete(
					array('type' => $config['type']),
					$config['data']
				);
				$this->message->json_post_response(true,'delete',array('id' => $this->id));
				break;
		}
	}

	/**
	 * Execute the plugin
	 */
	public function run()
	{
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
		$this->api = $this->getItems('api',null,'one');

		if($this->action) {
			switch ($this->action) {
				case 'add':
					if(!empty($this->content)) {
						list($this->list_id, $this->name_list) = explode('|', $this->list_id);
						$this->add(
							array(
								'type' => 'list',
								'data' => array(
									'id_api' => $this->api['id_api'],
									'list_id' => $this->list_id
								)
							)
						);

						$list = $this->getItems('root',null,'one',false);

						if ($list['id_list']) {
							$this->saveContent($list['id_list']);
							$this->message->json_post_response(true,'add_redirect');
						}
					}
					else {
						$this->modelLanguage->getLanguage();
						$this->getLists();
						$lists = $this->getItems('lists',$defaultLanguage['id_lang'],'all',false);
						$narr = array();
						foreach ($lists as $list) {
							$narr[] = $list['list_id'];
						}
						$this->template->assign('clists',$narr);
						$this->template->display('add.tpl');
					}
					break;
				case 'edit':
					if($this->id_api) {
						$this->upd(
							array(
								'type' => 'api',
								'data' => array(
									'id' => $this->id_api,
									'key' => $this->api_key
								))
						);
						$this->message->json_post_response(true, $this->edit_type === 'update' ? 'update' : 'add_redirect', array('result'=>$this->id_api));
					}
					elseif (isset($this->id) && !empty($this->content)) {
						$this->saveContent($this->id);
						$this->message->json_post_response(true, 'update', array('result'=>$this->id));
					}
					else {
						$this->modelLanguage->getLanguage();
						$data = $this->getItems('data',$this->edit,'all',false);
						$editData = $this->setItemContentData($data);
						$this->template->assign('list',$editData[$this->edit]);
						$this->getMembers($editData[$this->edit]['list_id']);
						$scheme = array(
							'email_address' => array(
								'type' => 'text',
								'title' => 'email_list'
							),
							'language' => array(
								'type' => 'text',
								'title' => 'lang_list'
							)
						);
						$this->template->assign('scheme',$scheme);
						$this->template->display('edit.tpl');
					}
					break;
				case 'delete':
					if(isset($this->id)) {
						$this->del(
							array(
								'type'=>'list',
								'data'=>array(
									'id' => $this->id
								)
							)
						);
					}
					break;
			}
		}
		else {
			if($this->api['api_key']) {
				$this->getItems('lists',$defaultLanguage['id_lang'],'all');
				$assign = array(
					'id_list',
					'list_id',
					'name_list' => ['title' => 'name']
				);
				$this->data->getScheme(array('mc_mailchimp_list','mc_mailchimp_content'),array('id_list','list_id','name_list'),$assign);
			}
			$this->template->display('index.tpl');
		}
	}
}