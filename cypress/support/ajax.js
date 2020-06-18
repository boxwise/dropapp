
Cypress.Commands.add("setupAjaxActionHook", () => {
    // set up a hook to listen for POSTs to the action url
    cy.server();
    // TODO - could this be a wildcard ajax action to wait for?
    cy.route("POST", "?action=**").as('ajaxPostAction');
});

Cypress.Commands.add("waitForAjaxAction", (expectedResponse) => {
    // wait for the expected response
    cy.wait('@ajaxPostAction').then(xhr => {
        cy.log(xhr.response.body);
        cy.log(xhr.response.body.success);
        expect(xhr.response.body.success).to.equal(true);
        expect(xhr.response.body.message).to.equal(expectedResponse);

        if (xhr.response.body.redirect) {
            cy.log('Ajax response will trigger redirect');
            // may need to call cy.reload() here for cypress to deal
            // with the client-side redirect randomly reloading the page
            // but so far doesn't seem to be a problem
            // possibly because in these scenarios we're immediately
            // navigating elsewhere
        }
    });
});