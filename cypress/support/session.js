import { getLoginConfiguration } from '../config';

// Standard Login method for all tests

function loginWithAjax(username,password){
    cy.log(`Logging in as ${username}`);
        const options = {
            method: 'POST',
            url: Cypress.env('auth_url'),
            body: {
                grant_type: 'password',
                username: username,
                password: password,
                audience: Cypress.env('auth_audience'),
                scope: 'openid profile email',
                client_id: Cypress.env('auth_client_id'),
                client_secret: Cypress.env('auth_client_secret'),
            },
        };
        cy.request(options).then(({ body }) => {
            const { access_token, expires_in, id_token } = body;
            cy.request({
                method: "POST",
                url: '/ajax.php?file=cypresslogin',
                body: {
                    access_token: access_token,
                    id_token: id_token

                },
                form: true
            }).then(response => {
                expect(response.status).to.eq(200);
                expect(response.body.message).to.be.empty;
            });

})}

// these are shortcuts to sign in via ajax, as we
// don't need to test the full login flow for most
// tests
Cypress.Commands.add("loginAsVolunteer", () => {
    let config = getLoginConfiguration();
    loginWithAjax(config.testVolunteer, config.testPwd);
});

Cypress.Commands.add("loginAsVolunteerWithNoPermissions", () => {
    let config = getLoginConfiguration();
    loginWithAjax(config.testVolunteerWithNoPermissions, config.testPwd);
});

Cypress.Commands.add("loginAsAdmin", () => {
    let config = getLoginConfiguration();
    loginWithAjax(config.testAdmin, config.testPwd);
});

Cypress.Commands.add("loginAsCoordinator", () => {
    let config = getLoginConfiguration();
    loginWithAjax(config.testCoordinator, config.testPwd);
});
