//test usergroup data to be created
const BrowserTestUserGroup_Coordinator = "BrowserTestUserGroup_Coordinator";
const BrowserTestUserGroup_User = "BrowserTestUserGroup_User";
const TestBase = "TestBase";
const TestBaseOfAnotherOrg = "DummyTestBaseWithBoxes"
const TestUserGroupFunctions = ["Boxes", "Checkout"];

// data from seed
const AllFunctions = ['Free Shop', 'Admin','Users','Settings','Texts','Manage menu functions','Manage products',
'Checkout','Boxes','Give tokens to all','Sales reports','Inventory','Fancy graphs',
'Stockroom','Generate market schedule','Generate QR labels','Actions','Warehouses','Containers List',
'Stock Overview','Manage beneficiaries','Hidden menu items','Start page','Give tokens to selected families',
'User profile','Exit login as','Sales List Download','Insight','General stock','Lists','Services',
'Bicycles / Sport','Borrow edit','Borrow history ','Library','Borrow books','Library history',
'Library','Laundry','All Residents export','Laundry No show','Laundry start new cycle','Organisations',
'Boxwise Gods','User groups','Bases','Add beneficiary'];
const AdminAvailableFunctions = ['Users','Manage products',
'Checkout','Boxes','Give tokens to all','Fancy graphs',
'Stockroom','Generate market schedule','Generate QR labels',
'Stock Overview','Manage beneficiaries', 'Sales reports',
'User groups','Add beneficiary'];
const CoordinatorAvailableFunctions = ['Checkout','Boxes','Fancy graphs',
'Stockroom','Generate QR labels','Users','Manage products','Generate market schedule',
'Stock Overview','Manage beneficiaries', 'Sales reports','Give tokens to all',
'User groups','Add beneficiary'];

const AllLevels = ["Coordinator", "User", "Admin"]
const LevelsVisibleToAdmin = ["Coordinator", "User"];
const LevelsVisibleToCoordinator = ["User"];

const TestUserGroupWithUsersAssigned = "TestUserGroup_NoPermissions";
const TestUserGroup_Coordinator = "TestUserGroup_Coordinator";
const TestUserGroup_User = "TestUserGroup_User"


function userGroupFormElementsAreVisible(){
    cy.get("input[data-testid='userGroupName']").should('be.visible');
    cy.get("select[data-testid='userGroupLevel']").should('be.visible');
    cy.get("select[data-testid='userGroupBases']").should('be.visible');
    cy.get("select[data-testid='userGroupFunctions']").should('be.visible');
}

function deleteUserGroup(name) {
    cy.get('body').then(($body) => {
        if ($body.text().includes(name)) {
            cy.checkGridCheckboxByText(name)
            cy.getListDeleteButton().click();
            cy.getConfirmActionButton().click();
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

describe('Create usergroups (admin)', () => {
    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_usergroups_edit&origin=cms_usergroups');
    });

    it("Create & delete 'coordinator' usergroup", () => {
        FillUserGroupForm(BrowserTestUserGroup_Coordinator, "Coordinator", TestBase, TestUserGroupFunctions)
        cy.getButtonWithText("Save and close").click();
        cy.getRowWithText(BrowserTestUserGroup_Coordinator).should('exist');
        //testing delete
        cy.checkGridCheckboxByText(BrowserTestUserGroup_Coordinator);
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().click();
        cy.getRowWithText(BrowserTestUserGroup_Coordinator).should('not.exist');
        cy.notyTextNotificationWithTextIsVisible("Item deleted");

        //cleanup just in case
        deleteUserGroup(BrowserTestUserGroup_Coordinator);
    });

    it("Create & delete 'user' usergroup", () => {
        FillUserGroupForm(BrowserTestUserGroup_User, "User", TestBase, TestUserGroupFunctions)
        cy.getButtonWithText("Save and close").click();
        cy.getRowWithText(BrowserTestUserGroup_User).should('exist');
        //testing delete
        cy.checkGridCheckboxByText(BrowserTestUserGroup_User);
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().click();
        cy.getRowWithText(BrowserTestUserGroup_User).should('not.exist');
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
        cy.getButtonWithText("Save and close").click();
        cy.checkQtipWithText("qtip-content","This field is required");
        userGroupFormElementsAreVisible();
    });

    it('Prevent usergroup creation without functions', () => {
        cy.get("input[data-testid='userGroupName']").clear().type(BrowserTestUserGroup_Coordinator);
        cy.selectOptionByText('userlevel', "Coordinator");
        cy.selectOptionByText('camps', TestBase);
        cy.getButtonWithText("Save and close").click();
        cy.checkQtipWithText("qtip-content","This field is required");
        userGroupFormElementsAreVisible();
    });

    it("Check available usergroup levels", () => {
        cy.clickSelect("userlevel");
        for (var lvl of AllLevels){
            if (LevelsVisibleToAdmin.includes(lvl)) {
                cy.getOptionByText(lvl).should('exist');
            } else {
                cy.getOptionByText(lvl).should('not.exist');
            }
            
        }
    });

    it('Check available bases', () => {
        cy.clickSelect("camps");
        cy.getOptionByText(TestBase).should('exist');
        cy.getOptionByText(TestBaseOfAnotherOrg).should('not.exist');
        cy.checkOptionsCount("camps", 1);
    });

    it("Check available functions", () => {
        cy.clickSelect("cms_functions");
        for (var fcn of AllFunctions){
            if (AdminAvailableFunctions.includes(fcn)) {
                cy.getOptionByText(fcn).should('exist');
            } else {
                cy.getOptionByText(fcn).should('not.exist');
            }
            
        }
    });

    it('Prevent deletion of usergroup with users', () => {
        cy.visit('/?action=cms_usergroups');
        cy.checkGridCheckboxByText(TestUserGroupWithUsersAssigned)
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().click();
        cy.notyTextNotificationWithTextIsVisible("Please edit or remove it first");
        cy.getRowWithText(TestUserGroupWithUsersAssigned).should('exist');
    });
});


describe('Create usergroups (coordinator)', () => {
    beforeEach(function () {
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_usergroups_edit&origin=cms_usergroups');
    });

    it("Create & delete 'user' usergroup", () => {
        FillUserGroupForm(BrowserTestUserGroup_User, "User", TestBase, TestUserGroupFunctions)
        cy.getButtonWithText("Save and close").click();
        cy.getRowWithText(BrowserTestUserGroup_User).should('exist');
        //testing delete
        cy.checkGridCheckboxByText(BrowserTestUserGroup_User)
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().click();
        cy.getRowWithText(BrowserTestUserGroup_User).should('not.exist');
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
        cy.getButtonWithText("Save and close").click();
        cy.checkQtipWithText("qtip-content","This field is required");
        userGroupFormElementsAreVisible();
    });

    it('Prevent usergroup creation without functions', () => {
        cy.get("input[data-testid='userGroupName']").clear().type(BrowserTestUserGroup_Coordinator);
        cy.selectOptionByText('userlevel', "User");
        cy.selectOptionByText('camps', TestBase);
        cy.getButtonWithText("Save and close").click();
        cy.checkQtipWithText("qtip-content","This field is required");
        userGroupFormElementsAreVisible();
    });

    it("Check available usergroup levels", () => {
        cy.clickSelect("userlevel");
        for (var lvl of AllLevels){
            if (LevelsVisibleToCoordinator.includes(lvl)) {
                cy.getOptionByText(lvl).should('exist');
            } else {
                cy.getOptionByText(lvl).should('not.exist');
            }
            
        }
    });

    it('Check available bases', () => {
        cy.clickSelect("camps");
        cy.getOptionByText(TestBase).should('exist');
        cy.getOptionByText(TestBaseOfAnotherOrg).should('not.exist');
        cy.checkOptionsCount("camps", 1);
    });

    it("Check available functions", () => {
        cy.clickSelect("cms_functions");
        for (var fcn of AllFunctions){
            if (CoordinatorAvailableFunctions.includes(fcn)) {
                cy.getOptionByText(fcn).should('exist');
            } else {
                cy.getOptionByText(fcn).should('not.exist');
            }
            
        }
    });

    it('Prevent deletion of usergroup with users', () => {
        cy.visit('/?action=cms_usergroups');
        cy.checkGridCheckboxByText(TestUserGroupWithUsersAssigned)
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().click();
        cy.notyTextNotificationWithTextIsVisible("Please edit or remove it first");
        cy.getRowWithText(TestUserGroupWithUsersAssigned).should('exist');
    });
});
