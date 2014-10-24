<?php
namespace Fab\VidiStarter\ViewHelpers;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper which tells whether a data type is loaded through the Vidi Module Loader.
 */
class IsInModuleLoaderViewHelper extends AbstractViewHelper {

	/**
	 * Tells whether a data type is loaded through the Vidi Module Loader.
	 *
	 * @param string $dataType
	 * @return string
	 */
	public function render($dataType) {

		/** @var \TYPO3\CMS\Vidi\ModuleLoader $moduleLoader */
		$moduleLoader = GeneralUtility::makeInstance('TYPO3\CMS\Vidi\ModuleLoader', $dataType);
		$dataTypes = $moduleLoader->getDataTypes();
		return in_array($dataType, $dataTypes);
	}

}

?>