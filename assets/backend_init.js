  Sa11y.Lang.addI18n(Sa11yLangEn.strings);
  const sa11y = new Sa11y.Sa11y({
    customChecks: new CustomChecks, // Optional
    checkRoot: "#rex-js-main-content",
    containerIgnore: '.rex-minibar',
  });
