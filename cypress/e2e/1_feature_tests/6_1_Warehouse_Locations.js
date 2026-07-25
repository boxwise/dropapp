context("6_1_Warehouse_Locations_Test", () => {
    // exact-length strings, built programmatically so the boundary is never off-by-one
    const Test_label_prefix = "aaa_LocLenTest_";
    const Test_label_at_limit = Test_label_prefix.padEnd(50, "X"); // exactly 50 chars
    const Test_label_over_limit = Test_label_prefix.padEnd(51, "X"); // exactly 51 chars

    function NavigateToNewLocationForm() {
        cy.visit('/?action=locations_edit&origin=locations');
    }

    function FillLocationForm(label) {
        cy.get("input[id='field_label']").clear().type(label);
        cy.selectOptionByText("box_state_id", "Instock");
    }

    function ArchiveTestedLocation(label) {
        cy.visit('/?action=locations');
        cy.get('body').then(($body) => {
            if ($body.text().includes(label)) {
                cy.checkGridCheckboxByText(label);
                cy.get("button[data-testid='reactivate-cms-user']").click();
                cy.getConfirmActionButton().click();
            }
        });
    }

    beforeEach(function () {
        cy.loginAsCoordinator();
    });

    afterEach(function () {
        ArchiveTestedLocation(Test_label_at_limit);
    });

    it("6_1_1 Rejects a location name over the character limit with an inline error, and does not save it", () => {
        NavigateToNewLocationForm();
        FillLocationForm(Test_label_over_limit);
        cy.getButtonWithText("Save and close").click();

        cy.checkQtipWithText("qtip-content", "Please use 50 characters or fewer.");
        // still on the form - the too-long name was never submitted
        cy.url().should('include', 'action=locations_edit');

        cy.visit('/?action=locations');
        cy.get('body').should('not.contain', Test_label_prefix);
    });

    it("6_1_2 Saves a location name that is exactly at the character limit", () => {
        NavigateToNewLocationForm();
        FillLocationForm(Test_label_at_limit);
        cy.getButtonWithText("Save and close").click();

        cy.url().should('include', 'action=locations');
        cy.getRowWithText(Test_label_at_limit).should('exist');
    });
});
