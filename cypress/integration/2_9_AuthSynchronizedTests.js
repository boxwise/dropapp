/* eslint-disable no-undef */

import { getLoginConfiguration } from '../config';

const USER_DEACTVATE_REQUEST = "do=delete"
const USER_DEACTVATE_RESPONSE = "Item deleted";

context('2.9 Auth0 synchronized on CRUD', () => {

  let config = getLoginConfiguration();
  
  let testName = 'Test New User';
  let testGroup = "Base TestBase - Volunteer";
  let testEmail = 'testnewuser@boxtribute.org';
  let modifiedTestName = 'Test New User Edited';
  let modifiedValidFrom = '02-08-2021';

  let testAdminName = config.testAdminName;
  let testAdminEmail = config.testAdmin;
  let modifiedTestAdminName = 'm' + config.testAdminName;
  let modifiedTestAdminEmail = 'm' + config.testAdmin;
  

  Cypress.config('defaultCommandTimeout',200000);

  function DeleteTestUser(address) {
    cy.testdbdelete('cms_users', [], [address]);
  }

  function CheckUserSyncedAuth0(address) {
    cy.testauth0user(address);
  }

  function FillForm(name, address, group) {
    cy.get('input[data-testid=\'user_name\']').type(name);
    cy.get('input[data-testid=\'user_email\']').type(address);
    cy.get('#field_email2').type(address);
    cy.selectOptionByText('cms_usergroups_id', group);
  }

  beforeEach(function () {
    cy.setupAjaxActionHook();
    cy.loginAsAdmin();
  });
    
  it('2.9.1 - When creating a new user is the user synchronized to Auth0',() => {
    DeleteTestUser(testEmail);
    cy.visit('/?action=cms_users_edit&origin=cms_users');
    cy.get('input[data-testid=\'user_name\']').should('be.visible');
    cy.get('input[data-testid=\'user_email\']').should('be.visible');
    cy.CheckDropDownEmpty('cms_usergroups_id');
    cy.get('input[data-testid=\'user_valid_from\']').should('be.visible');
    cy.get('input[data-testid=\'user_valid_to\']').should('be.visible');
    cy.getButtonWithText('Save and close').should('be.visible');
    cy.get('a').contains('Cancel').should('be.visible');
    FillForm(testName,testEmail,testGroup);
    cy.getButtonWithText('Save and close').click();
    cy.url().should('include', 'action=cms_users');
    cy.notyTextNotificationWithTextIsVisible("User will receive an email with instructions and their password within couple of minutes!");
    CheckUserSyncedAuth0(testEmail);
  });
    
  // // it('2.9.2 - When an error happens during creation of a user is the db and Auth0 out of sync', () => {
        
  // // });
    
  it('2.9.3 - When updating an existing user is the user synchronized to Auth0', () => {
    cy.visit('/?action=cms_users');
    cy.clickOnElementBySelectorAndText('a', testName);
    cy.get('[data-testid=user_name]').clear().type(modifiedTestName);
    cy.get('[data-testid=user_valid_from]').clear().type(modifiedValidFrom);
    cy.getButtonWithText('Save and close').click();
    cy.get('tr').contains(testEmail).should('be.visible');
    CheckUserSyncedAuth0(testEmail);
  });

  // // it('2.9.4 - When an error happens during updating a user is the db and Auth0 out of sync', () => {

  // // });
    
  it('2.9.5 - When soft deleting an existing user is the user synchronized to Auth0', () => {
    cy.visit('/?action=cms_users');
    cy.checkGridCheckboxByText(modifiedTestName);
    cy.getListDeleteButton().should('be.visible');
    cy.getListDeleteButton().click();
    cy.getConfirmActionButton().click();
    cy.waitForAjaxAction(USER_DEACTVATE_REQUEST, USER_DEACTVATE_RESPONSE);
    cy.url().should('include', 'action=cms_users');
    CheckUserSyncedAuth0(testEmail);
    cy.get("div[data-testid='dropapp-header']").should('be.visible');
    DeleteTestUser(testEmail);
  
  });
    
  // it('2.9.6 - When an error happens during soft deleting a user is the db and Auth0 out of sync', () => {
    
  // });

  it('2.9.7 - When a user edit itself is the user synchronized to Auth0', () => {
    cy.visit('/?action=cms_profile');
    cy.get('input[data-testid=\'user_name\']').should('be.visible');
    cy.get('input[data-testid=\'user_email\']').should('be.visible');
    cy.get('input[data-testid=\'user_name\']').clear().type(modifiedTestAdminName);
    cy.get('input[data-testid=\'user_email\']').clear().type(modifiedTestAdminEmail);
    cy.getButtonWithText('Save and close').click();
    cy.url().should('include', '/?action=cms_profile');
    cy.get('.created').should('be.visible');
    CheckUserSyncedAuth0(modifiedTestAdminEmail);
    cy.get('input[data-testid=\'user_name\']').clear().type(testAdminName);
    cy.get('input[data-testid=\'user_email\']').clear().type(testAdminEmail);
    cy.getButtonWithText('Save and close').click();
    cy.url().should('include', '/?action=cms_profile');
    cy.get('.created').should('be.visible');
    cy.get("div[data-testid='dropapp-header']").should('be.visible');
    CheckUserSyncedAuth0(testAdminEmail);
    
  });

  // it('2.9.8 - When an error happens while a user is editing himself is the db and Auth0 out of sync', () => {
    
  // });

  it('2.9.9 - When a user edits its own password is the user in sync with Auth0 and do all warnings appear.', () => {
    cy.visit('/?action=cms_profile');
    cy.get('input[data-testid=\'user_name\']').should('be.visible');
    cy.get('input[data-testid=\'user_email\']').should('be.visible');
    cy.get('input[data-testid=\'user_pass\']').clear().type(config.testWeekPwd);
    cy.get('input[data-testid=\'user_pass2\']').clear().type(config.testWeekPwd);
    cy.checkQtipWithText("qtip-content","Your password must be at least 12 characters including at least 3 of the following 4 types of characters: a lowercase letter, an uppercase letter, a number, a special character (such as !@#$%&/=?_.,:;-).");
    cy.get('input[data-testid=\'user_pass\']').clear().type(config.testNewPwd);
    cy.get('input[data-testid=\'user_pass2\']').clear().type(config.testNewPwd);
    cy.getButtonWithText('Save and close').click();
    cy.get('.created').should('be.visible');
    cy.get("div[data-testid='dropapp-header']").should('be.visible');
    cy.logout();
    cy.log(`Logging in with Wrong Password`)
    cy.request({
      method: "POST",
      url: '/cypress-session.php',
      body: {
        email: config.testAdmin,
        password: config.testPwd
      },
      form: true // submit as POST fields not JSON encoded body
    }).then(response => {
      expect(response.status).to.eq(200);
      expect(response.body.success).to.be.false;
    });
    cy.loginAs(config.testAdmin, config.testNewPwd)
    cy.visit('/?action=cms_profile');
    cy.get('[data-testid=dropapp-header]').should('be.visible');
    cy.get('[data-testid=dropapp-header]').contains(Cypress.env('orgName'));
    cy.get('input[data-testid=\'user_name\']').should('be.visible');
    cy.get('input[data-testid=\'user_email\']').should('be.visible');
    cy.get('input[data-testid=\'user_pass\']').clear().type(config.testPwd);
    cy.get('input[data-testid=\'user_pass2\']').clear().type(config.testPwd);
    cy.getButtonWithText('Save and close').click();
    cy.get('.created').should('be.visible');
    cy.get("div[data-testid='dropapp-header']").should('be.visible');
  });
});
