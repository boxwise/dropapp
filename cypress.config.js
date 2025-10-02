const { defineConfig } = require('cypress')

module.exports = defineConfig({
  projectId: 'tip3z9',
  chromeWebSecurity: false,
  retries: {
    runMode: 2,
    openMode: 0,
  },
  env: {
    testAdmin: 'admin@admin.co',
    testAdminName: 'BrowserTestUser_Admin',
    testCoordinator: 'coordinator@coordinator.co',
    testVolunteer: 'user@user.co',
    testVolunteerWithNoPermissions: 'noPermissions@noPermissions.co',
    testExpiredUser: 'expired@expired.co',
    testNotActivatedUser: 'notactivated@notactivated.com',
    testDeletedUser: 'deleted@deleted.co',
    testUnknownUser: 'unknown@unknown.co',
    testPwd: 'Browser_tests',
    testNewPwd: 'Browser_tests_2',
    testWeekPwd: 'browsertests',
    testWrongPwd: 'wrongPwd',
    incorrectLoginNotif: 'Incorrect login.',
    genericErrLoginNotif:
      'This email either does not have an active account associated with it, or access has expired. Contact your coordinator to enable your account.',
    unknownEmailErrLoginNotif:
      'There is no user with this email address in our systems. Check your spelling and try again.',
    successPwdChangeNotif:
      'Within a few minutes you will receive an e-mail with further instructions to reset your password.',
    orgName: 'TestOrganisation',
    auth0Domain: 'staging-login.boxtribute.org',
  },
  e2e: {
    // We've imported your old cypress plugins here.
    // You may want to clean this up later by importing these.
    setupNodeEvents(on, config) {
      return require('./cypress/plugins/index.js')(on, config)
    },
    baseUrl: 'https://staging.boxtribute.org',
    specPattern: 'cypress/e2e/**/*.js',
  },
})
