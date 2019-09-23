context("Box_creation_tests", () => {
    let Test_location = "TestWarehouse";
    let Test_number = "100";
    let Test_product = "Jeans Female";
    let productname  = "Jeans"; //in the box creation product name is automatically concatenated with gender-info, therefore we use this variable to store a processed version for checks in the boxes menu
    let Test_size = "S";  

    
    beforeEach(function() {
        cy.loginAsVolunteer();
        cy.visit('/?action=stock_edit&origin=stock');
    })


    function CheckBoxCreated(Id,productname,size,location,items) {
        cy.get("a[class='menu_stock']").last().click()
        cy.get("input[class ='form-control input-sm']").type(Id)
        cy.get("span[class ='input-group-btn']").click();
        cy.get("td[class = 'list-level-0 list-column-product").should('contain',productname)
        cy.get("td[class = 'list-level-0 list-column-items").should('contain',items)
        cy.get("td[class = 'list-level-0 list-column-size").should('contain',size)
        cy.get("td[class = 'list-level-0 list-column-location").should('contain',location)
    }
    function FillForm(product,size,location,items){
        cy.selectOptionByText("product_id", product)
        cy.get("input[id='field_items']").click().type(items);  
        cy.selectOptionByText("size_id",size)
        cy.selectOptionByText("location_id",location)
    }
    
    function CheckEmpty(){
        cy.get("span[id='select2-chosen-1']").contains("Please select").should("be.visible")
        cy.get("span[id='select2-chosen-2']").contains("Please select").should("be.visible")
        cy.get("span[id='select2-chosen-3']").contains("Please select").should("be.visible")
        cy.get("input[id='field_items']").should("be.empty");
        cy.get("textarea[id='field_comments']").should("be.empty");
        //Check buttons
        cy.get("button").contains("Save and close").should("be.visible")
        cy.get("button").contains("Save and new").should("be.visible")
        cy.get("a").contains("Cancel").should("be.visible")
    }
    
    it('3_2_1 Prevent box creation without data', () => {
        CheckEmpty();
        cy.get("button[class='btn btn-submit btn-success").contains("Save and close").click();
        cy.get("div[id='qtip-1-content']").should("be.visible");
        cy.get("div[id='qtip-2-content']").should("be.visible");
        cy.get("div[id='qtip-3-content']").should("be.visible");
    })


    it('3_2_2 Create Box with data', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number)
        cy.get("button[class='btn btn-submit btn-success").contains("Save and close").click();
        cy.url().should('contain',"action=stock_confirm")
        cy.get("h2").should('contain',"This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location).should("be.visible");
        cy.get("h2").then(($message) => {
            const Test_id = $message.text().split('ID').pop().split('(write')[0].trim();
            cy.get("a").contains("Continue").click();
            cy.url().should('contain','action=stock');
            cy.get("a[class='menu_stock']").last().click()
            CheckBoxCreated(Test_id, productname,Test_size, Test_location,Test_number);
            cy.get("input[class ='form-control input-sm']").type(Test_id)
            cy.get("span[class ='input-group-btn']").click();
        });
        
    })

    it('3_2_3/4 Create Box with data(Save and new) + create QR-code for box', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number)
        cy.get("button[class='btn btn-submit btn-success").contains("Save and new").click();
        cy.get("h2").should('contain',"This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location).should("be.visible");
        cy.get("h2").then(($message) => {
            const Test_id = $message.text().split('ID').pop().split('(write')[0].trim();
            cy.get("a[class='menu_stock']").last().click()
            CheckBoxCreated(Test_id, productname,Test_size, Test_location,Test_number);
            cy.get("a[class=menu_stock]").last().click();
            cy.get("input[class ='form-control input-sm']").click();
            cy.get("span[class = 'fa fa-search']").click();
            cy.get("input[class='item-select']").click();
            cy.get("i[class='fa fa-print']").click();
            cy.url().should('contain','pdf');
            cy.url().should('contain','label');
    })
})

})
