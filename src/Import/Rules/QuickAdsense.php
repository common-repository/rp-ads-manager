<?php

namespace RP\AdsManager\Import\Rules;

use RP\AdsManager\Import\Rule;

final class QuickAdsense extends Rule {
	protected $plugin_key = 'quick-adsense/quick-adsense.php';

	protected $settings_key = 'quick_adsense_2_options';

	private $pluginInfo = [];

	public function __construct() {
		$this->pluginInfo = [
			'Name' => 'QuickAdsense'
		];
	}

	public function getInfo( $key ) {
		if (file_exists(WP_PLUGIN_DIR . '/' . $this->plugin_key)) {
			$this->pluginInfo = get_plugin_data( WP_PLUGIN_DIR . '/' . $this->plugin_key );
		}

		return array_key_exists( $key, $this->pluginInfo ) ? $this->pluginInfo[ $key ] : false;
	}

	public function getPortedConfig() {
		$config = [
			'ads' => [],
			'wgt' => []
		];

		$aligns = [1 => 'left', 2 => 'center', 3 => 'right', 4 => 'none'];

		$display = $params = [];

		$options = get_option($this->settings_key, []);

		foreach ($options as $key => $value) {
			if (false !== strpos($key, 'App')) {
				$display[] = str_replace('App', '', $key);
			}

			if (false !== strpos($key, 'Rnd') && $value != '0') {
				$params[(int)$value] = [];
				$test = str_replace('Rnd', '', $key);
				switch ($test){
					case 'Begn':
						$params[(int)$value]['position'] = 'top';
						break;
					case 'Midd':
						$params[(int)$value]['position'] = 'middle';
						break;
					case 'Endi':
						$params[(int)$value]['position'] = 'bottom';
						break;
					case 'More':
						$params[(int)$value]['position'] = 'after-more';
						break;
					case 'Lapa':
						$params[(int)$value]['position'] = 'before-last-p';
						break;
					case 'Par1':
					case 'Par2':
					case 'Par3':
						$params[(int)$value]['position'] = 'after-n-p';
						$params[(int)$value][ 'number'] = (int)$options[$test.'Nup'];
						if (array_key_exists($test.'Con', $options)) {
							$params[(int)$value]['in_the_end'] = true;
						}
						break;
					case 'Img1':
						$params[(int)$value]['position'] = 'after-n-img';
						$params[(int)$value]['number'] = (int)$options[$test.'Nup'];
						if (array_key_exists($test.'Con', $options)) {
							$params[(int)$value]['in_the_end'] = true;
						}
						break;
				}
			}

			if (false !== strpos($key, 'AdsCode') && $value !== '') {
				if (array_key_exists(str_replace('AdsCode', '', $key), $config['ads'])) {
					$config['ads'][str_replace('AdsCode', '', $key)]['code'] = $value;
				} else {
					$config['ads'][str_replace('AdsCode', '', $key)] = [
						'code' => $value
					];
				}
			}
			if (false !== strpos($key, 'AdsAlign') && $value !== '' && $options['AdsCode'.str_replace('AdsAlign', '', $key)] !== '') {
				if (array_key_exists(str_replace('AdsAlign', '', $key), $config['ads'])) {
					$config['ads'][ str_replace( 'AdsAlign', '', $key ) ]['align'] = $aligns[ $value ];
				} else {
					$config['ads'][str_replace('AdsAlign', '', $key)] = [
						'align' => $aligns[ $value ]
					];
				}
			}
			if (false !== strpos($key, 'WidCode') && $value !== '') {
				$config['wgt'][str_replace('WidCode', '', $key)] = [
					'code' => $value
				];
			}
		}

		$relations = [
			'Post' => ['top', 'middle', 'bottom', 'after-n-p', 'after-n-img', 'before-last-p', 'after-more'],
			'Page' => ['top', 'middle', 'bottom', 'after-n-p', 'after-n-img', 'before-last-p'],
			'Home' => ['top', 'middle', 'bottom'],
			'Cate' => ['top', 'middle', 'bottom'],
			'Arch' => ['top', 'middle', 'bottom'],
			'Tags' => ['top', 'middle', 'bottom']
		];

		$displayConvert = [
			'Post' => 'single',
			'Page' => 'page',
			'Home' => 'homepage',
			'Cate' => 'category',
			'Arch' => 'archive',
			'Tags' => 'tag'
		];

		foreach ($config['ads'] as $key => &$ad) {
			if (array_key_exists($key, $params) && !empty($display)) {
				$ad['options'] = [];
				foreach ($display as $page) {
					if (in_array($params[$key]['position'], $relations[$page])) {
						$ad['options'][$displayConvert[$page]] = $params[$key];
						$ad['options'][$displayConvert[$page]]['aligment'] = $ad['align'];
						$ad['options'][$displayConvert[$page]]['class'] = '';
						$ad['options'][$displayConvert[$page]]['policy'] = 'none';
					}
				}
			}
			unset($ad['align']);
		}

		if (empty($config['ads'])) {
			unset($config['ads']);
		}

		if (empty($config['wgt'])) {
			unset($config['wgt']);
		}

		return $config;
	}
}
