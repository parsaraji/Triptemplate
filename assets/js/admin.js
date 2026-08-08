/**
 * Custom WordPress Settings Panel Repeater and Admin Interactions
 * Includes robust drag-and-drop jQuery UI Sortable reordering for homepage modules
 * and localized Persian digit conversions.
 */

jQuery(document).ready(function ($) {
  'use strict';

  // Helper to translate standard digits to Persian digits
  function translateToPersian(text) {
    var englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    var persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    var str = text.toString();
    for (var i = 0; i < 10; i++) {
      str = str.replace(new RegExp(englishDigits[i], 'g'), persianDigits[i]);
    }
    return str;
  }

  function normalizeAdminDigits() {
    $('.wp-list-table td, .description, code, .form-table th').each(function () {
      var self = $(this);
      if (self.children().length === 0) {
        self.text(translateToPersian(self.text()));
      }
    });
  }

  // 1. FAQ Repeater Field Logic
  var faqContainer = $('#ppt_faq_repeater_container');
  var faqCount = faqContainer.find('.ppt-repeater-item').length;

  $('#ppt_add_faq_btn').on('click', function () {
    var index = faqCount;
    var html = `
      <div class="ppt-repeater-item animate-fade-in" style="border:1px solid #ccc; padding:15px; margin-bottom:15px; background:#f9f9f9; border-radius: 4px; position:relative;">
        <p>
          <label><strong>سوال:</strong></label><br>
          <input type="text" name="ppt_faqs[${index}][q]" style="width:100%; margin-top:5px;" placeholder="سوال متداول مسافران را وارد کنید" />
        </p>
        <p style="margin-top:10px;">
          <label><strong>پاسخ:</strong></label><br>
          <textarea name="ppt_faqs[${index}][a]" style="width:100%; margin-top:5px;" rows="3" placeholder="پاسخ کامل سوال را اینجا بنویسید"></textarea>
        </p>
        <button type="button" class="button button-link-delete ppt-remove-repeater-item" style="color:#d63638; margin-top:5px;">حذف سوال</button>
      </div>
    `;
    faqContainer.append(html);
    faqCount++;
    normalizeAdminDigits();
  });

  // Handle Removal of FAQ Item
  faqContainer.on('click', '.ppt-remove-repeater-item', function () {
    $(this).closest('.ppt-repeater-item').remove();
  });

  // 2. Budget Repeater Field Logic (Table Rows)
  var budgetContainer = $('#ppt_budget_repeater_container');
  var budgetCount = budgetContainer.find('tr').length;

  $('#ppt_add_budget_btn').on('click', function () {
    var index = budgetCount;
    var html = `
      <tr>
        <td>
          <input type="text" name="ppt_budget[${index}][title]" style="width:100%;" placeholder="مثلاً: هزینه بلیت قطار، رزرو هتل" />
        </td>
        <td>
          <input type="text" name="ppt_budget[${index}][cost]" style="width:100%;" placeholder="مثلاً: ۵۰۰,۰۰۰ تومان" />
        </td>
        <td>
          <button type="button" class="button button-link-delete ppt-remove-budget-row" style="color:#d63638;">حذف</button>
        </td>
      </tr>
    `;
    budgetContainer.append(html);
    budgetCount++;
    normalizeAdminDigits();
  });

  // Handle Removal of Budget Row
  budgetContainer.on('click', '.ppt-remove-budget-row', function () {
    $(this).closest('tr').remove();
  });

  // 3. Homepage Modules Drag-and-Drop Sortable Reordering
  var sortableContainer = $('.ppt-sortable-sections');
  if (sortableContainer.length && $.fn.sortable) {
    sortableContainer.sortable({
      handle: '.ppt-drag-handle',
      axis: 'y',
      placeholder: 'ui-state-highlight',
      update: function (event, ui) {
        // Re-index names dynamically on drag update to save exactly sorted order
        sortableContainer.find('.ppt-section-row').each(function (index) {
          var row = $(this);

          row.find('input[name*="[id]"]').attr('name', 'ppt_homepage_sections[sections][' + index + '][id]');
          row.find('input[name*="[source]"]').attr('name', 'ppt_homepage_sections[sections][' + index + '][source]');
          row.find('input[name*="[title]"]').attr('name', 'ppt_homepage_sections[sections][' + index + '][title]');
          row.find('input[name*="[count]"]').attr('name', 'ppt_homepage_sections[sections][' + index + '][count]');
          row.find('select[name*="[layout]"]').attr('name', 'ppt_homepage_sections[sections][' + index + '][layout]');
          row.find('select[name*="[enabled]"]').attr('name', 'ppt_homepage_sections[sections][' + index + '][enabled]');
        });
      }
    });
  }

  // Convert digits initially
  normalizeAdminDigits();
});
