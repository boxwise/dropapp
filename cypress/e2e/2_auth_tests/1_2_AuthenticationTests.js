
import { getLoginConfiguration } from '../../config';

context('Authentication tests', () => {

  let config = getLoginConfiguration();

  it('1.2.1 - Should not get to authenticated page when enter invalid user', () => {
    cy.visit('/');
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.fillLoginFormFor(config.testUnknownUser,config.testWrongPwd)
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.get(".auth0-global-message").contains('Wrong email or password')
  })

  it('1.2.2 - Should not get to authenticated page with deactivated user', () => {
    cy.visit('/');
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.fillLoginFormFor(config.testDeletedUser,config.testPwd)
    cy.get("h3").contains('user is blocked')
  })

  it('1.2.3 - Should not get to authenticated page with user before valid dates ', () => {
    cy.visit('/');
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.fillLoginFormFor(config.testNotActivatedUser,config.testPwd)
    cy.get("h3").contains('This user is not currently active')
  })

  it('1.2.4 - Should not get to authenticated page with user after valid dates ', () => {
    cy.visit('/');
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.fillLoginFormFor(config.testExpiredUser,config.testPwd)
    cy.get("h3").contains('This user is not currently active')
  })

  it('1.2.5 - Should not get to authenticated page when enter wrong password', () => {
    cy.visit('/');
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.fillLoginFormFor(config.testVolunteer,config.testWrongPwd)
    cy.url().should('include', Cypress.env('auth0Domain'))
    cy.get(".auth0-global-message").contains('Wrong email or password')
  })


});
