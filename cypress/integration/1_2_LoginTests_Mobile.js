import { getLoginConfiguration } from '../config';

context('Login tests - Mobile', () => {
  let config = getLoginConfiguration();

  it('Login test (Admin)', () => {
    cy.backgroundLoginUsing(config.testAdmin, config.testPwd);
    cy.visit('/mobile.php');
    cy.get("h2[data-testid='mobileHeader']").should('be.visible');
  });

  it('Login test (Coordinator)', () => {
    cy.backgroundLoginUsing(config.testCoordinator, config.testPwd);
    cy.visit('/mobile.php');
    cy.get("h2[data-testid='mobileHeader']").should('be.visible');
  })

  it('Login test (Volunteer)', () => {
    cy.backgroundLoginUsing(config.testVolunteer, config.testPwd);
    cy.visit('/mobile.php');
    cy.get("h2[data-testid='mobileHeader']").should('be.visible');
  })

  // it('Should be redirected without login', () => {
  //   cy.visit('/mobile.php');
  //   cy.location('host').should('eq', Cypress.env('auth0_domain'));
  // })

});