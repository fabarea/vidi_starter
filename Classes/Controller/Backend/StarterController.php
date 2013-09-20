<?php
namespace TYPO3\CMS\VidiStarter\Controller\Backend;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Fabien Udriot <fabien.udriot@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * Controller which handles actions related to Vidi Starter in the Backend.
 */
class StarterController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\CMS\Core\Page\PageRenderer
	 * @inject
	 */
	protected $pageRenderer;

	/**
	 * Initialize every action.
	 */
	public function initializeAction() {
		$this->pageRenderer->addInlineLanguageLabelFile('EXT:vidi_starter/Resources/Private/Language/locallang.xlf');
	}

	/**
	 * Display a form where to create a new BE module.
	 *
	 * @return void
	 */
	public function indexAction() {
		$dataTypes = array();
		foreach ($GLOBALS['TCA'] as $dataType => $tca) {
			$dataTypes[] = $dataType;
		}

		$this->view->assign('dataTypes', $dataTypes);
	}

	/**
	 * @param string $extensionName
	 * @param array $dataTypes
	 * @return void
	 */
	public function createAction($extensionName, array $dataTypes = array()) {
		$extensionName = trim($extensionName);

		if (empty($dataTypes)) {
			# Not working?
			#$this->getControllerContext()->getFlashMessageQueue()->addMessage(
			#	new \TYPO3\CMS\Core\Messaging\FlashMessage('foo', '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR)
			#);
			$message = sprintf('No data type selected.', $extensionName);
			$severity = \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING;

		} elseif (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extensionName) && strlen($extensionName) == 0) {

			$message = sprintf('Extension name "%s" already exists or is blank. Nothing was done!', $extensionName);
			$severity = \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR;
		}  else {

			/** @var \TYPO3\CMS\VidiStarter\Service\StarterService $starterService */
			$starterService = $this->objectManager->get('TYPO3\CMS\VidiStarter\Service\StarterService');
			$starterService->kickStart($extensionName, $dataTypes);

			$message = sprintf('Extension "%s" created. Next step is to activate it in the Extension Manager and fine tune the grid display.', $extensionName);
			$severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK;
		}

		$this->flashMessageContainer->add($message, '', $severity);
		$this->redirect('index');
	}
}
?>
