<?php
$mainTitle   = isset($attributes['mainTitle'])   ? $attributes['mainTitle']   : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
?>
<section class="container blacktext-container-kariera">
    <div id="text-container">
        <div class="section-main-title"><?php echo wp_kses_post($mainTitle); ?></div>
        <div class="section-text"><?php echo wp_kses_post($description); ?></div>
    </div>
</section>

<style>
#text-container {
    margin: 0 auto;
    max-width: 1300px;
    text-align: center;
    color: #000;
}

        .blacktext-container-kariera {
    background: #000;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: color 1s;
        }

.section-main-title {
    font-family: "bebas-neue-pro", sans-serif;
    font-size: 42px;
    font-weight: 600;
    line-height: normal;
    color: #140B00;
    margin-bottom: 15px;

    @media (max-width: 768px) { 
        font-family: "bebas-neue-pro", sans-serif;
   
    font-weight: 600;
    line-height: normal;
        text-align: center;
        font-size: 34px;
    
    }

    @media (max-width: 415px) { 
      text-align: center;
      font-size: 32px;
  }

  }

  .section-text {
    font-family: 'roboto', sans-serif;
    font-size: 16px;
    font-weight: 500;
    line-height: normal;
    color: #140B00;
    margin-bottom: 32px;
    line-height: normal;
    font-weight: 400;

    @media (max-width: 768px) {
      font-size: 14px;
      }
  }
    </style>
