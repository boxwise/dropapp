Cypress.Commands.add("checkInputIsEmpty", (Field_id) => {
    cy.getElementByTypeAndTestId("input", Field_id).should("be.empty");
});

Cypress.Commands.add("checkCommentFieldIsEmpty", () => {
    cy.getElementByTypeAndTestId("textarea", "comments_id").should("be.empty");
});

Cypress.Commands.add("checkCancelButton", () => {
    cy.get("a").contains("Cancel").should("be.visible");
}); 