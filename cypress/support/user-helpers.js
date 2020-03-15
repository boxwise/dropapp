Cypress.Commands.add("checkAllUsersSelected", () => {
    cy.get('tbody tr').each(($tr) => {
        expect($tr).to.have.class('selected');
    });
});

Cypress.Commands.add("checkElementDoesNotExistByText", (selector, text) => {
    cy.getElementBySelectorAndText(selector, text).should('not.exist');
});

Cypress.Commands.add("checkElementIsVisibleByText", (selector, text) => {
    cy.getElementBySelectorAndText(selector, text).should('be.visible');
});

Cypress.Commands.add("clickOnElementBySelectorAndText", (selector, text) => {
    cy.getElementBySelectorAndText(selector, text).click();
});

Cypress.Commands.add("checkForElementByTypeAndTestId", (type, testId) => {
    cy.getElementByTypeAndTestId(type, testId).should("be.visible");
});

Cypress.Commands.add("clickOnElementByTypeAndTestId", (type, testId) => {
    cy.getElementByTypeAndTestId(type, testId).click();
});

Cypress.Commands.add("checkClassByTypeAndTestId", (type, testId, _class, hasClass) => {
    if (hasClass == true) {
        cy.getElementByTypeAndTestId(type, testId).should("have.class", _class);
    } else {
        cy.getElementByTypeAndTestId(type, testId).should("not.have.class", _class);
    }
});