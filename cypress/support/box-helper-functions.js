const SEED_BOX_COMMENT = "Cypress seed test box";

Cypress.Commands.add("deleteAllBoxesExceptSeed", () => {
    cy.loginAsVolunteer();
    cy.visit('/?action=stock');
    cy.get("input[data-testid='select_all']").click();

    cy.get("body").then($body => {
        // make sure the seed box will not get deleted!
        if ($body.text().includes(SEED_BOX_COMMENT)) {
            cy.getRowWithText("Martin test comment").parent().parent().within(()=>{
                cy.get("input[type='checkbox']").uncheck();
            });
        }
        return cy.get('button:visible');
    })
    .then($button => {
        if ($button.text().includes("Delete")) {
            cy.log("Delete button is visible")
            cy.get("button[data-operation='delete']").click();
            cy.getConfirmActionButton().click();
        }
    });
});

Cypress.Commands.add("clickMakeLabelsButton", () => {
    cy.get("button").contains("Print label(s)").click();
    // cy.getButtonWithText("Make labels").click({force: true});
});

Cypress.Commands.add("uncheckBigLabelsCheckbox", () => {
    cy.getElementByTypeAndTestId("input", "field_fulllabel").uncheck();
});

Cypress.Commands.add("typeNumberOfLabels", (number) => {
    cy.get("input[id='field_count']").clear().type(number);
});

Cypress.Commands.add("checkBigLabelsCheckbox", () => {
    cy.get("input[data-testid='field_fulllabel']").check();
});