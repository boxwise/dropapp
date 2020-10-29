
Cypress.Commands.add("setupAjaxActionHook", () => {
    // set up a hook to listen for POSTs to the action url
    cy.server();
    // TODO - could this be a wildcard ajax action to wait for?
    cy.route("POST", "/?action=**").as('ajaxPostAction');
});

Cypress.Commands.add("waitForAjaxAction", (expectedRequest,expectedResponse) => {
    // wait for the expected response
    cy.wait('@ajaxPostAction')
        .should('have.property', 'status', 200)

    cy.get('@ajaxPostAction')
        .then(xhr => {
            cy.log("Request Body", JSON.stringify(xhr.request.body));
            cy.log("Response Body", JSON.stringify(xhr.response.body));
        })

    cy.get('@ajaxPostAction')
        .its('request.body')
        .should('contain', expectedRequest)
    
    cy.get('@ajaxPostAction')
        .then(xhr => {
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