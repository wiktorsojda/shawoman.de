<section class="container reviews-container">
    <div class="container--narrow2-important">
        <div class="reviews-content">
            <div class="section-subtitle">Co mówią o nas klienci?</div>
            <!-- <div class="section-main-title">Co mówią o nas nasi klienci?</div> -->
            
            <!-- Blok z oceną główną -->
            <div class="average-ratings">
                 <span class="rating-stars">★★★★★</span>
                <div class="reviews-count">4,9/5 na podstawie <strong>ponad 20 tys opinii</strong></div>
            </div>

            <!-- Lista opinii -->
            <div class="reviews-carousel">
            <div class="reviews-list">
                <!-- Pojedyncza opinia -->
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Kasia</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Naprawdę jestem pozytywnie zaskoczona. Golarka jest bardzo delikatna dla skóry, a jednocześnie dokładna – w końcu zero podrażnień po goleniu. Używam jej głównie pod prysznicem i działa bez zarzutu.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Magda</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Świetny sprzęt do codziennej pielęgnacji. Bardzo dobrze leży w dłoni i łatwo się nią manewruje nawet w trudniejszych miejscach. Na plus też czas pracy – długo trzyma na jednym ładowaniu.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Aneta</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Mega wygodna w użyciu i bardzo szybka. Rano oszczędzam sporo czasu, bo mogę golić się pod prysznicem. Skóra jest gładka i bez czerwonych kropek – duży plus.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Wiktoria</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Najbardziej podoba mi się to, że nie ma podrażnień jak po jednorazówkach. Technologia faktycznie robi robotę – skóra po goleniu jest miękka i wygląda zdrowo.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Sonia</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Bardzo poręczna i lekka – idealna też na wyjazdy. Szybko się ładuje i nie zajmuje dużo miejsca w kosmetyczce. Używam jej zarówno do nóg, jak i stref bikini.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Ania</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Cicha, wygodna i bardzo przyjemna w użytkowaniu. To jedna z tych rzeczy, które realnie ułatwiają życie – szczególnie jeśli ktoś ma problem z podrażnieniami.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Magda</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Najlepsza golarka, jaką miałam. Zero zacięć, zero podrażnień, a skóra jest naprawdę gładka. Używam regularnie i nie wróciłabym już do zwykłych maszynek.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Milena</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Bardzo delikatna dla skóry, a jednocześnie skuteczna. Mam wrażliwą skórę i po tej golarce nie mam żadnych czerwonych kropek – ogromny plus.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">Natalia</span>
                        <div class="review-stars">★★★★★</div>
                    </div>
                    <p class="review-text">Świetnie sprawdza się zarówno na sucho, jak i pod prysznicem. Jest wygodna w użyciu i bardzo dokładna. W końcu golenie nie jest uciążliwe.</p>
                    <div class="review-verified">Zweryfikowany <span class="verified-badge">✓</span></div>
                </div>
            </div>
                            <!-- Kropki nawigacyjne -->
                            <div class="carousel-dots"></div>
            </div>
        </div>
    </div>
</section>

<style>

/* Kropki nawigacyjne */
.carousel-dots {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
  }
  
  .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .dot.active {
    background: #1CC9F2;
    transform: scale(1.2);
    width: 20px;
    border-radius: 30px;
  }
  
  /* Animacje karuzeli */
  #sbi_images {
    transition: transform 0.3s ease-out;
    display: flex;
    gap: 20px;
  }
  
  .sbi_item {
    flex: 0 0 calc((100% / var(--items-per-view)) - 20px);
    min-width: 200px;
  }
  
  @media (max-width: 768px) {
    .sbi_item {
      flex: 0 0 calc((100% / 2) - 10px);
    }
  }

/* Główny kontener sekcji */
.reviews-container {
    padding: 60px 0;
    text-align: center;
    /* display: flex; */
    flex-direction: column;
    align-items: center;
    align-self: stretch;
    padding: 60px 0;
    text-align: center;
    background: black;
  }
  

  /* Nagłówki sekcji */
  .reviews-content {
    display: flex;
    flex-direction: column;
  }
  .section-main-title {

    color: #000;
    margin-bottom: 15px;

    @media (max-width: 768px) { 

        text-align: center;
        font-size: 34px;
 
    }

    @media (max-width: 415px) { 

      text-align: center;
      font-size: 32px;

  }

  }
  
  .section-subtitle {

    align-self: stretch;
color: #FFF;

text-align: center;
font-family: 'bebas-neue-pro', sans-serif;
font-size: 64px;
font-style: normal;
font-weight: 400;
line-height: 117.75%; /* 75.36px */
text-transform: uppercase;
 
    @media (max-width: 768px) { 
 
        font-weight: 400;
        /* font-size: 16px; */
    }
  }

 


  .section-text {

    color: #000;
    margin-bottom: 32px;
    line-height: normal;
    font-weight: 400;

    @media (max-width: 768px) {
      font-size: 14px;
      }
  }
  
  /* Blok z oceną główną */
  .average-ratings {
    
    justify-content: center;
    align-items: center;
    gap: 24px;
    display: flex;
    margin-bottom: 40px;
    @media (max-width: 768px) {
        gap: 8px;
        }

    .rating-stars {
        color: #1CC9F2;
    }
  }
  
  .average-rating .rating-text, .rating-text {
    font-size: 24px;
    font-weight: bold;
    color: #2d2d2d;
    margin-left: 10px;
    vertical-align: middle;
  }
  
  .average-rating .reviews-count {
    font-size: 14px;
    color: #6c757d;
    margin-top: 8px;
  }

  .reviews-count {
    font-size: 14px;
    color: #6c757d;
  }
  
  /* Lista opinii */
  .reviews-carousel {
    position: relative;
    overflow: hidden;

    @media (max-width: 800px) { 
      padding-left: 20px;
    }
  }

  .reviews-list {
    display: flex;
    gap: 30px;
    margin: 0 auto;
    transition: transform 0.3s ease-out;

  }
  
  /* Pojedyncza opinia */
  .review-item {
    background-color: #181818 !important;
    border-radius: 12px;
    padding: 25px;
    text-align: left;
    // box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    flex: 0 0 calc(33.333% - 72px);
    min-width: 0;
  }

  
  .review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }
  
  .review-author {
    color: #FFF;

text-align: center;
font-family: "bebas-neue-pro", sans-serif;
font-size: 32px;
font-style: normal;
font-weight: 700;
line-height: normal;
    
  }
  
  .review-stars {
    color: #1CC9F2;
    font-size: 18px;
  }
  
  .review-text {
    font-size: 16px;
font-style: normal;
font-weight: 400;
line-height: normal;
    margin-bottom: 15px;
    color: #fff;

  }
  
  .review-verified {
    color: #FFF;
    text-align: center;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    font-weight: 400;
    display: flex;
    align-items: center;
    justify-content: right;
    gap: 5px;
  }
  
  .verified-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: transparent;
    border: 1px solid green;
    color: green;
    font-size: 14px;
    font-weight: bold;
  }

  /* Responsywność */
  @media (max-width: 768px) {
    .reviews-container {
      padding: 45px 0;
    }
    
    
    .review-item {
      padding: 20px;
    }
    
    .review-item {
        flex: 0 0 70%;
      }
      

  }

</style>