// ***********************************************************
// This example plugins/index.js can be used to load plugins
//
// You can change the location of this file or turn off loading
// the plugins file with the 'pluginsFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/plugins-guide
// ***********************************************************

// This function is called when a project is opened or re-opened (e.g. due to
// the project's config changing)

module.exports = (on, config) => {
  // `on` is used to hook into various events Cypress emits
  // `config` is the resolved Cypress config
  on('before:browser:launch', (browser = {}, launchOptions) => {
    if (browser.name === 'chrome' || browser.name === 'edge') {
      launchOptions.args.push('--disable-features=SameSiteByDefaultCookies'); // bypass 401 unauthorised access on chromium-based browsers
      launchOptions.args.push('--reduce-security-for-testing');
      return launchOptions;
    }
  });

  return config;
};
