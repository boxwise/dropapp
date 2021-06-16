
export function getLoginConfiguration() {
    return { 
        domain: Cypress.env("orgName"),
        incorrectLoginNotif: Cypress.env("incorrectLoginNotif"),
        genericErrLoginNotif: Cypress.env("genericErrLoginNotif"),
        unknownEmailErrLoginNotif: Cypress.env("unknownEmailErrLoginNotif"),
        successPwdChangeNotif: Cypress.env("successPwdChangeNotif"),
        testPwd: Cypress.env("testPwd"),
        testAdmin: Cypress.env("testAdmin"),
        testVolunteer: Cypress.env("testVolunteer"),
        testVolunteerWithNoPermissions: Cypress.env("testVolunteerWithNoPermissions"),
        testCoordinator: Cypress.env("testCoordinator"),
        testExpiredUser: Cypress.env("testExpiredUser"),
        testNotActivatedUser: Cypress.env("testNotActivatedUser"),
        testDeletedUser: Cypress.env("testDeletedUser"),
        testUnknownUser: Cypress.env("testUnknownUser"),
        testPwd: Cypress.env("testPwd"),
        testWrongPwd: Cypress.env("testWrongPwd"),
        orgName: Cypress.env("orgName"),
     };
};