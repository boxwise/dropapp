
export function getLoginConfiguration() {
    return { 
        domain: Cypress.env("orgName"),
        incorrectLoginNotif: Cypress.env("incorrectLoginNotif"),
        genericErrLoginNotif: Cypress.env("genericErrLoginNotif"),
        unknownEmailErrLoginNotif: Cypress.env("unknownEmailErrLoginNotif"),
        successPwdChangeNotif: Cypress.env("successPwdChangeNotif"),
        testPwd: Cypress.env("testPwd"),
        testNewPwd: Cypress.env("testNewPwd"),
        testWeekPwd: Cypress.env("testWeekPwd"),
        testWrongPwd: Cypress.env("testWrongPwd"),
        testAdmin: Cypress.env("testAdmin"),
        testAdminName: Cypress.env("testAdminName"),
        testVolunteer: Cypress.env("testVolunteer"),
        testVolunteerWithNoPermissions: Cypress.env("testVolunteerWithNoPermissions"),
        testCoordinator: Cypress.env("testCoordinator"),
        testExpiredUser: Cypress.env("testExpiredUser"),
        testNotActivatedUser: Cypress.env("testNotActivatedUser"),
        testDeletedUser: Cypress.env("testDeletedUser"),
        testUnknownUser: Cypress.env("testUnknownUser"),
        orgName: Cypress.env("orgName"),
        auth0Domain: Cypress.env("auth0Domain"),
     };
};