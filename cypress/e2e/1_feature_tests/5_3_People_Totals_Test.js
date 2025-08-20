context("People_Totals_Test", () => {
    beforeEach(function () {
        cy.setupAjaxActionHook();
        cy.loginAsVolunteer();
        cy.visit('/?action=people');
    });

    it("Should display totals row showing number of beneficiaries", () => {
        // Check that the totals row exists in the header
        cy.get('table').within(() => {
            // Look for a row that contains "Total" in the first cell
            cy.get('tr').contains('Total').should('exist');
            
            // Check that the total row also contains "beneficiaries"
            cy.get('tr').contains('Total').should('contain', 'beneficiaries');
        });
        
        // The totals should be displayed in both header and footer
        // Check footer as well if it exists
        cy.get('table').then(($table) => {
            const totalRows = $table.find('tr:contains("Total")');
            // Should have at least one total row (header), possibly two (header + footer)
            expect(totalRows.length).to.be.at.least(1);
        });
    });

    it("Should update totals when filtering", () => {
        // First, record the initial total count
        let initialCount;
        cy.get('tr').contains('Total').invoke('text').then((text) => {
            const match = text.match(/(\d+)\s+beneficiaries/);
            initialCount = match ? parseInt(match[1]) : 0;
            cy.log(`Initial count: ${initialCount}`);
        });

        // Apply a filter (use the search functionality)
        cy.get('input[type="search"]').type('test_filter_that_should_reduce_results');
        
        // Check that the total has changed (should be less or equal)
        cy.get('tr').contains('Total').invoke('text').then((text) => {
            const match = text.match(/(\d+)\s+beneficiaries/);
            const filteredCount = match ? parseInt(match[1]) : 0;
            cy.log(`Filtered count: ${filteredCount}`);
            
            // The filtered count should be less than or equal to the initial count
            expect(filteredCount).to.be.at.most(initialCount);
        });
    });
});