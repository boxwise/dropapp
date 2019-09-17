Cypress.Commands.add("verifyActiveSideMenuNavigation", menuItemClass => {
    cy.get("a[class='" + menuItemClass + "']").last().parent().should("have.class","active");
});
