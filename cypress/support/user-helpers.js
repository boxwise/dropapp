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