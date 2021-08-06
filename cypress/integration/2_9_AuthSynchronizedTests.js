/* eslint-disable no-undef */

import { getLoginConfiguration } from '../config';

const USER_DEACTVATE_REQUEST = "do=delete"
const USER_DEACTVATE_RESPONSE = "Item deleted";

context('2.9 Auth0 synchronized on CRUD', () => {

  let config = getLoginConfiguration();
  
  let Testname = 'paul';
  let Testgroup = "TestUserGroup_User";
  let Testaddress = 'pauli@pauli.co';
  let ModifiedTestName = 'paul2';
  let ModifiedValidFrom = '02-08-2021';

  let TestAdminName = 'BrowserTestUser_Admin';
  let TestAdminEmail = 'admin@admin.co';
  let ModifiedTestAdminName = 'BrowserTestUser_Admin2';
  let ModifiedTestAdminEmail = 'admin@admin.com';
  

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
    DeleteTestUser(Testaddress);
    cy.visit('/?action=cms_users_edit&origin=cms_users');
    cy.get('input[data-testid=\'user_name\']').should('be.visible');
    cy.get('input[data-testid=\'user_email\']').should('be.visible');
    cy.CheckDropDownEmpty('cms_usergroups_id');
    cy.get('input[data-testid=\'user_valid_from\']').should('be.visible');
    cy.get('input[data-testid=\'user_valid_to\']').should('be.visible');
    cy.getButtonWithText('Save and close').should('be.visible');
    cy.get('a').contains('Cancel').should('be.visible');
    FillForm(Testname,Testaddress,Testgroup);
    cy.getButtonWithText('Save and close').click();
    cy.url().should('include', 'action=cms_users');
    cy.notyTextNotificationWithTextIsVisible("User will receive an email with instructions and their password within couple of minutes!");
    CheckUserSyncedAuth0(Testaddress);
  });
    
  // // it('2.9.2 - When an error happens during creation of a user is the db and Auth0 out of sync', () => {
        
  // // });
    
  it('2.9.3 - When updating an existing user is the user synchronized to Auth0', () => {
    cy.visit('/?action=cms_users');
    cy.clickOnElementBySelectorAndText('a', Testname);
    cy.get('[data-testid=user_name]').clear().type(ModifiedTestName);
    cy.get('[data-testid=user_valid_from]').clear().type(ModifiedValidFrom);
    cy.getButtonWithText('Save and close').click();
    cy.get('tr').contains(Testaddress).should('be.visible');
    CheckUserSyncedAuth0(Testaddress);
  });

  // // it('2.9.4 - When an error happens during updating a user is the db and Auth0 out of sync', () => {

  // // });
    
  it('2.9.5 - When soft deleting an existing user is the user synchronized to Auth0', () => {
    cy.visit('/?action=cms_users');
    cy.checkGridCheckboxByText(ModifiedTestName);
    cy.getListDeleteButton().should('be.visible');
    cy.getListDeleteButton().click();
    cy.getConfirmActionButton().click();
    cy.waitForAjaxAction(USER_DEACTVATE_REQUEST, USER_DEACTVATE_RESPONSE);
    cy.url().should('include', 'action=cms_users');
    CheckUserSyncedAuth0(Testaddress);
  
  });
    
  // it('2.9.6 - When an error happens during soft deleting a user is the db and Auth0 out of sync', () => {
    
  // });

  it('2.9.7 - When a user edit itself is the user synchronized to Auth0', () => {
    cy.visit('/?action=cms_profile');
    cy.get('input[data-testid=\'user_name\']').should('be.visible');
    cy.get('input[data-testid=\'user_email\']').should('be.visible');
    cy.get('input[data-testid=\'user_name\']').clear().type(ModifiedTestAdminName);
    cy.get('input[data-testid=\'user_email\']').clear().type(ModifiedTestAdminEmail);
    cy.getButtonWithText('Save and close').click();
    cy.url().should('include', '/?action=cms_profile');
    cy.get('.created').should('be.visible');
    CheckUserSyncedAuth0(ModifiedTestAdminName);
    cy.get('input[data-testid=\'user_name\']').clear().type(TestAdminName);
    cy.get('input[data-testid=\'user_email\']').clear().type(TestAdminEmail);
    cy.getButtonWithText('Save and close').click();
    cy.url().should('include', '/?action=cms_profile');
    cy.get('.created').should('be.visible');
    CheckUserSyncedAuth0(TestAdminEmail);
    DeleteTestUser(Testaddress);
  });

  // it('2.9.8 - When an error happens while a user is editing himself is the db and Auth0 out of sync', () => {
    
  // });
});
