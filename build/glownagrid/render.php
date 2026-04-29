<section class="container complex-grid-section">
    <div class="container--narrow2-important grid-section">
        <!-- <div class="grid-header">
            <div class="section-subtitle">zobacz więcej</div>
            <div class="section-main-title">Nasze produkty</div>
        </div> -->

        <div class="master-grid">
            <!-- Lewa kolumna -->
            <div class="grid-item grid-item-1 recipe-box" style="">
                <img src="http://shavwoman.pl/wp-content/uploads/golarka-dla-kobiet-produktowe2.jpg">
                <div class="content-overlay">
                    <div class="overlay-heading">Golarka Shav Woman</div>
                    <button class="grid-button">
                        <a href="https://shavwoman.pl/produkt/golarka-damska-shav-woman/">Kup</a></button>
                </div>
            </div>

            <!-- Górny środkowy blok -->
            <div class="grid-item grid-item-2 book-box" style="">
                <img src="http://shavwoman.pl/wp-content/uploads/4-1.png">
                <div class="content-overlay">
                    <div class="overlay-heading">Zestaw Shav Woman</div>
                    <button class="grid-button">
                    <a href="https://shavwoman.pl/produkt/zestaw-shav-woman/">Kup</a>
                    </button>               
                </div>
            </div>

            <!-- Nakrętka -->
            <div class="grid-item grid-item-3 cap-box" style="">
                <img src="http://shavwoman.pl/wp-content/uploads/20260323_200245-1.jpg">
                <div class="content-overlay">
                    <div class="overlay-heading">Etui podróżne</div>
                    <button class="grid-button">
                    <a href="https://shavwoman.pl/produkt/etui-do-golarki-shav-woman/">Kup</a>
                    </button>
                </div>
            </div>


            <!-- Zestaw promocyjny -->
            <div class="grid-item grid-item-5" style="">
                <img src="http://shavwoman.pl/wp-content/uploads/myjka-1000x1000-uzycie.png">
                <div class="content-overlay">
                    <div class="overlay-heading">Myjka do ciała Shav</div>
                    <button class="grid-button">
                    <a href="https://shavwoman.pl/produkt/myjka-do-ciala-shav-woman/">Kup</a>
                    </button>
                </div>
            </div>

            <!-- Dolny lewy -->
            <div class="grid-item grid-item-6" style="">
                <img src="http://shavwoman.pl/wp-content/uploads/DSC03821-1.png">
                <div class="content-overlay">
                    <div class="overlay-heading">Myjka do głowy Shav</div>
                    <button class="grid-button">
                    <a href="https://shavwoman.pl/produkt/myjka-do-glowy-shav-woman/">Kup</a>
                    </button>
                </div>
            </div>

            <!-- Zamienniki -->
            <div class="grid-item grid-item-7" style="">
                <img src="http://shavwoman.pl/wp-content/uploads/shav-woman-ostrze-beztla.png">
                <div class="content-overlay">
                    <div class="overlay-heading">Ostrze okrągłe</div>
                    <button class="grid-button">
                    <a href="https://shavwoman.pl/produkt/ostrze-okragle-foliowe/">Kup</a>
                    </button>
                </div>
            </div>


            <!-- Prawa kolumna -->
            <div class="grid-item grid-item-8" style="">
                   
                        <img src="http://shavwoman.pl/wp-content/uploads/shav-woman-ostrze2-beztla.png">
                        <div class="content-overlay">
                    <div class="overlay-heading">Ostrze bazowe</div>
                    <button class="grid-button">
                    <a href="https://shavwoman.pl/produkt/ostrze-bazowe-foliowe/">Kup</a>
                    </button>
                </div>
            </div>
    </div>
</section>


<style>

    .overlay-heading {
        position: absolute;
        background: rgba(255, 255, 255, 0.60);
    color: black;

    top: 20px;
    left: 20px;
    display: flex;
padding: 8px 24px;
justify-content: center;
align-items: center;
gap: 10px;
border-radius: 4px;
font-size: 12px;
font-family: 'poppins', sans-serif;

@media (max-width: 900px) { 
    display: inline-flex;
padding: 6px 12px;
justify-content: center;
align-items: center;
gap: 12px;
  }

    }
   
.grid-section {
  display: flex;
  flex-direction: column;
  gap: 60px;
  padding: 10px;
  background: #000 !important;

  @media (max-width: 800px) { 
    gap: 17px;
  }
}
.grid-header {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}


.complex-grid-section {
  padding: 60px 0px;
  background: #000 !important;
  height: auto;

  @media (max-width: 800px) {
    padding: 45px 0 45px 0;
  }

  .video-container {
  display: grid; 
  grid-template-columns: 1fr 1fr; 
  gap: 30px; 
  

  @media (max-width: 1000px) {  
    display: flex;
    flex-direction: column;
    gap: 32px;
  }

  }
}

.master-grid {
  display: grid;
  grid-template-columns: repeat(10, 1fr);
  grid-template-rows: repeat(10, auto);
  gap: 15px;
  position: relative;
}

.grid-item {
  position: relative;
  border-radius: 32px;
  overflow: hidden;
  background-color: #fff;
  
}

.grid-item-1 {
  grid-area: 1 / 1 / 7 / 4;

  @media (max-width: 800px) { 
    grid-area: 4 / 1 / 6 / 1 !important;
    height: 300px;

}
}

.grid-item-2 {
  grid-area: 1 / 4 / 5 / 8;

  @media (max-width: 800px) { 
    grid-area: 1 / 1 / 2 / -1 !important;
  }
}

.grid-item-3 {
  grid-area: 7 / 1 / 11 / 4;
  @media (max-width: 800px) { 
    grid-area: 5 / 2 / 8 / -1 !important;
  }
  
}



.grid-item-5 {
  grid-area: 1 / 8 / 5 / 11;
  @media (max-width: 800px) { 
    grid-area: 4 / 2 / 5 / -1 !important;
  }
}

.grid-item-6 {
  grid-area: 8 / 7 / 11 / 11;
  @media (max-width: 800px) { 
    grid-area: 2 / 2 / 4 / -1 !important;
  }
}

.grid-item-7 {
  grid-area: 5 / 7 / 8 / 11;
    @media (max-width: 800px) { 
      grid-area: 6 / 1 / 8 / 2 !important;
}
}

.grid-item-8 {
  grid-area: 5 / 4 / 11 / 7;
  @media (max-width: 800px) { 
    grid-area: 2 / 1 / 4 / 2 !important;
  }
}

.grid-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.content-overlay {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  /* padding: 20px; */
  color: white;

  @media (max-width: 800px) { 
    padding: 0px;
  }
}

.heading-overlay {

  color: #000;
}

.badge {
  background: #2a7a3b;
  padding: 5px 15px;
  border-radius: 20px;
  display: inline-block;
  margin-bottom: 10px;
  font-weight: 600;
}

.grid-button {
    bottom: 20px;
    right: 20px;
    position: absolute;
    /* width: 50px; */
  color: #fff;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  background-color: #080808;
  display: flex;
    padding: 12px 48px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-radius: 4px;

    @media (max-width: 800px) { 
        display: inline-flex;
padding: 8px 24px;
justify-content: center;
align-items: center;
gap: 12px;
  }

    a {
      text-decoration: none;
      color: #fff;
    }
}



.text-box {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}





@media (max-width: 800px) {
  .master-grid {
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: minmax(120px, auto);
    grid-template-rows: none;
  }
}

</style>