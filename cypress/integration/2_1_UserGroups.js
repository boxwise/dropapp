//test user groups to be created
const BrowserTestUserGroup_Coordinator = "BrowserTestUserGroup_Coordinator";
const BrowserTestUserGroup_User = "BrowserTestUserGroup_User";

// data from seed
const TestBase = "TestBase";
const AllFunctions = ['Free Shop', 'Admin','Users','Settings','Texts','Manage menu functions','Manage products',
'Checkout','Boxes','Find beneficiary','Give tokens to all','Sales reports','Inventory','Fancy graphs',
'Stockroom','Generate market schedule','Generate QR labels','Actions','Warehouses','Containers List',
'Needed items','Manage beneficiaries','Hidden menu items','Start page','Give tokens to selected families',
'User profile','Exit login as','Sales List Download','Insight','General stock','Lists','Services',
'Bicycles / Sport','Borrow edit','Borrow history ','Library','Borrow books','Library history',
'Library','Laundry','All Residents export','Laundry No show','Laundry start new cycle','Organisations',
'Boxwise Gods','User groups','Bases','Add beneficiary'];
const AdminAvailableFunctions = ['Users','Manage products',
'Checkout','Boxes','Give tokens to all','Fancy graphs', 'Find beneficiary',
'Stockroom','Generate market schedule','Generate QR labels',
'Needed items','Manage beneficiaries', 'Sales reports',
'User groups','Add beneficiary'];
const CoordinatorAvailableFunctions = ['Checkout','Boxes','Fancy graphs', 'Find beneficiary',
'Stockroom','Generate QR labels','Users','Manage products','Generate market schedule',
'Needed items','Manage beneficiaries', 'Sales reports','Give tokens to all',
'User groups','Add beneficiary'];
const TestUserGroupFunctions = ["Boxes", "Checkout"];
const AllLevels = ["Coordinator", "User", "Admin"]
const LevelsVisibleToAdmin = ["Coordinator", "User"];
const LevelsVisibleToCoordinator = ["User"];
const UserGroupWithUsersAssigned = "TestUserGroup_NoPermissions";
const TestUserGroup_Coordinator = "TestUserGroup_Coordinator";
const TestUserGroup_User = "TestUserGroup_User"


function userGroupFormElementsAreVisible(){
    cy.get("input[data-testid='userGroupName']").should('be.visible');
    cy.get("select[data-testid='userGroupLevel']").should('be.visible');
    cy.get("select[data-testid='userGroupBases']").should('be.visible');
    cy.get("select[data-testid='userGroupFunctions']").should('be.visible');
}

function getOption(lvl){
    return cy.get("ul[class='select2-results'] li").contains(lvl);
}

function clickButtonWithText(buttontext) {
    cy.get("button").contains(buttontext).click();
}

function checkUserGroupCheckboxByName(name){
    getUserGroupRow(name).parent().parent().parent().within(() => {
        cy.get("input[type='checkbox']").check();
    });
}

function getUserGroupRow(name){
    return cy.get('tr').contains(name);
}

function deleteUserGroup(name) {
    cy.get('body').then(($body) => {
        if ($body.text().includes(name)) {
            checkUserGroupCheckboxByName(name)
            clickDeleteButton();
            confirmAction();
        }
    });
}

function FillUserGroupForm(name, level, availableBase, availableFunctions){
    cy.get("input[data-testid='userGroupName']").clear().type(name);
    cy.selectOptionByText('userlevel', level);
    cy.selectOptionByText('camps', availableBase);
    for (var fcn of availableFunctions) {
        cy.selectOptionByText("cms_functions",fcn);
    }
}

function clickDeleteButton(){
    cy.get("button[data-testid='list-delete-button']").click();
}

function confirmAction(){
    cy.get("a[data-apply='confirmation']").click();
}

function checkQtip(qtipText) {
    cy.get("div[class='qtip-content']").contains(qtipText).should("be.visible");
}

describe('Create usergroups (admin)', () => {
    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_usergroups_edit&origin=cms_usergroups');
    });

    it("Create & delete 'coordinator' usergroup", () => {
        FillUserGroupForm(BrowserTestUserGroup_Coordinator, "Coordinator", TestBase, TestUserGroupFunctions)
        clickButtonWithText("Save and close");
        getUserGroupRow(BrowserTestUserGroup_Coordinator).should('exist');
        //testing delete
        checkUserGroupCheckboxByName(BrowserTestUserGroup_Coordinator)
        clickDeleteButton();
        confirmAction();
        getUserGroupRow(BrowserTestUserGroup_Coordinator).should('not.exist');
        cy.notyTextNotificationWithTextIsVisible("Item deleted");

        //cleanup just in case
        deleteUserGroup(BrowserTestUserGroup_Coordinator);
    });

    it("Create & delete 'user' usergroup", () => {
        FillUserGroupForm(BrowserTestUserGroup_User, "User", TestBase, TestUserGroupFunctions)
        clickButtonWithText("Save and close");
        getUserGroupRow(BrowserTestUserGroup_User).should('exist');
        //testing delete
        checkUserGroupCheckboxByName(BrowserTestUserGroup_User)
        clickDeleteButton();
        confirmAction();
        getUserGroupRow(BrowserTestUserGroup_User).should('not.exist');
        cy.notyTextNotificationWithTextIsVisible("Item deleted");

        //cleanup just in case
        deleteUserGroup(BrowserTestUserGroup_User);
    });

    it('Prevent usergroup creation without base', () => {
        cy.get("input[data-testid='userGroupName']").clear().type(BrowserTestUserGroup_Coordinator);
        cy.selectOptionByText('userlevel', "Coordinator");
        for (var fcn of TestUserGroupFunctions) {
            cy.selectOptionByText("cms_functions",fcn);
        }
        clickButtonWithText("Save and close");
        checkQtip("This field is required");
        userGroupFormElementsAreVisible();
    });

    it('Prevent usergroup creation without functions', () => {
        cy.get("input[data-testid='userGroupName']").clear().type(BrowserTestUserGroup_Coordinator);
        cy.selectOptionByText('userlevel', "Coordinator");
        cy.selectOptionByText('camps', TestBase);
        clickButtonWithText("Save and close");
        checkQtip("This field is required");
        userGroupFormElementsAreVisible();
    });


    it("Check available usergroup levels", () => {
        cy.clickSelect("userlevel");
        for (var lvl of AllLevels){
            if (LevelsVisibleToAdmin.includes(lvl)) {
                getOption(lvl).should('exist');
            } else {
                getOption(lvl).should('not.exist');
            }
            
        }
    });

    it("Check available functions", () => {
        cy.clickSelect("cms_functions");
        for (var fcn of AllFunctions){
            if (AdminAvailableFunctions.includes(fcn)) {
                getOption(fcn).should('exist');
            } else {
                getOption(fcn).should('not.exist');
            }
            
        }
    });

    it('Prevent deletion of usergroup with users', () => {
        cy.visit('/?action=cms_usergroups');
        checkUserGroupCheckboxByName(UserGroupWithUsersAssigned)
        clickDeleteButton();
        confirmAction();
        cy.notyTextNotificationWithTextIsVisible("Please edit or delete it first");
        getUserGroupRow(UserGroupWithUsersAssigned).should('exist');
    });
});


describe('Create usergroups (coordinator)', () => {
    beforeEach(function () {
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_usergroups_edit&origin=cms_usergroups');
    });

    it("Create & delete 'user' usergroup", () => {
        FillUserGroupForm(BrowserTestUserGroup_User, "User", TestBase, TestUserGroupFunctions)
        clickButtonWithText("Save and close");
        getUserGroupRow(BrowserTestUserGroup_User).should('exist');
        //testing delete
        checkUserGroupCheckboxByName(BrowserTestUserGroup_User)
        clickDeleteButton();
        confirmAction();
        getUserGroupRow(BrowserTestUserGroup_User).should('not.exist');
        cy.notyTextNotificationWithTextIsVisible("Item deleted");

        //cleanup just in case
        deleteUserGroup(BrowserTestUserGroup_User);
    });

    it('Prevent usergroup creation without base', () => {
        cy.get("input[data-testid='userGroupName']").clear().type(BrowserTestUserGroup_Coordinator);
        cy.selectOptionByText('userlevel', "User");
        for (var fcn of TestUserGroupFunctions) {
            cy.selectOptionByText("cms_functions",fcn);
        }
        clickButtonWithText("Save and close");
        checkQtip("This field is required");
        userGroupFormElementsAreVisible();
    });

    it('Prevent usergroup creation without functions', () => {
        cy.get("input[data-testid='userGroupName']").clear().type(BrowserTestUserGroup_Coordinator);
        cy.selectOptionByText('userlevel', "User");
        cy.selectOptionByText('camps', TestBase);
        clickButtonWithText("Save and close");
        checkQtip("This field is required");
        userGroupFormElementsAreVisible();
    });

    it("Check available usergroup levels", () => {
        cy.clickSelect("userlevel");
        for (var lvl of AllLevels){
            if (LevelsVisibleToCoordinator.includes(lvl)) {
                getOption(lvl).should('exist');
            } else {
                getOption(lvl).should('not.exist');
            }
            
        }
    });

    it("Check available functions", () => {
        cy.clickSelect("cms_functions");
        for (var fcn of AllFunctions){
            if (CoordinatorAvailableFunctions.includes(fcn)) {
                getOption(fcn).should('exist');
            } else {
                getOption(fcn).should('not.exist');
            }
            
        }
    });

    it('Prevent deletion of usergroup with users', () => {
        cy.visit('/?action=cms_usergroups');
        checkUserGroupCheckboxByName(UserGroupWithUsersAssigned)
        clickDeleteButton();
        confirmAction();
        cy.notyTextNotificationWithTextIsVisible("Please edit or delete it first");
        getUserGroupRow(UserGroupWithUsersAssigned).should('exist');
    });
});