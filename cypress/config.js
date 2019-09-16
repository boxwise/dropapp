
export function getLoginConfiguration() {
    return { 
        domain: Cypress.env("orgName"),
        incorrectLoginNotif: Cypress.env("incorrectLoginNotif"),
        genericErrLoginNotif: Cypress.env("genericErrLoginNotif"),
        successPwdChangeNotif: Cypress.env("successPwdChangeNotif"),
        testPwd: Cypress.env("testPwd"),
        testAdmin: Cypress.env("testAdmin"),
        testVolunteer: Cypress.env("testVolunteer"),
        testCoordinator: Cypress.env("testCoordinator"),
        testExpiredUser: Cypress.env("testExpiredUser"),
        testNotActivatedUser: Cypress.env("testNotActivatedUser"),
        testDeletedUser: Cypress.env("testDeletedUser"),
        testPwd: Cypress.env("testPwd"),
        testWrongPwd: Cypress.env("testWrongPwd")
     };
};