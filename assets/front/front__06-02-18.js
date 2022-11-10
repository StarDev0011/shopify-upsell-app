
localStorage.removeItem('showpop');

url = window.location.href;
domain = extractHostname(url);
curr_page = extractPageName(url);
prodSlug = extractProductSlug(url);

if(prodSlug!='' && prodSlug!='all'){
	var variant_id	=	getCurrentProductID(prodSlug);
}

base_url = 'https://scarcifyapps.com/smartupsellapp/';
cart_items = getCurrentCartItems();

jQuery('[name="checkout"], [name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled',true);

jQuery(document).ready(function() {
		
		//console.log(localStorage.getItem('showpop'));
		//console.log(jQuery('.sp').length);
	
	   // Show popup when user click on checkout button
	   jQuery(document).on('click', '[name="checkout"], [name="goto_pp"], [name="add"], [name="goto_gc"]', function(e) {	
	   	
		 if(localStorage.getItem('showpop') != 'shown' && jQuery('.sp').length > 0){
		  if(jQuery('.bundle_id').val()){
				updateBundleView(jQuery('.bundle_id').val());
		   }
		  localStorage.setItem('showpop','shown');
		  e.preventDefault(); // disable normal link function so that it doesn't refresh the page
		  var docHeight = jQuery(document).height(); //grab the height of the page
		  var scrollTop = jQuery(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
		  jQuery('.overlay-bg').show().css({'height' : docHeight}); //display your popup and set height to the page height
		  jQuery('.overlay-content').css({'top': scrollTop+20+'px'}); //set the content 20px from the window top
		  
		  return false;
		 }
	   });

		// hide popup when user clicks on close button
		jQuery(document).on('click','.pop-close-btn', function(){
			jQuery('.overlay-bg').hide(); // hide the overlay
			jQuery('[name="checkout"]').click();
			jQuery('[name="add"]').click();
		});
	
		// hides the popup if user clicks anywhere outside the container
		jQuery(document).on('click','.overlay-bg', function(){
			//jQuery('.overlay-bg').hide();
			//jQuery('[name="checkout"]').click();
			return false;
		});
		// prevents the overlay from closing if user clicks inside the popup overlay
		jQuery(document).on('click','.overlay-content', function(){
			return false;
		});
	
	
	  //When user click on skip this item 	
	  jQuery(document).on('click', '.skip-this', function(){
		
		jQuery(this).parents('.sp').removeClass('active');
		if(jQuery(this).parents('.sp').next().length<1){
		  jQuery(this).parents('#slider').find('.sp:first').addClass('active');
		}else{
		  jQuery(this).parents('.sp').next().addClass('active');
		}
	
		product_id = jQuery('.active').attr('prod-id');
		jQuery('.add-this-cart').attr('prod-id',product_id);
	
		jQuery(".col-item").removeClass("fadeInLeft")
        jQuery(".col-item").addClass("fadeInLeft");
	
		jQuery('.sp').hide();
		jQuery('.active').show().animate({"margin-right": '-=200'});
	
	  });/*previous*/
	
	  //Add to cart functionality
	  jQuery(document).on('click','.add-this-cart', function(e){
		var prod_id = jQuery(this).attr('prod-id');
		jQuery(this).attr('disabled',true);
		addItem(prod_id);
		
	  });
	  
	  
});/*jQuery*/


function getCurrentCartItems(){

	jQuery.ajax({
		  type: 'GET',
		  url: '/cart.js',
		  dataType: 'json',
		  data: '',
		  success : function(data){
			//console.log(data.total_price);
			cart_items = data.items;
			cart_price = data.total_price;
			getPOPUpProducts(domain, data.items, cart_price);
		  }
	 });	
}

function getPOPUpProducts(domain, cart_items, cart_price){
 
 	
  frontFunction = 'getPopup';
  if(prodSlug!=''){
  	frontFunction = 'getCartPagePopup';
  }
  
  data = {'domain':domain, 'cart_items':cart_items, 'cart_price':cart_price, 'product_slug':prodSlug};
 
  jQuery.ajax({
	type: 'POST',
	url : base_url+'front/'+frontFunction,

	data: data,
	
	success : function(data){
	  jQuery('#shopify-section-footer').append(data);

	  jQuery('.sp').first().addClass('active');
	  jQuery('.sp').hide();
	  jQuery('.active').show();
	
	  jQuery('[name="checkout"], [name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled',false);
	},
	error: function (textStatus, errorThrown) {
         getPOPUpProducts(domain, cart_items)
    }

  });/*ajax*/
  
  
}

function updateBundleView(bundle_id){
	
  data = {'domain':domain, 'bundle_id':bundle_id};
  jQuery.ajax({
	type: 'POST',
	url : base_url+'front/updateBundleView',

	data: data,
	success : function(data){
		console.log(data);
	}
  });/*ajax*/

}

function getCurrentProductID(prodSlug){
	
	jQuery.ajax({
		  type: 'GET',
		  url: '/products/'+prodSlug+'.js',
		  dataType: 'json',
		  data: '',
		  success : function(data){
			variant_id = data.variants[0].id;
			
		  }
	 });	
	 
	 return variant_id;
}

function addVariant() {
 jQuery.ajax({
      type: 'POST',
      url: '/cart/add.js',
      dataType: 'json',
      data: { quantity: 1, id: variant_id },
      success : function(data){
      	jQuery('[name="add"]').click();
	  }
   });
  
}/*addtocart*/

function addItem(form_id) {
	
 jQuery.ajax({
      type: 'POST',
      url: '/cart/add.js',
      dataType: 'json',
      data: jQuery('#'+form_id).serialize(),
      success : function(data){
        jQuery('.response-'+form_id).html('<span class="text tex-center">Added Successfully..!!</span>');
		
		addDiscount();
		if(jQuery('.bundle_type').val()==6){
			addVariant();
		}else{
			jQuery('[name="add"]').click();
		}
	
		
      }
   });
}/*addtocart*/

function addDiscount(){
	if(jQuery('.discount_code').val()!=''){
		jQuery('form.cart').attr('action','/cart?discount='+jQuery('.discount_code').val());
	}else{
		jQuery('form.cart').attr('action','/cart?discount=');
	}
}

function extractHostname(url) {
    var hostname;
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("://") > -1) {
        hostname = url.split('/')[2];
    }else {
        hostname = url.split('/')[0];
    }
	//console.log(hostname);
    return hostname;
}/*extractHostname*/

function extractPageName(url) {
    var pagename = '';
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("://") > -1) {
        pagename = url.split('/')[3];
    }
	//console.log(pagename);
 
    return pagename;
}/*extractPageName*/

function extractProductSlug(url) {
    var prodSlug = '';
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("://") > -1) {
		if(url.split('/')[4]){
			prodSlug = url.split('/')[4];
		}
        
    }
	//console.log(pagename);
 
    return prodSlug;
}/*extractPageName*/
