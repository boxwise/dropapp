
import { getLoginConfiguration } from '../config';

context('Login tests', () => {

  let config = getLoginConfiguration();
  Cypress.config('defaultCommandTimeout',200000);

  it('1.1.1 - Should be redirected to auth0 login when unauthenticated', () => {
    cy.visit('/');
    cy.url().should('include', Cypress.env('auth0Domain'));
  })

  it('1.1.2 - Should be redirected to initialy requested page when authenticated', () => {
    cy.visit('/?action=cms_profile');
    cy.fillLoginForm();
    cy.url().should('include', 'action=cms_profile');
    cy.get("div[data-testid='dropapp-header']").should('be.visible');
    cy.get("div[data-testid='dropapp-header']").contains(Cypress.env('orgName'));
  })


  it('1.1.3 -Should be redirected to auth0 login when unauthenticated on mobile', () => {
    cy.visit('/mobile.php');
    cy.url().should('include', Cypress.env('auth0Domain'));
  })

  it('1.1.4 - Should be redirected to initialy requested page when authenticated on mobile', () => {
    cy.visit('mobile.php?vieworders');
    cy.fillLoginForm();
    cy.url().should('include', 'mobile.php');
    cy.url().should('include', 'vieworders');
    cy.get('[data-testid=orgcampDiv]').should('be.visible');
    cy.get('[data-testid=orgcampDiv]').contains(Cypress.env('orgName'));
    
  })

});
