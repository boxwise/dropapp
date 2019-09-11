context("Box_creation_tests", () => {
    let Test_location = "TestWarehouse";
    let Test_number = "100";
    let Test_user;
    let Test_passwd;
    let Test_product;
    let productname; //in the box creation product name is automatically concatenated with gender-info, therefore we use this variable to store a processed version for checks in the boxes menu
    let Test_size;
    let Test_id1;
    let Test_id2;    
    
    before(function() {
        cy.getCoordinatorUser().then(($result) => {
            Test_user = $result.testCoordinator;
            Test_passwd = $result.testPwd;
            });

    });
    
    beforeEach(function() {
        cy.LoginAjax(Test_user,Test_passwd,true);
        cy.visit('/');
        cy.get("a[class=menu_stock]").last().click();
        cy.get("a.new-page.item-add").click();

    })

    function selectRandomProduct(){
        //select random Product, modified this from Martins function
        cy.get("div[id='s2id_field_product_id']").click();
        cy.get("body").then($body => {
        cy.get("ul[class='select2-results'] li").first().click();})
        cy.get("div[id='s2id_field_product_id']").then((text) =>{
              Test_product = text.text().replace('Product','').trim();
          });
        cy.get("div[id='s2id_field_product_id']").then((text) =>{
            productname = text.text().replace('Product','').replace("Unisex",'').replace("Baby",'').replace("Female",'').replace("Male",'').replace("girl",'').replace("boy",'').replace("-",'').trim();//text.text().replace('Product','').split("Unisex").split("Female").split("Male")[0].trim();
        });
        };
    function pickSize(){
        //just pick the first size
        cy.get("span[id='select2-chosen-2']").click();
        cy.get("body").then($body => {
        cy.get("ul[class='select2-results'] li").eq(1).click()});
        cy.get("span[id='select2-chosen-2']").then((text) =>{
                Test_size = text.text().replace('Size','').trim();
            });
        };
    
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
        cy.get("div[id='s2id_field_product_id']").click();
        cy.get("div[class='select2-result-label']").contains(product).click();
        cy.get("input[id='field_items']").click().type(items);  
        cy.get("span[id=select2-chosen-2]").click();
        cy.get("div[class='select2-result-label']").contains(size).click();
        cy.get("span[id=select2-chosen-3]").click();
        cy.get("div[class='select2-result-label']").contains(location).click();
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
    
    it('3_2_1 Prevent box creation without data (Admin)', () => {
        CheckEmpty();
        cy.get("button[class='btn btn-submit btn-success").contains("Save and close").click();
        cy.get("div[id='qtip-1-content']").should("be.visible");
        cy.get("div[id='qtip-2-content']").should("be.visible");
        cy.get("div[id='qtip-3-content']").should("be.visible");
    })

    it('pick random product and size', () => {
        selectRandomProduct();
        pickSize();
    });

    it('3_2_2 Create Box with data (Admin)', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number)
        cy.get("button[class='btn btn-submit btn-success").contains("Save and close").click();
        cy.url().should('contain',"action=stock_confirm")
        cy.get("h2").should('contain',"This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location).should("be.visible");
        cy.get("h2").then((message) => {
            Test_id1 = message.text().split('ID').pop().split('(write')[0].trim()});
        cy.get("a").contains("Continue").click();
        cy.url().should('contain','action=stock');
    })
    it('3_2_2 Check created box', ()=> {
        //separation because variable saved via alias is not defined within same it()
        CheckBoxCreated(Test_id1,productname,Test_size,Test_location,Test_number);

    })

    it('3_2_3 Create Box with data(Save and new)', () => {
        FillForm(Test_product,Test_size,Test_location,Test_number)
        cy.get("button[class='btn btn-submit btn-success").contains("Save and new").click();
        cy.get("h2").should('contain',"This box contains "+Test_number+" "+Test_product+" and is located in "+Test_location).should("be.visible");
        cy.get("h2").then((message) => {
            Test_id2 = message.text().split('ID').pop().split('(write')[0].trim()});
        CheckEmpty();
    })
    it('3_2_3 Check created box', () => {
        //separation because variable saved via alias is not defined within same it()
        CheckBoxCreated(Test_id2,productname,Test_size,Test_location,Test_number);

    })
    it('3_2_4 Create QR-code', () => {
        cy.get("a[class=menu_stock]").last().click();
        cy.get("input[class ='form-control input-sm']").type(Test_id2).click();
        cy.get("span[class = 'fa fa-search']").click();
        cy.get("input[class='item-select']").click();
        cy.get("i[class='fa fa-print']").click();
        cy.url().should('contain','pdf');
        cy.url().should('contain','label');
    });
});