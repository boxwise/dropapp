Cypress.Commands.add("inputFill", (Field_id, Field_input) => {
    cy.get("input[data-testid = '" + Field_id + "']").type(Field_input);
});

Cypress.Commands.add("getButtonWithText", (text) => {
    cy.get("button").contains(text);
});

Cypress.Commands.add("getRowWithName", (name) => {
    cy.get('tr').contains(name);
});