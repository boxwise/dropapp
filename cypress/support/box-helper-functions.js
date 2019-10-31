const SEED_BOX_COMMENT = "Cypress seed test box";

Cypress.Commands.add("deleteAllBoxes", () => {
    cy.loginAsVolunteer();
    cy.visit('/?action=stock');
    cy.get("input[data-testid='select_all']").click();

    cy.get("body").then($body => {
        // make sure the seed box will not get deleted!
        if ($body.text().includes(SEED_BOX_COMMENT)) {
            cy.get('tr').contains("Martin test comment").parent().parent().within(()=>{
                cy.get("input[type='checkbox']").uncheck();
            });
        }
        return cy.get('button:visible');
    })
    .then($button => {
        if ($button.text().includes("Delete")) {
            cy.log("Delete button is visible")
            cy.get("button[data-operation='delete']").click();
            cy.get("a[data-apply='confirmation']").click();
        }
    });
});
