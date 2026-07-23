Cypress.Commands.add("testdbdelete", (table, ids, emails = null) => {
    cy.request({
        method: "POST",
        url: "/ajax.php?file=testdbdelete",
        body: {
            table: table,
            ids: ids,
            emails: emails
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        expect(response.body).to.contain("true");
    });
});

Cypress.Commands.add("testauth0user", (email) => {
    cy.request({
        method: "POST",
        url: "/ajax.php?file=testauth0user",
        body: {
            email: email
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        expect(response.body).to.contain("true");
    });
});

Cypress.Commands.add("checkHistoryLog", (tablename, recordId, expectedChange) => {
    cy.request({
        method: "POST",
        url: "/ajax.php?file=testhistorycheck",
        body: {
            tablename: tablename,
            record_id: recordId,
            expected_change: expectedChange
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        const body = typeof response.body === 'string' ? JSON.parse(response.body) : response.body;
        expect(body.found).to.eq(true);
        expect(body.count).to.be.greaterThan(0);
    });
});

Cypress.Commands.add("getBeneficiaryIdFromRow", (lastname) => {
    return cy.getRowWithText(lastname).then($row => {
        const id = $row.closest('tr').attr('data-id');
        expect(id).to.not.be.undefined;
        return parseInt(id);
    });
});
