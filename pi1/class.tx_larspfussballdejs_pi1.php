<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Lars Peipmann <lars@peipmann.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Fussball.de JavaScript' for the 'larsp_fussballde_js' extension.
 *
 * @author	Lars Peipmann <lars@peipmann.de>
 * @package	TYPO3
 * @subpackage	tx_larspfussballdejs
 */
class tx_larspfussballdejs_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_larspfussballdejs_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_larspfussballdejs_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'larsp_fussballde_js';	// The extension key.
	var $pi_checkCHash = true;
	var $local_lang_values = array();

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->local_lang_values = $this->get_local_lang_values();

		// Importiere Konfiguration
		$this->conf = $this->import_flexform_values($this->conf);
		// Ueberpruefe Dateipfade
		$errors = $this->check_pahtes($this->conf);
		if (count($errors) > 0)
			return $this->pi_wrapInBaseClass(implode('<br>', $errors));

		// Stlyesheet einbinden
		$GLOBALS['TSFE']->pSetup['includeCSS.'][$this->extKey] = $this->conf['template_dir'].$this->conf['stylesheet'];

		// Smarty-Instanz erstellen
		$smarty = tx_smarty::smarty();
		$smarty->setSmartyVar('debugging', $this->conf['smartydebugging']);
		$smarty->setSmartyVar('template_dir', $this->conf['template_dir']);

		// Werte an Smarty uebergeben
		$smarty->assign('conf', $this->conf);
		$smarty->assign('LL', $this->local_lang_values);
		$smarty->assign('contentID', $this->cObj->data['uid']);
		$smarty->assign('extKey', $this->extKey);

		// Kopf-Template im Header einbinden
		if (!isset($GLOBALS['TSFE']->additionalHeaderData[$this->extKey]))
			$GLOBALS['TSFE']->additionalHeaderData[$this->extKey] = $smarty->display($this->conf['template_head']);

		// Content ueber Smarty laden
		$content = $smarty->display($this->conf['template_content']);

		return $this->pi_wrapInBaseClass($content);
	}

	function check_pahtes($conf) {
		if (!is_dir($conf['template_dir']))
			return array($this->pi_getLL('error_dir').$conf['template_dir']);

		$errors = array();
		foreach (array($conf['template_head'], $conf['template_content'], $conf['stylesheet']) as $file)
			if (!file_exists($conf['template_dir'].$file))
				$errors[] = $this->pi_getLL('error_file').$conf['template_dir'].$file;

		return $errors;
	}

	function get_local_lang_values() {
		$local_lang_merge = $this->LOCAL_LANG['default'];
		if (isset($this->LOCAL_LANG[$this->LLkey]))
			$local_lang_merge = array_merge($local_lang_merge, $this->LOCAL_LANG[$this->LLkey]);
		if (isset($this->LOCAL_LANG[$this->altLLkey]))
			$local_lang_merge = array_merge($local_lang_merge, $this->LOCAL_LANG[$this->altLLkey]);

		$labels = array();
		foreach ($local_lang_merge as $index => $value)
			$labels[$index] = $this->pi_getLL($index);
		return $labels;
	}

	function import_flexform_values($conf) {
		$this->pi_initPIflexForm();
		$piFlexForm = $this->cObj->data['pi_flexform'];

		foreach ( $piFlexForm['data'] as $sheet => $data ) {
			foreach ( $data as $lang => $value ) {
				foreach ( $value as $key => $val ) {
					$flexvalue = $this->pi_getFFvalue($piFlexForm, $key, $sheet);

					switch ($key):
						case 'template_dir':
							foreach (array($flexvalue, $conf[$key]) as $dir) {
								if (strlen($dir) > 0) {
									$dir = str_replace('EXT:'.$this->extKey.'/', t3lib_extMgm::siteRelPath($this->extKey), $dir);
									if (is_dir($dir)) {
										if(substr($dir,-1,1)!='/') $dir .= '/';
										$conf[$key] = $dir;
										break;
									}
								}
							}
							break;
						case 'display':
						case 'smartydebugging':
							if ($flexvalue != '0' && $flexvalue != 'standard' && $flexvalue != 'default' && strlen($flexvalue)>0)
								$conf[$key] = $flexvalue;
							break;
						default:
							if (strlen($flexvalue)>0)
								$conf[$key] = $flexvalue;
					endswitch;

				}
			}
		}

		return $conf;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/larsp_fussballde_js/pi1/class.tx_larspfussballdejs_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/larsp_fussballde_js/pi1/class.tx_larspfussballdejs_pi1.php']);
}

?>