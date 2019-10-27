Cypress.Commands.add("inputFill", (Field_id, Field_input) => {
    cy.get("input[data-testid = '" + Field_id + "']").type(Field_input);
});
