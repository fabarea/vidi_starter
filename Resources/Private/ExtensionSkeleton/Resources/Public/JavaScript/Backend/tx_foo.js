/** @namespace Vidi */

$(document).ready(function () {

	"use strict";

	/**
	 * Create relation action
	 */
	$(document).on('click', '.dataTable tbody .btn-tx_ebook_domain_model_book', function (e) {

		var contentObjectUid = $(this).data('uid');

		/**
		 * After the form was submitted.
		 *
		 * @param data
		 */
		var onSubmitSuccessCallBack = function (data) {

			// Hide modal.
			bootbox.hideAll();

			// Store in session the last edited uid
			Vidi.Session.set('lastEditedUid', contentObjectUid);

			// Reload data table.
			Vidi.table.fnDraw();
		}

		// Get content by ajax for the modal...
		$.ajax(
			{
				type: 'get',
				url: '/typo3/ajax.php',
				data: {
					ajaxID: 'vidiAjaxDispatcher',
					extensionName: 'ebook',
					pluginName: 'Pi1',
					controllerName: 'AccessCode',
					actionName: 'showWizard',
					arguments: {
						book: contentObjectUid
					}
				}
			})
			.done(function (data) {
				$('.modal-body').html(data);

				// bind submit handler to form.
				$('#form-create-multiple-codes').on('submit', function (e) {
					e.preventDefault(); // prevent native submit
					$(this).ajaxSubmit({
						success: onSubmitSuccessCallBack,
						beforeSubmit: function (arr, $form, options) {

							// Only submit if button is not disabled
							if ($('.btn-create-multiple-codes').hasClass('disabled')) {
								return false;
							}

							// Check the value download limit exists.
							// Force 0 for empty values to prevent Extbase validation errors.
							$.each(arr, function (index, element) {
								if (element.name == 'arguments[totalDownloads]' && !element.value) {
									element.value = 0;
								} else if (element.name == 'arguments[quantity]' && !element.value) {
									element.value = 0;
								}
							});

							// Else submit form
							$('.btn-create-multiple-codes').text('Sending...').addClass('disabled');
						}
					})
				});

				// bind submit handler to form.
				$('#form-create-code').ajaxForm({
					success: onSubmitSuccessCallBack,
					beforeSubmit: function (arr, $form, options) {

						// Only submit if button is not disabled
						if ($('.btn-create-code').hasClass('disabled')) {
							return false;
						}

						// Check the value download limit exists.
						$.each(arr, function (index, element) {
							if (element.name == 'arguments[downloadLimit]' && !element.value) {
								element.value = 0;
							}
						});

						// GUI updating the sending button.
						$('.btn-create-code').text('Sending...').addClass('disabled');
					}
				});

			})
			.fail(function (data) {
				alert('Something went wrong! Check out console log for more detail');
				console.log(data);
			});

		// Display modal box with default loading icon.
		var template = '<div style="text-align: center">' +
			'<img src="' + Vidi.module.publicPath + 'Resources/Public/Images/loading.gif" width="" height="" alt="" />' +
			'</div>';

		bootbox.dialog(template, [
			{
				'label': 'Cancel'
			},
			{
				'label': 'Mehrere Codes erstellen',
				'class': 'btn-create-multiple-codes btn-primary',
				'callback': function () {
					$('#form-create-multiple-codes').submit();

					// Prevent modal closing - modal will be closed after submitting.
					return false;
				}
			},
			{
				'label': 'Code erstellen',
				'class': 'btn-create-code btn-primary hidden',
				'callback': function () {
					$('#form-create-code').submit();

					// Prevent modal closing - modal will be closed after submitting.
					return false;
				}
			}
		], {
			onEscape: function () {
				// required to have escape stroke hiding modal window.
			}
		});
		e.preventDefault()
	});
});
