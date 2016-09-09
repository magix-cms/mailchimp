<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2016 magix-cms.com support[at]magix-cms[point]com
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
class database_plugins_mailchimp
{
	/**
	 * Checks if the tables of the plugins are installed
	 * @access protected
	 * return integer
	 */
	protected function c_show_tables(){
		$tables = array(
			'mc_plugins_mailchimp',
			'mc_plugins_mailchimp_list'
		);

		$i = 0;
		do {
			$t = magixglobal_model_db::layerDB()->showTable($tables[$i]);
			$i++;
		} while($t && $i < count($tables));

		return $t;
	}

	/**
	 * Checks if the requested table is installed
	 * @param $t
	 * @return integer
	 */
	protected function c_show_table($t){
		return magixglobal_model_db::layerDB()->showTable($t);
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
	 * @param $api
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

	/**
	 * @param $api
	 * @param $iso
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