/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 */

(function () {
    "use strict";

    var MAX_DEPTH = <?php
echo Core::getCoreParam('menuDepth', Core::MENU_DEPTH); ?>;

    function getUlDepth(ul) {
        var depth = 0;
        var el = ul;
        while (el) {
            if (el.id === 'menuEditTree' || el.id === 'menuEditOrphans') break;
            if (el.tagName === 'UL') depth++;
            el = el.parentElement;
        }
        return depth;
    }

    function getSubtreeHeight(li) {
        var max = 0;
        function walk(ul, h) {
            var items = ul.children;
            for (var i = 0; i < items.length; i++) {
                if (!items[i].classList.contains('menuEditItem')) continue;
                if (h > max) max = h;
                var sub = items[i].querySelector(':scope > ul.sortableList');
                if (sub) walk(sub, h + 1);
            }
        }
        var nested = li.querySelector(':scope > ul.sortableList');
        if (nested) walk(nested, 1);
        return max;
    }

    function serializeTree(ul, parentId) {
        var result = [];
        parentId = parentId || "";
        var items = ul.children;
        var position = 1;
        for (var i = 0; i < items.length; i++) {
            var li = items[i];
            if (!li.dataset || !li.dataset.id) continue;
            if (li.classList.contains("menuEditEmpty")) continue;
            var pageId   = li.dataset.id;
            var isOrphan = !!li.closest("#menuEditOrphans");
            result.push({
                id:       pageId,
                parent:   isOrphan ? "" : parentId,
                position: isOrphan ? 0  : position
            });
            if (!isOrphan) position++;
            var sub = li.querySelector(":scope > ul.sortableList");
            if (sub) {
                serializeTree(sub, isOrphan ? "" : pageId).forEach(function (c) {
                    result.push(c);
                });
            }
        }
        return result;
    }

    function updateHiddenField() {
        var tree    = document.getElementById("menuEditTree");
        var orphans = document.getElementById("menuEditOrphans");
        var field   = document.getElementById("menuEditOrder");
        if (!field) return;
        var order = [];
        if (tree)    serializeTree(tree,    "").forEach(function (o) { order.push(o); });
        if (orphans) serializeTree(orphans, "").forEach(function (o) { order.push(o); });
        field.value = JSON.stringify(order);
    }

    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll('.sortableList').forEach(function (el) {
            Sortable.create(el, {
                group:                'menuTree',
                animation:            150,
                forceFallback:        true,
                fallbackOnBody:       true,
                fallbackClass:        'sortable-fallback',
                handle:               '.menuEditHandle',
                draggable:            '.menuEditItem',
                ghostClass:           'sortable-ghost',
                chosenClass:          'sortable-chosen',
                emptyInsertThreshold: 20,
                onMove: function (evt) {
                    if (evt.to.id === 'menuEditOrphans') return true;
                    var depth  = getUlDepth(evt.to);
                    var height = getSubtreeHeight(evt.dragged);
                    return (depth + height < MAX_DEPTH);
                },
                onEnd: function () {
                    updateHiddenField();
                }
            });
        });

        updateHiddenField();
        var form = document.getElementById("menuIndexForm");
        if (form) form.addEventListener("submit", updateHiddenField);
    });

}());
