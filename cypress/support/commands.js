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
    return {
        testAdmin: Cypress.env('testAdmin'),
        testPwd: Cypress.env('testPwd')
    };
});

Cypress.Commands.add("getCoordinatorUser", () => {
    return {
        testCoordinator: Cypress.env('testCoordinator'),
        testPwd: Cypress.env('testPwd')
    };
});

Cypress.Commands.add("getVolunteerUser", () => {
    return {
        testUser: Cypress.env('testUser'),
         testPwd: Cypress.env('testPwd')
    };
});

Cypress.Commands.add("getLoginScenarioUsers", () => { 
    return {
        testExpiredUser: Cypress.env('testExpiredUser'),
        testNotActivatedUser: Cypress.env('testNotActivatedUser'),
        testDeletedUser: Cypress.env('testDeletedUser'),
        testPwd: Cypress.env('testPwd'),
        testWrongPwd: Cypress.env('testWrongPwd')
    };
});

Cypress.Commands.add("getLoginNotifications", () => {
    return {
        incorrectLoginNotif: Cypress.env('incorrectLoginNotif'),
        genericErrLoginNotif: Cypress.env('genericErrLoginNotif'),
        successPwdChangeNotif: Cypress.env('successPwdChangeNotif')
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