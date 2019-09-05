// Standard Login method for all tests
Cypress.Commands.add("LoginAjax", (userMail, userPassword, autologin) => {
    cy.visit('/'); //not sure if needed. Somehow it does not work without it.
    Cypress.$.ajax({
        type: "post",
        url: "/ajax.php?file=login",
        data: {
            email: userMail,
            pass: userPassword,
            autologin: autologin
        },
        dataType: "json"
    });
});

// Login commands trhough login page
Cypress.Commands.add("Login", (userMail, userPassword) => {
    cy.visit("/login.php");
    cy.get("input[data-testid='email']").type(`${userMail}`);
    cy.get("input[data-testid='password']").type(`${userPassword}`);
    cy.get("input[data-testid='signInButton']").click();
});

Cypress.Commands.add("LoginMobile", (userMail, userPassword) => {
    cy.visit("/mobile.php");
    cy.get("input[data-testid='email']").type(`${userMail}`);
    cy.get("input[data-testid='password']").type(`${userPassword}`);
    cy.get("input[data-testid='signInButton']").click();
});

// For Boxwise Gods to select organisation
Cypress.Commands.add("SelectOrganisationByName", orgName => {
    cy.get("a[data-testid='organisationsDropdown']").click();
    cy.get("li[data-testid='organisationOption'] a")
        .invoke("text")
        .contains(orgName)
        .click();
});

