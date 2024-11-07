describe('QR labels tests - user with rights', () => {

    function labelsCountInputIsVisible(number){
        cy.get("input[data-testid='numberOfLabelsInput']").should("be.visible");
    }

    
    function isQrsNumberCorrect(numberOfQrs){
        cy.url().should('include', 'count='+numberOfQrs);
    }

    function isUsingBigLabels(){
        cy.get("body").find('embed').should('exist');
    }

    beforeEach(() => {
        cy.loginAsVolunteer();
        cy.visit('/?action=qr');
    });
    

    it('Left panel navigation', () => {
        cy.visit('/');
        cy.viewport(1280, 720);
        cy.get("a[class='menu_qr']").last().contains("Print Box Labels").click();
        labelsCountInputIsVisible();
        cy.verifyActiveSideMenuNavigation('menu_qr');
    });

    it('Check if QR-Code is generated correctly', () => {
        cy.request({
            method: "POST",
            url: '/?action=qr',
            body: {
                label: "100000000",
            },
            form: true // submit as POST fields not JSON encoded body
        }).then(response => {
            expect(response.status).to.eq(200);
            if(Cypress.config("baseUrl").includes("staging")){
                expect(response.body).to.include("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAIAAACzY+a1AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAEMUlEQVR4nO2d0ZLbMAhFk07+/5fdV4+nciGAdk9yztt2ZFnxLSFIgJ/HcTyEzOvxeDyfz/Z5I/8zzvc9j8+uZ3Wv1fyrMZE5I/N3fa4Ix3H8aZ9UNqOEeJQQz+vyd+XXzeq7PuKTVmuI+I+sj8mup8s3Z8esuKxHK8SjhHiUEM/VF56J+IDK934lVqv4s533jVB8zlohHiXEo4R47nxhF5Xv+kqMeL52wv91/VYoohXiUUI8Sohnhy/MxlVZvzgRq0XWsxozcS54g1aIRwnxKCGeO1+4cw8wm8OSjfNWdJ0XVvxx8TlrhXiUEI8S4rn6wumYprL3WMnbrMxfWX+Xz75BK8SjhHiUEM/rMX+m1eWHuu474Xez62lEK8SjhHiUEM/zOI5KjNJVF9jlCyfyS1fjV3TFrMHnoxXiUUI8SojnGXQ8lZzJ6Zr0ibPD7D7nzq4Tl/tqhXiUEI8S4rnzhdN9WyJ0xWET963QGCNqhXiUEI8S4on2YKvUQmTnydb8ZcdU5oxQiSndI/1GlBCPEuK5xoUT+4rT+Z8TY1ZM1N0X59QK8SghHiXEUz0vrOSbTJ+xTfTI7vq8jb5TK8SjhHiUEM8776moxFgT/qBC1rdFPm+2P07RZ2uFeJQQjxLiidZUTMRwWT9ayYvp2tvc+R6oyHoeWuEHoIR4lBDPb8kj7arz6+oLk13b9HugVrhH+gkoIR4lxHPXg21nbshEfWHXPu2K6dyf4O8PrRCPEuJRQjyde6QTceREn5fstZH1TNSHRDAu/ASUEI8S4rnrRxrp1Xkm4i8n8k4n/F9XPWX2vmeCz0ErxKOEeJQQz1194c54K7uGLD/VmzSyHusLvx0lxKOEeN6pL5yul6+sJzu+Ky9mYi/U+sJvQQnxKCGe63lhVw/PofqB/95rOk6NMN0r7oJWiEcJ8SghnmoeaaTGPEtXPmr22ux6Vkzn6VzQCvEoIR4lxLOjH2l2/omzvci10/u9Qzm0WiEeJcSjhHiie6QTuZ3TdRQRJvqAr8Zn1xBEK8SjhHiUEE/VF64g9h1dMZ0XU4xTtUI8SohHCfFczwtX7Dyf68o1jcwZYbpmv1hfoRXiUUI8SojnLi7sisnOTNQXTuSITsepK6wv/EaUEI8S4onWF5busTHO68r57JpzusZfX/gJKCEeJcTzzvsLI2T7lk37tq4+LxO9vIv30grxKCEeJcRzPS/cGat15aZm15b1iz/VWyc4XivEo4R4lBDPXe7ML6kZ+Oe1Xf3MzkzUV2TX+caz0grxKCEeJcQTzSOdYKJ+ozJPhaF3UETGa4V4lBCPEuLZ7Quz70KqxFWRf4/EXtO96IoxqFaIRwnxKCGeO184kWI6Xeewsx/pRK19ZMxlPVohHiXEo4R47npz/wa6zu2y8d+Z7Bne5n6qWiEeJcSjhHh21BfKKH8Bh0P9GzVnvC8AAAAASUVORK5CYII=");
            } else if (Cypress.config("baseUrl").includes("localhost")){
                expect(response.body).to.include("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAIAAACzY+a1AAAACXBIWXMAAA7EAAAOxAGVKw4bAAADvklEQVR4nO2dQW7jMAwAk0X//+Xs1QgggzRJJRPPHFvHVjMgWEqi/Hy9Xg8h8+/TA5AqKsSjQjwqxKNCPCrEo0I8KsTzd/K75/PZ/rzVTMLxWcdrVmPI3idC5FmVayqc/C1GIR4V4lEhnrNceKQyG77KDZGckc15XTlpdZ/s/Se+tzeMQjwqxKNCPNFceKQrB0TySrYOW+XFFZE6MvKsCEO50yjEo0I8KsRzJRd2MZ1TJ+Zjv3C3mFGIR4V4VIjnk7mwUldN13Cr566e9UGMQjwqxKNCPFdyYVcOiNRnldqua20vMn+brV8bMQrxqBCPCvFEc+HE3sgK2fyXXZvM1p0rNnxvRiEeFeJRIZ7nl0z0rZhe/9vZszGEUYhHhXhUiOcsF070JFTucyR7z8ocbHY8XTVrMNcahXhUiEeFeKK5sFJv7cw3K7L9FdnPZu+5wp6KO6JCPCrE01kXVuYVu3LnRO7pqkGz11sX3gUV4lEhnivrhRM5sus+0/3vXXm9MoY3jEI8KsSjQjxX5ki7rqnMkXYx3XdR+ax14V1QIR4V4onWhd+Qz7rOVJsYT9f8reuFd0SFeFSIZ0dPxc59pBN7fCLPzY6hazwPo/AHUCEeFeKZ2keaHseHnrVieo2zazwPo/AHUCEeFeK5Mkd6ZOK9fV214M5z3brOVrUuvCMqxKNCPFNzpJVewCMTw5vuj1wxdJaNUYhHhXhUiKfaX9iV5yrrdjv7C3eeAxD8ToxCPCrEo0I8Z2dzV865nniXRXZslftXrt98ZqlRiEeFeFSIp9pfGPnsiomcOnEe2/QZctlr3jAK8agQjwrx7HiX70SNONHjWBnn9FkEzpH+MirEo0I8V3Jh13v+dp55PbEHtWvvaPF/BaMQjwrxqBBPZ104UZ9N9C1ExhAZT4RKvegc6V1QIR4V4qn22k/kmG/Inav7R67PYn/h3VEhHhXi+eR77XeeBZplYt/sRM/+wyj8AVSIR4V4zuZIp98Lsfp5136TSt6q9JNkc5t14d1RIR4V4omuF07sc+nad7Pz3RQRKjWi+0jviArxqBBPdR/pikpOiuSGyD0re1K+Lb+eYBTiUSEeFeLZ0V+YZWde6Tr7LXJNZE71iHOkd0GFeFSI51tyYdd64eqayHOzP6/seWncsmQU4lEhHhXiuZILd249rawXds3Tdu2FWT23uE/VKMSjQjwqxBPNhdPzll3rc9PvlDgy0WuR/ezDKPwBVIhHhXg+2V8oLRiFeFSIR4V4VIhHhXhUiEeFeFSI5z8czhANodb3GgAAAABJRU5ErkJggg==");
            }
        });
    });

    it('(Desktop) Generate 250 QR codes - small', () => {
        let numberOfQrs = 250;
        cy.typeNumberOfLabels(numberOfQrs);
        cy.uncheckBigLabelsCheckbox();
        cy.clickMakeLabelsButton();
        cy.get("div[data-testid='boxlabel-small']").then($smallLabels => {
            expect($smallLabels.length).to.equal(numberOfQrs);
        })
    });
    
    // QRs shown in PDF cause issues when run in CircleCI
    // it('(Desktop) Generate 10 QR codes - big', () => {
    //     let numberOfQrs = 1;
    //     cy.typeNumberOfLabels(numberOfQrs);
    //     cy.checkBigLabelsCheckbox();
    //     cy.clickMakeLabelsButton();
    //     isQrsNumberCorrect(numberOfQrs);
    //     isUsingBigLabels();
    // });

    // it('(iPhone 6 viewport) Generate 10 QR codes - small', () => {
    //     cy.viewport('iphone-6')
    //     let numberOfQrs = 10;
    //     cy.typeNumberOfLabels(numberOfQrs);
    //     cy.uncheckBigLabelsCheckbox();
    //     cy.clickMakeLabelsButton();
    //     cy.get("div[data-testid='boxlabel-small']").then($smallLabels => {
    //         expect($smallLabels.length).to.equal(numberOfQrs);
    //     })
    // });

    // QRs shown in PDF cause issues when run in CircleCI
    // it('(iPhone 6 viewport) Generate 10 QR codes - big', () => {
    //     cy.viewport('iphone-6')
    //     let numberOfQrs = 1;
    //     cy.typeNumberOfLabels(numberOfQrs);
    //     cy.checkBigLabelsCheckbox();
    //     cy.clickMakeLabelsButton();
    //     isQrsNumberCorrect(numberOfQrs);
    //     isUsingBigLabels();
    // });
});

describe('QR labels tests - user without rights', () => {

    function labelsCountInputDoesntExist(number){
        cy.getElementByTypeAndTestId("input", "numberOfLabelsInput").should('not.exist');
    }  

    function generateQrsMenuDoesntExist(){
        cy.get("a[class='menu_qr']").should('not.exist');
    }

    beforeEach(() => {
        cy.loginAsVolunteerWithNoPermissions();
        cy.visit('/?action=qr');
    });
  
    it("'Print box labels' menu is hidden", () => {
        generateQrsMenuDoesntExist();
    });
  
    it('Print box labels page empty', () => {
        labelsCountInputDoesntExist();
    });    
  });