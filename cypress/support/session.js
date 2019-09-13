import { getLoginConfiguration } from '../config';

// Standard Login method for all tests
function loginWithAjax(userMail, userPassword) {
    cy.request({
        method: "POST",
        url: '/ajax.php?file=login',
        body: {
            email: userMail,
            pass: userPassword
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        expect(response.body.message).to.be.empty;
        expect(response.body.success).to.be.true;
    });
};

// these are shortcuts to sign in via ajax, as we
// don't need to test the full login flow for most
// tests
Cypress.Commands.add("loginAsVolunteer", () => {
    let config = getLoginConfiguration();
    loginWithAjax(config.testVolunteer, config.testPwd);
});

Cypress.Commands.add("loginAsAdmin", () => {
    let config = getLoginConfiguration();
    loginWithAjax(config.testAdmin, config.testPwd);
});

Cypress.Commands.add("loginAsCoordinator", () => {
    let config = getLoginConfiguration();
    loginWithAjax(config.testCoordinator, config.testPwd);
});
