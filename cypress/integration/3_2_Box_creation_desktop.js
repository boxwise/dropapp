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

    function getBoxesMenu() {
        cy.get("a[class='menu_stock']").last().click()
    }
    function SaveAndProgress(buttonname) {
        cy.get("button").contains(buttonname).click()
    }
    function SearchBoxById(Id) {
        cy.get("input[data-testid ='box-search']").type(Id)
        cy.get("button[data-testid='search-button']").click();
    }
    function CheckBoxMessage(Text) {
        cy.get("h2").should('contain',Text).should('be.visible');
    }
    
    function CheckBoxCreated(Id,productname,size,location,items) {
        getBoxesMenu()
        SearchBoxById(Id)
        cy.get("td").should('contain',productname).and('contain',size).and('contain',location)
    }

    function DeleteTested(Id) {
        //SearchBoxById(Id)
        cy.get("input[data-testid='select-id").click()
        cy.get("button[data-operation='delete']").click()
        cy.get("a[data-apply='confirmation']").click()
    }
    function FillForm(product,size,location,items){
        cy.selectOptionByText("product_id", product)
        cy.get("input[id='field_items']").click().type(items);  
        cy.selectOptionByText("size_id",size)
        cy.selectOptionByText("location_id",location)
    }
    function IdFromMessage(message) {
        return message.text().split('ID').pop().split('(write')[0].trim();
    }

    function ContinueToMenu() {
        cy.get("a").contains("Continue").click();
    }

    function CreateQR() {
        cy.get("input[data-testid='select-id").click()
        cy.get("i[class='fa fa-print']").click();
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
        SaveAndProgress("Save and close")
        cy.get("div[id='qtip-1-content']").should("be.visible");
        cy.get("div[id='qtip-2-content']").should("be.visible");
        cy.get("div[id='qtip-3-content']").should("be.visible");
    })

    it('3_2_2 Create Box with data', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number)
        SaveAndProgress("Save and close");
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location)
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message) 
            ContinueToMenu()
            CheckBoxCreated(Test_id, productname,Test_size, Test_location,Test_number);
            DeleteTested(Test_id)
        });
        
    })
    it('3_2_3 Create Box with data(Save and new)', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number)
        SaveAndProgress("Save and new")
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location)
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message) 
            CheckEmpty();
            getBoxesMenu();
            CheckBoxCreated(Test_id, productname,Test_size, Test_location,Test_number);
            DeleteTested();

    })
    })
    it('3_2_4 Create new Box and create QR-code', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number)
        SaveAndProgress("Save and close")
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location)
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message) 
            ContinueToMenu()
            SearchBoxById(Test_id)
            CreateQR()
            cy.url().should('contain','pdf');
            cy.url().should('contain','label');
            cy.visit('/?action=stock');
            DeleteTested()
    })
})
})
