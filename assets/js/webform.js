(function () {
  'use strict';

  const ATTR_FIELD_NAME = 'data-field-name';

  const SELECTOR_SUMMARY = '.error-summary';
  const SELECTOR_SUMMARY_LIST = 'ul';
  const SELECTOR_SUMMARY_ITEM = `li[${ATTR_FIELD_NAME}]`;

  function enhanceDocument() {
    document
      .querySelectorAll(SELECTOR_SUMMARY)
      .forEach(enhanceSummary);
  }

  function enhanceSummary(element) {
    element
      .querySelectorAll(SELECTOR_SUMMARY_LIST)
      .forEach(enhanceSummaryList);
  }

  function enhanceSummaryList(element) {
    element
      .querySelectorAll(SELECTOR_SUMMARY_ITEM)
      .forEach(enhanceSummaryItem);
  }

  function enhanceSummaryItem(element) {
    const fields = Array.from(document.getElementsByName(element.getAttribute(ATTR_FIELD_NAME)));
    const field = fields.find((field) => field.hasAttribute('id'));

    if (! field) {
      return;
    }

    const link = document.createElement('a');
    link.href = `#${field.id}`;
    link.textContent = element.textContent;

    element.replaceChildren(link);
  }

  enhanceDocument();
})();
