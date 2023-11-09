Cypress.Commands.add("setupAjaxActionHook", () => {
    // TODO - could this be a wildcard ajax action to wait for?
    cy.intercept("POST", "/?action=**").as('ajaxPostAction');
});

Cypress.Commands.add("waitForAjaxActionWithoutAssert", () => {
    // wait for the expected response
    cy.wait('@ajaxPostAction').then(({id,request,response}) => {
        cy.log("Request", request);
        cy.log("Response", response);
    })
});

Cypress.Commands.add("waitForAjaxAction", (expectedRequest,expectedResponse) => {
    // wait for the expected response
    cy.wait('@ajaxPostAction').then(({id,request,response}) => {
        cy.log("Request", request);
        cy.log("Response", response);
        expect(request.body).to.contain(expectedRequest);
        expect(response.statusCode).to.equal(200);
        expect(response.body).to.contain('"success":true');
        expect(response.body).to.contain('"message":"' + expectedResponse + '"');
        if (response.body.includes('"redirect":true')) {
            cy.log('Ajax response will trigger redirect');
            // may need to call cy.reload() here for cypress to deal
            // with the client-side redirect randomly reloading the page
            // but so far doesn't seem to be a problem
            // possibly because in these scenarios we're immediately
            // navigating elsewhere
            cy.reload();
        }
    })
});

Cypress.Commands.add("interceptCallsToV2", () => {
    cy.intercept("**/qrreader/**").as('v2intercept');
});

Cypress.Commands.add("waitforCallToV2", (expectedRequest) => {
    cy.wait('@v2intercept').then(({id,request,response}) => {
        cy.log("Request Body", request);
        cy.log("Response Body", response);
        expect(request.url).to.contain(expectedRequest);
        expect(response.statusCode).to.equal(200);
    });
});