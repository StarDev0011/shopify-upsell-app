<style type="text/css">
.installation-page{
	width: 1200px;
	padding: 0 40px;
	max-width: 100%;
	margin: 0 auto;
}
.installation-page .title{
	font-size: 32px;
	margin: 40px 0 30px;
	text-transform: capitalize;
}
.installation-card{
	position: relative;
background-color: #fff;
padding: 60px 30px 30px 60px;
border-radius: 4px;
display: flex;
align-items: center;
justify-content: space-between;
margin-bottom: 20px;
}
.installation-card .index-no{
	position: absolute;
	top: 20px;
	left: 20px;
	height: 40px;
width: 40px;
background-color: rgba(38,124,238,0.6);
display: block;
border-radius: 50%;
color: #fff;
text-align: center;
font-size: 24px;
line-height: 40px;
}
.installation-card figure{
	flex:0 0 300px;
	display: flex;
align-items: center;
justify-content: center;
}
.installation-card p{
	font-size: 18px;
line-height: 30px;
padding: 0 50px 0 0;
}
.installation-card p b{
	font-weight: 700;
}
.installation-card p.imp{
	background-color: #e5ffcc;
    color: #127512;
    padding: 20px;
    max-width: 500px;
    border-radius: 6px;
}
.max-100{
	max-width: 100%;
}
</style>
<div class="installation-page">
	<h2 class="title">installation guide</h2>
	<div class="installation-card">
		<span class="index-no">1.</span>
		<p>Select <b>Online Store</b> from the left hand menu.</p>
		<figure>
			<img src="<?php echo $this->config->item('img_url') ?>installation/1st.png"/>
		</figure>
		
	</div>
	<div class="installation-card">
		<span class="index-no">2.</span>
		<p>Themes section will be selected by default. Click on '<b>Actions</b>' dropdown which will displayed on main area. It will be shown on currently published theme. Choose '<b>Edit code</b>' from the dropdown options.</p>
		<figure>
			<img src="<?php echo $this->config->item('img_url') ?>installation/2nd.png" class="max-100" />
		</figure>
	</div>
	<div class="installation-card">
		<span class="index-no">3.</span>
		<p>Please add one line code to show cross sell on product detail page:
			<br><br>{% include 'smart-cross-sell' %}<br>
			<br>Follow below steps for where to place this code.
		<br><b>NOTE:</b> Please put code in product detail page.</p>
	</div>
<div class="installation-card">
	<span class="index-no">4.</span>
	<p>Locate the <b>product-template.liquid</b> file within the <b>Sections</b> folder on the left hand sidebar of the code editor.</p>
	<figure>
		<img src="<?php echo $this->config->item('img_url') ?>installation/3rd.png" class="max-100" />
	</figure>
</div>
<div class="installation-card">
	<span class="index-no">5.</span>
	<p> On <b>product-template.liquid</b> you can put code anywhere you want.
		Suppose you want to display cross sell below <b>Add to Cart</b> button.
	Put it to below line which shown in image. Here theme <b>DEBUT</b> is considered.</p>
	<figure>
		<img src="<?php echo $this->config->item('img_url') ?>installation/4th.png" class="max-100" />
	</figure>
</div>
<div class="installation-card">
	<span class="index-no">6.</span>
	<p class="imp">Try not to put code within any <b>if</b> or < script > tags. This may prevent Cross Sell items from appearing on your site.</p>
	
</div>
<div class="installation-card">
	<span class="index-no">7.</span>
        <p>TIP - Write down the <b>line #</b> for where you place your code, in case you make an error, you can easily delete and move it wherever you need to.</p>
</div>
<div class="installation-card">
	<span class="index-no">8.</span>
	<p>Save the Template or Section which you modified.</p>
	
</div>
</div>