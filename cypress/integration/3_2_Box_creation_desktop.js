context("Box_creation_tests", () => {
    let Test_location = "TestWarehouse";
    let Test_number = "100";
    let Test_product = "Jeans Female";
    let Product_name  = "Jeans"; //in the box creation product name is automatically concatenated with gender-info, therefore we use this variable to store a processed version for checks in the boxes menu
    let Test_size = "S";  
    
    beforeEach(function() {
        cy.loginAsVolunteer();
        cy.visit('/?action=stock');
        DeleteAllBoxes()
        cy.visit('/?action=stock_edit&origin=stock');

    })

    function getBoxesMenu() {
        cy.get("a[class='menu_stock']").last().click();
    }
    function SaveAndProgress(buttonname) {
        cy.get("button").contains(buttonname).click();
    }
    function SearchBoxById(Id) {
        cy.get("input[data-testid ='box-search']").type(Id);
        cy.get("button[data-testid='search-button']").click();
    }
    function CheckBoxMessage(Text) {
        cy.get("h2").should('contain',Text).should('be.visible');
    }
    
    function CheckBoxCreated(Id,productname,size,location,items) {
        getBoxesMenu();
        SearchBoxById(Id);
        cy.get("td").should('contain',productname).and('contain',size).and('contain',location);
    }

    
    function DeleteAllBoxes(){
        cy.get("input[data-testid='select_all']").click()
        cy.get('button:visible').then(($body) => {
            if ($body.text().includes("Delete")) {
                cy.log("FOUND IT")
            cy.get("button[data-operation='delete']").click();
            cy.get("a[data-apply='confirmation']").click();
            }})

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
        cy.get("input[data-testid='select-id").click();
        cy.get("i[class='fa fa-print']").click();
    }
    function CheckInput(Field_id){
        cy.get("input[data-testid = '" + Field_id + "']").should("be.empty");
    }
    function CheckSaveButton(buttonname){
        cy.get("button").contains(buttonname).should("be.visible");
    }
    function CheckCancelButton(){
        cy.get("a").contains("Cancel").should("be.visible");
    }
    function CheckQtip(qtip_id){
        cy.get("div[id='"+ qtip_id + "']").should("be.visible");
    }
    function CheckCommentField(){
        cy.get("textarea[data-testid='comments_id']").should("be.empty");
    }
    function CheckUrl(Text){
        cy.url().should('contain',Text);
    }
    
    function CheckEmpty(){
        cy.getSelectedValueInDropDown("product_id").contains("Please select").should('exist');
        cy.getSelectedValueInDropDown("size_id").contains("Please select").should('exist');
        cy.getSelectedValueInDropDown("location_id").contains("Please select").should('exist');
        CheckInput("items_id");
        CheckCommentField();
        //Check buttons
        CheckSaveButton("Save and close");
        CheckSaveButton("Save and new");
        CheckCancelButton();
    }
    

    it('3_2_1 Prevent box creation without data', () => {
        CheckEmpty();
        SaveAndProgress("Save and close");
        CheckQtip('qtip-1-content');
        CheckQtip('qtip-2-content');
        CheckQtip('qtip-3-content');
    });
    it('3_2_2 Create Box with data', () => {

        FillForm(Test_product,Test_size,Test_location,Test_number);
        SaveAndProgress("Save and close");
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location);
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message);
            ContinueToMenu();
            CheckBoxCreated(Test_id, Product_name,Test_size, Test_location,Test_number);
        });  
    });

    it('3_2_3 Create Box with data(Save and new)', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number);
        SaveAndProgress("Save and new");
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location);
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message) 
            CheckEmpty();
            getBoxesMenu();
            CheckBoxCreated(Test_id, Product_name,Test_size, Test_location,Test_number);
        });
    });

    it('3_2_4 Create new Box and create QR-code', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number);
        SaveAndProgress("Save and close");
        CheckBoxMessage("This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location);
        cy.get("h2").then(($message) => {
            const Test_id = IdFromMessage($message) ;
            ContinueToMenu();
            SearchBoxById(Test_id);
            CreateQR();
            CheckUrl('pdf');
            CheckUrl('label');
            cy.visit('/?action=stock');
        });
    });
});
