context("Box_creation_tests", () => {
    let Test_location = "TestWarehouse";
    let Test_number = "100";
    let Test_product = "Jeans Female";
    let Product_name  = "Jeans"; //in the box creation product name is automatically concatenated with gender-info, therefore we use this variable to store a processed version for checks in the boxes menu
    let Test_size = "S";  

    beforeEach(function() {
        cy.loginAsVolunteer();
        cy.visit('/?action=stock_edit&origin=stock');
    })

    // afterEach(function(){
    //     cy.visit('/?action=stock');
    //     cy.deleteAllBoxesExceptSeed();
    // })

    function getBoxesMenu() {
        cy.visit('/?action=stock');
    }
    function clearSearchbox(){
        cy.get("input[data-testid ='box-search']").clear();
        cy.get("button[data-testid='search-button']").click();
    }
    function SearchBoxById(Id) {
        cy.get("input[data-testid ='box-search']").type(Id);
        cy.get("button[data-testid='search-button']").click();
        cy.reload(); // The search submits a form and reloads the whole page. This is not caught properly by Ajax though. Cypress executes the next command without waiting for the reload to be finished. therefore, we added an additional reload.
    }
    function CheckBoxMessage(Text) {
        cy.get("h2").should('contain',Text).should('be.visible');
    }
    
    function CheckBoxCreated(Id,productname,size,location,items) {
        getBoxesMenu();
        SearchBoxById(Id);
        cy.get("td").should('contain',productname).and('contain',size).and('contain',location);
    }
    function FillForm(product,size,location,items){
        cy.selectOptionByText("product_id", product);
        cy.get("input[id='field_items']").click().type(items);  
        cy.selectOptionByText("size_id",size);
        cy.selectOptionByText("location_id",location);
    }
    function IdFromMessage(message) {
        return message.text().split('ID').pop().split('(write')[0].trim();
    }
    function ContinueToMenu() {
        cy.get("a").contains("Continue").click();
    }

    function CreateQR() {
        cy.get("input[data-testid='select-id']").first().click();
        cy.get("i[class='fa fa-print']").click();
    }
    function CheckUrl(Text){
        cy.url().should('contain',Text);
    }
    
    function CheckEmptyBoxForm(){
        cy.getSelectedValueInDropDown("product_id").contains("Please select").should('exist');
        cy.getSelectedValueInDropDown("size_id").contains("Please select").should('exist');
        cy.getSelectedValueInDropDown("location_id").contains("Please select").should('exist');
        cy.checkInputIsEmpty("items_id");
        cy.checkCommentFieldIsEmpty();
        //Check buttons
        cy.getButtonWithText("Save and close").should("be.visible");
        cy.getButtonWithText("Save and new").should("be.visible");
        cy.checkCancelButton();
    }
    

    it('3_2_1 Prevent box creation without data', () => {
        CheckEmptyBoxForm();
        cy.getButtonWithText("Save and close").click();
        cy.checkQtip('qtip-1-content');
        cy.checkQtip('qtip-2-content');
        cy.checkQtip('qtip-3-content');
    });

    it('3_2_2 Create Box with data', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number);
        cy.getButtonWithText("Save and close").click();
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location);
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message);
            ContinueToMenu();
            CheckBoxCreated(Test_id, Product_name,Test_size, Test_location,Test_number);
            clearSearchbox();
            cy.deleteAllBoxesExceptSeed();
        });  
    });

    it('3_2_3 Create Box with data(Save and new)', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number);
        cy.getButtonWithText("Save and new").click();
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location);
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message) 
            CheckEmptyBoxForm();
            getBoxesMenu();
            CheckBoxCreated(Test_id, Product_name,Test_size, Test_location,Test_number);
            clearSearchbox();
            cy.deleteAllBoxesExceptSeed();
        });
    });

    it('3_2_4 Create new Box and create QR-code', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number);
        cy.getButtonWithText("Save and close").click();
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location);
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message) ;
            ContinueToMenu();
            SearchBoxById(Test_id);
            clearSearchbox();
            // Pdf cannot be opened in circle ci
            CreateQR();
            // CheckUrl('pdf');
            // CheckUrl('label');
            cy.deleteAllBoxesExceptSeed();
        });
    });
});
