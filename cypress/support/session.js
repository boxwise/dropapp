import { getLoginConfiguration } from '../config';

// Standard Login method for all tests
function backgroundLoginUsing(userMail, userPassword) {
    cy.log(`Logging in as ${userMail}`);
    cy.request({
        method: "POST",
        url: '/cypress-session.php',
        body: {
            email: userMail,
            password: userPassword
        },
        form: true // submit as POST fields not JSON encoded body
    }).then(response => {
        expect(response.status).to.eq(200);
        expect(response.body.success).to.be.true;
    });
};

// these are shortcuts to sign in via ajax, as we
// don't need to test the full login flow for most
// tests
Cypress.Commands.add("loginAsVolunteer", () => {
    let config = getLoginConfiguration();
    backgroundLoginUsing(config.testVolunteer, config.testPwd);
});

Cypress.Commands.add("backgroundLoginUsing", backgroundLoginUsing);

Cypress.Commands.add("loginAsVolunteerWithNoPermissions", () => {
    let config = getLoginConfiguration();
    backgroundLoginUsing(config.testVolunteerWithNoPermissions, config.testPwd);
});

Cypress.Commands.add("loginAs", (user, pass) => {
    let config = getLoginConfiguration();
    backgroundLoginUsing(user, pass);
});

Cypress.Commands.add("loginAsAdmin", () => {
    let config = getLoginConfiguration();
    //Rate limit of Auth0 reached 
    //One can only request /userinfo 5 times per minute from the same user.
    cy.wait(12000);
    backgroundLoginUsing(config.testAdmin, config.testPwd);
});

Cypress.Commands.add("loginAsCoordinator", () => {
    let config = getLoginConfiguration();
    backgroundLoginUsing(config.testCoordinator, config.testPwd);
});
