//****
//****
//--------------------------JQUERY FUNCTIONS ----------------------------------//
//****
//****

$(document).ready(function(){

	//-------------------------- logo animations ------------------------------///

	//remove load in animation after 1 sec so it doesn't replay
	setTimeout(function(){
		$('.logo').removeClass('logoHomepageAnimation');
	} ,1000);

	//make logo bounce when mouse over. Used static container behind moving logo to prevent buggy behaviour
	$('.logoContainer').mouseenter(function(){
		//add logoBounce class to run animation
		$('.logo').addClass('logoBounce');
		
		//after .5s when animation has run, remove class
		setTimeout(function(){
			$('.logo').removeClass('logoBounce');
		},500);
	});


	//--------------- card hover microinteraction animation----------------------//

	
	//homepage cards
	$('.card').hover(function(){
		$(this.children[0].children).toggleClass('moveUp');
	})

	//item cards in store
	$('.itemcard').hover(function(){
		$(this.children[0].children).toggleClass('item_moveUp');
	})

	//--------------- cart update button appear function ----------------------//

	$( ".cartUpdateQty" ).focus(function() {
  		$(this).next().addClass('cartBtnVisible');
	});

	//------------------------------ Accordion Functions ---------------------------------//
    
    $('.accordionHead').click(function(){
    	//target the div immediately after the clicked accordionHead.

    	var thisTarget = $(this).next();
    	var thisArrow = $(this).children('.arrowUp');

    	$.when(
	    		$('.contentContainerOpen').not(thisTarget).removeClass('contentContainerOpen')
    		).then(
	    		$(this).next().toggleClass('contentContainerOpen'));
	    
        	$.when(
	    		$('.arrowUp').not(thisArrow).removeClass('arrowDown')
    		).then(
	    		$(this).children('.arrowUp').toggleClass('arrowDown'));
	    	
    		
    	
    });

}) //----------------- end jQuery document ready function ------------------//





//****
//****
//--------------------------JAVASCRIPT FUNCTIONS ----------------------------------//
//****
//****




//-----------------------simulation warning function-------------------------//

function simulationWarning() {
	alert('simulation of a full working website, page does not exist');
}

//-----------------------display image function -----------------------------//

function displayImage(path, desc) {
	//create empty popup window
	puw = window.open("","", "height=700, width=600, top=100, left=300, toolbar=no, resizeable=no, status=no, location=no, titlebar=no, scrollbars=no");

	//import font from google
	puw.document.write('<link href="https://fonts.googleapis.com/css?family=Quicksand:500" rel="stylesheet">')

	//display the image in the popup window
	puw.document.write("<img src='"+path+"' alt='' height='550' width='550'>");

	//display the description beneath the popup window
	puw.document.write("<p style='text-align: center; font-family: Quicksand, open-sans; font-size: 0.9em;'>"+ desc +"</p>");

	//display a button to close the popup window
	puw.document.write("<br><p style='text-align: center'><input type='button' value='Close Window' onclick='window.close()'></p>");
}

//----------------------------  form validation function --------------------------//

function checkAddCustomer(theForm) {
	var errMsg = "";
	//------------------------check email------//
	if (theForm.email.value == "") {
		errMsg = errMsg + "Please enter a valid email \n";
	} else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(theForm.email.value))) {
		errMsg = errMsg + "Email is not a valid email address \n";
	}

	//---------------------check last name --//
	if (theForm.lastName.value == "") {
		errMsg = errMsg + "Please enter a last name \n";
	}

	//---------------------check address --//
	if (theForm.address.value == "") {
		errMsg = errMsg + "Please enter an address name \n";
	}

	//--------------------------check state -- // 
	if (theForm.stateId.selectedIndex == 0) {
		errMsg = errMsg + "Please select your state \n";
	}

	//----------------------check postcode -----//
	if (theForm.postCode.value == "") {
		errMsg = errMsg + "Please enter a postcode \n";
	} else if (isNaN(theForm.postCode.value)) {
		errMsg = errMsg + "Please enter a numeric postcode \n";
	}

	//--check if password 1 has data --
	if (theForm.passWord.value == "") {
		errMsg = errMsg + "Please enter a password \n";
	} 
	else if (theForm.passWord.value.length < 7 || theForm.passWord.value.length > 12) {
		errMsg = errMsg + "Password must be between 7 and 12 characters \n";
	}
	
	//-- check to see if password 2 has data--
	if (theForm.passWord2.value == "") {
		errMsg = errMsg + "Please confirm password \n";
	}
	else if (theForm.passWord2.value.length < 7 || theForm.passWord2.value.length > 12) {
		errMsg = errMsg + "Password confirmation must be between 7 and 12 characters \n";
	} 
	else if (theForm.passWord.value != theForm.passWord2.value) {
		errMsg = errMsg + "Confirmation password does not match, please try again \n";
	}


	//--final error check--
	if (errMsg != "") {
		alert(errMsg);
		return false;
	} else {
		return true;
	}
} 

//----------------------------  login validation function --------------------------//

function checkLogin(theForm) {
	var errMsg = "";
	//------------------------check email------//
	if (theForm.loginEmail.value == "") {
		errMsg = errMsg + "Please enter a valid email \n";
	} else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(theForm.loginEmail.value))) {
		errMsg = errMsg + "Email is not a valid email address \n";
	}

	//--check if password 1 has data --
	if (theForm.loginPassword.value == "") {
		errMsg = errMsg + "Please enter a password \n";
	} 
	else if (theForm.loginPassword.value.length < 7 || theForm.loginPassword.value.length > 12) {
		errMsg = errMsg + "Password must be between 7 and 12 characters \n";
	}


	//--final error check--
	if (errMsg != "") {
		alert(errMsg);
		return false;
	} else {
		return true;
	}
} 

//----------------------------  reset password validation function --------------------------//

function checkResetPass(theForm) {
	var errMsg = "";
	//------------------------check email------//
	if (theForm.newPassEmail.value == "") {
		errMsg = errMsg + "Please enter a valid email \n";
	} else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(theForm.newPassEmail.value))) {
		errMsg = errMsg + "Email is not a valid email address \n";
	}

	//--final error check--
	if (errMsg != "") {
		alert(errMsg);
		return false;
	} else {
		return true;
	}
} 

//----------------------------  update account validation function --------------------------//

function checkUpdateCustomer(theForm) {
	var errMsg = "";
	//------------------------check email------//
	if (theForm.email.value == "") {
		errMsg = errMsg + "Please enter a valid email \n";
	} else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(theForm.email.value))) {
		errMsg = errMsg + "Email is not a valid email address \n";
	}

	//---------------------check last name --//
	if (theForm.lastName.value == "") {
		errMsg = errMsg + "Please enter a last name \n";
	}

	//---------------------check address --//
	if (theForm.address.value == "") {
		errMsg = errMsg + "Please enter an address \n";
	}

	//---------------------check suburb --//
	if (theForm.suburb.value == "") {
		errMsg = errMsg + "Please enter a suburb \n";
	}

	//--------------------------check state -- // 
	if (theForm.stateId.selectedIndex == 0) {
		errMsg = errMsg + "Please select your state \n";
	}

	//----------------------check postcode -----//
	if (theForm.postCode.value == "") {
		errMsg = errMsg + "Please enter a postcode \n";
	} else if (isNaN(theForm.postCode.value)) {
		errMsg = errMsg + "Please enter a numeric postcode \n";
	}


	//--final error check--
	if (errMsg != "") {
		alert(errMsg);
		return false;
	} else {
		return true;
	}
} 

//----------------------------  update password validation function --------------------------//

function checkUpdatePassword(theForm) {
	var errMsg = "";

	//--check if current password has data --
	if (theForm.currentPassWord.value == "") {
		errMsg = errMsg + "Please enter your current password \n";
	} 

	//--check if newPassword has data --
	if (theForm.newPassWord.value == "") {
		errMsg = errMsg + "Please enter a new password \n";
	} 
	else if (theForm.newPassWord.value.length < 7 || theForm.newPassWord.value.length > 12) {
		errMsg = errMsg + "Password must be between 7 and 12 characters \n";
	}
	
	//-- check to see if password 2 has data--
	if (theForm.newPassWord2.value == "") {
		errMsg = errMsg + "Please confirm new password \n";
	}
	else if (theForm.newPassWord2.value.length < 7 || theForm.newPassWord2.value.length > 12) {
		errMsg = errMsg + "Password confirmation must be between 7 and 12 characters \n";
	} 
	else if (theForm.newPassWord.value != theForm.newPassWord2.value) {
		errMsg = errMsg + "Confirmation password does not match, please try again \n";
	}


	//--final error check--
	if (errMsg != "") {
		alert(errMsg);
		return false;
	} else {
		return true;
	}
} 

//----------------------------  confirm checkout function --------------------------//

function confirmCheckout() {
	var ret = confirm('Your order is about to be submitted & your credit card will be charged. Please do not refresh or use your browser\'s back button');

	if (ret == true) {
		return true;
	} else {
		return false;
	}
}

//----------------------------  check checkout1 function --------------------------//

function checkCheckout1(theForm) {
	var errMsg = "";

	//---------------------check last name --//
	if (theForm.deliveryTo.value == "") {
		errMsg = errMsg + "Please enter name for 'delivery to' \n";
	}

	//---------------------check address --//
	if (theForm.deliveryAddress.value == "") {
		errMsg = errMsg + "Please enter a delivery address \n";
	}

	//---------------------check suburb --//
	if (theForm.deliverySuburb.value == "") {
		errMsg = errMsg + "Please enter a delivery suburb \n";
	}

	//--------------------------check state -- // 
	if (theForm.deliveryStateId.selectedIndex == 0) {
		errMsg = errMsg + "Please select your state \n";
	}

	//----------------------check postcode -----//
	if (theForm.deliveryPostCode.value == "") {
		errMsg = errMsg + "Please enter a postcode \n";
	} else if (isNaN(theForm.postCode.value)) {
		errMsg = errMsg + "Please enter a numeric postcode \n";
	}


	//--final error check--
	if (errMsg != "") {
		alert(errMsg);
		return false;
	} else {
		return true;
	}
} 