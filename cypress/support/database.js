Cypress.Commands.add("testdbdelete", (table, ids) => {
    cy.request({
        method: "POST",
        url: "/ajax.php?file=testdbdelete",
        body: {
            table: table,
            ids: ids
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        expect(response.body.message).to.be.empty;
        expect(response.body.success).to.be.true;
    });
});
