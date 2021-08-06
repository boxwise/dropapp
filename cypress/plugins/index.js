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
  // on('before:browser:launch', (browser = {}, launchOptions) => {
  //   if (browser.name === 'chrome' || browser.name === 'edge') {
  //     launchOptions.args.push('--disable-features=SameSiteByDefaultCookies'); // bypass 401 unauthorised access on chromium-based browsers
  //     return launchOptions;
  //   }
  // });
  on('before:browser:launch', (browser = {}, launchOptions) => {
    if (browser.family === 'chromium') {
      // Mac/Linux
      launchOptions.args.push('--disable-features=SameSiteByDefaultCookies');
      launchOptions.args.push('--reduce-security-for-testing');
    }
    console.log('launch options', launchOptions);
    return launchOptions;
  });
  //require('cypress-log-to-output').install(on);
  //require('cypress-fail-fast/plugin')(on, config);
  return config;
};
