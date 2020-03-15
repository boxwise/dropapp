Cypress.Commands.add("inputFill", (Field_id, Field_input) => {
    cy.get("input[data-testid = '" + Field_id + "']").type(Field_input);
});

Cypress.Commands.add("getButtonWithText", (text) => {
    cy.get("button").contains(text);
});

Cypress.Commands.add("getRowWithText", (text) => {
    cy.get("tr").contains(text);
});

Cypress.Commands.add("checkGridCheckboxByText", (text) => {
    cy.getRowWithText(text).parent().parent().parent().within(() => {
        cy.get("input[type='checkbox']").scrollIntoView().check({force: true});
    });
});

Cypress.Commands.add("getConfirmActionButton", () => {
    return cy.get("a[data-apply='confirmation']");
});

Cypress.Commands.add("checkQtip", (qtip_id) => {
    cy.get("div[id='"+ qtip_id + "']").should("be.visible");
});

Cypress.Commands.add("checkQtipWithText", (qtip_class, qtipText) => {
    cy.get("div[class='"+qtip_class+"']").contains(qtipText).should("be.visible");
});

Cypress.Commands.add("getElementBySelectorAndText", (selector, text) => {
    cy.get(selector).contains(text);
});

Cypress.Commands.add("clickListDeleteButton", () => {
    cy.get("button[data-testid='list-delete-button']").click();
});

Cypress.Commands.add("getElementByTypeAndTestId", (type, testId) => {
    cy.get(type + "[data-testid = '" + testId + "']");
});