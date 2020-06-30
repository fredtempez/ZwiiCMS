/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2008-2018, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

/**
 * Paramétrage du format de date
 */
$(function() {
	$(".datepicker").flatpickr({
		altInput: true,
		altFormat: "d/m/Y",
		enableTime: false,
		locale: "fr",
		dateFormat: "d/m/Y"
	});
});
