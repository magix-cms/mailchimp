<?php
class plugins_mailchimp_db
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'lists':
					$sql = 'SELECT l.*,lc.*,lang.*
							FROM mc_mailchimp_list AS l
							JOIN mc_mailchimp_content AS lc USING(id_list)
							JOIN mc_lang AS lang ON(lc.id_lang = lang.id_lang)
							WHERE lc.id_lang = :id
							GROUP BY l.id_list';
					break;
				case 'iso_lists':
					$sql = 'SELECT l.*,lc.*,lang.*
							FROM mc_mailchimp_list AS l
							JOIN mc_mailchimp_content AS lc USING(id_list)
							JOIN mc_lang AS lang ON(lc.id_lang = lang.id_lang)
							WHERE lang.iso_lang = :id AND lc.active = 1
							GROUP BY l.id_list';
					break;
				case 'data':
					$sql = 'SELECT l.*,lc.*,lang.*
							FROM mc_mailchimp_list AS l
							JOIN mc_mailchimp_content AS lc USING(id_list)
							JOIN mc_lang AS lang ON(lc.id_lang = lang.id_lang)
							WHERE l.id_list = :id';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'api':
					$sql = 'SELECT * FROM mc_mailchimp ORDER BY id_api DESC LIMIT 0,1';
					break;
				case 'root':
					$sql = 'SELECT * FROM mc_mailchimp_list ORDER BY id_list DESC LIMIT 0,1';
					break;
				case 'content':
					$sql = 'SELECT * FROM `mc_mailchimp_content` WHERE `id_list` = :id_list AND `id_lang` = :id_lang';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'list':
				$sql = 'INSERT INTO `mc_mailchimp_list`(id_api, list_id) 
						VALUES (:id_api,:list_id)';
				break;
			case 'content':
				$sql = 'INSERT INTO `mc_mailchimp_content`(id_list,id_lang,name_list,active) 
						VALUES (:id_list,:id_lang,:name_list,:active)';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'api':
				$sql = 'UPDATE mc_mailchimp SET api_key = :key WHERE id_api = :id';
				break;
			case 'content':
				$sql = 'UPDATE mc_mailchimp_content 
							SET 
								name_list = :name_list,
								active = :active
							WHERE id_list = :id_list 
							AND id_lang = :id_lang';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';
			$sql = '';

			switch ($config['type']) {
				case 'list':
					$sql = 'DELETE FROM mc_mailchimp_list WHERE id_list IN ('.$params['id'].')';
					$params = array();
					break;
			}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}