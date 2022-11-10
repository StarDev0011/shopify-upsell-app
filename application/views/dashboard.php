<?php include_once('templates/header.php'); ?>
<div class="wrap-1000">
  <div class="row">
    <div class="col-md-12">
      <div class="card padding-20">
        <h3 class="title line-height-normal">Today’s Status</h2>
        <div class="row">
          <div class="col-md-4">
            <div class="dash-box bundle">
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>bundle.png" width="42" alt="Bundle" />
              </div>
              <div class="content">
                <p class="name">Bundle Views</p>
                <p class="count">
                  352,000
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="dash-box item-added">
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>added.png" width="42" alt="Bundle" />
              </div>
              <div class="content">
                <p class="name">Items Added</p>
                <p class="count">
                  236,000
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="dash-box added-cart">
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>addedcart.png" width="42" alt="Bundle" />
              </div>
              <div class="content">
                <p class="name">Added to Cart</p>
                <p class="count">
                  987,450
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt20">
      <div class="col-md-4">
        <div class="panel panel-default panel-dashboard bundle">
          <div class="panel-heading">
            <h3>Yesterday Status</h3>
          </div>
          <div class="panel-body">
            <div class="report-container">
              <div class="content">
                <p class="count">358</p>
                <p class="name">Bundle Views</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>bundle.png" width="42" alt="Bundle" />
              </div>
            </div>
            <div class="report-container">
              <div class="content">
                <p class="count">758</p>
                <p class="name">Items Added</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>added.png" width="42" alt="Bundle" />
              </div>
            </div>
            <div class="report-container">
              <div class="content">
                <p class="count">847</p>
                <p class="name">Added to Cart</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>addedcart.png" width="42" alt="Bundle" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default panel-dashboard added">
          <div class="panel-heading">
            <h3>Yesterday Status</h3>
          </div>
          <div class="panel-body">
            <div class="report-container">
              <div class="content">
                <p class="count">125</p>
                <p class="name">Bundle Views</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>bundle.png" width="42" alt="Bundle" />
              </div>
            </div>
            <div class="report-container">
              <div class="content">
                <p class="count">753</p>
                <p class="name">Items Added</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>added.png" width="42" alt="Bundle" />
              </div>
            </div>
            <div class="report-container">
              <div class="content">
                <p class="count">145</p>
                <p class="name">Added to Cart</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>addedcart.png" width="42" alt="Bundle" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default panel-dashboard addedcart">
          <div class="panel-heading">
            <h3>Yesterday Status</h3>
          </div>
          <div class="panel-body">
            <div class="report-container">
              <div class="content">
                <p class="count">147</p>
                <p class="name">Bundle Views</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>bundle.png" width="42" alt="Bundle" />
              </div>
            </div>
            <div class="report-container">
              <div class="content">
                <p class="count">699</p>
                <p class="name">Items Added</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>added.png" width="42" alt="Bundle" />
              </div>
            </div>
            <div class="report-container">
              <div class="content">
                <p class="count">364</p>
                <p class="name">Added to Cart</p>
              </div>
              <div class="icon-box">
                <img src="<?php echo $this->config->item('img_url') ?>addedcart.png" width="42" alt="Bundle" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<?php include_once('templates/footer.php'); ?>