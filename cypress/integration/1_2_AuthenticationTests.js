
import { getLoginConfiguration } from '../config';

context('Authentication tests', () => {

  let config = getLoginConfiguration();

  beforeEach(function () {
    cy.wait(3000);
  });

  it('1.2.1 - Should not get to authenticated page when enter invalid user', () => {
    cy.visit('/');
    cy.get("input[id='username']").type(config.testUnknownUser)
    cy.get("input[type='password']").type(config.testWrongPwd)
    cy.get("button[type='submit']").click();
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.get("span[id='error-element-password']").contains('Wrong email or password')
  })

  it('1.2.2 - Should not get to authenticated page with deactivated user', () => {
    cy.visit('/');
    cy.get("input[id='username']").type(config.testDeletedUser)
    cy.get("input[type='password']").type(config.testPwd)
    cy.get("button[type='submit']").click();
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.get("h3").contains('user is blocked')
  })

  it('1.2.3 - Should not get to authenticated page with user before valid dates ', () => {
    cy.visit('/');
    cy.get("input[id='username']").type(config.testExpiredUser)
    cy.get("input[type='password']").type(config.testPwd)
    cy.get("button[type='submit']").click();
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.get("h3").contains('This user is not currently active')
  })

  it('1.2.4 - Should not get to authenticated page with user after valid dates ', () => {
    cy.visit('/');
    cy.get("input[id='username']").type(config.testExpiredUser)
    cy.get("input[type='password']").type(config.testPwd)
    cy.get("button[type='submit']").click();
    cy.get("h3").contains('This user is not currently active')
  })

  it('1.2.5 - Should not get to authenticated page when enter wrong password', () => {
    cy.visit('/');
    cy.get("input[id='username']").type(config.testVolunteer)
    cy.get("input[type='password']").type(config.testWrongPwd)
    cy.get("button[type='submit']").click();
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.get("span[id='error-element-password']").contains('Wrong email or password')
  })


});
