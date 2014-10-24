<?php
namespace Fab\VidiStarter\Controller\Backend;

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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller which handles actions related to Vidi Starter in the Backend.
 */
class StarterController extends ActionController {

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
		} elseif (strlen($extensionName) == 0) {

			$message = sprintf('Extension name can not be blank. Nothing was done!', $extensionName);
			$severity = \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR;

		} elseif (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extensionName) || strlen($extensionName) == 0) {

			$message = sprintf('Extension name "%s" is already loaded. Nothing was done!', $extensionName);
			$severity = \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR;
		} else {

			/** @var \Fab\VidiStarter\Service\StarterService $starterService */
			$starterService = $this->objectManager->get('Fab\VidiStarter\Service\StarterService', $extensionName, $dataTypes);
			$starterService->kickStart();

			$message = sprintf('Extension "%s" created. Next step is to activate it in the Extension Manager and fine tune the grid display.', $extensionName);
			$severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK;
		}

		$this->flashMessageContainer->add($message, '', $severity);
		$this->redirect('index');
	}
}
?>
