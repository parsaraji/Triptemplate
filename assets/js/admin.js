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

  // 1.2 Destination FAQ Repeater Field Logic
  var destFaqContainer = $('#ppt_dest_faq_container');
  $('#ppt_add_faq_btn_dest').on('click', function () {
    var index = destFaqContainer.find('.ppt-repeater-item').length;
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
    destFaqContainer.append(html);
    normalizeAdminDigits();
  });
  destFaqContainer.on('click', '.ppt-remove-repeater-item', function () {
    $(this).closest('.ppt-repeater-item').remove();
  });

  // 1.5 Itinerary Day-by-Day Repeater Logic
  var itineraryContainer = $('#ppt_itinerary_days_container');

  function reindexItineraryDays() {
    itineraryContainer.find('.ppt-itinerary-day-row').each(function (index) {
      var row = $(this);
      row.find('.day-number-label').text(translateToPersian(index + 1));
      row.find('input[name*="ppt_itinerary_days"]').attr('name', `ppt_itinerary_days[${index}][title]`);
      row.find('textarea[name*="ppt_itinerary_days"]').attr('name', `ppt_itinerary_days[${index}][desc]`);
    });
  }

  $('#ppt_add_itinerary_day_btn').on('click', function () {
    var index = itineraryContainer.find('.ppt-itinerary-day-row').length;
    var html = `
      <div class="ppt-repeater-item ppt-itinerary-day-row animate-fade-in" style="border:1px solid #ccd0d4; padding:15px; margin-bottom:15px; background-color:#f6f7f7; border-radius:4px; position: relative;">
        <p>
          <label><strong>عنوان روز <span class="day-number-label">${index + 1}</span>:</strong></label>
          <input type="text" name="ppt_itinerary_days[${index}][title]" style="width:100%; margin-top:5px;" placeholder="مثال: روز ${index + 1} - گشت و گذار در منطقه" />
        </p>
        <p style="margin-top:10px;">
          <label><strong>شرح اقدامات و جزییات مسیر:</strong></label>
          <textarea name="ppt_itinerary_days[${index}][desc]" style="width:100%; margin-top:5px;" rows="3" placeholder="مکان‌های بازدید، رستوران‌ها و نکات ترابری این روز را بنویسید"></textarea>
        </p>
        <button type="button" class="button button-link-delete ppt-remove-itinerary-day" style="color:#d63638; position: absolute; left: 15px; bottom: 15px;">حذف روز</button>
      </div>
    `;
    itineraryContainer.append(html);
    reindexItineraryDays();
    normalizeAdminDigits();
  });

  // Handle Removal of Itinerary Day
  itineraryContainer.on('click', '.ppt-remove-itinerary-day', function () {
    $(this).closest('.ppt-itinerary-day-row').remove();
    reindexItineraryDays();
    normalizeAdminDigits();
  });

  // 1.8 Backlinks Dynamic Repeater Logic
  var backlinksContainer = $('#ppt_backlinks_repeater_container');

  function reindexBacklinks() {
    backlinksContainer.find('.ppt-backlink-row').each(function (index) {
      var row = $(this);
      row.find('input[name*="[anchor]"]').attr('name', `ppt_ad_slots[backlinks][${index}][anchor]`);
      row.find('input[name*="[url]"]').attr('name', `ppt_ad_slots[backlinks][${index}][url]`);
      row.find('input[name*="[nofollow]"]').attr('name', `ppt_ad_slots[backlinks][${index}][nofollow]`);
    });
  }

  $('#ppt_add_backlink_btn').on('click', function () {
    var index = backlinksContainer.find('.ppt-backlink-row').length;
    var html = `
      <div class="ppt-backlink-row animate-fade-in" style="display:flex; gap:15px; margin-bottom:12px; align-items:center; border-bottom: 1px dashed #E2E8F0; padding-bottom: 12px;">
        <div style="flex:1;">
          <label style="font-weight:bold;">عنوان پیوند (Anchor):</label>
          <input type="text" name="ppt_ad_slots[backlinks][${index}][anchor]" style="width:100%; margin-top:5px;" placeholder="مثال: خرید بلیط هواپیما" />
        </div>
        <div style="flex:2;">
          <label style="font-weight:bold;">آدرس اینترنتی (URL):</label>
          <input type="url" name="ppt_ad_slots[backlinks][${index}][url]" style="width:100%; text-align:left; direction:ltr; margin-top:5px;" placeholder="https://example.com" />
        </div>
        <div style="flex:1; text-align:center; padding-top:20px;">
          <label style="font-weight:bold;">
            <input type="checkbox" name="ppt_ad_slots[backlinks][${index}][nofollow]" value="1" />
            نوفالو (Nofollow)
          </label>
        </div>
        <div style="padding-top:20px;">
          <button type="button" class="button button-link-delete ppt-remove-backlink-row" style="color:#d63638;">حذف</button>
        </div>
      </div>
    `;
    backlinksContainer.append(html);
    reindexBacklinks();
    normalizeAdminDigits();
  });

  backlinksContainer.on('click', '.ppt-remove-backlink-row', function () {
    $(this).closest('.ppt-backlink-row').remove();
    reindexBacklinks();
    normalizeAdminDigits();
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
