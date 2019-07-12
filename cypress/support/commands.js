// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add("login", (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add("drag", { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add("dismiss", { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This is will overwrite an existing command --
// Cypress.Commands.overwrite("visit", (originalFn, url, options) => { ... })


Cypress.Commands.add("getAdminUser", () => { 
    let testAdmin = Cypress.env('testAdmin');
    let testPwd = Cypress.env('testPwd');
    return {testAdmin: testAdmin, testPwd: testPwd};
});

Cypress.Commands.add("getCoordinatorUser", () => { 
    let testCoordinator = Cypress.env('testCoordinator');
    let testPwd = Cypress.env('testPwd');
    return {testCoordinator: testCoordinator, testPwd: testPwd};
});

Cypress.Commands.add("getVolunteerUser", () => { 
    let testUser = Cypress.env('testUser');
    let testPwd = Cypress.env('testPwd');
    return {testUser: testUser, testPwd: testPwd};
});

Cypress.Commands.add("getLoginScenarioUsers", () => { 
    let testExpiredUser = Cypress.env('testExpiredUser');
    let testNotActivatedUser = Cypress.env('testNotActivatedUser');
    let testDeletedUser = Cypress.env('testDeletedUser');
    let testPwd = Cypress.env('testPwd');
    let testWrongPwd = Cypress.env('testWrongPwd');
    return {testExpiredUser: testExpiredUser, testNotActivatedUser: testNotActivatedUser, testDeletedUser: testDeletedUser, testWrongPwd: testWrongPwd, testPwd: testPwd};
});

Cypress.Commands.add("getLoginNotifications", () => {
    return {
        incorrectLoginNotif: Cypress.env('incorrectLoginNotif'),
        genericErrLoginNotif: Cypress.env('genericErrLoginNotif')
    };
});

Cypress.Commands.add("getDomain", () => {
    return {domain: Cypress.env('targetDomain')};
});

Cypress.Commands.add("getTestOrgName", () => {
    return {domain: Cypress.env('orgName')};
});

Cypress.Commands.add("Login", (userMail, userPassword) => { 
    cy.visit(Cypress.env('targetDomain') + "/login.php");
    cy.get("input[data-testid='email']").type(`${userMail}`);
    cy.get("input[data-testid='password']").type(`${userPassword}`);
    cy.get("input[data-testid='signInButton']").click();
});

Cypress.Commands.add("LoginMobile", (userMail, userPassword) => { 
    cy.visit(Cypress.env('targetDomain') + "/mobile.php");
    cy.get("input[data-testid='email']").type(`${userMail}`);
    cy.get("input[data-testid='password']").type(`${userPassword}`);
    cy.get("input[data-testid='signInButton']").click();
});

Cypress.Commands.add("SelectOrganisationByName", (orgName) => {
    cy.get("a[data-testid='organisationsDropdown']").click();
    cy.get("li[data-testid='organisationOption'] a").invoke('text').contains(orgName).click();
});

Cypress.Commands.add("NotificationWithTextIsVisible", (notificationText) => {
    cy.get("ul[id='noty_topCenter_layout_container']").should('contain', notificationText);
});

Cypress.Commands.add("MobileNotificationWithTextIsVisible", (notificationText) => {
    cy.get("div[class='message warning']").should('contain', notificationText);
});