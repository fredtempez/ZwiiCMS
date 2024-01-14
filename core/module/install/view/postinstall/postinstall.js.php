/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
$("#installId").on("change keydown keyup",(function(event){var userId=$(this).val();if(8!==event.keyCode&&37!==event.keyCode&&39!==event.keyCode&&46!==event.keyCode&&window.getSelection().toString()!==userId){var searchReplace={"á":"a","à":"a","â":"a","ä":"a","ã":"a","å":"a","ç":"c","é":"e","è":"e","ê":"e","ë":"e","í":"i","ì":"i","î":"i","ï":"i","ñ":"n","ó":"o","ò":"o","ô":"o","ö":"o","õ":"o","ú":"u","ù":"u","û":"u","ü":"u","ý":"y","ÿ":"y","Á":"A","À":"A","Â":"A","Ä":"A","Ã":"A","Å":"A","Ç":"C","É":"E","È":"E","Ê":"E","Ë":"E","Í":"I","Ì":"I","Î":"I","Ï":"I","Ñ":"N","Ó":"O","Ò":"O","Ô":"O","Ö":"O","Õ":"O","Ú":"U","Ù":"U","Û":"U","Ü":"U","Ý":"Y","Ÿ":"Y","'":"-",'"':"-"," ":"-"};userId=(userId=userId.replace(/[áàâäãåçéèêëíìîïñóòôöõúùûüýÿ'" ]/gi,(function(match){return searchReplace[match]}))).replace(/[^a-z0-9-]/gi,""),$(this).val(userId)}}));