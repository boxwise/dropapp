
import { getLoginConfiguration } from '../config';

context('Login tests', () => {
  let config = getLoginConfiguration();

  it('Login test (Admin)', () => {
    cy.backgroundLoginUsing(config.testAdmin, config.testPwd);
    cy.visit('/');
    cy.get("div[data-testid='dropapp-header']").should('be.visible');
    cy.get("div[data-testid='dropapp-header']")
      .contains('BrowserTestUser_Admin');
  });

  it('Login test (Coordinator)', () => {
    cy.backgroundLoginUsing(config.testCoordinator, config.testPwd);
    cy.visit('/');
    cy.get("div[data-testid='dropapp-header']").should('be.visible');
    cy.get("div[data-testid='dropapp-header']")
      .contains('BrowserTestUser_Coordinator');
  })

  it('Login test (Volunteer)', () => {
    cy.backgroundLoginUsing(config.testVolunteer, config.testPwd);
    cy.visit('/');
    cy.get("div[data-testid='dropapp-header']")
      .should('be.visible');
    cy.get("div[data-testid='dropapp-header']")
      .contains('BrowserTestUser_User');
  })

  // it('Should be redirected without credentials', () => {
  //   cy.visit('/');
  //   cy.location('host').should('eq', Cypress.env('auth0_domain'));
  // })

  // // we're testing our expired user rule configured in auth0
  // // not auth0 itself here
  // it('Login with expired user', () => {
  //   cy.visit('/');
  //   cy.get("input[type='email']").type(config.testExpiredUser)
  //   cy.get("input[type='password']").type(config.testPwd)
  //   cy.get("button[type='submit']").click();
  //   cy.location('host').should('not.eq', Cypress.env('auth0_domain'))
  //   cy.get("body").contains('This user is not currently active.')
  // })
});
