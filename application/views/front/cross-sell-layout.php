<style type="text/css">
    .slider {
        width: 100%;
        margin: 0 auto;
    }
    .slick-slide {
      margin: 0px 20px;
    }    
    .slick-prev:before,
    .slick-next:before {
      color: black;
    }
        
    
    #smart-cross-sell{
        width: 100%;
    }
    .you-may-like {
        position: relative;        
        margin-bottom: 15px;
    }  
   
    .you-may-like .prodult-img{
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .you-may-like .prodult-img img{
        max-height: 100px;
    }
    
    .cross-sell-add-cart{
        border: none;
        color: #fff;
        background: #000;
        padding: 10px 40px;
        margin: 0 auto;
        display: table;

    }
    .you-may-like  .product-price{
        font-size: 11px;
        text-align: center;
    }
    .you-may-like  .product-name{
        margin-top: 10px;
        text-align: center;
        text-transform: capitalize;
        font-size: 15px;
        font-weight: 500;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .you-may-like  .product-name a{
        outline: 0;
        text-decoration: none; 
    }
    .like-title{
        margin-top: 20px;
        margin-bottom: 10px;
        text-align: center;
        font-size: 18px;
        font-weight: 600; 
    }
    /* The custom-checkbox */
    .custom-checkbox {
        margin-top: 10px;
        position: relative;
        padding-left: 0;
        margin-bottom: 0;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        height: 25px;
        width: 25px;
        margin: 10px auto;
        display: table;
    }

    /* Hide the browser's default checkbox */
    .custom-checkbox input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 24px;
      width: 24px;
      background-color: transparent;
      border: 2px solid #000;
      border-radius: 50%;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
      content: "";
      position: absolute;
      display: none;
    }

    /* Show the checkmark when checked */
    .custom-checkbox input:checked ~ .checkmark:after {
      display: block;
    }

    /* Style the checkmark/indicator */
    .custom-checkbox .checkmark:after {
        left: 5px;
        top: 5px;
        width: 10px;
        height: 10px;
        background-color: #000;
        border-radius: 50%;   
      
    }
    
    .offer-headline-cross-sell{
        font-size: 14px;
        margin: 0 0 10px 0;
        font-weight: 500;
        color: #03b303;
        text-align: center;
    }
    
    #toast-container.toast-top-center>div {
        width: 420px !important;
    }
    </style>
    <div class="like-title"><?= $targetProduct->bundle_title; ?></div>
    <?php if(!empty($targetProduct->offer_headline)){ ?>
    <div class="offer-headline-cross-sell"><?= $targetProduct->offer_headline; ?></div>
    <?php } ?>
    <section class="regular slider">
    <?php foreach ($crossSellProducts as $prod){ ?>
    
        <div class="you-may-like">        
            <div class="prodult-img"><img src="<?= $prod->image ?>"></div>            
            <div class="product-name"><a href="<?= $prod->product_link ?>" target="_blank" title="<?= $prod->title ?>"><?= $prod->title ?></a></div>
            <div class="product-price"><?php echo $currecny.' '.$prod->price ?></div>    
            <label class="custom-checkbox">
                <input type="checkbox" class="smart-cross-sell-cart" value="<?= $prod->variant_id ?>">
              <span class="checkmark"></span>
            </label>        
        </div>
    
    <?php } ?>    
    </section>
    <button type="button" class="cross-sell-add-cart" disabled="" data-dc="<?= $targetProduct->discount_code ?>" data-dt="<?= $targetProduct->discount_type ?>">Add to Cart</button>
    
    <?php $this->load->view('front/cross-sell-thanks',['code'=>$targetProduct->discount_code,'success_text'=>$targetProduct->success_text]); ?>