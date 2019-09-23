import { getLoginConfiguration } from '../config';

context('Login tests - Mobile', () => {
  let config = getLoginConfiguration();

  function loginUsing(userMail, userPassword) {
    cy.visit("/mobile.php");
    cy.get("input[data-testid='email']").type(`${userMail}`);
    cy.get("input[data-testid='password']").type(`${userPassword}`);
    cy.get("input[data-testid='signInButton']").click();
  };

  it('Login test (Admin)', () => {
    loginUsing(config.testAdmin, config.testPwd);
    cy.get("h2[data-testid='mobileHeader']").should('be.visible');
  });

  it('Login test (Coordinator)', () => {
    loginUsing(config.testCoordinator, config.testPwd);
    cy.get("h2[data-testid='mobileHeader']").should('be.visible');
  })

  it('Login test (Volunteer)', () => {
    loginUsing(config.testVolunteer, config.testPwd);
    cy.get("h2[data-testid='mobileHeader']").should('be.visible');
  })

  it('Login with non-activated user', () => {
    loginUsing(config.testNotActivatedUser, config.testPwd);
    cy.mobileNotificationWithTextIsVisible(config.genericErrLoginNotif);
  })

  it('Login with expired user', () => {
    loginUsing(config.testExpiredUser, config.testPwd);
    cy.mobileNotificationWithTextIsVisible(config.genericErrLoginNotif);
  })

  it('Login with deleted user', () => {
    loginUsing(config.testDeletedUser, config.testPwd);
    cy.mobileNotificationWithTextIsVisible(config.genericErrLoginNotif);
  })

  it('Login with wrong password', () => {
    loginUsing(config.testAdmin, config.testWrongPwd);
    cy.mobileNotificationWithTextIsVisible(config.incorrectLoginNotif);
  })

  it('Forgot password form', () => {
    cy.visit('/mobile.php');
    cy.get("a[data-testid='forgotPassword']").click();
    cy.get("form[data-testid='resetForm']").should('be.visible');
  });

  it('Forgot password form - nonexistent user', () => {
    cy.visit('/mobile.php');
    cy.get("a[data-testid='forgotPassword']").click();
    cy.get("form[data-testid='resetForm']").should('be.visible');
    cy.get("input[data-testid='forgotPwdEmailField']").type("nonexistent@address.com");
    cy.get("input[data-testid='submitForgottenPwd']").click();
    cy.notificationWithTextIsVisible(config.genericErrLoginNotif);
  });

  it('Forgot password form success confirmation', () => {
    cy.visit('/mobile.php');
    cy.get("a[data-testid='forgotPassword']").click();
    cy.get("form[data-testid='resetForm']").should('be.visible');
    cy.get("input[data-testid='forgotPwdEmailField']").type(config.testAdmin);
    cy.get("input[data-testid='submitForgottenPwd']").click();
    cy.notificationWithTextIsVisible(config.successPwdChangeNotif);
  });
});