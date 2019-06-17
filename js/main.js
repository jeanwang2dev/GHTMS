/*
The main Javascript file 
*/
"use strict";

var pageObj ={}

//contact form
pageObj.cform = document.getElementById("form");

//initialize the page Object
pageObj.init = function(){

    //** FOR SETTING reCAPTCHA */
    pageObj.set_reCAPTCHA();
    
    //** FOR NAVIGATION */
    var navlist = document.getElementsByClassName('navigation__list');
    //add eventlistener to the navigation List ul element
    navlist[0].addEventListener("click", goToLink, false);

    //turn off the menu effect by making the checkbox uncheck
    function goToLink() {
        document.querySelector("#navi-toggle").checked = false;
    }

    //**  FOR POPUP */
    var popup = document.querySelector("#popup");
    //Detect all clicks on the popup element
    popup.addEventListener("click", function(e) {
        
        //if the click is outof the popup content, then link to the tours section
        //this way makes the css target code lose effect
        if(!e.target.closest(".popup__content")){
            self.location = "#footer";
        } 
        
    });    

    //** FOR CONTACT FORM ONE */
    if ( document.getElementById("cformBtn").length != 0 ){

        document.getElementById("cformBtn").addEventListener('click', function(e){

           //clean the form message field when user re-submit a new message
           document.getElementById('resultmsg').setAttribute("style", "display: none");
           if(document.getElementById('resultmsg').value!=""){
               document.getElementById('resultmsg').innerHTML ="";
           }
           if(document.getElementById('status').value!=""){
               document.getElementById('status').innerHTML ="";
           }
            
         });

         //add event listener to form on submit, use HTML5 validation with a fallback on Safari 9 
         //create a XHR object for ajax call  
         pageObj.cform.addEventListener('submit', function(e){

           e.preventDefault();
           //for html5validation fallback on Safari 9
           if(!this.checkValidity()){
               this.classList.add('invalid');
               document.getElementById('status').innerHTML = "Please fill out the empty field or correct the invalid data field with the red border";
               return;
           }else{
               this.classList.add('valid');
           }
           //grab attributes and values out of the form and append to formData 
           var email = document.getElementById('email').value;
           var name= document.getElementById('name').value;
           var subject = document.getElementById('subject').value;
           var msg = document.getElementById('msg').value;
           var reCAP = document.getElementById('recaptchaResponse').value;

           var formData = new FormData(); 
           formData.append('email',email);
           formData.append('name',name);
           formData.append('subject', subject);
           formData.append('msg', msg);
           formData.append('reCAP', reCAP);

           //use contact-form-handler.php for sending the email
           var endpoint = 'php/contact-form-handler.php'; 

           //make the ajax request
           var xhr = new XMLHttpRequest();
           xhr.onload = function(){

               if(xhr.readyState == 4 && xhr.status == 200){     
                   document.getElementById("resultmsg").setAttribute("style", "display: block;");  
                   var resArr = this.responseText.split("^^^");
                   document.getElementById("resultmsg").innerHTML =  resArr[1]; 
                   
                   if(resArr[0].trim() == 'success'){
                        //clear the fields
                        console.log("clear!");
                        pageObj.clear();
                   }
                   //reset grecaptcha
                   pageObj.set_reCAPTCHA();
                   //clear the red border html5 fallback effect
                   pageObj.cform.classList.remove('invalid');

               }
           }          
           xhr.open('POST', endpoint, true);           
           xhr.send(formData);

        }, false);//end submit

    }

}

//**  GOOGLE reCAPTCHA client side */
pageObj.set_reCAPTCHA = function(){

    //create a token for the current user for the recaptcha field
    grecaptcha.ready(function () {
        grecaptcha.execute('6LcjhaUUAAAAALEQ4sQXnQMqD61UA3bds1MmXNZP', { action: 'contact' }).then(function (token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });  
}

//** Clear all the form fields */
pageObj.clear = function(){
    
    var len = pageObj.cform.elements.length;
    var fEl = pageObj.cform.elements;
    var valid = true;
    /* loop through the elements in the form except the first one(fieldset) so i starts at 1*/
    for(var i= 0; i<len; i++){
        fEl[i].value = "";
    }    
}

pageObj.init(); 