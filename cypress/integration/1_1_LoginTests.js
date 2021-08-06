
import { getLoginConfiguration } from '../config';

context('Login tests', () => {

  let config = getLoginConfiguration();

  it('1.1.1 - Should be redirected to auth0 login when unauthenticated', () => {
    cy.visit('/');
    cy.url().should('include', Cypress.env('auth0Domain'));
  })

  it('1.1.2 - Should be redirected to initialy requested page when authenticated', () => {
    Cypress.Cookies.debug(true);
    cy.visit('/?action=cms_profile');
    cy.get("input[id='username']").type(config.testCoordinator);
    cy.get("input[type='password']").type(config.testPwd);
    cy.get("button[type='submit']").click();
    cy.url().then((url) => {
      // first time login with the user prompt for consent
      if (url.includes('consent?')) {
        cy.get('button[value="accept"]').click()
      }
    });
    cy.url().should('include', 'action=cms_profile');
  })


  it('1.1.3 -Should be redirected to auth0 login when unauthenticated on mobile', () => {
    cy.visit('/mobile.php');
    cy.url().should('include', Cypress.env('auth0Domain'));
  })

  it('1.1.4 - Should be redirected to initialy requested page when authenticated on mobile', () => {
    cy.visit('mobile.php?vieworders');
    cy.get("input[id='username']").type(config.testCoordinator);
    cy.get("input[type='password']").type(config.testPwd);
    cy.get("button[type='submit']").click();
    cy.url().then((url) => {
      // first time login with the user prompt for consent
      if (url.includes('consent?')) {
        cy.get('button[value="accept"]').click()
      }
    });
    cy.url().should('include', 'mobile.php');
    cy.url().should('include', 'vieworders');
  })


    // it('Login test (Admin)', () => {
  //   cy.backgroundLoginUsing(config.testAdmin, config.testPwd);
  //   cy.visit('/');
  //   cy.get("div[data-testid='dropapp-header']").should('be.visible');
  //   cy.get("div[data-testid='dropapp-header']")
  //     .contains('BrowserTestUser_Admin');
  // });

  // it('Login test (Coordinator)', () => {
  //   cy.backgroundLoginUsing(config.testCoordinator, config.testPwd);
  //   cy.visit('/');
  //   cy.get("div[data-testid='dropapp-header']").should('be.visible');
  //   cy.get("div[data-testid='dropapp-header']")
  //     .contains('BrowserTestUser_Coordinator');
  // })

  // it('Login test (Volunteer)', () => {
  //   cy.backgroundLoginUsing(config.testVolunteer, config.testPwd);
  //   cy.visit('/');
  //   cy.get("div[data-testid='dropapp-header']")
  //     .should('be.visible');
  //   cy.get("div[data-testid='dropapp-header']")
  //     .contains('BrowserTestUser_User');
  // })

});
